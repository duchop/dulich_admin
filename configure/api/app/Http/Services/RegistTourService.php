<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 1/28/2019
 * Time: 5:00 PM
 */

namespace App\Http\Services;


use App\Http\Models\sql\CategoryTour;
use App\Http\Models\sql\Tour;

class RegistTourService extends Service
{
    /**
     * @var $tour Tour
     */
    private $tour;

    /**
     * @var $category_tour CategoryTour
     */
    private $category_tour;

    /**
     * Hàm khởi tạo của service
     *
     * RegistTourService constructor.
     */
    public function __construct()
    {
        $this->tour = app(Tour::class);
        $this->category_tour = app(CategoryTour::class);
    }

    /**
     * Lấy thông tin đẻ hiển thị lên view
     *
     * @return mixed
     */
    public function getData()
    {
        $list_category_tour = $this->category_tour->getListCategoryTour();
        return $list_category_tour;
    }

}