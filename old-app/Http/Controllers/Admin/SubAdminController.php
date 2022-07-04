<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Cookie;
use Illuminate\Http\Request;
use Validator;
use Input;
use App\Models\User;
use App\Models\Cms;
use App\Models\Category;
use App\Models\AdminPermission;
use Session;
use DB;
use Image;
use File;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\DataTables\SubAdminDataTable;

class SubAdminController extends Controller
{
    //=================================================================

	public function index(SubAdminDataTable $dataTable)
	{
		return $dataTable->render('admin/sub_admin/index');
	}

	//=================================================================

	public function add(Request $request)
	{
		return view('admin/sub_admin/add');
	}

	//=================================================================
	
	public function save(Request $request)
	{
		try {
	        //dd($request); die;
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'email' => 'required',
				'password' => 'required',
			]);

			if ($validator->fails()) { 
				return redirect('admin/sub_admin/add')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {	        
	         	$user = new User();
             	$user->user_type = 'sub_admin';
             	$user->account_type = $request->account_type;
             	$user->name = $request->name;
	         	$user->email = $request->email;
	         	$user->password = Hash::make($request->password);
	         	$user->status = '1';
	         	$user->save();

             	$subadmin_id = $user->id;

             	//code for save user permissions
              	// $total_row=$request->total_row;
              	// for($i=1; $i<=$total_row;$i++)
              	// {
               //   	$actid = $request['action_id'.$i];
               //   	$rolid = $request['role_id'.$i];

               //   	if(!empty($rolid) && !empty($actid)) 
               //    	{
               //   		$acc=implode(',',$actid);
               //   		$adminpermission = new AdminPermission();
               //   		$adminpermission->user_id = $subadmin_id;
	             	// 	$adminpermission->role_id = $rolid;
	             	// 	$adminpermission->action_id = 'index,'.$acc;
	             	// 	$adminpermission->save();
               //    	}
              	// } 

              	$adminpermission = new AdminPermission();
         		$adminpermission->user_id = $subadmin_id;
         		$adminpermission->role_id = '16';
         		$adminpermission->action_id = 'index,view';
         		$adminpermission->save();

				session()->flash('message', 'Sub Admin Created successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/sub_admin/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save Sub Admin');
            Session::flash('alert-type', 'error');
           	return redirect('admin/sub_admin/add');  
        }
	}

	//===========================================================================

	public function edit($id)
	{
		$data = array();
		$data['user'] = User::where('id',$id)->first();
		
		return view('admin/sub_admin/edit',$data);
	}

	public function update(Request $request) 
	{
		try {
			$id = $request->id;

			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'email' => 'required',
			]);

			if ($validator->fails()) { 
				return redirect('admin/sub_admin/edit'.'/'.$id)
	                        ->withErrors($validator)
	                        ->withInput();
			} else {	        
	         	$user = User::find($id);

             	$user->user_type = 'sub_admin';
             	$user->name = $request->name;
             	$user->account_type = $request->account_type;
	         	$user->email = $request->email;
	         	$user->password = $request->password != '' ? Hash::make($request->password) : $user->password;
	         	$user->status = $user->status;
	         	$user->save();

             	$subadmin_id = $user->id;

             	//code for save user permissions
             	DB::table('admin_permissions')->where('user_id',$id)->delete();

              	// $total_row=$request->total_row;
              	// for($i=1; $i<=$total_row;$i++)
              	// {
               //   	$actid=$request['action_id'.$i];
               //   	$rolid=$request['role_id'.$i];

               //   	if(!empty($rolid) && !empty($actid)) 
               //    	{
               //   		$acc=implode(',',$actid);
               //   		$adminpermission = new AdminPermission();
               //   		$adminpermission->user_id = $subadmin_id;
	             	// 	$adminpermission->role_id = $rolid;
	             	// 	$adminpermission->action_id = 'index,'.$acc;
	             	// 	$adminpermission->save();
               //    	}
              	// }
              	
              	$adminpermission = new AdminPermission();
         		$adminpermission->user_id = $subadmin_id;
         		$adminpermission->role_id = '16';
         		$adminpermission->action_id = 'index,view';
         		$adminpermission->save();

				session()->flash('message', 'Sub Admin Created successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/sub_admin/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured!');
			Session::flash('alert-type', 'error'); 
			return redirect('admin/sub_admin/edit'.'/'.$id);
        }
	}

	public function delete($id)
	{
		try {
			User::where('id', $id)->delete();
			AdminPermission::where('user_id', $id)->delete();

			session()->flash('message', 'Sub Admin deleted successfully.');
			Session::flash('alert-type', 'success'); 
			
			return redirect('admin/sub_admin/index');
		} catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured!');
			Session::flash('alert-type', 'error'); 
			
			return redirect('admin/sub_admin/index');
        }
	}

	//=================================================================
	public function set_status(Request $request, $id){
		
		try {
			
			$User = User::find($id);
			
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
			
		
			session()->flash('message', 'Sub Admin status update successfully');
	        Session::flash('alert-type', 'success');
	        return redirect('admin/sub_admin/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured during status update');
            Session::flash('alert-type', 'error');
          return redirect('admin/sub_admin/index');
        }
    }

    //===================================================
	
	


}
