<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\AdminPermission;
use App\Models\Section;
use App\Models\Role;
use Session;

class UserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()) {
            $user = Auth::user();

            if ($user->user_type == 'admin') {
                return $next($request);
            }else{
                $current_route = \Request::route()->getName();
                $routeArr = explode('.', $current_route);
                $section = $routeArr[0];
                $action = $routeArr[1];

                if ($action == 'update' || $action == 'delete' || $action == 'add_machine' || $action == 'getMakes' || $action == 'getSerialNumbers') {
                    return $next($request);
                }

                $permission = AdminPermission::where('user_id',$user->id)->get();

                if (count($permission)>0) {
                    $checkPermission = AdminPermission::join('roles','admin_permissions.role_id','=','roles.id')
                        ->select('admin_permissions.id as id','admin_permissions.user_id','admin_permissions.role_id','admin_permissions.action_id','roles.name','roles.name_slug','roles.url')
                        ->where('roles.name_slug',$section)
                        ->where('admin_permissions.user_id',$user->id)
                        ->whereRaw("find_in_set('".$action."',admin_permissions.action_id)")
                        ->first();

                    if (!empty($checkPermission)) {
                        return $next($request);
                    }else{
                        session()->flash('message', 'You do not have permission to access!');
                        Session::flash('alert-type', 'error');
                        return redirect('/admin');
                    }
                }else{
                    session()->flash('message', 'You do not have permission to access!');
                    Session::flash('alert-type', 'error');
                    return redirect('/admin');
                }
            }
        }

        return redirect('/admin')->with('error','You have not admin access');
    }
}