<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 11/30/2018
 * Time: 3:10 PM
 */

namespace App\Http\Models\sql;

use App\Constants\CommonConst;
use App\Http\Models\UserModel;

class User
{
    /**
     * Lấy thông tin user
     *
     * @param $user_name
     * @return mixed
     */
    public function getUserInfo($user_id)
    {
        $ary_colums = [
            'user_id',
            'user_name',
            'email',
            'area',
            'password',
            'number_phone',
            'address_office'
        ];
        $user = UserModel::where('user_id', $user_id)->first($ary_colums);

        return $user;
    }

    /**
     * Lấy thông tin đăng nhập của user
     *
     * @param $email
     * @param $password
     * @return mixed
     */
    public function getLoginUserInfoByEmail($email) {
        $ary_colums = [
            'user_id',
            'password'
        ];
        $result = UserModel::where('email', $email)->where('role', CommonConst::ROLE_ADMIN)->first($ary_colums);

        return $result;
    }
}