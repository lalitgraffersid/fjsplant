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
use App\Models\Category;
use App\Models\Dealer;
use App\Models\Product;

class HomeController extends Controller
{
    //====================================

	public function index()
	{
		$data = array();
		$data['categories'] = Category::get();
		$data['dealers'] = Dealer::where('status','1')->orderBy('order_no')->get();
		$data['models'] = Product::select('model')->groupBy('model')->orderBy('model','desc')->get();

		return view('front/home/index',$data);
	}

	//====================================

	public function getModels($dealer_id)
	{
		$models = Product::where('dealer_id',$dealer_id)
							->select('model')
							->groupBy('model')
							->orderBy('model','desc')
							->get();

		$data[] = '<option value="">Select Model</option>';
		if (count($models)>0) {
			foreach ($models as $key => $value) {
				$data[] = '<option value="'.$value->model.'">'.$value->model.'</option>';
			}
		}

		return response()->json(['data'=>$data]);
	}
	
	//====================================



}
