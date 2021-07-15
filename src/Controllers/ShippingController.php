<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\FormEntry;
use ChuckRepeater;
use ChuckSite;
use Mollie;
use URL;
use Mail;

class ShippingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function isAddressEligible(Request $request)
    {
        $locationKey = $request['locationKey'];

        $limited_to = ChuckRepeater::find($locationKey)->delivery_limited_to;

        if($limited_to == null) {
            return response()->json(['status' => 'success']);
        } elseif ($limited_to == 'radius') {
            return $this->checkRadius($request);
        } elseif ($limited_to == 'postalcode') {
            return $this->checkPostalcode($request);
        }

        return response()->json(['status' => 'error']);
    }

    public function checkRadius(Request $request)
    {
        $locationKey = $request['locationKey'];

        $from = urlencode(ChuckRepeater::find($locationKey)->delivery_radius_from);
        $to = urlencode($request['to']);
        $data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$from."&destinations=".$to."&language=en-EN&sensor=false&key=".ChuckSite::module('chuckcms-module-order-form')->getSetting('delivery.google_maps_api_key'));
        $data = json_decode($data);

        $time = 0;
        $distance = 0;
        if(isset($data->rows[0]->elements)){
            foreach($data->rows[0]->elements as $road) {
                if(!empty($road)){ //@todo fix validation on is empty or not
                    $time += $road->duration->value;
                    $distance += $road->distance->value;    
                } else {
                    return response()->json(['status' => 'error']);
                }            
            }

            if ($distance <= ChuckRepeater::find($locationKey)->delivery_radius ) {
                return response()->json(['status' => 'success']);
            }  
        } else {
            return response()->json(['status' => 'error']);
        }
        return response()->json(['status' => 'error']);
    }

    public function checkPostalcode(Request $request)
    {
        $locationKey = $request['locationKey'];
        if ( in_array($request['postalcode'], ChuckRepeater::find($locationKey)->delivery_in_postalcodes) ) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error']);
    }
}