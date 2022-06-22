<?php
namespace App\Http\Controllers\Admin;

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
use App\Models\Action;
use App\Models\Role;
use App\Models\AdminPermission;
use App\DataTables\ProductDataTable;
use App\Helpers\AdminHelper;

class ProductController extends Controller
{
    //=================================================================

	public function index(Request $request)
	{
		$data = [];
		//==============================================
		$status_action = Action::where('action_slug','status')->first();
        $data['checkStatusAction'] = Role::where('name_slug','products')->whereRaw("find_in_set('".$status_action->id."',action_id)")->first();
        $data['roles'] = Role::where('name_slug','products')->first();
        $data['checkStatusPermission'] = AdminPermission::where('user_id',Auth::user()->id)->whereRaw("find_in_set('status',action_id)")->first();
        $data['action_ids'] = explode(',', $data['roles']->action_id);
        //==============================================

		$data['categories'] = Category::get();
		$data['dealers'] = Dealer::get();

		if (!empty($request->category_id) || !empty($request->dealer_id) || !empty($request->status) || !empty($request->title)) {

			$query = Product::select('*');

			if (!empty($request->category_id)) {
				$query = $query->where('category_id',$request->category_id);
				$data['category_id'] = $request->category_id;
			}else{
				$data['category_id'] = '';
			}
			if (!empty($request->dealer_id)) {
				$query = $query->where('dealer_id',$request->dealer_id);
				$data['dealer_id'] = $request->dealer_id;
			}else{
				$data['dealer_id'] = '';
			}
			if (!empty($request->status)) {
				$query = $query->where('status',$request->status);
				$data['status'] = $request->status;
			}else{
				$data['status'] = '';
			}
			if (!empty($request->title)) {
				$query = $query->where('title','LIKE','%'.$request->title.'%');
				$data['title'] = $request->title;
			}else{
				$data['title'] = '';
			}
		}else{
			$query = Product::select('*');
		}

        $data['results'] = $query->orderBy('order_no')->orderBy('title')->get();

		return view('admin/products/index',$data);
	}

	//=================================================================

	public function add()
	{
		$data = array();
		$data['categories'] = Category::get();
		$data['dealers'] = Dealer::get();

		return view('admin/products/add',$data);
	}

	//=================================================================

