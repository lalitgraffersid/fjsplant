<?php
namespace App\Http\Controllers\Front;

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
use App\Models\News;

class NewsController extends Controller
{
    //=================================================================

	public function index()
	{
		$data = array();
		$data['results'] = News::where('status',1)->orderBy('id','desc')->get();

		return view('front/news/index',$data);
	}

	//=================================================================

	public function details($id)
	{
		$data = array();
		$data['result'] = News::find($id);

		return view('front/news/details',$data);
	}

	//=================================================================

}
