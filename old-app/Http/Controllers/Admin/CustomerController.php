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
use App\User;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\AdminPermission;
use App\DataTables\CustomerDataTable;
use App\Helpers\AdminHelper;

class CustomerController extends Controller
{
    //=================================================================

	public function index(CustomerDataTable $dataTable)
	{
		return $dataTable->render('admin/customers/index');
	}

	//=================================================================

	public function add()
	{
		return view('admin/customers/add');
	}

	//=================================================================

	public function save(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'email' => 'required|unique:customers,email',
				'phone' => 'required|unique:customers,phone',
			]);
			if ($validator->fails()) { 
	            return redirect('admin/customers/add')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {

		        $data = new Customer;
		        //=========================================================
		        $data->name = $request->name;
		        $data->vat_number = $request->vat_number;
		        $data->email = $request->email;
		        $data->phone = $request->phone;
		        $data->address = $request->address;
		        $data->save();

				session()->flash('message', 'Record added successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/customers/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save record');
            Session::flash('alert-type', 'error');
           	return redirect('admin/customers/add');
        }
	}

	//=================================================================

	public function edit($id)
	{
		$data = array();
		$data['result'] = Customer::find($id);

		return view('admin/customers/edit',$data);
	}

	//=================================================================

	public function update(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'email' => 'required|unique:customers,email,'.$request->id,
				'phone' => 'required|unique:customers,phone,'.$request->id,
			]);
			if ($validator->fails()) { 
	            return redirect('admin/customers/edit'.'/'.$request->id)
	                        ->withErrors($validator)
	                        ->withInput();
			} else {

		        $data = Customer::find($request->id);
				$data->name = $request->name;
				$data->vat_number = $request->vat_number;
		        $data->email = $request->email;
		        $data->phone = $request->phone;
		        $data->address = $request->address;
		        $data->save();

		        DB::table('leads')
	        		->where('customer_id',$request->id)
	        		->update([
			        	'name' => $request->name,
			        	'email' => $request->email,
			        	'phone' => $request->phone,
			        	'address' => $request->address,
			        	'vat_number' => $request->vat_number,
		        	]);

				session()->flash('message', 'Record updated successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/customers/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during update record');
            Session::flash('alert-type', 'error');
           	return redirect('admin/customers/edit'.'/'.$request->id);
        }
	}

	//=================================================================

	public function delete($id){
		
		try {
			Customer::where('id',$id)->delete();
		
			session()->flash('message', 'Record deleted successfully');
	        Session::flash('alert-type', 'success');

	        return redirect('admin/customers/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured!');
            Session::flash('alert-type', 'error');

          	return redirect('admin/customers/index');
        }
    }

    //===================================================

    public function status(Request $request, $id){
		
		try {
			
			$data = Customer::find($id);
			
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
			
		
			session()->flash('message', 'Status update successfully');
	        Session::flash('alert-type', 'success');
	        return redirect('admin/customers/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured during status update');
            Session::flash('alert-type', 'error');
          return redirect('admin/customers/index');
        }
    }

    //=================================================================

	public function import(Request $request)
	{
		if(!empty($request->file('csv'))){
			$arr_image = $request->file('csv');
	      	$arr_extension = $arr_image->getClientOriginalExtension();
	      	$arr_image_name = $arr_image->getClientOriginalName();
	      	$arr_img_name = str_replace(' ', '_', strtolower($arr_image_name));
	      	$input['csv'] = time().'_'.$arr_img_name;
	      	$arr_path = base_path().'/public/admin/clip-one/assets/customers/csv/';
	      	$arr_image->move($arr_path, $input['csv']);
	      	$file = base_path().'/public/admin/clip-one/assets/customers/csv/'.$input['csv'];
	      
	      	$productArr = AdminHelper::csvToArray($file);
	      	
	      	$tableHeader = array(
	        	'name'=>'name',
	        	'email'=>'email',
	        	'phone'=>'phone',
	        	'address'=>'address',
	        	'vat_number'=>'vat_number',
	      	);
	      	
	      	for ($i = 0; $i < count($productArr); $i ++){
		        if (array_diff_key($tableHeader,$productArr[$i])) {
		          	$msg_error="Wrong format, Please check sample format first!";
		      		$request->session()->flash('msg_error', $msg_error);
		      		return redirect()->back();
		        }else{
		          $data[] = [
		            'name' => $productArr[$i]['name'],
		            'email' => $productArr[$i]['email'],
		            'phone' => $productArr[$i]['phone'],
		            'address' => $productArr[$i]['address'],
		            'vat_number' => $productArr[$i]['vat_number']
		          ];
		        }
	      	}
	      	$result = DB::table('customers')->insert($data);

	      	if ($result) {
	        	session()->flash('message', 'Data imported successfully');
				Session::flash('alert-type', 'success'); 
				return redirect()->back();
	      	}else{
		      	session()->flash('message', $msg_error);
				Session::flash('alert-type', 'success'); 
				return redirect()->back();
	      	}
		}else{
			return view('admin.customers.import');
		}
	}

}