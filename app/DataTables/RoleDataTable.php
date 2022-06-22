<?php

namespace App\DataTables;

use App\Models\Section;
use App\Models\Role;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function($row){
                        $btn = '<a href="'.route("roles.edit",$row->id).'" class="btn btn-info btn-sm" data-placement="top" data-original-title="Remove"><i class="fas fa-pencil-alt"></i>Edit</a>
                           <a href="'.route("roles.delete",$row->id).'" onclick="return confirm("Are you sure you want to delete  role?")" class="btn btn-danger btn-sm" data-placement="top" data-original-title="Remove"><i class="fas fa-trash"></i>Delete</a>';
       
                            return $btn;
                    });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ActionDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(RoleDataTable $model)
    {
        //return $model->newQuery();
        $data = Role::join('sections','roles.section_id','=','sections.id')
                    ->orderBy('sections.section_order','ASC')
                    ->orderBy('roles.order','ASC')
                    ->select('roles.id','roles.section_id','roles.action_id','roles.name','roles.url','roles.order','sections.section_title','sections.section_order');

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
                    ->minifiedAjax();
                    //->dom('Bfrtip')
                    //->orderBy(0,'ASC');
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
            'section_title' => ['name' => 'sections.section_title'],
            'name',
            'action' => ['
                visible' => true, 
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
