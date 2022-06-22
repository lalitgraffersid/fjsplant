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
use App\Models\News;
use App\Models\AdminPermission;
use App\DataTables\NewsDataTable;
use App\Helpers\AdminHelper;

class NewsController extends Controller
{
    //=================================================================

	public function index(NewsDataTable $dataTable)
	{
		return $dataTable->render('admin/news/index');
	}

	//=================================================================

	public function add()
	{
		return view('admin/news/add');
	}

	//=================================================================

	public function save(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'title' => 'required',
				'image' => 'required',
			]);
			if ($validator->fails()) { 
	            return redirect('admin/news/add')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {			        
		        $data = new News;

		        //====== page product=====================================
				$image = $request->file('image');
				//dd($product); die;
				if(!empty($image)) {
		        	$imagename = time().'.'.$image->getClientOriginalExtension();
			        $destinationPath = public_path('/admin/clip-one/assets/news/thumbnail');
			        
			        $img = Image::make($image->getRealPath());

			        $img->resize(100, 100, function ($constraint) {
					    $constraint->aspectRatio();
					})->save($destinationPath.'/'.$imagename);

					$destinationPath = public_path('/admin/clip-one/assets/news/original');
			        $image->move($destinationPath, $imagename);

			        $source_url = public_path().'/admin/clip-one/assets/news/original/'.$imagename;
        			$destination_url = public_path().'/admin/clip-one/assets/news/original/'.$imagename;
        			$quality = 40;

        			AdminHelper::compress_image($source_url, $destination_url, $quality);

				} else {
					$imagename = '';
				}
					
		        //=========================================================
		        $data->title = $request->title;
		        $data->description = $request->description;
		        $data->image = $imagename;
		        $data->save();

				session()->flash('message', 'News added successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/news/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save News');
            Session::flash('alert-type', 'error');
           	return redirect('admin/news/add');
        }
	}

	//=================================================================

	public function edit($id)
	{
		$data = array();
		$data['result'] = News::find($id);

		return view('admin/news/edit',$data);
	}

	//=================================================================

	public function update(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'title' => 'required',
			]);
			if ($validator->fails()) { 
	            return redirect('admin/news/edit/'.$request->id)
	                        ->withErrors($validator)
	                        ->withInput();
			} else {			        
		        $data = News::find($request->id);

		        //====== page product=====================================
				$image = $request->file('image');
				//dd($product); die;
				if(!empty($image)) {
					$file1 = public_path().'/admin/clip-one/assets/news/thumbnail/'.$data->image;
        			$file2 = public_path().'/admin/clip-one/assets/news/original/'.$data->image;
        			// echo "<pre>";
        			// print_r($file1);die;

        			File::delete($file1, $file2);

		        	$imagename = time().'.'.$image->getClientOriginalExtension();
			        $destinationPath = public_path('/admin/clip-one/assets/news/thumbnail');
			        
			        $img = Image::make($image->getRealPath());

			        $img->resize(100, 100, function ($constraint) {
					    $constraint->aspectRatio();
					})->save($destinationPath.'/'.$imagename);

					$destinationPath = public_path('/admin/clip-one/assets/news/original');
			        $image->move($destinationPath, $imagename);

			        $source_url = public_path().'/admin/clip-one/assets/news/original/'.$imagename;
        			$destination_url = public_path().'/admin/clip-one/assets/news/original/'.$imagename;
        			$quality = 40;

        			AdminHelper::compress_image($source_url, $destination_url, $quality);

				} else {
					$imagename = $data->image;
				}
					
		        //=========================================================
		        $data->title = $request->title;
		        $data->description = $request->description;
		        $data->image = $imagename;
		        $data->save();

				session()->flash('message', 'News updated successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/news/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save News');
            Session::flash('alert-type', 'error');
           	return redirect('admin/news/edit/'.$request->id);
        }
	}

	//=================================================================

	public function delete($id){
		
		try {
			$data = News::find($id);

			$file1 = public_path().'/admin/clip-one/assets/news/thumbnail/'.$data->image;
			$file2 = public_path().'/admin/clip-one/assets/news/original/'.$data->image;
			File::delete($file1, $file2);

			$delete = News::where('id',$id)->delete();
		
			session()->flash('message', 'News deleted successfully');
	        Session::flash('alert-type', 'success');

	        return redirect('admin/news/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured');
            Session::flash('alert-type', 'error');

          	return redirect('admin/news/index');
        }
    }

    //===================================================
	
	public function status(Request $request, $id){
		
		try {
			
			$data = News::find($id);
			
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
			
		
			session()->flash('message', 'News update successfully');
	        Session::flash('alert-type', 'success');
	        return redirect('admin/news/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured during status update');
            Session::flash('alert-type', 'error');
          return redirect('admin/news/index');
        }
    }

    //===================================================

}
