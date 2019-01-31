<?php
namespace App\Http\Middleware;

use Closure;
use App\Constants\RegularConst;
use App\Constants\CommonConst;

class RedirectIfAuthenticated
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (! $this->isAuthenticated()) {
            return redirect()->secure(CommonConst::APP_LOGIN);
        }

        return $next($request);
    }

    /**
     * ログイン済みの場合にtrueを返します。
     */
    public function isAuthenticated()
    {
        $user_id = session()->get('user_id');
        if (! $user_id) {
            return false;
        }
        return true;
    }
}
