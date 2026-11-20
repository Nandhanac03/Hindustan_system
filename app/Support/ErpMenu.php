<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\ErpModule;
use App\Models\ErpSubModule;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class ErpMenu
{
    /**
     * Get all visible ERP menu sections and modules for the given user.
     * Modules and sub-modules are loaded dynamically from the database.
     * Items are strictly filtered by user module view permissions.
     */
    public static function getSectionsForUser(?User $user): array
    {
        if (!$user) {
            return [];
        }

        $modules = ErpModule::with(['subModules' => function ($q) {
            $q->where('status', 'active')->orderBy('order', 'asc')->orderBy('id', 'asc');
        }])
        ->where('status', 'active')
        ->orderBy('order', 'asc')
        ->orderBy('id', 'asc')
        ->get();

        $sectionsMap = [];
        $sectionOrder = [
            'Overview',
            'Projects & Configuration',
            'Sales & Booking',
            'Customer & Collections',
            'Contractor Operations',
            'Finance & Analytics',
            'Loans & Debt Servicing',
            'Administration',
        ];

        foreach ($modules as $module) {
            $permittedSubs = [];

            foreach ($module->subModules as $sub) {
                // Strict View Permission Gate
                if (!$user->canViewModule((string) $sub->code)) {
                    continue;
                }

                $resolved = self::resolveSubModuleUrl($sub);
                if (!$resolved) {
                    continue;
                }

                $permittedSubs[] = [
                    'id' => $sub->id,
                    'name' => $sub->name,
                    'code' => $sub->code,
                    'url' => $resolved['url'],
                    'base_route' => $resolved['base_route'],
                    'query_params' => $resolved['query_params'],
                    'is_active' => $resolved['is_active'],
                    'actions' => $sub->actions ?? ['view'],
                ];
            }

            // Only display module if it has at least one permitted sub-module
            if (empty($permittedSubs)) {
                continue;
            }

            $moduleIsActive = collect($permittedSubs)->contains('is_active', true);
            $sectionName = trim($module->section ?: 'General');

            if (!isset($sectionsMap[$sectionName])) {
                $sectionsMap[$sectionName] = [
                    'title' => $sectionName,
                    'is_overview' => strtolower($sectionName) === 'overview',
                    'modules' => [],
                ];
            }

            $sectionsMap[$sectionName]['modules'][] = [
                'id' => $module->id,
                'name' => $module->name,
                'code' => $module->code,
                'icon' => $module->icon ?: 'folder',
                'is_active' => $moduleIsActive,
                'sub_modules' => $permittedSubs,
            ];
        }

        // Sort sections according to preferred ERP section hierarchy
        $sortedSections = [];
        foreach ($sectionOrder as $secTitle) {
            if (isset($sectionsMap[$secTitle])) {
                $sortedSections[] = $sectionsMap[$secTitle];
                unset($sectionsMap[$secTitle]);
            }
        }
        // Append any remaining custom dynamic sections
        foreach ($sectionsMap as $sec) {
            $sortedSections[] = $sec;
        }

        return $sortedSections;
    }

    /**
     * Resolve the target URL and active state for an ERP sub-module.
     */
    public static function resolveSubModuleUrl(ErpSubModule $sub): ?array
    {
        $rawRoute = trim((string) $sub->route_name);
        if ($rawRoute === '') {
            return null;
        }

        $parts = explode('?', $rawRoute, 2);
        $baseRoute = trim($parts[0]);
        $queryParams = [];

        if (isset($parts[1])) {
            parse_str($parts[1], $queryParams);
        }

        if (!Route::has($baseRoute)) {
            return null;
        }

        try {
            $url = route($baseRoute, $queryParams);
        } catch (\Throwable $e) {
            return null;
        }

        // Determine if current request matches this sub-module item
        $isCurrentRoute = request()->routeIs($baseRoute);
        $isActive = false;

        if ($isCurrentRoute) {
            if (!empty($queryParams)) {
                $allMatched = true;
                foreach ($queryParams as $k => $v) {
                    if ((string) request($k) !== (string) $v) {
                        $allMatched = false;
                        break;
                    }
                }
                $isActive = $allMatched;
            } else {
                // If sub-module has no query params, ensure current request doesn't have distinguishing tab/action
                if ($baseRoute === 'sales.index') {
                    $hasActionOrTab = request()->filled('action') || request()->filled('tab');
                    $isActive = !$hasActionOrTab;
                } elseif ($baseRoute === 'dms.index') {
                    $isActive = !request()->filled('upload');
                } elseif ($baseRoute === 'expenses.ra-bills.verification') {
                    $isActive = !request()->filled('new');
                } else {
                    $isActive = true;
                }
            }
        }

        return [
            'url' => $url,
            'base_route' => $baseRoute,
            'query_params' => $queryParams,
            'is_active' => $isActive,
        ];
    }

    /**
     * Get the first permitted module URL for a user (skipping dashboard if not permitted).
     */
    public static function getFirstPermittedUrl(User $user): ?string
    {
        $sections = self::getSectionsForUser($user);

        foreach ($sections as $section) {
            foreach ($section['modules'] as $module) {
                foreach ($module['sub_modules'] as $sub) {
                    if ($sub['code'] !== 'dashboard.view' && !empty($sub['url'])) {
                        return $sub['url'];
                    }
                }
            }
        }

        return null;
    }
}

