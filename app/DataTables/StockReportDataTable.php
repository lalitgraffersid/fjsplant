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

class StockReportDataTable extends DataTable
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
        $checkStatusAction = Role::where('name_slug','stock_report')->whereRaw("find_in_set('".$status_action->id."',action_id)")->first();
        $roles = Role::where('name_slug','stock_report')->first();
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
        $d = $this->data;

        if (!empty($d['type']) || !empty($d['status'])) {
            $queryData = Product::join('categories','products.category_id','=','categories.id')
                            ->join('dealers','products.dealer_id','=','dealers.id');

            if (!empty($d['type'])) {
                $queryData = $queryData->where('products.type',$d['type']);
            }
            if (!empty($d['status'])) {
                $queryData = $queryData->where('products.status',$d['status']);
            }
                        
            $queryData = $queryData->select('products.*','categories.name as category_name','dealers.name as dealer_name');

        }else{
            $queryData = Product::join('categories','products.category_id','=','categories.id')
                    ->join('dealers','products.dealer_id','=','dealers.id')
                    ->select('products.*','categories.name as category_name','dealers.name as dealer_name');
        }

        return $this->applyScopes($queryData);
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
                    ->dom('Bfrtip')
                    ->orderBy(0,'ASC')
                    ->buttons(
                        Button::make(['csv','excel']),
                        Button::make('print')
                    );
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
            'status'
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
