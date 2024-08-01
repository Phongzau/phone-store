@extends('vendor.layouts.master')

@section('content')
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('vendor.layouts.sidebar')

            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <a href="{{ route('vendor.products.index') }}" class="btn btn-warning mb-3"><i
                            class="far fa-long-arrow-left"></i> Back</a>
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="fas fa-images"></i> Product Image Gallery</h3>
                        <div class="wsus__dashboard_profile">
                            <div class="wsus__dash_pro_area">
                                <h4 class="mb-4">Product: {{ $product->name }}</h4>
                                <form method="POST" action="{{ route('vendor.product-image-gallery.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group wsus__input">
                                        <label for="">Image <code>(Multiple image supported)</code></label>
                                        <input type="file" name="image[]" class="form-control" multiple>
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    </div>
                                    <button class="btn btn-primary" type="submit">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            // two

            <div class="row mt-5">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="fas fa-images"></i> Product Images</h3>
                        <div class="wsus__dashboard_profile">
                            <div class="wsus__dash_pro_area">
                                {{ $dataTable->table() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush