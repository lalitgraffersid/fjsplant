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
use App\Models\Contact;
use App\Models\AdminPermission;
use App\DataTables\ContactDataTable;
use App\Helpers\AdminHelper;

class ContactController extends Controller
{
    //=================================================================

	public function index(ContactDataTable $dataTable)
	{
		return $dataTable->render('admin/contacts/index');
	}

    //===================================================

    public function view($id)
    {
    	$data['result'] = Contact::find($id);

    	return view('admin.contacts.view',$data);
    }

    //===================================================

    public function delete($id){
		
		try {
			$data = Contact::find($id)->delete();
		
			session()->flash('message', 'Contact deleted successfully');
	        Session::flash('alert-type', 'success');

	        return redirect('admin/contacts/index');
	    } catch (\Exception $e) {
            Log::error($e->getMessage());
		    session()->flash('message', 'Some error occured');
            Session::flash('alert-type', 'error');

          	return redirect('admin/contacts/index');
        }
    }

}
