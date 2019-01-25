<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/20/2018
 * Time: 1:44 PM
 */

namespace App\Http\Services;


use App\Http\Models\sql\Transportation;

class ListTransportationService extends Service
{
    /**
     * @var Transportation $transportation
     */
    private $transportation;

    /**
     * Hàm khởi tạo của service
     */
    public function __construct()
    {
        $this->transportation = app(Transportation::class);
    }

    /**
     * Lấy thông tin hiển thị màn hình list transportation
     *
     * @return mixed
     * @throws \Exception
     */
    public function getData()
    {
        $ary_request_all = request()->all();

        $transportation_category_id = $ary_request_all['transportation_category_id'];

        // lấy thông tin hiển thị menu
        $data = parent::getMenuHeaderData();

        // lấy thông tin chi tiết tour
        $list_transportation = $this->transportation->getListTransportationByCategoryId($transportation_category_id, 3);
        $data['list_transportation'] = $list_transportation;
        return $data;
    }
}