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
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Dealer;
use App\Models\SalesOrder;
use App\Helpers\AdminHelper;
use Carbon;

class SalesOrderController extends Controller
{
    /*Sales order list by user*/
    public function getSalesOrders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        }

        $data = SalesOrder::join('quotes','sales_orders.quote_id','=','quotes.id')
                            ->join('leads','leads.id','=','quotes.lead_id')
                            ->join('users','users.id','=','leads.user_id')
                            ->select('sales_orders.*','quotes.product_id','quotes.lead_id','quotes.attachment','leads.user_id','leads.name as lead_name','leads.email','leads.phone','leads.status','users.name as user_name')
                            ->where('leads.user_id',$request->user_id)
                            ->orderBy('sales_orders.id','DESC')
                            ->get();

        if (count($data)>0) {
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

    /*Create sale order*/
    public function createSalesOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote_id' => 'required',
            'tax' => 'required',
            'depot' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } else {
            $quote = Quote::find($request->quote_id);
            
            if(empty($quote)){
                return response()->json(array(
                                            'status' => 400,
                                            'message'=> 'Error',
                                            'error_message'=>'Wrong quote id!'
                                        ),200);
            }
            $quote = Quote::where('id',$request->quote_id)->first();
            $product_ids = explode(',', $quote->product_id);
            $quantities = explode(',', $quote->quantity);

            foreach ($product_ids as $key => $value) {
                if ($quantities[$key] == '1') {
                    $save_product = Product::find($value);
                    $save_product->status = 'Sold';
                    $save_product->save();
                }else{
                    $find_products = Product::where('id',$value)->first();
                    $duplicate_products = Product::where('title',$find_products->title)->limit($quantities[$key])->orderBy('order_no')->get();

                    foreach ($duplicate_products as $other_products) {
                        $save_product1 = Product::find($other_products->id);
                        $save_product1->status = 'Sold';
                        $save_product1->save();
                    }
                }
            }

            $data = new SalesOrder;
            //=========================================================
            $data->quote_id = $request->quote_id;
            $data->customer_id = $quote->customer_id;
            $data->price = $quote->price;
            $data->tax = $request->tax;
            $data->total_price = $quote->price + $request->tax;
            $data->message = $request->message;
            $data->date = date('Y-m-d');
            $data->depot = $request->depot;
            $data->hitch = $request->hitch;
            $data->buckets = $request->buckets;
            $data->extra = $request->extra;

            if ($data->save()) {
                return response()->json(array(
                                            'status' => 200,
                                            'message'=> 'Success',
                                            'success_message'=>'Sales order created successfully.',
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
