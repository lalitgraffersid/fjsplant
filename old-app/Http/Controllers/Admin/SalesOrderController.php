<?php
namespace App\Http\Controllers\Admin;

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
use App\Models\User;
use App\Models\Action;
use App\Models\Role;
use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\Quote;
use App\Models\QuoteProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\ExtraProductInfo;
use App\Models\Dealer;
use App\Models\AdminPermission;
use App\DataTables\SalesOrderDataTable;
use App\Helpers\AdminHelper;

class SalesOrderController extends Controller
{
    //=================================================================

	public function index(Request $request)
	{
        SalesOrder::where('is_read','0')->update(['is_read'=>'1']);

        $data = [];
        //==============================================
        $status_action = Action::where('action_slug','status')->first();
        $data['checkStatusAction'] = Role::where('name_slug','sales_order')->whereRaw("find_in_set('".$status_action->id."',action_id)")->first();
        $data['roles'] = Role::where('name_slug','sales_order')->first();
        $data['checkStatusPermission'] = AdminPermission::where('user_id',Auth::user()->id)->whereRaw("find_in_set('status',action_id)")->first();
        $data['action_ids'] = explode(',', $data['roles']->action_id);
        //==============================================
        $data['customers'] = Customer::where('status','1')->get();
        $data['dealers'] = Dealer::where('status','1')->get();

        if (!empty($request->from) || !empty($request->to) || !empty($request->customer) || !empty($request->status) || $request->delivered == '0' || $request->delivered == '1' || !empty($request->dealer_id) || !empty($request->model)) {
            $query = SalesOrder::join('products','sales_orders.product_id','=','products.id')
                                ->orderBy('id','DESC')
                                ->select('sales_orders.*','products.dealer_id','products.model');

            if (!empty($request->from)) {
                $query = $query->where('date','>=',$request->from);
            }
            if (!empty($request->to)) {
                $query = $query->where('date','<=',$request->to);
            }
            if (!empty($request->customer)) {
                $query = $query->where('customer_id',$request->customer);
            }
            if (!empty($request->status)) {
                if ($request->status == 'Closed') {
                    $query = $query->where('PDI_status','1')
                                    ->where('payment_confirm','1')
                                    ->where('delivered','1');
                }else{
                    $query = $query->where('PDI_status','0')
                                    ->orWhere('payment_confirm','0')
                                    ->orWhere('delivered','0');
                }
            }
            if ($request->delivered == '0' || $request->delivered == '1') {
                $query = $query->where('delivered',$request->delivered);
            }
            if (!empty($request->dealer_id)) {
                $query = $query->where('dealer_id',$request->dealer_id);
            }
            if (!empty($request->model)) {
                $query = $query->where('model',$request->model);
            }
            $data['results'] = $query->paginate(10);
        }else{
            $data['results'] = SalesOrder::orderBy('id','DESC')->paginate(10);
        }

		return view('admin/sales_order/index',$data);
	}

    //===================================================

    public function view($id)
    {
    	$data['result'] = SalesOrder::join('quotes','sales_orders.quote_id','=','quotes.id')
                            ->join('leads','leads.id','=','quotes.lead_id')
		                    ->join('users','users.id','=','leads.user_id')
		                    ->select('sales_orders.*','quotes.lead_id','quotes.attachment','leads.name as lead_name','leads.email','leads.phone','leads.message as lead_message','leads.status','leads.title as lead_title','users.name as user_name')
		                    ->where('sales_orders.id',$id)
		                    ->first();
		
        $products = array();
		$product_data = Product::join('product_images','products.id','=','product_images.product_id')
                            ->where('products.id',$data['result']->product_id)
                            ->select('products.id as product_id','products.category_id','products.dealer_id','products.title','products.price as product_price','products.type','products.status as product_status','product_images.image','products.attachment as product_attachment')
                            ->first();
            
        if (!empty($product_data)) {
            $products[] = [
                'id' => $product_data->product_id,
                'title' => $product_data->title,
                'price' => $data['result']->price,
                'quantity' => $data['result']->qty,
                'total_price' => $data['result']->total_price,
                'product_attachment' => $product_data->product_attachment,
                'image' => $product_data->image,
            ];
        }
		$data['products'] = $products;
        $quote_product = QuoteProduct::where('quote_id',$data['result']->quote_id)
                                            ->where('product_id',$data['result']->product_id)
                                            ->first();

        $data['extra_info'] = ExtraProductInfo::where('quote_id',$data['result']->quote_id)
                                            ->where('product_id',$quote_product->id)
                                            ->first();

    	$data['comments'] = LeadComment::where('lead_id',$id)->get();
    	$data['user'] = User::find($data['result']->user_id);
    	$data['icons'] = [
					        'pdf' => 'pdf',
					        'doc' => 'word',
					        'docx' => 'word',
					        'xls' => 'excel',
					        'xlsx' => 'excel',
					        'ppt' => 'powerpoint',
					        'pptx' => 'powerpoint',
					        'txt' => 'alt',
                            'csv' => 'csv',
                            'png' => 'image',
					    ];
        $data['dealers'] = Dealer::where('status','1')->get();

    	return view('admin.sales_order.view',$data);
    }

    //===================================================


