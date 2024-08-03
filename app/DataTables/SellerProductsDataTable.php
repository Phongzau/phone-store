<?php

namespace App\DataTables;

use App\Models\Product;
use App\Models\SellerProduct;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;

class SellerProductsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $editBtn = "<a class='btn btn-primary' href='" . route('admin.products.edit', $query->id) . "'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a class='btn btn-danger delete-item ml-2' href='" . route('admin.products.destroy', $query->id) . "'><i class='far fa-trash-alt'></i></a>";
                $moreBtn = "<div class='dropdown dropleft d-inline ml-1'>
                      <button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenuButton2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                        <i class='fas fa-cog'></i>
                      </button>
                      <div class='dropdown-menu' x-placement='bottom-start' style='position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;'>
                        <a class='dropdown-item has-icon' href='" . route('admin.product-image-gallery.index', ['product_id' => $query->id]) . "'><i class='far fa-heart'></i> Image Gallery</a>
                        <a class='dropdown-item has-icon' href='" . route('admin.product-variant.index', ['product_id' => $query->id]) . "'><i class='far fa-file'></i> Variants</a>
                      </div>
                    </div>";
                return $editBtn . $deleteBtn . $moreBtn;
            })
            ->addColumn('image', function ($query) {
                return $img = "<img width='100px' src='" . asset($query->thumb_image) . "'>";
            })
            ->addColumn('status', function ($query) {
                if ($query->status == 1) {
                    $button = "<label class='custom-switch mt-2'>
                        <input type='checkbox' data-id='" . $query->id . "' checked name='custom-switch-checkbox' class='custom-switch-input change-status'>
                        <span class='custom-switch-indicator'></span>
                      </label>";
                } else {
                    $button = "<label class='custom-switch mt-2'>
                        <input type='checkbox' data-id='" . $query->id . "' name='custom-switch-checkbox' class='custom-switch-input change-status'>
                        <span class='custom-switch-indicator'></span>
                      </label>";
                }
                return $button;
            })
            ->addColumn('type', function ($query) {
                switch ($query->product_type) {
                    case 'new_arrival':
                        return "<i class='badge badge-success'>New Arrival</i>";
                        break;
                    case 'featured_product':
                        return "<i class='badge badge-warning'>Featured Product</i>";
                        break;
                    case 'top_product':
                        return "<i class='badge badge-info'>Top Product</i>";
                        break;
                    case 'best_product':
                        return "<i class='badge badge-danger'>Best Product</i>";
                        break;
                    default:
                        return "<i class='badge badge-dark'>None</i>";
                        break;
                }
            })
            ->addColumn('vendor', function ($query) {
                return $query->vendor->shop_name;
            })
            ->addColumn('approve', function ($query) {
                return "<select class='form-control is_approve' data-id='$query->id'>
                <option value='0'>Pending</option>
                <option selected value='1'>Approved</option>
                </select>";
            })
            ->rawColumns(['image', 'action', 'status', 'type', 'approve'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->where('vendor_id', '!=', Auth::user()->vendor->id)->where('is_approved', 1)->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('sellerproducts-table')
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

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->width(100),
            Column::make('vendor')->width(200),
            Column::make('image')->width(200),
            Column::make('name')->width(450),
            Column::make('price')->width(130),
            Column::make('type')->width(150),
            Column::make('status')->width(100),
            Column::make('approve')->width(150),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SellerProducts_' . date('YmdHis');
    }
}
