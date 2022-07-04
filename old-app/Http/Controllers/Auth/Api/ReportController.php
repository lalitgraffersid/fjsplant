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
use App\Helpers\AdminHelper;
use Carbon;

class ReportController extends Controller
{
    /*Quotes Reports by user (Sales Calls)*/
    public function quotesReport(Request $request)
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

        // if (!empty($request->search) || !empty($request->lead_id) || !empty($request->date)) {

            $query = Quote::join('leads','quotes.lead_id','=','leads.id');

            // if (!empty($request->search)) {
            //     $search = $request->search;
            //     $query = $query->where(function($query) use($search) {
            //                         return $query->where('leads.name','LIKE','%'.$search.'%')
            //                                     ->orWhere('leads.email','LIKE','%'.$search.'%')
            //                                     ->orWhere('leads.phone','LIKE','%'.$search.'%');
            //                     });
            // }
            // if (!empty($request->lead_id)) {
            //     $lead_id = $request->lead_id;
            //     $query = $query->where('quotes.lead_id',$lead_id);
            // }
            // if (!empty($request->date)) {
            //     $date = $request->date;
            //     $query = $query->where('quotes.date',$date);
            // }
                        
            $quotes = $query->select('quotes.*','leads.name','leads.email','leads.phone','leads.user_id','leads.date as lead_date','leads.status')
                        ->where('leads.user_id',$request->user_id)
                        ->where('quotes.sent','1')
                        ->get();

            $data = [];
            foreach($quotes as $quote){
                $data[] = [
                        'id' => $quote->id,
                        //'products' => $this->getProducts($quote->product_id),
                        'lead_id' => $quote->lead_id,
                        'attachment' => $quote->attachment,
                        'price' => $quote->price,
                        'quote_date' => date('Y-m-d',strtotime($quote->created_at)),
                        'lead_name' => $quote->name,
                        'lead_email' => $quote->email,
                        'lead_phone' => $quote->phone,
                        'user_id' => $quote->user_id,
                        'lead_date' => $quote->lead_date,
                        'lead_status' => $quote->status,
                    ];
            }
        // }else{
        //     $data = [];
        // }

        if (count($data)>0) {
            return response()->json(array(
                        'status' => 200,
                        'message'=> 'Success',
                        'success_message'=>'Data found.',
                        'product_image_path' => url('/public/admin/clip-one/assets/products/original/'),
                        'quote_attachment_path' => url('/public/admin/clip-one/assets/quotes/'),
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

    /*Sales Order Report by user (Sales Calls)*/
    public function salesOrdersReport(Request $request)
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

        if (!empty($request->search) || !empty($request->lead_id) || !empty($request->date) || $request->PDI_status == '0' || $request->PDI_status == '1' || $request->payment_confirm == '0' || $request->payment_confirm == '1') {

            $query = SalesOrder::join('quotes','sales_orders.quote_id','=','quotes.id')
                            ->join('leads','leads.id','=','quotes.lead_id');

            if (!empty($request->search)) {
                $search = $request->search;
                $query = $query->where(function($query) use($search) {
                                    return $query->where('leads.name','LIKE','%'.$search.'%')
                                                ->orWhere('leads.email','LIKE','%'.$search.'%')
                                                ->orWhere('leads.phone','LIKE','%'.$search.'%');
                                });
            }
            if (!empty($request->lead_id)) {
                $lead_id = $request->lead_id;
                $query = $query->where('quotes.lead_id',$lead_id);
            }
            if (!empty($request->date)) {
                $date = $request->date;
                $query = $query->where('sales_orders.date',$date);
            }
            if (!empty($request->PDI_status)) {
                $PDI_status = $request->PDI_status;
                $query = $query->where('sales_orders.PDI_status',$PDI_status);
            }
            if (!empty($request->payment_confirm)) {
                $payment_confirm = $request->payment_confirm;
                $query = $query->where('sales_orders.PDI_status',$payment_confirm);
            }
                        
            $data = $query->select('sales_orders.*','quotes.product_id','quotes.lead_id','quotes.attachment','leads.user_id','leads.name','leads.email','leads.phone','leads.status')
                            ->where('leads.user_id',$request->user_id)
                            ->get();
        }else{
            $data = [];
        }

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

    /*Stock Report by user (Quotes)*/
    public function stockReport(Request $request)
    {
        if (!empty($request->search) || !empty($request->dealer_id) || !empty($request->model) || !empty($request->type) || !empty($request->status)) {

            $query = Product::join('dealers','products.dealer_id','=','dealers.id')
                        ->join('categories','products.category_id','=','categories.id');

            if (!empty($request->search)) {
                $search = $request->search;
                $query = $query->where('products.title','LIKE','%'.$search.'%');
            }
            if (!empty($request->dealer_id)) {
                $dealer_id = $request->dealer_id;
                $query = $query->where('products.dealer_id',$dealer_id);
            }
            if (!empty($request->model)) {
                $model = $request->model;
                $query = $query->where('products.model',$model);
            }
            if (!empty($request->type)) {
                $type = $request->type;
                $query = $query->where('products.type',$type);
            }
            if (!empty($request->status)) {
                $status = $request->status;
                $query = $query->where('products.status',$status);
            }

            $products = $query->select('products.*','dealers.name as dealer_name','categories.name as category_name')
                            ->groupBy('products.title')
                            ->orderBy('products.order_no')
                            ->get();

            $data = [];
            foreach ($products as $key => $product) {
                $in_stock = Product::where('title',$product->title)->count();
                if(count($this->getProductImages($product->id))>0){
                    $product_image = $this->getProductImages($product->id)[0]->image;
                }else{
                    $product_image = '';
                }
                
                $data[] = [
                    'id' => $product->id,
                    'category_id' => $product->category_id,
                    'category_name' => $product->category_name,
                    'dealer_id' => $product->dealer_id,
                    'dealer_name' => $product->dealer_name,
                    'stock_number' => $product->stock_number,
                    'backorder_number' => $product->backorder_number,
                    'title' => $product->title,
                    'model' => $product->model,
                    'year' => $product->year,
                    'hours' => $product->hours,
                    'weight' => $product->weight,
                    //'description' => $product->description,
                    'price' => $product->price,
                    'type' => $product->type,
                    'attachment' => $product->attachment,
                    'status' => $product->status,
                    'product_image' => $product_image,
                    //'images' => $this->getProductImages($product->id),
                    'in_stock' => $in_stock,
                    'upcoming_quantity' => $product->upcoming_quantity,
                    'date' => $product->date,
                ];
            }
        }else{
            $data = [];
        }

        if (count($data)>0) {
            return response()->json(array(
                        'status' => 200,
                        'message'=> 'Success',
                        'success_message'=>'Data found.',
                        'image_path' => url('/public/admin/clip-one/assets/products/original/'),
                        'attachment_path' => url('/public/admin/clip-one/assets/products/attachment/'),
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

    public function getProductImages($id)
    {
        $data = ProductImage::where('product_id',$id)->get();

        if (count($data)>0) {
            return $data;
        }else{
            return [];
        }
    }

}