    /*Update Serial Number */
    public function update(Request $request)
    {
    	$data = SalesOrder::find($request->id);
        $quote = Quote::where('id',$data->quote_id)->first();
		//$data->serial_number = $request->serial_number;
		//$data->PDI_message = $request->PDI_message;
        if ($request->type != 'all') {
            if ($request->type == 'payment_confirm') {
                $data->payment_confirm = $request->payment_confirm;
            }
            if ($request->type == 'PDI_status') {
                $data->PDI_status = $request->PDI_status;
            }
            if ($request->type == 'delivered') {
                $data->delivered = $request->delivered;
                $data->delivery_date = $request->delivered != '0' ? date('Y-m-d') : '';
            }
        }else{
            if (!empty($request->price)) {
                $quote_product = QuoteProduct::where('quote_id',$data->quote_id)->where('product_id',$data->product_id)->first();

                $quote_product_update = QuoteProduct::find($quote_product->id);
                $quote_product_update->price = $request->price;
                $quote_product_update->total_price = $request->price * $quote_product_update->quantity;
                $quote_product_update->save();

                $final_price = QuoteProduct::where('quote_id',$quote_product->quote_id)->sum('total_price');

                $quote_update = Quote::find($quote_product->quote_id);
                $quote_update->price = $final_price;
                $quote_update->save();
            }
            if (!empty($request->depot) || !empty($request->hitch) || !empty($request->buckets) || !empty($request->extra)) {
                ExtraProductInfo::where('quote_id',$data->quote_id)
                                ->where('product_id',$data->product_id)
                                ->update([
                                    'depot' => $request->depot,
                                    'hitch' => $request->hitch,
                                    'buckets' => $request->buckets,
                                    'extra' => $request->extra,
                                ]);
            }
            //=====================================================
            $data->payment_confirm = $request->payment_confirm;
            $data->PDI_status = $request->PDI_status;
            $data->delivered = $request->delivered;
            $data->delivery_date = $request->delivered != '0' ? date('Y-m-d') : '';
            $data->serial_number = $request->serial_number;
            $data->PDI_message = $request->PDI_message;
            $data->price = $request->price;
            $data->tax = ($request->price * $data->qty) * 12 / 100;
            $data->total_price = ($request->price * $data->qty) + $data->tax;
        }

    	if ($data->save()) {
            $checkOrderStatus = SalesOrder::where('id',$request->id)
                                        ->where('PDI_status','1')
                                        ->where('payment_confirm','1')
                                        ->where('delivered','1')
                                        ->first();

            if (!empty($checkOrderStatus)) {
                $lead_update = Lead::find($quote->lead_id);
                $lead_update->status = 'Closed';
                $lead_update->save();
            }

    		return response()->json([
    			'status' => 'success'
    		]);
    	}else{
    		return response()->json([
    			'status' => 'error'
    		]);
    	}
    }

    //==========================================================

    public function status(Request $request, $id){
        
        try {
            
            $data = SalesOrder::find($id);
            
            if($data->order_status == '1')
            {
                $order_status = '0';
            } 
            else 
            {
                $order_status = '1';
            }
            $data->order_status = $order_status;
            $data->save();
            
        
            session()->flash('message', 'Status update successfully');
            Session::flash('alert-type', 'success');
            return redirect('admin/sales_order/index');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('message', 'Some error occured during status update');
            Session::flash('alert-type', 'error');
          return redirect('admin/sales_order/index');
        }
    }

    //===================================================

    public function getMakes($id)
    {
        $models = Product::where("dealer_id",$id)
                        ->where("status",'In Stock')
                        ->get(["model"]);

        $data = [];
        $data[] = '<option value="" >Select Model</option>';
        foreach ($models as $key => $value) {
            $data[] = '<option value="'.$value->model.'">'.$value->model.'</option>';
        }
        
        return response()->json($data);
    }

    //====================================================

    public function getSerialNumbers($name)
    {
        $serial_numbers = Product::where("model",$name)
                                ->where("status",'In Stock')
                                ->get(["stock_number","id"]);

        $data = [];
        $data[] = '<option value="" >Select Serial Number</option>';
        foreach ($serial_numbers as $key => $value) {

            $data[] = '<option value="'.$value->id.'">'.$value->stock_number.'</option>';
        }

        return response()->json($data);
    }

    //=================================================================

    public function add_machine(Request $request)
    {
        try {                
            $data = SalesOrder::find($request->sales_order_id);
            //=========================================================
            $product = Product::find($request->serial_no);
            //=========================================================
            $data->product_id = $product->id;
            $data->serial_number = $product->stock_number;
            $data->save();

            $product->status = 'Sold';
            $product->save();

            session()->flash('message', 'Machine added successfully');
            Session::flash('alert-type', 'success'); 
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('message', 'Some error occured!');
            Session::flash('alert-type', 'error');
            return redirect()->back();
        }
    }

    //=================================================================

    public function getModels($id,$selected='')
    {
        $makes = Product::where("dealer_id",$id)->get(["model","id"]);

        $data = [];
        $data[] = '<option value="" >Select</option>';
        foreach ($makes as $key => $value) {
            if ($value->model == $selected) {
                $checked = 'selected';
            }else{
                $checked = '';
            }

            $data[] = '<option value="'.$value->model.'" '.$checked.'>'.$value->model.'</option>';
        }
        
        return response()->json($data);
    }

    //=================================================================


}