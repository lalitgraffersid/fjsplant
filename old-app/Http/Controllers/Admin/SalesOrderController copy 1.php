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
use App\User;
use App\Models\Action;
use App\Models\Role;
use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\Quote;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\AdminPermission;
use App\DataTables\SalesOrderDataTable;
use App\Helpers\AdminHelper;

class SalesOrderController extends Controller
{
    //=================================================================

	public function index(Request $request)
	{
        $data = [];
        //==============================================
        $status_action = Action::where('action_slug','status')->first();
        $data['checkStatusAction'] = Role::where('name_slug','sales_order')->whereRaw("find_in_set('".$status_action->id."',action_id)")->first();
        $data['roles'] = Role::where('name_slug','sales_order')->first();
        $data['checkStatusPermission'] = AdminPermission::where('user_id',Auth::user()->id)->whereRaw("find_in_set('status',action_id)")->first();
        $data['action_ids'] = explode(',', $data['roles']->action_id);
        //==============================================
        $data['customers'] = Customer::where('status','1')->get();

        if (!empty($request->from) || !empty($request->to) || !empty($request->customer)) {
            $query = SalesOrder::orderBy('id','DESC');

            if (!empty($request->from)) {
                $query = $query->where('date','>=',$request->from);
            }
            if (!empty($request->to)) {
                $query = $query->where('date','<=',$request->to);
            }
            if (!empty($request->customer)) {
                $query = $query->where('customer_id',$request->customer);
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
		                    ->select('sales_orders.*','quotes.product_id','quotes.lead_id','quotes.attachment','quotes.quantity','leads.name as lead_name','leads.email','leads.phone','leads.message as lead_message','leads.status','users.name as user_name')
		                    ->where('sales_orders.id',$id)
		                    ->first();

		$product_ids = explode(',', $data['result']->product_id);
        $quantity = explode(',', $data['result']->quantity);
		$products = array();

		foreach ($product_ids as $key => $value) {
			$product_data = Product::join('product_images','products.id','=','product_images.product_id')
                                ->where('products.id',$value)
                                ->select('products.id as product_id','products.category_id','products.dealer_id','products.title','products.price as product_price','products.type','products.status as product_status','product_images.image','products.attachment as product_attachment')
                                ->first();
            
            if (!empty($product_data)) {
                $products[] = [
                    'id' => $product_data->product_id,
                    'title' => $product_data->title,
                    'quantity' => $quantity[$key],
                    'price' => $product_data->product_price,
                    'total_price' => $product_data->product_price * $quantity[$key],
                    'product_attachment' => $product_data->product_attachment,
                    'image' => $product_data->image,
                ];
            }

		}
		$data['products'] = $products;

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

    	return view('admin.sales_order.view',$data);
    }

    //===================================================


    /*Update Serial Number */
    public function update(Request $request)
    {
    	$data = SalesOrder::find($request->id);
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
            $data->payment_confirm = $request->payment_confirm;
            $data->PDI_status = $request->PDI_status;
            $data->delivered = $request->delivered;
            $data->delivery_date = $request->delivered != '0' ? date('Y-m-d') : '';
            $data->serial_number = $request->serial_number;
            $data->PDI_message = $request->PDI_message;
        }

    	if ($data->save()) {
    		return response()->json([
    			'status' => 'success'
    		]);
    	}else{
    		return response()->json([
    			'status' => 'error'
    		]);
    	}
    }

}
