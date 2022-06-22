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
use App\Models\Team;
use App\Models\AdminPermission;
use App\DataTables\TeamDataTable;
use App\Helpers\AdminHelper;

class TeamController extends Controller
{
    //=================================================================

	public function index(TeamDataTable $dataTable)
	{
		return $dataTable->render('admin/team/index');
	}

	//=================================================================

	public function add()
	{
		return view('admin/team/add');
	}

	//=================================================================

	public function save(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'designation' => 'required',
				'image' => 'required',
			]);
			if ($validator->fails()) { 
	            return redirect('admin/team/add')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {			        
		        $data = new Team;

		        //====== page product=====================================
				$image = $request->file('image');
				//dd($product); die;
				if(!empty($image)) {
		        	$imagename = time().'.'.$image->getClientOriginalExtension();
			        $destinationPath = public_path('/admin/clip-one/assets/team/thumbnail');
			        
			        $img = Image::make($image->getRealPath());

			        $img->resize(100, 100, function ($constraint) {
					    $constraint->aspectRatio();
					})->save($destinationPath.'/'.$imagename);

					$destinationPath = public_path('/admin/clip-one/assets/team/original');
			        $image->move($destinationPath, $imagename);

			        $source_url = public_path().'/admin/clip-one/assets/team/original/'.$imagename;
        			$destination_url = public_path().'/admin/clip-one/assets/team/original/'.$imagename;
        			$quality = 40;

        			AdminHelper::compress_image($source_url, $destination_url, $quality);

				} else {
					$imagename = '';
				}
					
		        //=========================================================
		        $data->name = $request->name;
		        $data->designation = $request->designation;
		        $data->description = $request->description;
		        $data->image = $imagename;
		        $data->save();

				session()->flash('message', 'Team added successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/team/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save Team');
            Session::flash('alert-type', 'error');
           	return redirect('admin/team/add');
        }
	}

	//=================================================================

	public function edit($id)
	{
		$data = array();
		$data['result'] = Team::find($id);

		return view('admin/team/edit',$data);
	}

	//=================================================================

	public function update(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'designation' => 'required',
			]);
			if ($validator->fails()) { 
	            return redirect('admin/team/edit/'.$request->id)
	                        ->withErrors($validator)
	                        ->withInput();
			} else {			        
		        $data = Team::find($request->id);

		        //====== page product=====================================
				$image = $request->file('image');
				//dd($product); die;
				if(!empty($image)) {
					$file1 = public_path().'/admin/clip-one/assets/team/thumbnail/'.$data->image;
        			$file2 = public_path().'/admin/clip-one/assets/team/original/'.$data->image;
        			// echo "<pre>";
        			// print_r($file1);die;

        			File::delete($file1, $file2);

		        	$imagename = time().'.'.$image->getClientOriginalExtension();
			        $destinationPath = public_path('/admin/clip-one/assets/team/thumbnail');
			        
			        $img = Image::make($image->getRealPath());

			        $img->resize(100, 100, function ($constraint) {
					    $constraint->aspectRatio();
					})->save($destinationPath.'/'.$imagename);

					$destinationPath = public_path('/admin/clip-one/assets/team/original');
			        $image->move($destinationPath, $imagename);

			        $source_url = public_path().'/admin/clip-one/assets/team/original/'.$imagename;
        			$destination_url = public_path().'/admin/clip-one/assets/team/original/'.$imagename;
        			$quality = 40;

        			AdminHelper::compress_image($source_url, $destination_url, $quality);

				} else {
					$imagename = $data->image;
				}
					
		        //=========================================================
		        $data->name = $request->name;
		        $data->designation = $request->designation;
		        $data->description = $request->description;
		        $data->image = $imagename;
		        $data->save();

				session()->flash('message', 'Team updated successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/team/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save Team');
            Session::flash('alert-type', 'error');
           	return redirect('admin/team/edit/'.$request->id);
        }
	}

	//=================================================================

	public function delete($id){
		
		try {
			$data = Team::find($id);

			$file1 = public_path().'/admin/clip-one/assets/team/thumbnail/'.$data->image;
			$file2 = public_path().'/admin/clip-one/assets/team/original/'.$data->image;
			File::delete($file1, $file2);

			$delete = Team::where('id',$id)->delete();
		
			session()->flash('message', 'Team deleted successfully');
	        Session::flash('alert-type', 'success');

	        return redirect('admin/team/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured');
            Session::flash('alert-type', 'error');

          	return redirect('admin/team/index');
        }
    }

    //===================================================
	
	public function status(Request $request, $id){
		
		try {
			
			$data = Team::find($id);
			
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
			
		
			session()->flash('message', 'Team update successfully');
	        Session::flash('alert-type', 'success');
	        return redirect('admin/team/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured during status update');
            Session::flash('alert-type', 'error');
          return redirect('admin/team/index');
        }
    }

    //===================================================

}
