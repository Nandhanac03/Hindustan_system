<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ErpSubModule;
use App\Support\ErpMenu;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceModulePermission
{
    /**
     * Routes that bypass module permission checks.
     */
    protected array $exceptRoutes = [
        'login',
        'logout',
        'profile.*',
        'storage.local',
        'storage.local.any',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. Guests pass through to auth middleware
        if (!$user) {
            return $next($request);
        }

        // 2. Owner and Super Admin roles bypass all restrictions
        if ($user->hasRole('Owner') || $user->hasRole('Super Admin')) {
            return $next($request);
        }

        $routeName = (string) ($request->route() ? $request->route()->getName() : '');

        // 3. Skip system utility and auth routes
        foreach ($this->exceptRoutes as $pattern) {
            if ($request->routeIs($pattern)) {
                return $next($request);
            }
        }

        // 4. Executive Dashboard Special Handling: Auto-redirect if dashboard.view missing
        if ($request->routeIs('dashboard')) {
            if ($user->canViewModule('dashboard.view')) {
                return $next($request);
            }

            // Redirect user to their first permitted module
            $firstUrl = ErpMenu::getFirstPermittedUrl($user);
            if ($firstUrl && $firstUrl !== url('/dashboard')) {
                return redirect($firstUrl);
            }

            // User has no module permissions at all
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You do not have permission to view the Dashboard or any ERP modules.'
                ], 403);
            }

            return response()->view('errors.403', [
                'message' => 'You do not have permission to view the Executive Dashboard or any assigned modules. Please contact your system administrator.'
            ], 403);
        }

        // 5. Match current request to an ERP Sub-Module
        $subModule = $this->resolveSubModuleForRequest($request);

        if ($subModule) {
            $requiredAction = $this->determineRequiredAction($request, $subModule);

            if (!$user->hasModulePermission((string) $subModule->code, $requiredAction)) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Access denied. You do not have '{$requiredAction}' permission for {$subModule->name}."
                    ], 403);
                }

                return response()->view('errors.403', [
                    'message' => "Access denied. You do not have '{$requiredAction}' permission for {$subModule->name}."
                ], 403);
            }
        }

        return $next($request);
    }

    /**
     * Resolve the corresponding ErpSubModule from active database catalog.
     */
    protected function resolveSubModuleForRequest(Request $request): ?ErpSubModule
    {
        $currentRouteName = $request->route() ? $request->route()->getName() : null;
        if (!$currentRouteName) {
            return null;
        }

        // Load active sub-modules from database
        $subModules = ErpSubModule::where('status', 'active')->get();

        $matchedSub = null;
        $maxSpecificity = -1;

        foreach ($subModules as $sub) {
            $rawRoute = trim((string) $sub->route_name);
            if ($rawRoute === '') {
                continue;
            }

            $parts = explode('?', $rawRoute, 2);
            $baseRoute = trim($parts[0]);
            $queryParams = [];
            if (isset($parts[1])) {
                parse_str($parts[1], $queryParams);
            }

            // Base route matching or prefix resource matching (e.g. projects.create -> projects.index)
            $matchesRoute = false;
            if ($currentRouteName === $baseRoute) {
                $matchesRoute = true;
            } else {
                $basePrefix = explode('.', $baseRoute)[0] ?? '';
                $currentPrefix = explode('.', $currentRouteName)[0] ?? '';
                if ($basePrefix !== '' && $basePrefix === $currentPrefix) {
                    $matchesRoute = true;
                }
            }

            if ($matchesRoute) {
                $specificity = 0;
                if (!empty($queryParams)) {
                    $allMatched = true;
                    foreach ($queryParams as $k => $v) {
                        if ((string) $request->input($k, $request->query($k)) !== (string) $v) {
                            $allMatched = false;
                            break;
                        }
                    }
                    if ($allMatched) {
                        $specificity = count($queryParams) + 10;
                    } else {
                        continue;
                    }
                } else {
                    $specificity = 1;
                }

                if ($specificity > $maxSpecificity) {
                    $maxSpecificity = $specificity;
                    $matchedSub = $sub;
                }
            }
        }

        return $matchedSub;
    }

    /**
     * Determine whether the request is asking for view, create, edit, delete, excel, or pdf.
     */
    protected function determineRequiredAction(Request $request, ErpSubModule $sub): string
    {
        $routeName = (string) ($request->route() ? $request->route()->getName() : '');
        $method = strtoupper($request->method());

        // 1. PDF Export
        if (str_contains($routeName, 'pdf') || $request->input('format') === 'pdf' || $request->routeIs('*.export-pdf')) {
            return 'pdf';
        }

        // 2. Excel Export
        if (str_contains($routeName, 'export') || $request->input('format') === 'excel' || $request->input('format') === 'csv' || $request->routeIs('*.export')) {
            return 'excel';
        }

        // 3. Delete Action
        if ($method === 'DELETE' || str_contains($routeName, 'destroy') || str_contains($routeName, 'delete') || str_contains($routeName, 'remove')) {
            return 'delete';
        }

        // 4. Create Action
        if (str_contains($routeName, 'create') || str_contains($routeName, 'store') || $request->query('action') === 'add' || $request->query('new') === '1') {
            return 'create';
        }

        // 5. Edit Action
        if (in_array($method, ['PUT', 'PATCH']) || str_contains($routeName, 'edit') || str_contains($routeName, 'update')) {
            return 'edit';
        }

        // 6. Default to View Action
        return 'view';
    }
}

