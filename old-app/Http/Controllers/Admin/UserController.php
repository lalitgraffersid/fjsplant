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
use App\Models\User;
use App\Models\Match;
use App\Models\AdminPermission;
use App\DataTables\UserDataTable;

class UserController extends Controller
{
    //=================================================================

	public function index(UserDataTable $dataTable)
	{
		return $dataTable->render('admin/users/index');
	}

	//=================================================================

	public function add()
	{
		return view('admin/users/add');
	}

	//=================================================================

	public function save(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'mobile' => 'required|unique:users,mobile',
				'email' => 'required|unique:users,email',
				'password' => 'required',
			]);
			if ($validator->fails()) { 
			            return redirect('admin/users/add')
			                        ->withErrors($validator)
			                        ->withInput();
			} else {			        
		        $data = new User;
		        //=========================================================
		        $data->user_type = 'user';
		        $data->name = $request->name;
		        $data->mobile = $request->mobile;
		        $data->email = $request->email;
		        $data->password = Hash::make($request->password);
		        $data->save();

				session()->flash('message', 'User added successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/users/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save User');
            Session::flash('alert-type', 'error');
           	return redirect('admin/users/add');
        }
	}

	//=================================================================

	public function edit($id)
	{
		$data = array();
		$data['result'] = User::find($id);

		return view('admin/users/edit',$data);
	}

	//=================================================================

	public function update(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'mobile' => 'required|unique:users,mobile,'.$request->id,
				'email' => 'required|unique:users,email,'.$request->id,
			]);
			if ($validator->fails()) { 
			            return redirect('admin/users/edit/'.$request->id)
			                        ->withErrors($validator)
			                        ->withInput();
			} else {			        
		        $data = User::find($request->id);
					
		        //=========================================================
		        $data->name = $request->name;
		        $data->mobile = $request->mobile;
		        $data->email = $request->email;
		        $data->save();

				session()->flash('message', 'User updated successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/users/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save User');
            Session::flash('alert-type', 'error');
           	return redirect('admin/users/edit/'.$request->id);
        }
	}

	//=================================================================

	public function delete($id){
		
		try {
			$data = User::find($id)->delete();
		
			session()->flash('message', 'User deleted successfully');
	        Session::flash('alert-type', 'success');

	        return redirect('admin/users/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured');
            Session::flash('alert-type', 'error');

          	return redirect('admin/users/index');
        }
    }

	//=================================================================

	public function reset_password($id)
	{
		$data = array();
		$data['user'] = User::find($id);

		return view('admin/users/password',$data);
	}

	//=================================================================

	public function savePassword(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'password' => 'required',
			]);
			if ($validator->fails()) { 
			            return redirect('admin/users/password/'.$request->id)
			                        ->withErrors($validator)
			                        ->withInput();
			} else {
	            $user = User::find($request->id);
	            $user->password = Hash::make($request->password);
	    
	            if ($user->save()) {
	                session()->flash('message', 'Password reset successfully');
					Session::flash('alert-type', 'success'); 
					return redirect('admin/users/index');
	            }else{
	                session()->flash('message', 'Something went wrong!');
					Session::flash('alert-type', 'success'); 
					return redirect('admin/users/password/'.$request->id);
	            }
	        }
        }catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during reset password');
            Session::flash('alert-type', 'error');
           	return redirect('admin/users/password/'.$request->id);
        }
	}

	//=================================================================
	
	public function status(Request $request, $id){
		
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
			
		
			session()->flash('message', 'User status update successfully');
	        Session::flash('alert-type', 'success');
	        return redirect('admin/users/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured during status update');
            Session::flash('alert-type', 'error');
          return redirect('admin/users/index');
        }
    }

    //===================================================

    public function view($id)
    {
    	$data['result'] = User::find($id);
    	if (!empty($data['result']->image)) {
    		$image = url('/public/admin/clip-one/assets/user/thumbnail').'/'.$data['result']->image;
    	}else if ($data['result']->social_image) {
    		$image = $data['result']->social_image;
    	}else{
    		$image = url('assets/admin/dist/img/avatar.png');
    	}
    	$data['image'] = $image;

    	return view('admin.users.view',$data);
    }

    //===================================================

}
