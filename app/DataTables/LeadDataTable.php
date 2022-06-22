<?php

namespace App\DataTables;

use App\Models\Lead;
use App\Models\LeadComment;
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

class LeadDataTable extends DataTable
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
        $checkStatusAction = Role::where('name_slug','leads')->whereRaw("find_in_set('".$status_action->id."',action_id)")->first();
        $roles = Role::where('name_slug','leads')->first();
        $checkStatusPermission = AdminPermission::where('user_id',Auth::user()->id)->whereRaw("find_in_set('status',action_id)")->first();

        return datatables()
            ->eloquent($query)
            ->editColumn('status', function($row) {
                if($row->status == 'New'){
                    return '<a href="javascript:void(0)" class="btn btn-secondary btn-sm" onclick="return confirm("Are you sure want to change status?")">'.$row->status.'</a>';
                }elseif($row->status == 'In Progress'){
                    return '<a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="return confirm("Are you sure want to change status?")">'.$row->status.'</a>';
                }elseif ($row->status == 'On Hold') {
                    return '<a href="javascript:void(0)" class="btn btn-warning btn-sm" onclick="return confirm("Are you sure want to change status?")">'.$row->status.'</a>';
                }elseif ($row->status == 'Lost') {
                    return '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="return confirm("Are you sure want to change status?")">'.$row->status.'</a>';
                }else{
                    return '<a href="javascript:void(0)" class="btn btn-success btn-sm" onclick="return confirm("Are you sure want to change status?")">'.$row->status.'</a>';
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
                            $btn .= '<a href="'.route("leads.$action->action_slug",$row->id).'" class="btn btn-'.$action->class.' btn-sm" data-placement="top" data-original-title="'.$action->action_title.'" onclick="return confirm("Are you sure?")"><i class="'.$action->icon.'"></i>'.$action->action_title.'</a>&nbsp;';
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
    public function query(Lead $model)
    {
        //return $model->newQuery();
        $data = Lead::join('users','leads.user_id','=','users.id')
                    ->orderBy('id','desc')
                    ->select('leads.*','users.name as rep');

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
                'title' => '#', 
                'orderable' => true, 
                'searchable' => false, 
                'render' => function() {
                        return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
                    }
            ],
            'title',
            'date',
            'name',
            'vat_number',
            'email',
            'phone',
            'rep' => ['name' => 'users.name'],
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