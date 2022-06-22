<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use Validator;
use Cookie;
use Session;
use Crypt;
use Mail;
use Hash;
use Auth;


class LoginController extends Controller
{
    
    //=========================================================
    /**
     * Admin login page
     * Layout Call
     * Render Html
     * 
     */
    //========================================================
   public function index() {
        
        
        if (\Auth::check()) {
            return \Redirect::route('admin.dashboard');
        }
        return view('admin/login');

    }
    //===========================================================
    /**
     * Check admin login
     * validate email and pasword
     * check status
     * 
     */
    //===========================================================
    public function postLogin(Request $request){
        // echo '<pre>'; print_r($request->all());
        //  die();
        $email   = $request->get('email');
        $password   = $request->get('password');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5'
            
        ]);
        if ($validator->fails()) {
            $request->session()->flash('alert-warning', 'Please enter mandatory fields.');
            return redirect('/admin')->withErrors($validator);
        } else {

        // $remember = ($request->input('remember')) ? 1 : 0;
        if (Auth::attempt(['email' => $email, 'password' => $password]))
        {
            // print_r(Auth::user());die;
            if(Auth::user()->status == 0) // status 0 is for disabled users
            {
                
                auth()->logout(); // log user out
                return redirect()->back()->with('message','Your are not authorized!.Please contact site administrator')
                    ->with('alert-type', 'error')
                    ->withInput();
            }

            //if set remember me, set cookie
            // if($remember==1)
            //     {
            //         Cookie::queue('remember', '1', 5400);
            //         Cookie::queue('user', trim($request->input('email')), 5400);
            //         Cookie::queue('pass', trim($request->input('password')), 5400);
            //     }
            //     else
            //     {
            //         Cookie::queue('remember', '', 5400);
            //         Cookie::queue('user', '', 5400);
            //         Cookie::queue('pass', '', 5400);
            //     }

                Auth::user()->last_login = date("Y-m-d H:i:s");
                Auth::user()->save();
                
            //  echo '<pre>'; print_r(Auth::user());die;
            return redirect('admin/dashboard');
 
        } else {
            
            return redirect()->back()
                ->with('message','Incorrect username or password')
                ->with('alert-class', 'alert-danger')
                ->withInput();
            }
        }
        
 
    }

    //=========================================================
   /**
     * User logout
     * 
     * 
     * 
     */
   //=========================================================
    public function getLogout(){
        //dd(session()->all());
        Auth::logout();
        session()->flash('message', 'You logged out successfully!');
        Session::flash('alert-class', 'alert-success'); 

        return redirect()->route('admin.login');

    }
    //=========================================================





    //=========================================================

    



}
