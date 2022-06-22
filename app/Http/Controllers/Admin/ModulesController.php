<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Auth;
use Cookie;
use Validator;
use Input;
use Session;
use DB;
use Image;
use File;
use Exception;
use App\Models\Action;
use App\Models\Section;
use App\Models\Role;
use App\Models\AdminPermission;
use DataTables;
use App\DataTables\ActionDataTable;
use App\DataTables\SectionDataTable;
use App\DataTables\RoleDataTable;

class ModulesController extends Controller
{
    //=================================================================
	public function index(ActionDataTable $dataTable)
	{
		return $dataTable->render('admin/action/index');
	}

	public function actionsAdd(Request $request)
	{
		return view('admin/action/actionsAdd');
	}
	
	public function actionsSave(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'action_title' => 'required|unique:actions',
			]);

			if ($validator->fails()) { 
				return redirect('admin/actions/add')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {	        
	         	$action = new Action();
             	$action->action_title = $request->action_title;
             	$action->class = $request->class;
             	$action->icon = $request->icon;
             	$action->action_slug = Str::slug($request->action_title, '-');

             	if ($action->save()) {
             		session()->flash('message', 'Action Created successfully');
					Session::flash('alert-type', 'success'); 
					return redirect('admin/actions/index');
             	}else{
             		session()->flash('message', 'Something went wrong!');
					Session::flash('alert-type', 'error'); 
					return redirect('admin/actions/add');
             	}
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured!');
			Session::flash('alert-type', 'error'); 
			return redirect('admin/actions/add');
        }
	}

	public function edit($id)
	{
		$data = array();
		$data['action'] = Action::where('id',$id)->first();
		
		return view('admin/action/edit',$data);
	}

	public function update(Request $request) 
	{
		try {
			$id = $request->id;

			$validator = Validator::make($request->all(), [
				'action_title' => 'required|unique:actions,action_title,'.$id,
			]);

			if ($validator->fails()) { 
				return redirect('admin/actions/edit')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {	        
	         	$action = Action::find($id);
             	$action->action_title = $request->action_title;
             	$action->class = $request->class;
             	$action->icon = $request->icon;
             	$action->action_slug = Str::slug($request->action_title, '-');

             	if ($action->save()) {
             		session()->flash('message', 'Action updated successfully');
					Session::flash('alert-type', 'success'); 
					return redirect('admin/actions/index');
             	}else{
             		session()->flash('message', 'Something went wrong!');
					Session::flash('alert-type', 'error'); 
					return redirect('admin/actions/edit');
             	}
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured!');
			Session::flash('alert-type', 'error'); 
			return redirect('admin/actions/add');
        }
	}

	public function delete($id)
	{
		try {
			Action::where('id', $id)->delete();

			session()->flash('message', 'Action deleted successfully.');
			Session::flash('alert-type', 'success'); 
			
			return redirect('admin/actions/index');
		} catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured!');
			Session::flash('alert-type', 'error'); 
			
			return redirect('admin/actions/index');
        }
	}

	//=================================================================
	//=================================================================

	public function sectionsList(SectionDataTable $dataTable)
	{
		return $dataTable->render('admin/section/index');
	}
	
	public function sectionsAdd(Request $request)
	{
		return view('admin/section/add');
	}
	
	public function sectionsSave(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'section_title' => 'required|unique:sections',
			]);

			if ($validator->fails()) { 
				return redirect('admin/sections/add')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {
				$sectionData = Section::max('section_order');

	         	$section = new Section();
             	$section->section_title = $request->section_title;
             	$section->section_slug = Str::slug($request->section_title, '-');
             	$section->section_order = $sectionData != '' ? $sectionData+1 : '0';

             	if ($section->save()) {
             		session()->flash('message', 'Section Created successfully');
					Session::flash('alert-type', 'success'); 
					return redirect('admin/sections/index');
             	}else{
             		session()->flash('message', 'Something went wrong!');
					Session::flash('alert-type', 'error'); 
					return redirect('admin/sections/add');
             	}
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured!');
			Session::flash('alert-type', 'error'); 
			return redirect('admin/sections/add');
        }
	}

	public function sectionsEdit($id)
	{
		$data = array();
		$data['section'] = Section::where('id',$id)->first();
		
		return view('admin/section/edit',$data);
	}

	public function sectionsUpdate(Request $request) 
	{
		try {
			$id = $request->id;

			$validator = Validator::make($request->all(), [
				'section_title' => 'required|unique:sections,section_title,'.$id,
			]);

			if ($validator->fails()) { 
				return redirect('admin/sections/edit')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {	        
	         	$section = Section::find($id);
             	$section->section_title = $request->section_title;
             	$section->section_slug = Str::slug($request->section_title, '-');

             	if ($section->save()) {
             		session()->flash('message', 'Section updated successfully');
					Session::flash('alert-type', 'success'); 
					return redirect('admin/sections/index');
             	}else{
             		session()->flash('message', 'Something went wrong!');
					Session::flash('alert-type', 'error'); 
					return redirect('admin/sections/edit');
             	}
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured!');
			Session::flash('alert-type', 'error'); 
			return redirect('admin/sections/add');
        }
	}

	public function sectionsDelete($id)
	{
		try {
			Section::where('id', $id)->delete();
			Role::where('section_id', $id)->delete();

			session()->flash('message', 'Section deleted successfully.');
			Session::flash('alert-type', 'success'); 
			
			return redirect('admin/sections/index');
		} catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured!');
			Session::flash('alert-type', 'error'); 
			
			return redirect('admin/sections/index');
        }
	}

	//=================================================================
	//=================================================================

	public function rolesList(RoleDataTable $dataTable)
	{
		return $dataTable->render('admin/role/index');
	}
	
	public function rolesAdd(Request $request)
	{
		$data = array();
		$data['sections'] = Section::get();
		$data['actions'] = Action::get();

		return view('admin/role/add',$data);
	}
	
	public function rolesSave(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name' => 'required|unique:roles',
				'section_id' => 'required',
			]);

			if ($validator->fails()) { 
				return redirect('admin/roles/add')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {
				$roleData = Role::where('section_id',$request->section_id)->max('order');

	         	$role = new Role();
             	$role->section_id = $request->section_id;
             	$role->action_id = implode(',', $request->action_id);
             	$role->name = $request->name;
             	$role->name_slug = Str::slug($request->name, '_');
             	$role->url = Str::slug($request->name, '_').'.index';
             	$role->order = $roleData != '' ? $roleData+1 : '0';

             	if ($role->save()) {
             		session()->flash('message', 'Role Created successfully');
					Session::flash('alert-type', 'success'); 
					return redirect('admin/roles/index');
             	}else{
             		session()->flash('message', 'Something went wrong!');
					Session::flash('alert-type', 'error'); 
					return redirect('admin/roles/add');
             	}
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured!');
			Session::flash('alert-type', 'error'); 
			return redirect('admin/roles/add');
        }
	}

	public function rolesEdit($id)
	{
		$data = array();
		$data['sections'] = Section::get();
		$data['actions'] = Action::get();
		$data['role'] = Role::where('id',$id)->first();
		$data['roleActions'] = explode(',',$data['role']['action_id']);
		
		return view('admin/role/edit',$data);
	}

	public function rolesUpdate(Request $request) 
	{
		try {
			$id = $request->id;

			$validator = Validator::make($request->all(), [
				'name' => 'required|unique:roles,name,'.$id,
				'section_id' => 'required',
				'action_id' => 'required',
			]);

			if ($validator->fails()) { 
				return redirect('admin/roles/edit')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {	        
	         	$role = Role::find($id);
             	$role->section_id = $request->section_id;
             	$role->action_id = implode(',', $request->action_id);
             	$role->name = $request->name;
             	$role->name_slug = Str::slug($request->name, '_');
             	$role->url = Str::slug($request->name, '_').'.index';

             	if ($role->save()) {
             		session()->flash('message', 'Role updated successfully');
					Session::flash('alert-type', 'success'); 
					return redirect('admin/roles/index');
             	}else{
             		session()->flash('message', 'Something went wrong!');
					Session::flash('alert-type', 'error'); 
					return redirect('admin/roles/edit');
             	}
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured!');
			Session::flash('alert-type', 'error'); 
			return redirect('admin/roles/add');
        }
	}

	public function rolesDelete($id)
	{
		try {
			Role::where('id', $id)->delete();

			session()->flash('message', 'Role deleted successfully.');
			Session::flash('alert-type', 'success'); 
			
			return redirect('admin/roles/index');
		} catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured!');
			Session::flash('alert-type', 'error'); 
			
			return redirect('admin/roles/index');
        }
	}

	//=================================================================
	//=================================================================
	


}
