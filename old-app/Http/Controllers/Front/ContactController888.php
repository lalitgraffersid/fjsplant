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
	    Session::flush(); // removes all session data
	   //  $data['error'] = "";
    //             $data['success'] = "";
		return view('front/contact_us',$data);
	}
	
	public function thankyou()
	{
	    Session::flush(); // removes all session data
		return view('front/thankyou');
	}
	
	public function contactSave(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name'       => 'required',
			'email'      => 'required',
			'contact_no' => 'required',
			'subject' 	 => 'required',
			'message' 	 => 'required',
			'g-recaptcha-response' => 'required|recaptcha',
		]);
        //'g-recaptcha-response' => 'required|recaptcha',
		
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
	            'title' => 'FJS Plant::Contact',
        	);

        	  Mail::send('front.emails.emailContact', $data, function($message) use ($data) {
                  $message->to('enquiries@fjsplant.com', 'FJS Plant Contact')->subject('FJS Plant::Contact!');
                 $message->from('enquiries@fjsplant.com','FJS Plant Contact');
              });
              
              if (Mail::failures()) {
                $data['success'] = "";
                $data['error'] = "errorContact";
        		return view('front/contact_us',$data);
            }else{
                $data['error'] = "";
                $data['success'] = "contactform";
        		return view('front/contact_us',$data);
		    }
		}
	}
}
