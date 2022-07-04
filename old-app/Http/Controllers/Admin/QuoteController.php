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
use Mail;
use App\Models\User;
use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\Quote;
use App\Models\QuoteProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ExtraProductInfo;
use App\Models\Dealer;
use App\Models\Customer;
use App\Models\AdminPermission;
use App\DataTables\QuoteDataTable;
use App\Helpers\AdminHelper;

class QuoteController extends Controller
{
    //=================================================================

	public function index(QuoteDataTable $dataTable,Request $request)
	{
		Quote::where('is_read','0')->update(['is_read'=>'1']);

		$data = [
			'from' => $request->from,
			'to' => $request->to,
            'customer' => $request->customer,
            'machine' => $request->machine
		];

		return $dataTable->with('data',$data)->render('admin/quotes/index');
	}

    //===================================================

    public function view($id)
    {
    	$data['result'] = Quote::join('leads','leads.id','=','quotes.lead_id')
		                    ->join('users','users.id','=','leads.user_id')
		                    ->select('quotes.*','leads.name as lead_name','leads.email','leads.phone','leads.message','leads.status','users.name as user_name','leads.title as leads_title')
		                    ->where('quotes.id',$id)
		                    ->first();

		$product_ids = QuoteProduct::where('quote_id',$id)->get();

		$products = array();
		foreach ($product_ids as $key => $value) {
			$product_data = Product::join('product_images','products.id','=','product_images.product_id')
                                ->where('products.id',$value->product_id)
                                ->select('products.id as product_id','products.category_id','products.dealer_id','products.title','products.price as product_price','products.type','products.status as product_status','product_images.image','products.attachment as product_attachment')
                                ->first();

            if (!empty($product_data)) {
            	$products[] = [
	                'id' => $product_data->product_id,
	                'quote_product_id' => $value->id,
	                'title' => $product_data->title,
	                'price' => $value->price,
	                'quantity' => $value->quantity,
	                'total_price' => $value->total_price,
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

    	return view('admin.quotes.view',$data);
    }

    //=================================================================

	public function edit($id)
	{
		$data = array();
		$data['result'] = Quote::join('leads','leads.id','=','quotes.lead_id')
		                    ->join('users','users.id','=','leads.user_id')
		                    ->select('quotes.*','leads.name as lead_name','leads.email','leads.phone','leads.message','leads.status','users.name as user_name','leads.title as leads_title')
		                    ->where('quotes.id',$id)
		                    ->first();

		$product_ids = QuoteProduct::where('quote_id',$id)->get();

		$products = array();
		foreach ($product_ids as $key => $value) {
			$product_data = Product::join('product_images','products.id','=','product_images.product_id')
                                ->where('products.id',$value->product_id)
                                ->select('products.id as product_id','products.category_id','products.dealer_id','products.title','products.price as product_price','products.type','products.status as product_status','product_images.image','products.attachment as product_attachment')
                                ->first();

            if (!empty($product_data)) {
            	$products[] = [
	                'id' => $product_data->product_id,
	                'quote_product_id' => $value->id,
	                'title' => $product_data->title,
	                'price' => $value->price,
	                'quantity' => $value->quantity,
	                'total_price' => $value->total_price,
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

		$data['dealers'] = Dealer::where('status','1')->orderBy('order_no')->get();

		return view('admin/quotes/edit',$data);
	}

	//====================================================================

	public function removeMachine(Request $request)
	{
		$quote_product = QuoteProduct::where('id',$request->quote_product_id)->first();
		
		ExtraProductInfo::where('quote_id',$quote_product->quote_id)->where('product_id',$quote_product->product_id)->delete();
		QuoteProduct::where('id',$request->quote_product_id)->delete();
		$final_price = QuoteProduct::where('quote_id',$request->id)->sum('total_price');

		$data = Quote::find($request->id);
        $data->price = $final_price;

        if ($data->save()) {
            return response()->json([
  				'msg'=>'success'
  			]);
        }else{
            return response()->json([
  				'msg'=>'error'
  			]);
        }
	}

	//=================================================================

	public function addExtra(Request $request)
	{
		try {
			if (empty($request->depot) && empty($request->hitch) && empty($request->buckets) && empty($request->extra)) {
				session()->flash('message', 'Please enter any one value!');
	            Session::flash('alert-type', 'error');
	           	return redirect('admin/quotes/edit'.'/'.$request->quote_id);
			}

			ExtraProductInfo::where('quote_id',$request->quote_id)
										->where('product_id',$request->product_id)
										->where('user_id',Auth::user()->id)
										->delete();

	        $data = new ExtraProductInfo;
			$data->quote_id = $request->quote_id;
			$data->product_id = $request->product_id;
	        $data->user_id = Auth::user()->id;
	        $data->depot = $request->depot;
	        $data->hitch = $request->hitch;
	        $data->buckets = $request->buckets;
	        $data->extra = $request->extra;
	        $data->save();

			session()->flash('message', 'Added successfully');
			Session::flash('alert-type', 'success'); 
			return redirect('admin/quotes/edit'.'/'.$request->quote_id);
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured!');
            Session::flash('alert-type', 'error');
           	return redirect('admin/quotes/edit'.'/'.$request->quote_id);
        }
	}

	//====================================================================

	public function getProducts($id,$type,$dealer_id)
    {
        $products = Product::where("type",$type)
    						->where('dealer_id',$dealer_id)
    						->where('status','!=','Sold')
    						->get(["title","id"]);

        $data = [];
        $data[] = '<option value="" >Select</option>';
    	foreach ($products as $key => $value) {
    		$data[] = '<option value="'.$value->id.'">'.$value->title.'</option>';
    	}

        return response()->json($data);
    }

    //=================================================================

	public function addMachine(Request $request)
	{
		try {
			$product = Product::where('id',$request->product_id)->first();

			$quote_product = new QuoteProduct;
			$quote_product->quote_id = $request->quote_id;
			$quote_product->product_id = $request->product_id;
			$quote_product->price = $product->price;
			$quote_product->quantity = $request->quantity;
			$quote_product->total_price = $product->price * $request->quantity;
			$quote_product->save();

			$final_price = QuoteProduct::where('quote_id',$request->quote_id)->sum('total_price');

	        $data = Quote::find($request->quote_id);
            $data->price = $final_price;
            $data->save();

			session()->flash('message', 'Machine added successfully');
			Session::flash('alert-type', 'success'); 
			return redirect('admin/quotes/edit'.'/'.$request->quote_id);
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured!');
            Session::flash('alert-type', 'error');
           	return redirect('admin/quotes/edit'.'/'.$request->quote_id);
        }
	}

	//====================================================================

	public function update(Request $request)
	{
		try {
			$quote_product = QuoteProduct::find($request->quote_product_id);
			$quote_product->price = $request->price;
			$quote_product->quantity = $request->quantity;
			$quote_product->total_price = $request->price * $request->quantity;
			$quote_product->save();

			$final_price = QuoteProduct::where('quote_id',$request->quote_id)->sum('total_price');

	        $quote = Quote::find($request->quote_id);
	        $quote->price = $final_price;
	        $quote->save();

			session()->flash('message', 'Updated successfully');
			Session::flash('alert-type', 'success'); 
			return redirect('admin/quotes/edit'.'/'.$request->quote_id);
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured!');
            Session::flash('alert-type', 'error');
           	return redirect('admin/quotes/edit'.'/'.$request->quote_id);
        }
	}

	//====================================================================

	public function resend($quote_id)
	{
		try {
	        $data = Quote::find($quote_id);
            $data->sent = '1';
            $data->sent_on = date('Y-m-d');
            
            if(!empty($data->attachment)){
                $pathToFile = public_path().'/admin/clip-one/assets/quotes/'.$data->attachment;
            }else{
                $pathToFile = '';
            }

            $lead = Lead::where('id',$data->lead_id)->first();
            $user = User::where('id',$lead->user_id)->first();
            $customer = Customer::where('id',$data->customer_id)->first();
            $products = QuoteProduct::where('quote_id',$quote_id)->get();

            $leadData = Lead::find($data->lead_id);
            $emailData = array(
                'email' => $leadData->email,
                'title' => 'FJS Plant Quotation',
                'pathToFile' => $pathToFile,
                'quote' => $data,
                'customer' => $customer,
                'products' => $products,
                'user_email' => $user->email,
                'users' => $user,
            );
            
            if(!empty($pathToFile)){
                Mail::send('api.emails.emailQuote', $emailData, function ($message) use ($emailData) {
                    $message->from('user@fjsplant.ie', 'FJS Plant Quotation');
                    //$message->to('munender.singh@commediait.com');
                    //$message->bcc(['vikas.nagar@commediait.com','santosh.kumar@commediait.com']);
                    $message->to($emailData['email']);
                    $message->cc([$emailData['user_email'],'lorraine@fjsplant.ie']);
                    $message->subject('FJS Plant Quotation');
                    $message->attach($emailData['pathToFile']);
                });   
            }else{
                Mail::send('api.emails.emailQuote', $emailData, function ($message) use ($emailData) {
                    $message->from('user@fjsplant.ie', 'FJS Plant Quotation');
                    //$message->to('munender.singh@commediait.com');
                    //$message->bcc(['vikas.nagar@commediait.com','santosh.kumar@commediait.com']);
                    $message->to($emailData['email']);
                    $message->cc([$emailData['user_email'],'lorraine@fjsplant.ie']);
                    $message->subject('FJS Plant Quotation');
                });
            }
            
            // if( count(Mail::failures()) > 0 ) {
            //     echo "There was one or more failures. They were: <br />";
            //     foreach(Mail::failures() as $email_address) {
            //       echo " - $email_address <br />";
            //     }die;
            // } else {
            //     Log::info('Working');
            //     echo "No errors, all sent successfully!";
            //     die;
            // }

            $data->save();

			session()->flash('message', 'Quote sent successfully');
			Session::flash('alert-type', 'success'); 
			return redirect('admin/quotes/edit'.'/'.$quote_id);
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured!');
            Session::flash('alert-type', 'error');
           	return redirect('admin/quotes/edit'.'/'.$quote_id);
        }
	}

	//====================================================================

}
