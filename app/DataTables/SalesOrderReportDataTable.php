<?php

namespace App\DataTables;

use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\Quote;
use App\Models\SalesOrder;
use App\Models\ProductImage;
use App\User;
use App\Models\Action;
use App\Models\Role;
use App\Models\AdminPermission;
use Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SalesOrderReportDataTable extends DataTable
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
        $checkStatusAction = Role::where('name_slug','sales_order_report')->whereRaw("find_in_set('".$status_action->id."',action_id)")->first();
        $roles = Role::where('name_slug','sales_order_report')->first();
        $checkStatusPermission = AdminPermission::where('user_id',Auth::user()->id)->whereRaw("find_in_set('status',action_id)")->first();

        return datatables()
            ->eloquent($query)
            ->editColumn('image', function($row) {
                if (!empty($row->image)) {
                    $product_image = ProductImage::where('product_id',$row->image)->first();
                    $url = url('/admin/clip-one/assets/products/thumbnail').'/'.$product_image->image;
                    return '<ul class="list-inline"><li class="list-inline-item"><img alt="Avatar" class="table-avatar" src="'.$url.'"></li></ul>';
                }else{
                    return '<ul class="list-inline"><li class="list-inline-item"><img alt="Avatar" class="table-avatar" src=""></li></ul>';
                }
            })
            ->editColumn('date', function($row) {
                $date_time = date('d F Y',strtotime($row->created_at));
                return $date_time;
            })
            ->editColumn('PDI', function($row) {
                if ($row->PDI == '1') {
                    return '<a href="javascript:void(0)" class="btn btn-success btn-sm" onclick="return confirm("Are you sure want to change status?")">Approved</a>';
                }else{
                    return '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="return confirm("Are you sure want to change status?")">Defected</a>';
                }
            })
            ->editColumn('delivered', function($row) {
                if ($row->delivered == '1') {
                    return '<a href="javascript:void(0)" class="btn btn-success btn-sm">Yes</a>';
                }else{
                    return '<a href="javascript:void(0)" class="btn btn-danger btn-sm">No</a>';
                }
            })
            ->editColumn('paid', function($row) {
                if ($row->paid == '1') {
                    return '<a href="javascript:void(0)" class="btn btn-success btn-sm" onclick="return confirm("Are you sure want to change status?")">Yes</a>';
                }else{
                    return '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="return confirm("Are you sure want to change status?")">No</a>';
                }
            })
            ->editColumn('action', function($row) use ($roles) {
                $action_ids = explode(',', $roles->action_id);
                $btn = '';
                foreach ($action_ids as $key => $action_id) {
                    $action = Action::find($action_id);
                    $checkPermission = AdminPermission::where('user_id',Auth::user()->id)->whereRaw("find_in_set('".$action->action_slug."',action_id)")->first();

                    if (!empty($checkPermission) || Auth::user()->user_type == 'admin') {
                        if ($action->action_slug == 'edit' || $action->action_slug == 'delete' || $action->action_slug == 'view' || $action->action_slug == 'password') {
                            $btn .= '<a href="'.route("sales_order.$action->action_slug",$row->id).'" class="btn btn-'.$action->class.' btn-sm" data-placement="top" data-original-title="'.$action->action_title.'" onclick="return confirm("Are you sure?")"><i class="'.$action->icon.'"></i>'.$action->action_title.'</a>&nbsp;';
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
    public function query(Quote $model)
    {
        $d = $this->data;
        if (!empty($d['from']) || !empty($d['to']) || !empty($d['customer']) || $d['PDI_status'] == '1'|| $d['PDI_status'] == '0' || $d['payment_confirm'] == '1' || $d['payment_confirm'] == '0' || !empty($d['user_id'])) {
            $queryData = SalesOrder::join('quotes','sales_orders.quote_id','=','quotes.id')
                            ->join('leads','quotes.lead_id','=','leads.id')
                            ->join('users','leads.user_id','=','users.id')
                            ->join('products','sales_orders.product_id','=','products.id')
                            ->join('product_extra_info', function($join)
                            {
                                $join->on('product_extra_info.quote_id', '=', 'quotes.id');
                                $join->on('product_extra_info.product_id','=', 'sales_orders.product_id');
                            });

            if (!empty($d['from'])) {
                $queryData = $queryData->where('sales_orders.date','>=',$d['from']);
            }
            if (!empty($d['to'])) {
                $queryData = $queryData->where('sales_orders.date','<=',$d['to']);
            }
            if (!empty($d['customer'])) {
                $queryData = $queryData->where('sales_orders.customer_id',$d['customer']);
            }
            if ($d['PDI_status'] == '1' || $d['PDI_status'] == '0') {
                $queryData = $queryData->where('sales_orders.PDI_status',$d['PDI_status']);
            }
            if ($d['payment_confirm'] == '1' || $d['payment_confirm'] == '0') {
                $queryData = $queryData->where('sales_orders.payment_confirm',$d['payment_confirm']);
            }
            if (!empty($d['user_id'])) {
                $queryData = $queryData->where('leads.user_id',$d['user_id']);
            }
            $queryData = $queryData->select('sales_orders.*','sales_orders.id as order_#','sales_orders.payment_confirm as paid','sales_orders.PDI_status as PDI','leads.name as customer_name','leads.email','leads.phone','leads.user_id','quotes.lead_id','users.name as rep','product_extra_info.depot','product_extra_info.hitch','product_extra_info.buckets','product_extra_info.extra','sales_orders.product_id as image','products.title as machine')
                ->orderBy('sales_orders.id','DESC');
        }else{
            $queryData = SalesOrder::join('quotes','sales_orders.quote_id','=','quotes.id')
                            ->join('leads','quotes.lead_id','=','leads.id')
                            ->join('users','leads.user_id','=','users.id')
                            ->join('products','sales_orders.product_id','=','products.id')
                            ->join('product_extra_info', function($join)
                            {
                                $join->on('product_extra_info.quote_id', '=', 'quotes.id');
                                $join->on('product_extra_info.product_id','=', 'sales_orders.product_id');
                            })
                            ->select('sales_orders.*','sales_orders.id as order_#','sales_orders.payment_confirm as paid','sales_orders.PDI_status as PDI','leads.name as customer_name','leads.email','leads.phone','leads.user_id','quotes.lead_id','users.name as rep','product_extra_info.depot','product_extra_info.hitch','product_extra_info.buckets','product_extra_info.extra','sales_orders.product_id as image','products.title as machine')
                            ->orderBy('sales_orders.id','DESC');
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
            // 'id'=> [
            //     'title' => 'Sr. No.', 
            //     'orderable' => true, 
            //     'searchable' => false, 
            //     'render' => function() {
            //             return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
            //         }
            // ],
            'image' => ['searchable' => false,'name'=>'sales_orders.product_id'],
            'machine' => ['searchable' => false,'name'=>'products.title'],
            'date' => ['searchable' => false],
            'rep' => ['name' => 'users.name'],
            'customer_name' => ['name' => 'leads.name'],
            'order_#',
            'depot',
            'hitch',
            'buckets',
            'extra',
            'paid',
            'PDI',
            'delivered',
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
