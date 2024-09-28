<?php

namespace App\DataTables;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BlogDataTable extends DataTable
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
                $editBtn = "<a href='".route('admin.blog.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='".route('admin.blog.destroy', $query->id)."' class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>";

                return $editBtn.$deleteBtn;
            })
            ->addColumn('image', function($query){
            return "<img width='100px' src='".asset($query->image)."' ></img>";
            })
            ->addColumn('category', function($query){
                return $query->category->name;
            })
            ->addColumn('publish_date', function($query){
                return date('d-m-y', strtotime($query->created_at));
            })
            ->addColumn('status', function($query){
                if($query->status == 1){
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
            })
            ->rawColumns(['action', 'image', 'status'])
            ->setRowId('id');
    }


    public function query(Blog $model): QueryBuilder
    {
        return $model::with('category')->newQuery();
    }


    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('blog-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(0)
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
            Column::make('image'),
            Column::make('title'),
            Column::make('category'),
            Column::make('status'),
            Column::make('publish_date'),
            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(200)
            ->addClass('text-center'),
        ];
    }

 
    protected function filename(): string
    {
        return 'Blog_' . date('YmdHis');
    }
}
