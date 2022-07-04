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
use App\Models\Dealer;
use App\Models\Action;
use App\Models\Role;
use App\Models\AdminPermission;
//use App\DataTables\DealerDataTable;
use App\Helpers\AdminHelper;

class DealerController extends Controller
{
    //=================================================================

	public function index()
	{
		$data = [];
		//==============================================
		$status_action = Action::where('action_slug','status')->first();
        $data['checkStatusAction'] = Role::where('name_slug','dealers')->whereRaw("find_in_set('".$status_action->id."',action_id)")->first();
        $data['roles'] = Role::where('name_slug','dealers')->first();
        $data['checkStatusPermission'] = AdminPermission::where('user_id',Auth::user()->id)->whereRaw("find_in_set('status',action_id)")->first();
        $data['action_ids'] = explode(',', $data['roles']->action_id);
        //==============================================

		$data['results'] = Dealer::orderBy('order_no')->get();
		

		return view('admin/dealers/index',$data);
	}

	//=================================================================

	public function add()
	{
		return view('admin/dealers/add');
	}

	//=================================================================

	public function save(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'image' => 'required',
			]);
			if ($validator->fails()) { 
	            return redirect('admin/dealers/add')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {			        
		        $data = new Dealer;
		        //===========================================
		        $image = $request->file('image');
		        if(!empty($image)) {
		        	$imagename = time().'.'.$image->getClientOriginalExtension();
			        $destinationPath = public_path('/admin/clip-one/assets/dealers/thumbnail');
			        
			        $img = Image::make($image->getRealPath());

			        $img->resize(100, 100, function ($constraint) {
					    $constraint->aspectRatio();
					})->save($destinationPath.'/'.$imagename);

					$destinationPath = public_path('/admin/clip-one/assets/dealers/original');
			        $image->move($destinationPath, $imagename);

			        $source_url = public_path().'/admin/clip-one/assets/dealers/original/'.$imagename;
        			$destination_url = public_path().'/admin/clip-one/assets/dealers/original/'.$imagename;
        			$quality = 40;

        			AdminHelper::compress_image($source_url, $destination_url, $quality);

				} else {
					$imagename = '';
				}
		        //===========================================
				$video_file = $request->file('video_file');
				//dd($product); die;
				if(!empty($video_file)) {
		        	$video_file_name = rand('1111','9999').'_'.time().'.'.$video_file->getClientOriginalExtension();

					$destinationPath = public_path('/admin/clip-one/assets/dealers/video_file');
			        $video_file->move($destinationPath, $video_file_name);
				} else {
					$video_file_name = '';
				}
		        //=========================================================
				if(!empty($request->video_url)) {
					$video_url = $request->video_url;
				} else {
					$video_url = '';
				}
		        //=========================================================
		        $data->name = $request->name;
		        $data->image = $imagename;
		        $data->type = $request->type;
		        $data->video_url = $video_url;
		        $data->video_file = $video_file_name;
		        $data->save();

				session()->flash('message', 'Dealer added successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/dealers/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save Dealer');
            Session::flash('alert-type', 'error');
           	return redirect('admin/dealers/add');
        }
	}

	//=================================================================

	public function edit($id)
	{
		$data = array();
		$data['result'] = Dealer::find($id);

		return view('admin/dealers/edit',$data);
	}

	//=================================================================

	public function update(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name' => 'required',
			]);
			if ($validator->fails()) { 
	            return redirect('admin/dealers/edit/'.$request->id)
	                        ->withErrors($validator)
	                        ->withInput();
			} else {			        
		        $data = Dealer::find($request->id);
		        //===========================================
		        $image = $request->file('image');
				//dd($product); die;
				if(!empty($image)) {
					$file1 = public_path().'/admin/clip-one/assets/dealers/thumbnail/'.$data->image;
        			$file2 = public_path().'/admin/clip-one/assets/dealers/original/'.$data->image;
        			// echo "<pre>";
        			// print_r($file1);die;

        			File::delete($file1, $file2);

		        	$imagename = time().'.'.$image->getClientOriginalExtension();
			        $destinationPath = public_path('/admin/clip-one/assets/dealers/thumbnail');
			        
			        $img = Image::make($image->getRealPath());

			        $img->resize(100, 100, function ($constraint) {
					    $constraint->aspectRatio();
					})->save($destinationPath.'/'.$imagename);

					$destinationPath = public_path('/admin/clip-one/assets/dealers/original');
			        $image->move($destinationPath, $imagename);

			        $source_url = public_path().'/admin/clip-one/assets/dealers/original/'.$imagename;
        			$destination_url = public_path().'/admin/clip-one/assets/dealers/original/'.$imagename;
        			$quality = 40;

        			AdminHelper::compress_image($source_url, $destination_url, $quality);

				} else {
					$imagename = $data->image;
				}
		        //===========================================
				$video_file = $request->file('video_file');
				//dd($product); die;
				if(!empty($video_file)) {
					$file1 = public_path().'/admin/clip-one/assets/dealers/video_file/'.$data->video_file;
        			File::delete($file1);

		        	$video_file_name = time().'.'.$video_file->getClientOriginalExtension();

					$destinationPath = public_path('/admin/clip-one/assets/dealers/video_file');
			        $video_file->move($destinationPath, $video_file_name);
				} else {
					$video_file_name = $data->video_file;
				}
		        //=========================================================
				if(!empty($request->video_url)) {
					$video_url = $request->video_url;
				} else {
					$video_url = '';
				}
		        //=========================================================
		        $data->name = $request->name;
		        $data->image = $imagename;
		        $data->type = $request->type;
		        $data->video_url = $video_url;
		        $data->video_file = $video_file_name;
		        $data->save();

				session()->flash('message', 'Dealer updated successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/dealers/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save Dealer');
            Session::flash('alert-type', 'error');
           	return redirect('admin/dealers/edit/'.$request->id);
        }
	}

	//=================================================================

	public function delete($id){
		
		try {
			$data = Dealer::find($id);

			$file1 = public_path().'/admin/clip-one/assets/dealers/thumbnail/'.$data->image;
			File::delete($file1, $file2);

			$delete = Dealer::where('id',$id)->delete();
		
			session()->flash('message', 'Dealer deleted successfully');
	        Session::flash('alert-type', 'success');

	        return redirect('admin/dealers/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured');
            Session::flash('alert-type', 'error');

          	return redirect('admin/dealers/index');
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
	        return redirect('admin/dealers/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured during status update');
            Session::flash('alert-type', 'error');
          return redirect('admin/dealers/index');
        }
    }
    //===================================================

    public function sortBrands(Request $request)
  	{
  		$tasks = Dealer::all();
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

}