	public function save(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'category_id' => 'required',
				'dealer_id' => 'required',
				'stock_number' => 'required',
				'backorder_number' => 'required',
				'date' => 'required',
				'title' => 'required',
				'model' => 'required',
				'year' => 'required',
				'hours' => 'required',
				'weight' => 'required',
				'description' => 'required',
				'price' => 'required',
				'type' => 'required',
				'status' => 'required',
				'image' => 'required',
				'image.*' => 'mimes:jpeg,jpg,png,gif',

			]);
			if ($validator->fails()) { 
	            return redirect('admin/products/add')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {
	            
				$attachment = $request->file('attachment');
				//=========================================================
				/*attachment*/
				if(!empty($attachment)) {
		        	$attachment_imagename = $attachment->getClientOriginalName();
					$destinationPath = public_path('/admin/clip-one/assets/products/attachment');
			        $attachment->move($destinationPath, $attachment_imagename);
				} else {
					$attachment_imagename = '';
				}
		            
		        //=========================================================
				foreach ($request->stock_number as $key => $stock_number) {
					$maxOrder = Product::max('order_no');

					$data = new Product;
			        $data->category_id = $request->category_id;
			        $data->dealer_id = $request->dealer_id;
			        $data->stock_number = $stock_number;
			        $data->backorder_number = $request->backorder_number;
			        $data->title = $request->title;
			        $data->model = $request->model;
			        $data->year = $request->year;
			        $data->hours = $request->hours;
			        $data->weight = $request->weight;
			        $data->description = $request->description;
			        $data->price = $request->price;
			        $data->type = $request->type;
			        $data->attachment = $attachment_imagename;
			        $data->status = $request->status;
			        $data->upcoming_quantity = $request->upcoming_quantity != '' ? $request->upcoming_quantity : '0';
			        $data->date = date('Y-m-d',strtotime($request->date));
			        $data->order_no = $maxOrder + 1;
			        $data->save();

			        $images = $request->file('image');
					foreach ($images as $key1 => $image) {
						$imagename = rand('1111','9999').'_'.time().'.'.$image->getClientOriginalExtension();
				        $destinationPath = public_path('/admin/clip-one/assets/products/thumbnail');
				        
				        $img = Image::make($image->getRealPath());

				        $img->resize(100, 100, function ($constraint) {
						    $constraint->aspectRatio();
						})->save($destinationPath.'/'.$imagename);

						$destinationPath = public_path('/admin/clip-one/assets/products/original').'/';
				        File::copy($image, $destinationPath.$imagename);

				        // $source_url = public_path().'/admin/clip-one/assets/products/original/'.$imagename;
	        			// $destination_url = public_path().'/admin/clip-one/assets/products/original/'.$imagename;
	        			// $quality = 40;
	        			// AdminHelper::compress_image($source_url, $destination_url, $quality);

	        			$product_image = new ProductImage;
	        			$product_image->product_id = $data->id;
	        			$product_image->image = $imagename;
	        			$product_image->save();

						// echo "<pre>";
						// print_r($product_image);
					}
				}
				//die;

				session()->flash('message', 'Product added successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/products/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save Product');
            Session::flash('alert-type', 'error');
           	return redirect('admin/products/add');
        }
	}

	//=================================================================

	public function edit($id)
	{
		$data = array();
		$data['result'] = Product::find($id);
		$data['productImages'] = ProductImage::where('product_id',$id)->get();
		$data['categories'] = Category::get();
		$data['dealers'] = Dealer::get();
		$data['icons'] = [
					        'pdf' => 'pdf',
					        'doc' => 'word',
					        'docx' => 'word',
					        'xls' => 'excel',
					        'xlsx' => 'excel',
					        'ppt' => 'powerpoint',
					        'pptx' => 'powerpoint',
					        'txt' => 'alt',
					        'csv' => 'csv',
                            'png' => 'image',
					    ];

		return view('admin/products/edit',$data);
	}

	//=================================================================

	public function update(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'category_id' => 'required',
				'dealer_id' => 'required',
				'stock_number' => 'required',
				'backorder_number' => 'required',
				'date' => 'required',
				'title' => 'required',
				'model' => 'required',
				'year' => 'required',
				'hours' => 'required',
				'weight' => 'required',
				'description' => 'required',
				'price' => 'required',
				'type' => 'required',
				'status' => 'required',
			]);
			if ($validator->fails()) { 
	            return redirect('admin/products/edit'.'/'.$request->id)
	                        ->withErrors($validator)
	                        ->withInput();
			} else {

		        $data = Product::find($request->id);
				/*attachment*/
				$attachment = $request->file('attachment');

				if(!empty($attachment)) {
					$file1 = public_path().'/admin/clip-one/assets/products/attachment/'.$data->attachment;
					File::delete($file1);

		        	$attachment_imagename = $attachment->getClientOriginalName();
					$destinationPath = public_path('/admin/clip-one/assets/products/attachment');
			        $attachment->move($destinationPath, $attachment_imagename);
				} else {
					$attachment_imagename = $data->attachment;
				}
					
		        //=========================================================
		        $data->category_id = $request->category_id;
		        $data->dealer_id = $request->dealer_id;
		        $data->stock_number = $request->stock_number;
		        $data->backorder_number = $request->backorder_number;
		        $data->title = $request->title;
		        $data->model = $request->model;
		        $data->year = $request->year;
		        $data->hours = $request->hours;
		        $data->weight = $request->weight;
		        $data->description = $request->description;
		        $data->price = $request->price;
		        $data->type = $request->type;
		        $data->attachment = $attachment_imagename;
		        $data->status = $request->status;
		        $data->upcoming_quantity = $request->upcoming_quantity != '' ? $request->upcoming_quantity : '0';
		        $data->date = date('Y-m-d',strtotime($request->date));
		        $data->save();

		        $images = $request->file('image');
		        if (!empty($images)) {
		        	foreach ($images as $key => $image) {
						$imagename = rand('1111','9999').'_'.time().'.'.$image->getClientOriginalExtension();
				        $destinationPath = public_path('/admin/clip-one/assets/products/thumbnail');
				        
				        $img = Image::make($image->getRealPath());

				        $img->resize(100, 100, function ($constraint) {
						    $constraint->aspectRatio();
						})->save($destinationPath.'/'.$imagename);

						$destinationPath = public_path('/admin/clip-one/assets/products/original');
				        $image->move($destinationPath, $imagename);

				        $source_url = public_path().'/admin/clip-one/assets/products/original/'.$imagename;
	        			$destination_url = public_path().'/admin/clip-one/assets/products/original/'.$imagename;
	        			$quality = 40;

	        			AdminHelper::compress_image($source_url, $destination_url, $quality);

	        			$product_image = new ProductImage;
	        			$product_image->product_id = $data->id;
	        			$product_image->image = $imagename;
	        			$product_image->save();
					}
		        }

				session()->flash('message', 'Product updated successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/products/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during update Product');
            Session::flash('alert-type', 'error');
           	return redirect('admin/products/edit'.'/'.$request->id);
        }
	}

	//=================================================================

	public function delete($id){
		
		try {
			$data = Product::find($id);
			$images = ProductImage::where('product_id',$id)->get();

			$file = public_path().'/admin/clip-one/assets/products/attachment/'.$data->attachment;
			File::delete($file);

			foreach ($images as $key => $value) {
				$file1 = public_path().'/admin/clip-one/assets/products/original/'.$value->image;
				$file2 = public_path().'/admin/clip-one/assets/products/thumbnail/'.$value->image;
				File::delete($file1,$file2);
			}

			Product::where('id',$id)->delete();
			ProductImage::where('product_id',$id)->delete();
		
			session()->flash('message', 'Product deleted successfully');
	        Session::flash('alert-type', 'success');

	        return back();
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured');
            Session::flash('alert-type', 'error');

          	return back();
        }
    }

    //===================================================
	
	public function status(Request $request, $id){
		
		try {
			
			$data = Dealer::find($id);
			
			if($data->status == '1')
			{
				$status = '0';
			} 
			else 
			{
				$status = '1';
			}
			$data->status = $status;
			$data->save();
			
		
			session()->flash('message', 'Dealer update successfully');
	        Session::flash('alert-type', 'success');
	        return redirect('admin/products/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured during status update');
            Session::flash('alert-type', 'error');
          return redirect('admin/products/index');
        }
    }

    //===================================================

    public function imageDelete($id){
		
		try {
			$data = ProductImage::find($id);

			$file1 = public_path().'/admin/clip-one/assets/products/thumbnail/'.$data->image;
			$file2 = public_path().'/admin/clip-one/assets/products/original/'.$data->image;
			File::delete($file1, $file2);

			$delete = ProductImage::where('id',$id)->delete();
		
			return response()->json(['msg'=>'success']);
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    return response()->json(['msg'=>'error']);
        }
    }

    //===================================================

    public function sortProducts(Request $request)
  	{
  		$tasks = Product::all();
        foreach ($tasks as $task) {
            $id = $task->id;
            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $task->update(['order_no' => $order['position']]);
                }
            }
        }
        return response()->json(['status'=>'success']);
  	}

  	//===================================================

  	public function add_more(Request $request)
	{
		try {
			$oldData = Product::where('id',$request->id)->first();

			foreach ($request->stock_number as $key => $stock_number) {
				$maxOrder = Product::max('order_no');

				$data = new Product;
		        $data->category_id = $oldData->category_id;
		        $data->dealer_id = $oldData->dealer_id;
		        $data->stock_number = $stock_number;
		        $data->backorder_number = $oldData->backorder_number;
		        $data->date = date('Y-m-d',strtotime($oldData->date));
		        $data->title = $oldData->title;
		        $data->model = $oldData->model;
		        $data->year = $oldData->year;
		        $data->hours = $oldData->hours;
		        $data->weight = $oldData->weight;
		        $data->description = $oldData->description;
		        $data->price = $oldData->price;
		        $data->type = $oldData->type;
		        $data->attachment = $oldData->attachment;
		        $data->status = $oldData->status;
		        $data->order_no = $maxOrder + 1;
		        $data->save();

		        $images = ProductImage::where('product_id',$request->id)->get();
				foreach ($images as $key1 => $image) {
        			$product_image = new ProductImage;
        			$product_image->product_id = $data->id;
        			$product_image->image = $image->image;
        			$product_image->save();
				}
			}

			session()->flash('message', 'Product added successfully');
			Session::flash('alert-type', 'success'); 
			return back();
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save Product');
            Session::flash('alert-type', 'error');
           	return back();
        }
	}

	//=================================================================

}
