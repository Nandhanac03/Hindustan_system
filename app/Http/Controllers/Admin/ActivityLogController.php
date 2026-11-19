<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $employees = User::withoutGlobalScopes()
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'employee_code']);

        $selectedUserId = $request->input('user_id', '');
        $fromDate = $request->input('from_date', now('Asia/Kolkata')->subDays(7)->toDateString());
        $toDate = $request->input('to_date', now('Asia/Kolkata')->toDateString());

        $payload = $this->buildAuditPayload($selectedUserId, $fromDate, $toDate);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $payload,
            ]);
        }

        return view('admin.activity-logs.index', [
            'employees' => $employees,
            'selectedUserId' => $selectedUserId,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'initialData' => $payload,
            'loggingActive' => ActivityLog::isLoggingActive(),
        ]);
    }

    public function toggleLogging(Request $request): JsonResponse
    {
        $active = $request->has('active')
            ? filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN)
            : ! ActivityLog::isLoggingActive();

        ActivityLog::setLoggingActive($active);

        return response()->json([
            'success' => true,
            'logging_active' => $active,
            'message' => $active ? 'Activity logging resumed.' : 'Activity logging paused.',
        ]);
    }

    public function clearLogs(Request $request): JsonResponse
    {
        $query = $this->filteredQuery(
            (string) $request->input('user_id', ''),
            $request->input('from_date'),
            $request->input('to_date')
        );

        $deleted = $query->delete();

        return response()->json([
            'success' => true,
            'deleted' => $deleted,
            'message' => "Cleared {$deleted} activity log record(s).",
        ]);
    }

    public function exportBatch(Request $request): StreamedResponse
    {
        $selectedUserId = (string) $request->input('user_id', '');
        $fromDate = $request->input('from_date', now('Asia/Kolkata')->subDays(7)->toDateString());
        $toDate = $request->input('to_date', now('Asia/Kolkata')->toDateString());

        $logs = $this->filteredQuery($selectedUserId, $fromDate, $toDate)
            ->with('user:id,name,email')
            ->orderByDesc('created_at')
            ->limit(2000)
            ->get();

        $filename = 'activity-audit-' . now('Asia/Kolkata')->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($logs): void {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for clean Microsoft Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Observed Time (IST)',
                'Employee Name',
                'Employee Email',
                'Category',
                'Action',
                'Module',
                'Screen',
                'Description',
                'IP Address',
                'Duration (s)',
            ]);

            foreach ($logs as $log) {
                $ist = $log->created_at?->copy()->setTimezone('Asia/Kolkata')->format('d M Y, H:i:s') ?? '';
                fputcsv($handle, [
                    $ist,
                    $log->user?->name ?? 'System',
                    $log->user?->email ?? '-',
                    $log->category ?? 'action',
                    $log->action,
                    $log->module ?? 'General',
                    $log->screen ?? '-',
                    $log->description,
                    $log->ip_address ?? '-',
                    $log->duration_seconds ?? 0,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportPdf(Request $request): View
    {
        $selectedUserId = (string) $request->input('user_id', '');
        $fromDate = $request->input('from_date', now('Asia/Kolkata')->subDays(7)->toDateString());
        $toDate = $request->input('to_date', now('Asia/Kolkata')->toDateString());

        $logs = $this->filteredQuery($selectedUserId, $fromDate, $toDate)
            ->with('user:id,name,email')
            ->orderByDesc('created_at')
            ->limit(1000)
            ->get();

        $selectedEmployee = $selectedUserId !== '' && $selectedUserId !== 'all'
            ? User::withoutGlobalScopes()->find((int) $selectedUserId)
            : null;

        return view('admin.activity-logs.pdf', [
            'logs' => $logs,
            'selectedEmployee' => $selectedEmployee,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'generatedAt' => now('Asia/Kolkata')->format('d M Y, H:i:s'),
        ]);
    }

    private function filteredQuery(string $selectedUserId, ?string $fromDate, ?string $toDate)
    {
        $query = ActivityLog::query();

        if ($selectedUserId !== '' && $selectedUserId !== 'all') {
            $query->where('user_id', (int) $selectedUserId);
        }

        if (! empty($fromDate)) {
            $start = Carbon::parse($fromDate, 'Asia/Kolkata')->startOfDay()->utc();
            $query->where('created_at', '>=', $start);
        }

        if (! empty($toDate)) {
            $end = Carbon::parse($toDate, 'Asia/Kolkata')->endOfDay()->utc();
            $query->where('created_at', '<=', $end);
        }

        return $query;
    }

    private function buildAuditPayload(string $selectedUserId, ?string $fromDate, ?string $toDate): array
    {
        $logs = $this->filteredQuery($selectedUserId, $fromDate, $toDate)
            ->with('user:id,name,email,employee_code')
            ->orderByDesc('created_at')
            ->limit(1000)
            ->get();

        // 1. Daily Presence Summary & Timeline
        $presenceSummary = $logs
            ->groupBy(fn (ActivityLog $log) => $log->user_id ?? 0)
            ->map(function (Collection $group) {
                $first = $group->min('created_at');
                $last = $group->max('created_at');
                $user = $group->first()?->user;
                $totalSeconds = (int) $group->sum('duration_seconds');

                // Fallback estimate if duration_seconds wasn't recorded yet
                if ($totalSeconds === 0 && $group->count() > 1 && $first && $last) {
                    $totalSeconds = min(28800, max(0, (int) Carbon::parse($first)->diffInSeconds(Carbon::parse($last))));
                }

                $opensCount = $group->filter(function (ActivityLog $item) {
                    return in_array($item->category, ['security', 'screen'], true)
                        || str_contains(strtolower((string) $item->action), 'open')
                        || str_contains(strtolower((string) $item->action), 'login');
                })->count();

                return [
                    'user_id' => $user?->id,
                    'employee_name' => $user?->name ?? 'System',
                    'employee_email' => $user?->email ?? 'system@hindustan.local',
                    'first_observed' => $first ? Carbon::parse($first)->setTimezone('Asia/Kolkata')->format('d M, H:i:s') : '-',
                    'last_recorded' => $last ? Carbon::parse($last)->setTimezone('Asia/Kolkata')->format('d M, H:i:s') : '-',
                    'estimated_active' => $this->formatDuration($totalSeconds),
                    'opens_resumes' => $opensCount,
                ];
            })
            ->values()
            ->all();

        $presenceTimeline = $logs
            ->filter(fn (ActivityLog $log) => $log->category !== 'record')
            ->map(function (ActivityLog $log) {
                return [
                    'id' => $log->id,
                    'observed_time' => $log->created_at?->copy()->setTimezone('Asia/Kolkata')->format('d M, H:i:s') ?? '-',
                    'employee_name' => $log->user?->name ?? 'System',
                    'employee_email' => $log->user?->email ?? 'system@hindustan.local',
                    'event' => $log->action,
                    'screen' => $log->screen ?: ($log->module ?: 'App'),
                    'description' => $log->description,
                ];
            })->values()->all();

        // 2. Screen & Module Usage
        $screenUsage = $logs
            ->filter(fn (ActivityLog $log) => $log->category === 'screen' || ! empty($log->screen))
            ->groupBy(fn (ActivityLog $log) => ($log->user_id ?? 0) . '|' . ($log->screen ?? 'App') . '|' . ($log->module ?? 'General'))
            ->map(function (Collection $group) {
                $first = $group->first();
                $last = $group->max('created_at');
                $seconds = (int) $group->sum('duration_seconds');

                return [
                    'employee_name' => $first?->user?->name ?? 'System',
                    'employee_email' => $first?->user?->email ?? '-',
                    'module' => $first?->module ?? 'General',
                    'screen' => $first?->screen ?? 'App',
                    'visits' => $group->count(),
                    'total_time' => $this->formatDuration($seconds),
                    'last_visited' => $last ? Carbon::parse($last)->setTimezone('Asia/Kolkata')->format('d M, H:i:s') : '-',
                ];
            })
            ->sortByDesc('visits')
            ->values()
            ->all();

        // 3. Actions
        $actions = $logs
            ->filter(fn (ActivityLog $log) => in_array($log->category, ['action', null], true) && ! $log->subject_type)
            ->map(fn (ActivityLog $log) => [
                'id' => $log->id,
                'observed_time' => $log->created_at?->copy()->setTimezone('Asia/Kolkata')->format('d M, H:i:s') ?? '-',
                'employee_name' => $log->user?->name ?? 'System',
                'employee_email' => $log->user?->email ?? '-',
                'action' => $log->action,
                'module' => $log->module ?? 'General',
                'screen' => $log->screen ?? '-',
                'description' => $log->description,
            ])
            ->values()
            ->all();

        // 4. Exports
        $exports = $logs
            ->filter(fn (ActivityLog $log) => $log->category === 'export'
                || str_contains(strtolower((string) $log->action), 'export')
                || str_contains(strtolower((string) $log->screen), 'export'))
            ->map(fn (ActivityLog $log) => [
                'id' => $log->id,
                'observed_time' => $log->created_at?->copy()->setTimezone('Asia/Kolkata')->format('d M, H:i:s') ?? '-',
                'employee_name' => $log->user?->name ?? 'System',
                'employee_email' => $log->user?->email ?? '-',
                'export_type' => $log->action,
                'module' => $log->module ?? 'General',
                'screen' => $log->screen ?? '-',
                'description' => $log->description,
            ])
            ->values()
            ->all();

        // 5. Devices & Security
        $devicesSecurity = $logs
            ->filter(fn (ActivityLog $log) => $log->category === 'security' || ! empty($log->ip_address))
            ->take(200)
            ->map(fn (ActivityLog $log) => [
                'id' => $log->id,
                'observed_time' => $log->created_at?->copy()->setTimezone('Asia/Kolkata')->format('d M, H:i:s') ?? '-',
                'employee_name' => $log->user?->name ?? 'System',
                'employee_email' => $log->user?->email ?? '-',
                'event' => $log->action,
                'ip_address' => $log->ip_address ?? '-',
                'device' => $this->parseUserAgent((string) ($log->user_agent ?? '')),
                'screen' => $log->screen ?? '-',
            ])
            ->values()
            ->all();

        // 6. Record History
        $recordHistory = $logs
            ->filter(fn (ActivityLog $log) => $log->category === 'record' || ! empty($log->subject_type))
            ->map(fn (ActivityLog $log) => [
                'id' => $log->id,
                'observed_time' => $log->created_at?->copy()->setTimezone('Asia/Kolkata')->format('d M, H:i:s') ?? '-',
                'employee_name' => $log->user?->name ?? 'System',
                'employee_email' => $log->user?->email ?? '-',
                'record_type' => $log->subject_type ? class_basename($log->subject_type) : ($log->module ?? 'Record'),
                'record_id' => $log->subject_id ?? '-',
                'action' => $log->action,
                'description' => $log->description,
            ])
            ->values()
            ->all();

        return [
            'logging_active' => ActivityLog::isLoggingActive(),
            'updated_at' => now('Asia/Kolkata')->format('H:i:s'),
            'total_records' => $logs->count(),
            'presence_summary' => $presenceSummary,
            'presence_timeline' => $presenceTimeline,
            'screen_usage' => $screenUsage,
            'actions' => $actions,
            'exports' => $exports,
            'devices_security' => $devicesSecurity,
            'record_history' => $recordHistory,
        ];
    }

    private function formatDuration(int $seconds): string
    {
        if ($seconds <= 0) {
            return '0m 0s';
        }

        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m {$secs}s";
        }

        return "{$minutes}m {$secs}s";
    }

    private function parseUserAgent(string $ua): string
    {
        if ($ua === '') {
            return 'Unknown Device';
        }

        $browser = match (true) {
            str_contains($ua, 'Edg/') => 'Edge',
            str_contains($ua, 'Chrome/') => 'Chrome',
            str_contains($ua, 'Firefox/') => 'Firefox',
            str_contains($ua, 'Safari/') => 'Safari',
            default => 'Browser',
        };

        $os = match (true) {
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Mac OS') => 'macOS',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') => 'iOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'OS',
        };

        return "{$browser} on {$os}";
    }
}

