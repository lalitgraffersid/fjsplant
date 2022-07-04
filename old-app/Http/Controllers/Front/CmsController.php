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
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Gallery;
use App\Models\Team;

class CmsController extends Controller
{
    //====================================

	public function about_us()
	{
		return view('front/about_us');
	}
	//====================================

	public function services()
	{
		$data = array();
		$data['galleries'] = Gallery::get();

		return view('front/services',$data);
	}
	//====================================

	public function contact_us()
	{
		return view('front/contact_us');
	}
	//====================================

	public function our_team()
	{
		$data = array();
		$data['teams'] = Team::get();

		return view('front/our_team',$data);
	}
	//====================================
	
	public function privacy_policy()
	{
		return view('front/privacy_policy');
	}
	//====================================



}
