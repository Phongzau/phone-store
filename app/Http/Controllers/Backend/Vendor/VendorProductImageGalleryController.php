<?php

namespace App\Http\Controllers\Backend\Vendor;

use App\DataTables\VendorProductImageGalleryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImageGallery;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorProductImageGalleryController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, VendorProductImageGalleryDataTable $dataTable)
    {
        $product = Product::query()->findOrFail($request->product_id);
        /** Check if it's the owner of the product */
        if ($product->vendor_id != Auth::user()->vendor->id) {
            return redirect()->route('vendor.error.404');
        }
        return $dataTable->render('vendor.product.image-gallery.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image.*' => ['required', 'image', 'max:2048'],
        ]);

        $imagePaths = $this->uploadMultipleImage($request, 'image', 'uploads');

        foreach ($imagePaths as $path) {
            $productImageGallery = new ProductImageGallery();
            $productImageGallery->image = $path;
            $productImageGallery->product_id = $request->product_id;
            $productImageGallery->save();
        }

        toastr('Uploaded Successfully!', 'success');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $productImageGallery = ProductImageGallery::query()->findOrFail($id);

        /** Check if it's the owner of the product */
        if ($productImageGallery->product->vendor_id != Auth::user()->vendor->id) {
            return redirect()->route('vendor.error.404');
        }
        $this->deleteImage($productImageGallery->image);
        $productImageGallery->delete();

        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }
}
