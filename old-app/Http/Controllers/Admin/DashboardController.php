<?php
namespace App\Http\Controllers\Admin;

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
use Illuminate\Support\Facades\Log;
use Exception;
use App\Helpers\AdminHelper;

class DashboardController extends Controller
{
    //====================================

	public function index()
	{   
	    // echo 'ajay';die;

		return view('admin/dashboard');
	}
	//==================================================================
	public function setting()
	{

		$setting_data = array();
		$setting_id = 1;
		$settingDetails = Setting::where('id',$setting_id)->first();
		
		$setting_data['settingDetails'] = $settingDetails;
		
		return view('admin/setting',$setting_data);
	}
	//====================================================
	public function update(Request $request)
	{
		try {
				
				$setting_id = 1;
		        $settings= array(
							'whatsapp' => $request->whatsapp,
							'home_phone' => $request->home_phone,
							'office_phone' => $request->office_phone,
							'email' => $request->email,
							'linkedin' => $request->linkedin,
							'address' => $request->address,
						);
		        
		        
		        //DB::table('settings')->where('id', $setting_id)->update($data);
		        Setting::where('id', $setting_id)->update($settings);

		        session()->flash('message', 'Setting updated successfully');
				Session::flash('alert-class', 'alert-success'); 
				return redirect('admin/settings');
				

			} catch (\Exception $e) {
	            Log::error($e->getMessage());
			    session()->flash('message', 'Some error occured during update setting');
	            Session::flash('alert-class', 'alert-danger');
	           return redirect('admin/settings');
	        }
	}

	//=================================================



}
