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
use App\Models\Service;

class ServiceController extends Controller
{
    //====================================

	public function requestService(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name'       => 'required',
				'email'      => 'required',
				'request_type' => 'required',
			]);
			if ($validator->fails()) { 	
	            return redirect('services')
	                        ->withErrors($validator)
	                        ->withInput();
			} else { 		        
	        	$service = new Service;
		        $service->name = $request->name;
		        $service->email = $request->email;
		        $service->address = $request->address;
		        $service->make = $request->make;
		        $service->model = $request->model;
		        $service->serial_number = $request->serial_number;
		        $service->request_type = $request->request_type;
		        $service->issue = $request->issue;

	       // 	$service->save();
	        
				$data = array(
		            'name' => $request->name,
		            'email' => $request->email,
		            'service' => $service,
		            'title' => 'FJS Plant::Service Request'
	        	);
	        	
	        	 // $user['to']='enquiries@fjsplant.com';
	             $user['to']='service@fjsplant.ie';

				\Mail::send('front.emails.emailServices',$data, function($message) use ($user){
					$message->to($user['to']);
					$message->subject('FJS Plant::Service Request!');
				});
			
	   
    //  echo 'ajay';die;
	        	//==== end mail script ======
				session()->flash('message', 'Service request added successfully.');
				Session::flash('alert-type', 'success'); 
				return redirect('services');
			}
		} catch (\Exception $e) {
		    
		    dd($e->getMessage());
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during mail sent!');
            Session::flash('alert-type', 'error');
           	return redirect('services');  
        }
	}
	//====================================



}
