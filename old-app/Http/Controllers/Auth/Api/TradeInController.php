<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;
use Input;
use Auth;
use Cookie;
use Session;
use DB;
use Image;
use File;
use Exception;
use App\User;
use App\Models\TradeIn;
use App\Models\TradeImage;
use App\Helpers\AdminHelper;
use Carbon;
use Illuminate\Support\Facades\Storage;
use Response;

class TradeInController extends Controller
{
    /*Create Trade in with quote*/
    public function saveTradeIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote_id' => 'required',
            'make' => 'required',
            'model' => 'required',
            'year' => 'required',
            'hours' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } else {
            $data = new TradeIn;
            //=========================================================
            $data->quote_id = $request->quote_id;
            $data->make = $request->make;
            $data->model = $request->model;
            $data->year = $request->year;
            $data->hours = $request->hours;
            if ($data->save()) {
                $images = $request->file('image');
                foreach ($images as $key => $image) {
                    $imagename = rand('1111','9999').'_'.time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/admin/clip-one/assets/trade_images');
                    $image->move($destinationPath, $imagename);

                    $source_url = public_path().'/admin/clip-one/assets/trade_images/'.$imagename;
                    $destination_url = public_path().'/admin/clip-one/assets/trade_images/'.$imagename;
                    $quality = 40;

                    AdminHelper::compress_image($source_url, $destination_url, $quality);

                    $trade_image = new TradeImage;
                    $trade_image->trade_id = $data->id;
                    $trade_image->image = $imagename;
                    $trade_image->save();
                }

                return response()->json(array(
                                            'status' => 200,
                                            'message'=> 'Success',
                                            'success_message'=>'Trade in submitted successfully.',
                                            'data' => $data,
                                        ),200);
            }else{
                return response()->json(array(
                                            'status' => 400,
                                            'message'=> 'Error',
                                            'error_message'=>'Something went wrong!'
                                        ),200);
            }
        }
    }
}