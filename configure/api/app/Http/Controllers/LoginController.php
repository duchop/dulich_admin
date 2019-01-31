<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 1/27/2019
 * Time: 1:21 PM
 */

namespace App\Http\Controllers;

use App\Constants\CommonConst;
use App\Http\Services\LoginService;
use App\Utils\Util;
use Illuminate\Http\Request;

/**
 * Class LoginController
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{

    /**
     * @var $login_service LoginService
     */
    private $login_service;

    /**
     * Phương thức khởi tạo
     *
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->login_service = app(LoginService::class);
    }

    /**
     * Trả về trang login cho user
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        return view('login');
    }

    /**
     * Phương thức kiếm tra thông tin đăng nhập của user
     *
     * @param Request $request
     */
    public function checkLogin(Request $request) {
        try {
            $email = $request->get('email');
            $password = $request->get('password');
            $result = $this->login_service->checkLogin($email, $password);

            if (! $result) {
                $error_msg = 'email hoặc mật khẩu không đúng';
                return view('login')->with('post_data', $request->all())->withErrors($error_msg);
            }

            return redirect()->route(CommonConst::HOME);
        } catch (\Exception $ex) {
            return view('404');
        }

    }
}