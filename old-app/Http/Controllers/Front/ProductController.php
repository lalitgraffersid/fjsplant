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
use App\Models\Category;
use App\Models\Dealer;
use App\Models\Product;
use App\Models\ProductImage;

class ProductController extends Controller
{
    //=================================================================

	public function getProducts($type)
	{
		$data = array();
		$data['results'] = Product::where('type',$type)->orderBy('order_no')->get();
		$data['productTypes'] = DB::table('products')->select('type')->groupby('type')->get();

		return view('front/products/products',$data);
	}

	//=================================================================

	public function productsByCategory($id)
	{
		$data = array();
		$data['category'] = Category::find($id);
		$data['categories'] = Category::get();
		$data['results'] = Product::where('category_id',$id)->orderBy('order_no')->get();

		return view('front/products/productsByCategory',$data);
	}

	//=================================================================

	public function productDetails($id)
	{
		$data = array();
		$data['result'] = Product::find($id);
		$data['product_images'] = ProductImage::where('product_id',$id)->get();

		return view('front/products/productDetails',$data);
	}

	//=================================================================

	/*Home Page Filter*/
	public function productsFilter(Request $request)
	{
		$data = array();
		//DB::enableQueryLog();

		$query = Product::select('*');

		if (!empty($request->type)) {
			$query = $query->where('type',$request->type);
			$data['type'] = $request->type;
		}else{
			$data['type'] = '';
		}
		if (!empty($request->dealer_id) && !empty(array_filter($request->dealer_id)) > 0) {
			$query = $query->whereIn('dealer_id',$request->dealer_id);
			$data['dealer_id'] = $request->dealer_id;
		}else{
			$data['dealer_id'] = [];
		}
		if (!empty($request->model) && !empty(array_filter($request->model)) > 0) {
			$query = $query->whereIn('model',$request->model);
			$data['model_value'] = implode(',', $request->model);
		}else{
			$data['model_value'] = '';
		}

		$data['results'] = $query->orderBy('order_no')->get();
		//dd(DB::getQueryLog());
		$data['dealers'] = Dealer::get();
		$data['models']  = Product::select('model')->groupBy('model')->orderBy('model','desc')->get();

		return view('front/products/productsFilter',$data);
	}

	//=================================================================

	public function getModels($id,$selected='')
    {
    	$dealer_ids = explode(',', $id);
    	$selecteds = explode(',', $selected);

    	$models = Product::whereIn('dealer_id',$dealer_ids)->get(["model"]);

    	$data = [];
    	$data[] = '<li><label><input type="checkbox" name="model[]" value="">Select Model</label></li>';
    	foreach ($models as $key => $value) {
    		if(in_array($value->model,$selecteds)) {
    			$checked = "checked";
    		}else{
    			$checked = "";
    		}

    		$data[] = '<li><label><input type="checkbox" name="model[]" value="'.$value->model.'" '.$checked.' >'.$value->model.'</label></li>';
    	}
        
        return response()->json($data);
    }

	//=================================================================

}
