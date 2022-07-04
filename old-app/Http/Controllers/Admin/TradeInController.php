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
use App\Models\TradeIn;
use App\Models\TradeImage;
use App\Models\AdminPermission;
use App\DataTables\TradeInDataTable;
use App\Helpers\AdminHelper;

class TradeInController extends Controller
{
    //=================================================================

	public function index(TradeInDataTable $dataTable)
	{
		return $dataTable->render('admin/trade_in/index');
	}

	//=================================================================

    public function view($id)
    {
    	$data['result'] = Lead::find($id);
    	$data['comments'] = LeadComment::where('lead_id',$id)->get();
    	$data['user'] = User::find($data['result']->user_id);

    	$readComment = LeadComment::where('lead_id',$id)->update(['is_read'=>'1']);

    	return view('admin.trade_in.view',$data);
    }

    //===================================================

}
