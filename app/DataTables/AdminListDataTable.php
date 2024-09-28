<?php

namespace App\DataTables;

use App\Models\AdminList;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AdminListDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($query){
                if($query->id != 1){
                    $deleteBtn = "<a href='".route('admin.admin-list.destory', $query->id)."' class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>";

                    return $deleteBtn;
                }
            })
            ->addColumn('status', function($query){
                if($query->id != 1){
                    if($query->status == 'active'){
                        $button = '<label class="custom-switch mt-2">
                            <input type="checkbox" checked name="custom-switch-checkbox" data-id="'.$query->id.'" class="custom-switch-input change-status" >
                            <span class="custom-switch-indicator"></span>
                        </label>';
                    }else {
                        $button = '<label class="custom-switch mt-2">
                            <input type="checkbox" name="custom-switch-checkbox" data-id="'.$query->id.'" class="custom-switch-input change-status">
                            <span class="custom-switch-indicator"></span>
                        </label>';
                    }
                    return $button;
                }
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    public function query(User $model): QueryBuilder
    {
        return $model->where('role', 'admin')->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('adminlist-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    // ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('email'),
            Column::make('role'),
            Column::make('status'),
            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'AdminList_' . date('YmdHis');
    }
}
