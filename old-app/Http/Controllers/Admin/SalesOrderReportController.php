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
use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\Quote;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SalesOrder;
use App\Models\AdminPermission;
use App\DataTables\SalesOrderReportDataTable;
use App\Helpers\AdminHelper;

class SalesOrderReportController extends Controller
{
    //=================================================================

	public function index(SalesOrderReportDataTable $dataTable,Request $request)
	{
		$data = [
            'from' => $request->from,
            'to' => $request->to,
            'customer' => $request->customer,
			'PDI_status' => $request->PDI_status,
            'payment_confirm' => $request->payment_confirm,
            'user_id' => $request->user_id,
		];

		return $dataTable->with('data',$data)->render('admin/sales_order_report/index');
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
        $data['extra_info'] = ExtraProductInfo::where('quote_id',$data['result']->quote_id)
                                            ->where('product_id',$data['result']->product_id)
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
    	return view('admin.sales_order_report.view',$data);
    }

    //===================================================

}
