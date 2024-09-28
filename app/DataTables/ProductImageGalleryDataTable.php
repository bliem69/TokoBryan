<?php

namespace App\DataTables;

use App\Models\ProductImageGallery;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductImageGalleryDataTable extends DataTable
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
                $deleteBtn = "<a href='".route('admin.products-image-gallery.destroy', $query->id)."' class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>";

                return $deleteBtn;
            })
            ->addColumn('image', function($query){
                return "<img width='200px' src='".asset($query->image)."' ></img>";
            })
            ->rawColumns(['image', 'action'])
            ->setRowId('id');
    }


    public function query(ProductImageGallery $model): QueryBuilder
    {
        return $model->where('product_id', request()->product)->newQuery();
    }

 
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('productimagegallery-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
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

            Column::make('id')->width(100),
            Column::make('image'),
            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(400)
            ->addClass('text-center'),
        ];
    }

   
    protected function filename(): string
    {
        return 'ProductImageGallery_' . date('YmdHis');
    }
}
