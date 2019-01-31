<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 1/27/2019
 * Time: 1:22 PM
 */

namespace App\Http\Services;


use App\Http\Models\sql\User;
use App\Utils\Util;

class LoginService extends Service
{
    /**
     * @var $user_sql User
     */
    private $user_sql;

    public function __construct()
    {
        $this->user_sql = app(User::class);
    }

    /**
     * Kiểm tra thông tin đăng nhập của user
     *
     * @param $email
     * @param $password
     * @return bool
     */
    public function checkLogin($email, $password) {
        $user = $this->user_sql->getLoginUserInfoByEmail($email);

        if ($user && Util::passDecryption($user->password) == $password) {
            session()->put('user_id', $user->user_id);
            return true;
        }

        return false;
    }
}