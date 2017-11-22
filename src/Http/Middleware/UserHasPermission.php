<?php

namespace Btybug\User\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Btybug\btybug\Services\RenderService;
use Btybug\Console\Repository\AdminPagesRepository;
use Btybug\Modules\Models\AdminPages;

/**
 * Class UserHasPermission
 * @package App\Http\Middleware
 */
class UserHasPermission
{
    /**
     * @var Illuminate\Contracts\Auth\Guard
     */
    protected $auth;
    protected $adminPagesRepo;

    /**
     * Create a new UserHasPermission instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth, AdminPagesRepository $adminPagesRepository)
    {
        $this->auth = $auth;
        $this->adminPagesRepo = $adminPagesRepository;
    }

    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $closure
     * @param array|string $permissions
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        editSiteUsers
        if ($this->checkIfIssetPermission($this->routeToSlug($request, true))) {
            return $next($request);
        }

        if ($request->ajax()) {
            return response('Unauthorized.', 403);
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * @param $permission
     * @return bool
     */
    private function checkIfIssetPermission($permission)
    {
        $page = $this->adminPagesRepo->findBy('permission', $permission);
        $user = \Auth::user();

        if ($user->role->slug == User::ROLE_SUPERADMIN_SLUG) {
            return true;
        }


        if ($page && RenderService::checkAccess($page->id, $user->role->slug)) {
            return true;
        }

        return false;
    }

    /**
     * @param $request
     * @return mixed|string
     */
    private function routeToSlug($request, $withAdmin = false)
    {
        $route = $request->getRequestUri();
        $params = $request->route()->parameters();

        $route = substr($route, 7);
        $route = str_replace('/', '.', $route);
        $forDynamicParams = explode('.', $route);

        foreach ($forDynamicParams as $key => $slug) {
            if (is_numeric($slug)) {
                $forDynamicParams[$key] = "{params}";
            }

            if (in_array($slug, $params)) {
                $forDynamicParams[$key] = "{params}";
            }
        }
        $route = implode('.', $forDynamicParams);
        return ($withAdmin) ? "admin." . $route : $route;
    }
}