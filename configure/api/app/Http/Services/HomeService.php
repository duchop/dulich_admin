<?php

namespace App\Http\Services;

use App\Http\Models\sql\Hotel;
use App\Http\Models\sql\Tour;
use App\Http\Models\sql\Transportation;

class HomeService extends Service
{

    /**
     * @var Tour $tour
     */
    private $tour;

    /**
     * @var Hotel $hotel
     */
    private $hotel;

    /**
     * @var Transportation $transportation
     */
    private $transportation;

    /**
     * Hàm khởi tạo của service
     */
    public function __construct()
    {
        $this->tour = app(Tour::class);
        $this->hotel = app(Hotel::class);
        $this->transportation = app(Transportation::class);
    }

    /**
     *
     *
     * @throws \Exception
     * @throws ApiException
     */
    public function getData($type)
    {
        try {
            if ($type == 'list_hotel') {
                $ary_hotel = $this->hotel->getListHotels();
                $data['ary_hotels'] = $ary_hotel;
            } elseif ($type == 'list_transportation') {
                $ary_transportation = $this->transportation->getListTransportation();
                $data['ary_transportation'] = $ary_transportation;
            } else {
                $ary_tour = $this->tour->getListTour();
                $data['ary_tour'] = $ary_tour;
            }
            return $data;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
