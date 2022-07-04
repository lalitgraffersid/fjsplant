<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Auth;
use Cookie;
use Illuminate\Http\Request;
use Validator;
use Input;
use Session;
use DB;
use Image;
use File;
use Mail;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Contact;
use Illuminate\Mail\Mailable;

class ContactController extends Controller
{
	//====================================

	public function contact_us()
	{
		return view('front/contact_us');
	}

	//====================================

	public function contactSave(Request $request)
	{

// 		echo '<pre>';print_r($request->name);die;
		try {
			$validator = Validator::make($request->all(), [
				'name'       => 'required',
				'email'      => 'required',
				'contact_no' => 'required',
				'subject' 	 => 'required',
				'message' 	 => 'required',
			]);
			if ($validator->fails()) { 	
	            return redirect('contact_us')
	                        ->withErrors($validator)
	                        ->withInput();
			} else { 		        
	        	$contact = new Contact;
		        $contact->name = $request->name;
		        $contact->email = $request->email;
		        $contact->contact_no = $request->contact_no;
		        $contact->subject = $request->subject;
		        $contact->message = $request->message;
	        	$contact->save();
	        
				$data = array(
		            'name' => $request->name,
		            'email' => $request->email,
		            'phone' => $request->contact_no,
		            'subject' => $request->subject,
		            'description' => $request->message,
		            'title' => 'FJS Plant::Contact'
	        	);
			
	  
	           // $user['to']='enquiries@fjsplant.com';
	             $user['to']='enquiries@fjsplant.ie';

				\Mail::send('front.emails.emailContact',$data, function($message) use ($user){
					$message->to($user['to']);
					$message->subject('FJS Plant::Contact!');
				});
	       
                  echo 'ajjyyyyyy';die;
                
	        	//==== end mail script ======
				session()->flash('message', 'Contact added successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('contact_us');
			}
		} catch (\Exception $e) {
		  //  dd($e->getMessage());
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during mail sent');
            Session::flash('alert-type', 'error');
           	return redirect('contact_us');  
        }
	}
	
	//====================================




}
