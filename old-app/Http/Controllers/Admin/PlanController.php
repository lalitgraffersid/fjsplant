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
use App\Models\Plan;
use App\Models\AdminPermission;
use App\DataTables\PlanDataTable;
use App\Helpers\AdminHelper;

class PlanController extends Controller
{
    //=================================================================

	public function index(PlanDataTable $dataTable)
	{
		return $dataTable->render('admin/plans/index');
	}

	//=================================================================

	public function add()
	{
		return view('admin/plans/add');
	}

	//=================================================================

	public function save(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'title' => 'required',
				'price' => 'required',

			]);
			if ($validator->fails()) { 
			            return redirect('admin/plans/add')
			                        ->withErrors($validator)
			                        ->withInput();
			} else {			        
		        $data = new Plan;
					
		        //=========================================================
		        $data->type = $request->type;
		        $data->title = $request->title;
		        $data->price = $request->price;
		        $data->duration = $request->duration;
		        $data->no_of_user = $request->no_of_user;
		        $data->status = '1';
		        $data->save();

				session()->flash('message', 'Plan added successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/plans/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save Plan');
            Session::flash('alert-type', 'error');
           	return redirect('admin/plans/add');
        }
	}

	//=================================================================

	public function edit($id)
	{
		$data = array();
		$data['result'] = Plan::find($id);

		return view('admin/plans/edit',$data);
	}

	//=================================================================

	public function update(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'title' => 'required',
				'price' => 'required',
			]);
			if ($validator->fails()) { 
			            return redirect('admin/plans/edit/'.$request->id)
			                        ->withErrors($validator)
			                        ->withInput();
			} else {			        
		        $data = Plan::find($request->id);
		        //=========================================================
		        $data->title = $request->title;
		        $data->price = $request->price;
		        $data->duration = $request->duration;
		        $data->no_of_user = $request->no_of_user;
		        $data->status = '1';
		        $data->save();

				session()->flash('message', 'Plan updated successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/plans/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save Plan');
            Session::flash('alert-type', 'error');
           	return redirect('admin/plans/edit/'.$request->id);
        }
	}

	//=================================================================

	public function delete($id){
		
		try {
			$data = Plan::find($id);

			$delete = Plan::where('id',$id)->delete();
		
			session()->flash('message', 'Plan deleted successfully');
	        Session::flash('alert-type', 'success');

	        return redirect('admin/plans/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured');
            Session::flash('alert-type', 'error');

          	return redirect('admin/plans/index');
        }
    }

    //===================================================
	
	public function status(Request $request, $id){
		
		try {
			
			$User = Plan::find($id);
			
			if($User->status == '1')
			{
				$status = '0';
			} 
			else 
			{
				$status = '1';
			}
			$User->status = $status;
			$User->save();
			
		
			session()->flash('message', 'Plan status updated successfully');
	        Session::flash('alert-type', 'success');
	        return redirect('admin/plans/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured during status update');
            Session::flash('alert-type', 'error');
          return redirect('admin/plans/index');
        }
    }

    //===================================================

}
