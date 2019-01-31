<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\HomeService;

class HomeController extends Controller
{

    /**
     * Service
     *
     * @var HomeService $service
     */
    private $service;

    /**
     * Hàm khởi tạo của controller
     */
    public function __construct()
    {
        $this->service = app(HomeService::class);
    }

    /**
     * Action được gọi đến khi vào trang chủ
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        try {

            $type = $request->get('type');
            $data = $this->service->getData($type);
            $data['type'] = $type;

            if ($type == 'list_hotel') {
                return view('index')->with($data);
            } elseif ($type == 'list_transportation') {
                return view('index')->with($data);
            } else {
                return view('index')->with($data);
            }
        } catch (\Exception $e) {
            return view('error')->with($data);
        }
    }
}
