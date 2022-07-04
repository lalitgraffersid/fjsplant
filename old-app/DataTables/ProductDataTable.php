<?php

namespace App\DataTables;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Action;
use App\Models\Role;
use App\Models\AdminPermission;
use Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $status_action = Action::where('action_slug','status')->first();
        $checkStatusAction = Role::where('name_slug','products')->whereRaw("find_in_set('".$status_action->id."',action_id)")->first();
        $roles = Role::where('name_slug','products')->first();
        $checkStatusPermission = AdminPermission::where('user_id',Auth::user()->id)->whereRaw("find_in_set('status',action_id)")->first();

        return datatables()
            ->eloquent($query)
            ->editColumn('image', function($row) {
                $product_image = ProductImage::where('product_id',$row->id)->first();
                if (!empty($product_image)) {
                    $url = url('/admin/clip-one/assets/products/thumbnail').'/'.$product_image->image;
                }else{
                    $url = url('assets/admin/dist/img/avatar.png');
                }
                return '<ul class="list-inline"><li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="'.$url.'"></li></ul>';
            })
            // ->editColumn('status', function($row) use ($checkStatusAction,$checkStatusPermission) {
            //     if (!empty($checkStatusAction) && (!empty($checkStatusPermission) || Auth::user()->user_type == 'admin')) {
            //         if($row->status == '1'){
            //             return '<a href="'.route("products.status",$row->id).'" class="btn btn-success btn-sm" onclick="return confirm("Are you sure want to change status?")">Active</a>';
            //         }
            //         if($row->status == '0'){
            //             return '<a href="'.route("products.status",$row->id).'" class="btn btn-danger btn-sm" onclick="return confirm("Are you sure want to change status?")">Inactive</a>';
            //         }
            //     }else{
            //         if($row->status == '1'){
            //             return '<a href="javascript:void(0)" class="btn btn-success btn-sm">Active</a>';
            //         }
            //         if($row->status == '0'){
            //             return '<a href="javascript:void(0)" class="btn btn-danger btn-sm">Inactive</a>';
            //         }
            //     }
            // })
            ->editColumn('action', function($row) use ($roles) {
                $action_ids = explode(',', $roles->action_id);
                foreach ($action_ids as $key => $action_id) {
                    $action = Action::find($action_id);
                    $checkPermission = AdminPermission::where('user_id',Auth::user()->id)->whereRaw("find_in_set('".$action->action_slug."',action_id)")->first();

                    if (!empty($checkPermission) || Auth::user()->user_type == 'admin') {
                        if ($action->action_slug == 'edit' || $action->action_slug == 'delete' || $action->action_slug == 'view' || $action->action_slug == 'password') {
                            $btn .= '<a href="'.route("products.$action->action_slug",$row->id).'" class="btn btn-'.$action->class.' btn-sm" data-placement="top" data-original-title="'.$action->action_title.'"><i class="'.$action->icon.'"></i>'.$action->action_title.'</a>&nbsp;';
                        }
                    }
                }
                return $btn;
            })
            ->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ActionDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
    {
        //return $model->newQuery();
        $data = Product::join('categories','products.category_id','=','categories.id')
                    ->join('dealers','products.dealer_id','=','dealers.id')
                    ->select('products.*','categories.name as category_name','dealers.name as dealer_name');

        return $this->applyScopes($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(0,'ASC');
                    // ->buttons(
                    //     Button::make('export'),
                    //     Button::make('print')
                    // );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id'=> [
                'title' => 'Sr. No.', 
                'orderable' => true, 
                'searchable' => false, 
                'render' => function() {
                        return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
                    }
            ],
            'type',
            'title',
            'category_name' => ['name' => 'categories.name'],
            'dealer_name' => ['name' => 'dealers.name'],
            'image' => ['searchable' => false],
            'status',
            'action' => [
                'searchable' => false,
                'visible' => true, 
                'printable' => false, 
                'exportable' => false
            ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Export_' . date('YmdHis');
    }
}
