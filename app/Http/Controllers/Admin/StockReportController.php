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
use App\Models\Category;
use App\Models\Dealer;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\AdminPermission;
use App\DataTables\StockReportDataTable;
use App\Helpers\AdminHelper;

class StockReportController extends Controller
{
    //=================================================================

	public function index(StockReportDataTable $dataTable,Request $request)
	{
		$data = [
			'type' => $request->type,
			'status' => $request->status
		];

		return $dataTable->with('data',$data)->render('admin/stock_report/index');
	}

}
