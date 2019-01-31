<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 1/28/2019
 * Time: 10:39 AM
 */

namespace App\Http\Controllers;


use App\Http\Services\RegistTourService;
use Illuminate\Http\Request;

class RegistTourController extends Controller
{
    /**
     * @var $regist_tour_service RegistTourService
     */
    private $regist_tour_service;

    /**
     * Hàm khởi tạo của controller
     *
     * RegistTourController constructor.
     */
    public function __construct()
    {
        $this->regist_tour_service = app(RegistTourService::class);
    }

    public function index(Request $request)
    {
        $data['list_category_tour'] = $this->regist_tour_service->getData();

        return view('regist_tour')->with($data);
    }
}