<?php

namespace App\Http\Controllers;

use App\City;
use App\Zone;
use Illuminate\Http\Request;

class DeliveryTigerController extends Controller
{
    public function deliveryTiger()
    {
        $result =  json_decode($this->getTigerDelivery(0));
        $courier_id = 4;
         foreach ($result->data->districtInfo as $item) {
             echo '<br>';
             echo $item->district;
             echo '<br>';

             $city = City::query()->where([
                 ['cityName','like',$item->district],
                 ['courier_id','=',$courier_id]
             ])->get()->first();

             if(!$city){
                 $city = new City();
                 $city->courier_id = $courier_id;
                 $city->cityName = $item->district;
                 $city->save();
                 $city['id'] = $city->id;
             }

//             var_dump($city['id']);
//             var_dump($city->id);

//             die();

            $result =  json_decode($this->getTigerDelivery($item->districtId));
//            var_dump($result->data->districtInfo[0]->thanaHome);
             $thanaHome = $result->data->districtInfo[0]->thanaHome;
            foreach ($thanaHome as $thana) {
//                echo $zoneName =  ucfirst(strtolower($thana->thana));

                $zone = Zone::query()->where([
                    ['zoneName','like',$thana->thana],
                    ['city_id','=',$city['id']],
                    ['courier_id','=',$courier_id]
                ])->get()->first();

                if(!$zone){
                    $zone = new Zone();
                    $zone->city_id = $city['id'];
                    $zone->courier_id = $courier_id;
                    $zone->zoneName = ucfirst(strtolower($thana->thana));
                    $zone = $zone->save();
                }
            //    die();

                echo '<br>';
            }
             echo '<br>';
             echo '<br>';
        }
    }
    public function getTigerDelivery($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://adcore.ajkerdeal.com/api/Other/GetAllDistrictFromApi/" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Access-Control-Allow-Origin:  *",
                "Accept: application/json",
                "Origin:  https://deliverytiger.com.bd",
                "Authorization:  Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjU1NzUiLCJyb2xlIjoiRGVsaXZlcnlUaWdlciIsIm5iZiI6MTU4ODEwNTIxMCwiZXhwIjoxNTg4MTkxNjEwLCJpYXQiOjE1ODgxMDUyMTB9.h9kWza4Kbk9YGobk27A65Awz1qLnilCyTkcbGiIiDmA",
                "User-Agent:  Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36",
                "Content-Type:  application/json",
                "Sec-Fetch-Site:  cross-site",
                "Sec-Fetch-Mode:  cors",
                "Referer:  https://deliverytiger.com.bd/add-order",
                "Accept-Encoding:  gzip, deflate, br",
                "Accept-Language:  en-US,en;q=0.9"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
