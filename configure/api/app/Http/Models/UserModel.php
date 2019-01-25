<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 11/30/2018
 * Time: 3:10 PM
 */

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{

    const CREATED_AT = 'regist_datetime';

    const UPDATED_AT = 'update_datetime';

    protected $table = 'tbl_user';

    protected $primaryKey = 'user_id';
}
