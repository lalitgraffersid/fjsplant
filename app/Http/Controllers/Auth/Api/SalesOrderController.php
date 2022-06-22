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
use App\Models\QuoteProduct;
use App\Models\Product;
use App\Models\ExtraProductInfo;
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
                            ->join('customers','customers.id','=','sales_orders.customer_id')
                            ->join('products','products.id','=','sales_orders.product_id')
                            ->join('users','users.id','=','leads.user_id')
                            ->select('sales_orders.*','products.title','customers.name as customer_name','sales_orders.price','sales_orders.product_id','quotes.lead_id','quotes.attachment','leads.user_id','leads.name as lead_name','leads.email','leads.phone','leads.status','users.name as user_name')
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
        ]);
        if ($validator->fails()) { 
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        } else {
            $quote = Quote::where('id',$request->quote_id)->first();
            $quote_products = QuoteProduct::where('quote_id',$request->quote_id)->get();

            foreach ($quote_products as $key => $value) {
                $serial_no = Product::where('id',$value->product_id)->first();
                if ($serial_no->status == "In Stock") {
                    $serial_number = $serial_no->stock_number;
                }else{
                    $serial_number = '';
                }

                $data = new SalesOrder;
                //=========================================================
                $data->quote_id = $request->quote_id;
                $data->customer_id = $quote->customer_id;
                $data->product_id = $value->product_id;
                $data->price = $value->price;
                $data->qty = $value->quantity;
                $data->tax = ($value->price * $value->quantity) * 12 / 100;
                $data->total_price = ($value->price * $value->quantity) + $data->tax;
                $data->message = $request->message;
                $data->serial_number = $serial_number;
                $data->date = date('Y-m-d');
                $data->save();
            }

            foreach ($quote_products as $key => $value) {
                if ($value->quantity == '1') {
                    $save_product = Product::find($value->product_id);
                    $save_product->status = 'Sold';
                    $save_product->save();
                }else{
                    $find_products = Product::where('id',$value->product_id)->first();
                    $duplicate_products = Product::where('title',$find_products->title)->limit($value->quantity)->orderBy('order_no')->get();

                    foreach ($duplicate_products as $other_products) {
                        $save_product1 = Product::find($other_products->id);
                        $save_product1->status = 'Sold';
                        $save_product1->save();
                    }
                }
            }

            return response()->json(array(
                                        'status' => 200,
                                        'message'=> 'Success',
                                        'success_message'=>'Sales order created successfully.',
                                        'data' => $data,
                                    ),200);
        }
    }
   public function filterSalesOrder(Request $request)
    {
       $query = SalesOrder::join('products','sales_orders.product_id','=','products.id')
                        ->join('customers','sales_orders.customer_id','=','customers.id')
                        ->join('dealers','products.dealer_id','=','dealers.id')
                        ->join('quotes','sales_orders.quote_id','=','quotes.id')
                        ->join('leads','leads.id','=','quotes.lead_id');;

        if (!empty($request->search)) {
            $search = $request->search;
            $query = $query->where('products.title','LIKE','%'.$search.'%');
        }
        if (!empty($request->model)) {
            $model = $request->model;
            $query = $query->where('products.model','LIKE','%'.$model.'%');
        }
        if (!empty($request->type)) {
            $type = $request->type;
            $query = $query->where('products.type',$type);
        }
        if (!empty($request->status)) {
            $status = $request->status;
            $query = $query->where('products.status',$status);
        }
        if (!empty($request->date)) {
            $date = $request->date;
            $query = $query->where('sales_orders.date',$date);
        }
        if (!empty($request->customers)) {
            $customer = $request->customers;
            $query = $query->where('customers.name','LIKE','%'.$customer.'%');
        }
		if (!empty($request->make)) {
			$make = $request->make;
			$query = $query->where('dealers.name','LIKE','%'.$make.'%');
        }

        $data = $query->select('sales_orders.*','products.title as product_title','products.type as product_type',
				'products.status as product_status','dealers.name as dealer_name')
                            ->where('leads.user_id',$request->user_id)
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
   
}