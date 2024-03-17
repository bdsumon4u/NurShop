<?php

namespace App\Http\Controllers;

use App\City;
use App\Order;
use App\Pathao\Facade\Pathao;
use App\Zone;
use Illuminate\Http\Request;

class PathaoController extends Controller
{

    public function pathao()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-hermes.pathaointernal.com/aladdin/api/v1/countries/1/city-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Accept-Encoding: gzip, deflate, br",
                "Accept-Language: en-US,en;q=0.9",
                "Connection: keep-alive",
                "Host: api-hermes.pathaointernal.com",
                "Origin: https://merchant.pathao.com",
                "Referer: https://merchant.pathao.com/deliveries/new",
                "Sec-Fetch-Mode: cors",
                "Sec-Fetch-Site: cross-site",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36",
                "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjUxYTQxMzRiMjcwMDBkNTcxZTdkZTllM2M1OWQyZDcxYWIwN2Y3MmQwMWU4MzM3NjAzN2Q4NDA5NGJjNTA2ODI0MGJjNDBlZDVkMjE0MGRlIn0.eyJhdWQiOiIzIiwianRpIjoiNTFhNDEzNGIyNzAwMGQ1NzFlN2RlOWUzYzU5ZDJkNzFhYjA3ZjcyZDAxZTgzMzc2MDM3ZDg0MDk0YmM1MDY4MjQwYmM0MGVkNWQyMTQwZGUiLCJpYXQiOjE1ODc4MzYwMjAsIm5iZiI6MTU4NzgzNjAyMCwiZXhwIjoxNTg4NzAwMDIwLCJzdWIiOiIxMjQ0MSIsInNjb3BlcyI6W119.TWUEXU5qJ0xvkTPkYH2sm6Fv3VXPvKMEyeNwwdumJRcddftKIvQI0phtLAMN-L5euSWbXw3V04e9AHFVABsfyb6HjkKZ9QujMv-8XosHqMGnEmv05WYUsknfCvLyeJHBojGhepvOSoBTYLIljp8cFPjMbXHeRu1UGxJFXZPy8DuS_KsztKxnAULv8En2BKzQflQSDZUDtekGaz_imcK4gzgQwIS_eDs9n8N-Vmz3eIts0MPozUvOccOAF5v5X65AGv6IX87exUhUEHbQ6GKf9R8QKCZ8Q1rMTRY8RZWYVdlLcu9iqjI142ZQGH_Dyk8pWLUy6pBf_6UzJwMBg3-2BVPwOSAaHbMiwCyIsW9_jkRH1yK0ysK-UMnWSUAA4qbzQl9MYw2oyDkGNCjjkuvIgGIPEmAf60Hcgj1gxuj2envmZFVBxd1p2LGZFUvyf74L_dGX0F2GQUf2AAYKx5fC6nosouvsm-u3343DYmi6UwFnHTP_fNMGWOzYCB-O53NQ6YUB6JijmA8QYZ8pQsoIX4pk7rLXGj-PWWby3RAg06qkpqFR_B0kMPvK7zHfoGm_d5fBJ58WbLPL8pzxtR3xph2UQBZjqAjEw6Db2zGQkVdqMjVJykxj3ruYLh8kvLMiKAgvku_XBeon7rNTT0ymAMgkOhJiSOaIOy9uau6OBdk"
            ),
        ));

        $response = json_decode(curl_exec($curl),true);
        $courier_id = 1;
        $cities =  $response['data']['data'];
         foreach ($cities as $city){
            echo 'city '.$city['city_name'];
             $DBcity = City::query()->where('cityName','like','%'.$city['city_name'].'%')->first();
             if(!$DBcity){
                 $DBcity = new City();
                 $DBcity->courier_id = $courier_id;
                 $DBcity->cityName = $city['city_name'];
                 $DBcity->save();
             }else{
                 echo ' available';
             }
             echo '<br>';
            $zones = $this->getzone($city['city_id']);
            foreach ($zones as $zone){
                echo '&nbsp;&nbsp;Zone '.$zone['zone_name'];
                $DBzone = Zone::query()->where('zoneName','like','%'.$zone['zone_name'].'%')->first();
                if(!$DBzone){
                    $DBzone = new Zone();
                    $DBzone->courier_id = $courier_id;
                    $DBzone->city_id = $DBcity->id;
                    $DBzone->zoneName = $zone['zone_name'];
                    $DBzone->save();
                }else{
                    echo ' available';
                }
                echo '<br>';
            }
        }
    }


    public function getzone($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-hermes.pathaointernal.com/aladdin/api/v1/cities/".$id."/zone-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Accept-Encoding: gzip, deflate, br",
                "Accept-Language: en-US,en;q=0.9",
                "Connection: keep-alive",
                "Host: api-hermes.pathaointernal.com",
                "Origin: https://merchant.pathao.com",
                "Referer: https://merchant.pathao.com/deliveries/new",
                "Sec-Fetch-Mode: cors",
                "Sec-Fetch-Site: cross-site",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36",
                 "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjUxYTQxMzRiMjcwMDBkNTcxZTdkZTllM2M1OWQyZDcxYWIwN2Y3MmQwMWU4MzM3NjAzN2Q4NDA5NGJjNTA2ODI0MGJjNDBlZDVkMjE0MGRlIn0.eyJhdWQiOiIzIiwianRpIjoiNTFhNDEzNGIyNzAwMGQ1NzFlN2RlOWUzYzU5ZDJkNzFhYjA3ZjcyZDAxZTgzMzc2MDM3ZDg0MDk0YmM1MDY4MjQwYmM0MGVkNWQyMTQwZGUiLCJpYXQiOjE1ODc4MzYwMjAsIm5iZiI6MTU4NzgzNjAyMCwiZXhwIjoxNTg4NzAwMDIwLCJzdWIiOiIxMjQ0MSIsInNjb3BlcyI6W119.TWUEXU5qJ0xvkTPkYH2sm6Fv3VXPvKMEyeNwwdumJRcddftKIvQI0phtLAMN-L5euSWbXw3V04e9AHFVABsfyb6HjkKZ9QujMv-8XosHqMGnEmv05WYUsknfCvLyeJHBojGhepvOSoBTYLIljp8cFPjMbXHeRu1UGxJFXZPy8DuS_KsztKxnAULv8En2BKzQflQSDZUDtekGaz_imcK4gzgQwIS_eDs9n8N-Vmz3eIts0MPozUvOccOAF5v5X65AGv6IX87exUhUEHbQ6GKf9R8QKCZ8Q1rMTRY8RZWYVdlLcu9iqjI142ZQGH_Dyk8pWLUy6pBf_6UzJwMBg3-2BVPwOSAaHbMiwCyIsW9_jkRH1yK0ysK-UMnWSUAA4qbzQl9MYw2oyDkGNCjjkuvIgGIPEmAf60Hcgj1gxuj2envmZFVBxd1p2LGZFUvyf74L_dGX0F2GQUf2AAYKx5fC6nosouvsm-u3343DYmi6UwFnHTP_fNMGWOzYCB-O53NQ6YUB6JijmA8QYZ8pQsoIX4pk7rLXGj-PWWby3RAg06qkpqFR_B0kMPvK7zHfoGm_d5fBJ58WbLPL8pzxtR3xph2UQBZjqAjEw6Db2zGQkVdqMjVJykxj3ruYLh8kvLMiKAgvku_XBeon7rNTT0ymAMgkOhJiSOaIOy9uau6OBdk"
            ),
        ));
         $response = json_decode(curl_exec($curl),true);
            return $response['data']['data'];
    }
    
    
        public function redx(){


        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.redx.com.bd/v1/logistics/address-mapping/area-tree',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer d4e9c0887c23cd76ce0129ae0317ba967f17505cfa71f1cc18f88ae25164506aca9f41831aba74d66a32fa28fd792d131c99f690258c40ee4ee818c64f98475e'
          ),
        )); 
        $courier_id = 24;
        $response = json_decode(curl_exec($curl),true);
        foreach ($response['body']['Divisions'] as $Divisions) {
            foreach ($Divisions['Districts'] as $Districts) {

                echo "<br>";
                echo "<br>";
                echo $Districts['NAME'];
                echo "<br>";
                echo "<br>";

                $DBcity = City::query()->where([
                    ['cityName','like','%'.$Districts['NAME'].'%'],
                    ['courier_id', '=', $courier_id]
                ])->first();
    
                if(!$DBcity){
                    $DBcity = new City();
                    $DBcity->courier_id = $courier_id;
                    $DBcity->cityName = $Districts['NAME'];
                    $DBcity->save();
                }else{
                    
                    echo ' available';

                }



                foreach ($Districts['Areas'] as $Areas) {
                    
                    echo $Areas['NAME'];
                    echo "<br>";

                    $DBzone = Zone::query()->where([
                        ['zoneName','like','%'.$Areas['NAME'].'%'],
                        ['courier_id', '=', $DBcity->id],
                        ['city_id', '=', $DBcity->id]
                    ])->first();

                    if(!$DBzone){
                        $DBzone = new Zone();
                        $DBzone->courier_id = $courier_id;
                        $DBzone->city_id = $DBcity->id;
                        $DBzone->zoneName = $Areas['NAME'];
                        $DBzone->save();
                    }else{
                        echo ' available';
                    }
                }
                
                


            }
        }
         



    }
    
    public function booking()
    {
        $orders =  Order::with('user', 'courier', 'city', 'zone', 'products', 'notification')
            ->join('customers', 'customers.order_id', '=', 'orders.id')
            ->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress');
        $orders = $orders->whereIn('orders.status', ['Invoiced'])
            ->where(function ($query) {
                $query->where('orders.memo', '=', 0)
                    ->orWhere('orders.memo', '=', null);
            })


            ->orderBy('orders.id', 'DESC')
            ->get();

        foreach ($orders as $order) {
            if ($order->courier_id == 1 && !empty($order->city->cityName) && !empty($order->zone->belongsToID)) {
                $response = json_encode(['status' => 'success', 'message' => $order->invoiceID . ': Successfully booked']);
                if ($order->memo) {
                    $details = \App\Pathao\Facade\Pathao::order()->orderDetails($order->memo);
                    if ($details->order_status != 'Pickup Cancel') continue;
                }

                $data = [
                    "store_id"            => "4745", // Find in store list,
                    "merchant_order_id"   => $order->invoiceID, // Unique order id
                    "recipient_name"      => $order->customerName, // Customer name
                    "recipient_phone"     => \Illuminate\Support\Str::after($order->customerPhone, '+88'), // Customer phone
                    "recipient_address"   => str_replace(array("\n", "\r"), '', $order->customerAddress), // Customer address
                    "recipient_city"      => $order->city_id - 1000, // Find in city method
                    "recipient_zone"      => $order->zone->belongsToID, // Find in zone method
                    // "recipient_area"      => "", // Find in Area method
                    "delivery_type"       => 48, // 48 for normal delivery or 12 for on demand delivery
                    "item_type"           => 2, // 1 for document, 2 for parcel
                    // "special_instruction" => "",
                    "item_quantity"       => 1, // item quantity
                    "item_weight"         => 0.5, // parcel weight
                    "amount_to_collect"   => $order->subTotal, // - $order->deliveryCharge, // amount to collect
                    "item_description"    => $this->getProductsDetails($order->id), // product details
                ];
                // dd(collect(\App\Pathao\Facade\Pathao::area()->city()->data)->filter(fn ($item) => $item->city_id == 23));
                // dd($data);

                try {
                    $data = \App\Pathao\Facade\Pathao::order()->create($data);

                    // $courier = collect(json_decode(json_encode($data), true))->only([
                    //     'consignment_id',
                    //     'order_status',
                    //     'reason',
                    //     'invoice_id',
                    //     'payment_status',
                    //     'collected_amount',
                    // ])->all();
                    $order->memo = $data->consignment_id;
                    $order->save();
                    // $order->forceFill(['courier' => ['booking' => 'Pathao'] + $courier])->save();
                } catch (\Exception $e) {
                    $city = collect(Pathao::area()->city()->data)->filter(function ($city) use (&$data) {
                        return $city->city_id == $data['recipient_city'];
                    })->first();
                    $zone = collect(Pathao::area()->zone($data['recipient_city'])->data)->filter(function ($zone) use (&$data) {
                        return $zone->zone_id == $data['recipient_zone'];
                    })->first();
                    $errors = collect($e->errors ?? null)->values()->flatten()->toArray();
                    $response = json_encode(['status' => 'failed', 'message' => $order->invoiceID . ': ' . ($errors[0] ?? $e->getMessage())]);
                    dump($city, $zone, $data, $errors, $response);
                    info($order->invoiceID . ': ' . ($errors[0] ?? $e->getMessage()));
                }

                echo $response;
            }
        }
    }
}
