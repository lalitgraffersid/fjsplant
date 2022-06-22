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
use App\Models\AdminPermission;
use App\DataTables\SalesCallDataTable;
use App\Helpers\AdminHelper;

class SalesCallController extends Controller
{
    //=================================================================

	public function index(SalesCallDataTable $dataTable,Request $request)
	{
		$data = [
            'from' => $request->from,
            'to' => $request->to,
            'customer' => $request->customer,
			'status' => $request->status,
            'user_id' => $request->user_id,
		];

		return $dataTable->with('data',$data)->render('admin/sales_calls_report/index');
	}

    //===================================================

    public function view($id)
    {
    	$data['result'] = Lead::find($id);
    	$data['comments'] = LeadComment::where('lead_id',$id)->get();
    	$data['user'] = User::find($data['result']->user_id);

    	return view('admin.sales_calls_report.view',$data);
    }

    //===================================================

}
