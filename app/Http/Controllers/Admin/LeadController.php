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
use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\Customer;
use App\Models\AdminPermission;
use App\DataTables\LeadDataTable;
use App\Helpers\AdminHelper;

class LeadController extends Controller
{
    //=================================================================

	public function index(LeadDataTable $dataTable)
	{
		return $dataTable->render('admin/leads/index');
	}

	//=================================================================

	public function add()
	{
		$data = array();
		$data['users'] = User::where('user_type','user')->get();
		$data['customers'] = Customer::where('status','1')->get();

		return view('admin/leads/add',$data);
	}

	//=================================================================

	public function save(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'customer_id' 	=> 'required',
				'user_id' 		=> 'required',
				'status' 		=> 'required',
			]);
			if ($validator->fails()) { 
	            return redirect('admin/leads/add')
	                        ->withErrors($validator)
	                        ->withInput();
			} else {
				$customer = Customer::where('id',$request->customer_id)->first();

		        $data = new Lead;
		        //=========================================================
		        $data->title 		= $request->title;
		        $data->customer_id 	= $request->customer_id;
		        $data->name 	 	= $customer->name;
		        $data->vat_number 	= $customer->vat_number;
		        $data->email 		= $customer->email;
		        $data->phone 		= $customer->phone;
		        $data->address 		= $customer->address;
		        $data->message 		= $request->message;
		        $data->user_id 		= $request->user_id;
		        $data->status 		= $request->status;
		        $data->date 		= date('Y-m-d');
		        $data->save();

		        /*Sending notification to sales rep(user)*/
		        $user = User::find($request->user_id);
		        //$device_id = $user->device_id;
		        $device_id = 'APA91bH0rqxdyNbeNodNRCeLXmoST9cX2q-Wx3kbcIsY7TS3wYSCzHqXBPAX0q47kOfaM-VaMmWFGm4MvX5hI5p_SiY27-4Z3tcOL0Y4QDT7I7kidIL_9GP5BZN1S9WFGkTje62MljhZ';
		        $message = "Admin assigned a new lead";
		        //AdminHelper::push_notification($device_id,$message);

				session()->flash('message', 'Lead added successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/leads/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during save Lead');
            Session::flash('alert-type', 'error');
           	return redirect('admin/leads/add');
        }
	}

	//=================================================================

	public function edit($id)
	{
		$data = array();
		$data['result'] = Lead::find($id);
		$data['users'] = User::where('user_type','user')->get();
		$data['customers'] = Customer::where('status','1')->get();

		return view('admin/leads/edit',$data);
	}

	//=================================================================

	public function update(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'customer_id' 	=> 'required',
				'user_id' 		=> 'required',
				'status' 		=> 'required',
			]);
			if ($validator->fails()) { 
	            return redirect('admin/leads/edit'.'/'.$request->id)
	                        ->withErrors($validator)
	                        ->withInput();
			} else {
				$customer = Customer::where('id',$request->customer_id)->first();

		        $data = Lead::find($request->id);
		        $data->title 		= $request->title;
				$data->customer_id 	= $request->customer_id;
		        $data->name 		= $customer->name;
		        $data->vat_number 	= $customer->vat_number;
		        $data->email 		= $customer->email;
		        $data->phone 		= $customer->phone;
		        $data->address 		= $customer->address;
		        $data->message 		= $request->message;
		        $data->user_id 		= $request->user_id;
		        $data->status 		= $request->status;
		        $data->date 		= date('Y-m-d');
		        $data->save();

				session()->flash('message', 'Lead updated successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/leads/index');
			}
		} catch (\Exception $e) {
	        Log::error($e->getMessage());
	        session()->flash('message', 'Some error occured during update Lead');
            Session::flash('alert-type', 'error');
           	return redirect('admin/leads/edit'.'/'.$request->id);
        }
	}

	//=================================================================

	public function delete($id){
		
		try {
			Lead::where('id',$id)->delete();
		
			session()->flash('message', 'Lead deleted successfully');
	        Session::flash('alert-type', 'success');

	        return redirect('admin/leads/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured!');
            Session::flash('alert-type', 'error');

          	return redirect('admin/leads/index');
        }
    }

    //===================================================

    public function view($id)
    {
    	$data['result'] = Lead::find($id);
    	$data['comments'] = LeadComment::where('lead_id',$id)->get();
    	$data['user'] = User::find($data['result']->user_id);

    	$readComment = LeadComment::where('lead_id',$id)->update(['is_read'=>'1']);

    	return view('admin.leads.view',$data);
    }

    //===================================================

    public function comment(Request $request)
    {
    	if (!empty($request->comment)) {
    		$data = new LeadComment;
	        //=========================================================
	        $data->lead_id = $request->lead_id;
	        $data->comment_by = Auth::user()->id;
	        $data->comment = $request->comment;
	        
	        if($data->save()){
	        	session()->flash('message', 'Comment added successfully');
				Session::flash('alert-type', 'success'); 
				return redirect('admin/leads/view/'.$request->lead_id);
	        }else{
	        	session()->flash('message', 'Some error occured!');
				Session::flash('alert-type', 'error'); 
				return redirect('admin/leads/view/'.$request->lead_id);
	        }
    	}else{
    		session()->flash('message', 'Please enter something!');
			Session::flash('alert-type', 'error'); 
			return redirect('admin/leads/view/'.$request->lead_id);
    	}
    }

    //===================================================

}
