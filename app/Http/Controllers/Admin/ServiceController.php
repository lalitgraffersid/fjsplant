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
use App\Models\Service;
use App\Models\AdminPermission;
use App\DataTables\ServiceDataTable;
use App\Helpers\AdminHelper;

class ServiceController extends Controller
{
    //=================================================================

	public function index(ServiceDataTable $dataTable)
	{
		return $dataTable->render('admin/services/index');
	}

    //===================================================

    public function view($id)
    {
    	$data['result'] = Service::find($id);

    	return view('admin.services.view',$data);
    }
}
