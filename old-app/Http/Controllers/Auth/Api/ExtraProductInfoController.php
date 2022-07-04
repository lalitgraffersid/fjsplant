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
use Mail;
use App\User;
use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\Quote;
use App\Models\SalesOrder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Dealer;
use App\Models\Customer;
use App\Models\ExtraProductInfo;
use App\Helpers\AdminHelper;
use Carbon;
use Illuminate\Support\Facades\Storage;
use Response;

class ExtraProductInfoController extends Controller
{
   public function AddExtraProductInfo(Request $request)
   {
        $validator = Validator::make($request->all(), [
            'quote_id' => 'required',
            'product_id' => 'required',
            'user_id' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } else {
            $data = ExtraProductInfo::where('quote_id',$request->quote_id)
                                    ->where('product_id',$request->product_id)
                                    ->where('user_id',$request->user_id)
                                    ->first();
            
            if(empty($data)){
                $extra_info = new ExtraProductInfo;
                $extra_info->quote_id = $request->quote_id;
                $extra_info->product_id = $request->product_id;
                $extra_info->user_id = $request->user_id;
                $extra_info->depot = $request->depot;
                $extra_info->hitch = $request->hitch;
                $extra_info->buckets = $request->buckets;
                $extra_info->extra = $request->extra;
            }else{
                $extra_info = ExtraProductInfo::find($data->id);
                $extra_info->depot = $request->depot;
                $extra_info->hitch = $request->hitch;
                $extra_info->buckets = $request->buckets;
                $extra_info->extra = $request->extra;
            }

            if ($extra_info->save()) {
                return response()->json(array(
                                        'status' => 200,
                                        'message'=> 'Success',
                                        'success_message'=>'Extra Info created successfully.',
                                        'data' => $extra_info,
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

    ///get extra product details//
    public function getExtraProduct(Request $request)
    {
        $data = ExtraProductInfo::where('quote_id',$request->quote_id)
                                ->where('product_id',$request->product_id)
                                ->where('user_id',$request->user_id)
                                ->first();

        if (!empty($data)) {
            return response()->json(array(
                        'status' => 200,
                        'message'=> 'Success',
                        'success_message'=>'Data found.',
                        'data' => $data
                    ),200);
        }else{
            return response()->json(array(
                        'status' => 400,
                        'message'=> 'Error',
                        'error_message'=>'No data found!'
                    ),200);
        }
    }

}