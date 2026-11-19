<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            if (! Auth::check() || ! ActivityLog::isLoggingActive()) {
                return $response;
            }

            if ($this->shouldIgnore($request)) {
                return $response;
            }

            // Skip failed/server-error responses except 403/401 security checks
            $status = $response->getStatusCode();
            if ($status >= 500) {
                return $response;
            }

            $user = Auth::user();
            $path = '/' . ltrim($request->path(), '/');
            $routeName = (string) ($request->route()?->getName() ?? '');
            $method = strtoupper($request->method());
            $module = $this->resolveModule($path, $routeName);

            $isExport = $this->isExportRequest($request, $path, $routeName);
            $isGet = $method === 'GET';
            $isAjax = $request->ajax() || $request->wantsJson();

            // Skip background GET JSON/AJAX polling requests unless it's an export
            if ($isGet && $isAjax && ! $isExport) {
                return $response;
            }

            // Throttle duplicate screen views within 15 seconds
            if ($isGet && ! $isExport) {
                $recentSame = ActivityLog::where('user_id', $user->id)
                    ->where('category', 'screen')
                    ->where('screen', $path)
                    ->where('created_at', '>=', now()->subSeconds(15))
                    ->exists();

                if ($recentSame) {
                    return $response;
                }
            }

            // Estimate active duration since previous log (within 30 mins)
            $durationSeconds = null;
            $lastLog = ActivityLog::where('user_id', $user->id)
                ->latest('created_at')
                ->first();

            if ($lastLog && $lastLog->created_at) {
                $diff = (int) $lastLog->created_at->diffInSeconds(now());
                if ($diff > 0 && $diff <= 1800) {
                    $durationSeconds = $diff;
                }
            }

            if ($isExport) {
                $category = 'export';
                $action = 'Exported Data';
                $description = "Exported {$module} ({$path})";
            } elseif ($isGet) {
                $category = 'screen';
                $action = 'Screen Viewed';
                $description = "Viewed {$module} ({$path})";
            } else {
                $category = 'action';
                $action = match ($method) {
                    'POST' => 'Created / Submitted',
                    'PUT', 'PATCH' => 'Updated Record',
                    'DELETE' => 'Deleted Record',
                    default => $method . ' Action',
                };
                $description = "{$action} in {$module} ({$path})";
            }

            ActivityLog::create([
                'user_id' => $user->id,
                'system_id' => $user->system_id,
                'action' => $action,
                'category' => $category,
                'screen' => Str::limit($path, 250, ''),
                'route_name' => $routeName !== '' ? Str::limit($routeName, 250, '') : null,
                'method' => $method,
                'module' => $module,
                'duration_seconds' => $durationSeconds,
                'description' => Str::limit($description, 250, ''),
                'ip_address' => $request->ip() ?? '127.0.0.1',
                'user_agent' => Str::limit((string) ($request->userAgent() ?? 'Browser'), 250, ''),
                'metadata' => [
                    'status_code' => $status,
                ],
            ]);
        } catch (\Throwable $e) {
            // Never break user requests if logging fails
        }

        return $response;
    }

    private function shouldIgnore(Request $request): bool
    {
        $path = ltrim($request->path(), '/');

        $ignoredPrefixes = [
            'admin/activity-logs',
            '_debugbar',
            'sanctum',
            'up',
            'build',
            'storage',
        ];

        foreach ($ignoredPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        return false;
    }

    private function isExportRequest(Request $request, string $path, string $routeName): bool
    {
        $haystack = strtolower($path . ' ' . $routeName . ' ' . $request->query('export', '') . ' ' . $request->query('format', ''));

        foreach (['export', 'excel', 'pdf', 'csv', 'download'] as $keyword) {
            if (str_contains($haystack, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function resolveModule(string $path, string $routeName): string
    {
        $target = strtolower($routeName . ' ' . $path);

        $map = [
            'admin.users' => 'Users & Roles',
            'admin.roles' => 'Users & Roles',
            'admin.modules' => 'Users & Roles',
            'dashboard' => 'Dashboard',
            'projects' => 'Projects & Configuration',
            'units' => 'Projects & Configuration',
            'partners' => 'Projects & Configuration',
            'sales' => 'Sales & Booking',
            'customers' => 'Customer Portal',
            'receipts' => 'Customer & Collections',
            'emi' => 'Customer & Collections',
            'reminders' => 'Customer & Collections',
            'contractor' => 'Contractor Desk',
            'ra-bills' => 'Contractor Desk',
            'broker' => 'Brokerage',
            'petty-cash' => 'Petty Cash',
            'dms' => 'Document Management',
            'documents' => 'Document Management',
            'site-expenses' => 'Site Expense',
            'treasury' => 'Bank & Treasury',
            'company-bank' => 'Bank & Treasury',
            'accounting' => 'Accounting Reports',
            'vouchers' => 'Accounting Reports',
            'journal' => 'Accounting Reports',
            'loans' => 'Loans & Debts',
            'approvals' => 'Approvals',
            'profile' => 'User Profile',
        ];

        foreach ($map as $needle => $label) {
            if (str_contains($target, $needle)) {
                return $label;
            }
        }

        $segment = trim(explode('/', trim($path, '/'))[0] ?? 'General');
        return $segment !== '' ? Str::title(str_replace(['-', '_'], ' ', $segment)) : 'General';
    }
}

