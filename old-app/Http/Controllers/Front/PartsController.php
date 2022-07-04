<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use Auth;
use Cookie;
use Illuminate\Http\Request;
use Validator;
use Input;
use App\Models\User;
use App\Models\Setting;
use Session;
use DB;
use Image;
use File;
use Mail;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Category;
use App\Models\Dealer;
use App\Models\Product;
use App\Models\Part;

class PartsController extends Controller
{
    //=================================================================

	public function index()
	{
		$data = array();
		$data['brands'] = Dealer::where('status','1')->orderBy('order_no')->get();

		return view('front/parts/index',$data);
	}

	//=================================================================

    public function getModels($id,$selected='')
    {
    	$dealer = Dealer::where('name',$id)->first();
    	$models = Product::where('dealer_id',$dealer->id)->get(["model"]);

    	$data = [];
    	$data[] = '<option value="" >Select Model</option>';
    	foreach ($models as $key => $value) {

    		$data[] = '<option value="'.$value->model.'">'.$value->model.'</option>';
    	}
        
        return response()->json($data);
    }

	//=================================================================

	public function partsRequest(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name'       => 'required',
				'email'      => 'required',
				'contact_no' => 'required',
				'message' 	 => 'required',
			]);
			if ($validator->fails()) { 	
	            session()->flash('message', 'Please fill all required fields!');
            	Session::flash('alert-type', 'error');
           		return redirect()->back();  
			} else {
				$data = array(
		            'name' => $request->name,
		            'email' => $request->email,
		            'contact_no' => $request->contact_no,
		            'brand' => $request->brand,
		            'model' => $request->model,
		            'subject' => 'FJS Parts Request',
		            'description' => $request->message,
		            'title' => 'FJS Plant::Parts Request'
	        	);

	        	$parts = new Part;
	        	$parts->name = $request->name;
	        	$parts->mobile = $request->contact_no;
	        	$parts->email = $request->email;
	        	$parts->make = $request->brand;
	        	$parts->model = $request->model;
	        	$parts->message = $request->message;

	              $parts->save();
	              
	            
	              // $user['to']='enquiries@fjsplant.com';
	             $user['to']='stores@fjsplant.ie';

				\Mail::send('front.emails.emailParts',$data, function($message) use ($user){
					$message->to($user['to']);
					$message->subject('FJS Plant::Parts Request!');
				});
	              
	   //echo 'jay'; die;
			    	session()->flash('message', 'Request sent successfully');
					Session::flash('alert-type', 'success'); 
					return redirect()->back();
				// 	return redirect('parts')->with('success','Request sent successfull');
	       
		}
		} catch (\Exception $e) {
		  //  dd($e->getMessage());
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured!');
            Session::flash('alert-type', 'error');
           	return redirect()->back();  
        }
	}
	
	//=================================================================

}
