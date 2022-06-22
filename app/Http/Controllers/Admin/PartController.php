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
use App\Models\Part;
use App\Models\AdminPermission;
use App\DataTables\PartDataTable;
use App\Helpers\AdminHelper;

class PartController extends Controller
{
    //=================================================================

	public function index(PartDataTable $dataTable)
	{
		return $dataTable->render('admin/parts/index');
	}

    //===================================================

    public function view($id)
    {
    	$data['result'] = Part::find($id);

    	return view('admin.parts.view',$data);
    }
}
