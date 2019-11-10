<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\MerchantModel;
use Illuminate\Support\Facades\DB;

class MerchantController extends Controller
{
    //

    public function getMerchatNearBy(Request $request){
        $origin = array(
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude')
        );
        
        $result = DB::table('merchant')
                    ->leftjoin('category', 'category.id', '=', 'merchant.idCategory')
                    ->leftjoin('rating', 'rating.idMerchant', '=', 'merchant.id')
                    ->select(DB::raw('merchantName,longitude,latitude,address,open,category.name,AVG(stars) as stars'))
                    ->groupBy('merchant.id')
                    ->get();

        $result = json_decode($result, true);
        for($i = 0;$i < count($result);$i++){
            $result[$i]['distance'] = $this->getMerchantDistance($origin,$result[$i]);
        }

       
        usort($result, function($a, $b) { //Sort the array using a user defined function
			return $a['distance'] < $b['distance'] ? -1 : 1; //Compare the scores
		});

        return $result;
        
    }

    public function getMerchatCategory(Request $request){
        
    }

    public function getMerchatPromotion(Request $request){

    }

    public function getPromotionBanner(Request $request){
        
    }

    public function getMerchantDistance($origin,$destination){

        $distance_data = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?&origins='.$origin['latitude'].','.$origin['longitude'].'&destinations='.$destination['latitude'].','.$destination['longitude'].'&key=AIzaSyDqbm_elcDKWWLMF2YT9a8-9Q0fns2YTEo');
		$distance_arr = json_decode($distance_data);
        //echo $distance_data;
        $result = $distance_arr->rows;
        return $result[0]->elements[0]->distance->value;
    }

    public function editMerchant(Request $request){

    }

    
}
