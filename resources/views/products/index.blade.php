@extends('layouts.master-layout')
@section('current-page')
    All Products
@endsection
@section('extra-styles')
    <link rel="stylesheet" href="/css/nprogress.css">
    <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/parsley.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="d-flex justify-content-end">
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addNewProduct" class="btn btn-primary  mb-3">Add Product <i class="bx bxs-plus-circle"></i> </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">All Products</h4>
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all me-2"></i>
                                {!! session()->get('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-close me-2"></i>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#home1" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">Products</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#messages1" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">Categories</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="home1" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 col-lx-12">
                                        <p><strong style="color: #ff0000 !important;" class="text-danger">SKU</strong>(Stock Keeping Unit)</p>
                                        <div class="table-responsive mt-3">
                                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                                <thead>
                                                <tr>
                                                    <th class="">#</th>
                                                    <th class="wd-15p">Name</th>
                                                    <th class="wd-15p">Category</th>
                                                    <th class="wd-15p">Cost</th>
                                                    <th class="wd-15p">SKU <span title="Stock Keeping Unit">?</span> </th>
                                                    <th class="wd-15p">Quantity</th>
                                                    <th class="wd-15p">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php $index = 1; @endphp
                                                @foreach($products as $product)
                                                    <tr>
                                                        <td>{{$index++}}</td>
                                                        <td>
                                                            <img class=" header-profile-user" src="{{url('storage/'.$product->photo)}}" alt="{{$product->product_name ?? '' }}">
                                                            {{$product->product_name ?? '' }}

                                                        </td>
                                                        <td>{{$product->getCategory->name ?? '' }}</td>
                                                        <td style="text-align: right">{{env('APP_CURRENCY')}}{{ number_format($product->cost ?? 0,2) }}</td>
                                                        <td >{{ $product->sku ?? null }}</td>
                                                        <td style="text-align: center;">{{ number_format($product->stock ?? 0) }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <i class="bx bx-dots-vertical dropdown-toggle text-warning" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" href="javascript:void(0);" data-bs-target="#editProduct_{{$product->id}}" data-bs-toggle="modal"> <i class="bx bxs-pencil text-warning"></i> Edit</a>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="messages1" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-title">Add Product Category</div>
                                                <div class="card-title-desc">Register different categories to group your products.</div>
                                                <form action="{{route('add-product-category')}}" method="post" autocomplete="off">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="">Category Name</label>
                                                        <input type="text" name="name" placeholder="Ex: Cement" class="form-control">
                                                        @error('name') <i class="text-danger">{{$message}}</i>@enderror
                                                    </div>
                                                    <div class="form-group d-flex justify-content-center mt-3">
                                                        <button type="submit" class="btn btn-primary">Submit <i class="bx bxs-right-arrow"></i> </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Product Categories</h4>
                                                <p class="card-title-desc">A list of your registered product categories</p>

                                                <div class="table-responsive">
                                                    <table class="table mb-0">

                                                        <thead class="table-light">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Category</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php $serial = 1; @endphp
                                                        @foreach($categories as $cat)
                                                            <tr>
                                                                <th scope="row">{{$serial++}}</th>
                                                                <td>{{$cat->name ?? '' }}</td>
                                                                <td>
                                                                    <a href="javascript:void(0);" data-bs-target="#editGroup_{{$cat->id}}" data-bs-toggle="modal"> <i class=" bx bx-pencil text-warning"></i> </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="addNewProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" >
                    <h6 class="modal-title text-info text-uppercase" id="myModalLabel2">Add Product</h6>
                    <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" action="{{route('add-product')}}" id="addProductForm" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mt-1">
                            <label for="">Product Name <span class="text-danger">*</span></label>
                            <input type="text" data-parsley-required-message="Enter product name" required name="productName" placeholder="Product Name" value="{{old('productName')}}" class="form-control">
                            @error('productName') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-2">
                            <label for="">Category</label>
                            <select name="productCategory" data-parsley-required-message="Select product category" required class="form-control">
                                @foreach($categories as $c)
                                    <option value="{{$c->id}}">{{$c->name ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="">Cost-of-Goods <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" data-parsley-required-message="What's the cost price?" required name="cost" placeholder="Cost-of-Goods" class="form-control">
                            @error('cost') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-2">
                            <label for="">Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" data-parsley-required-message="How much do you intend to sell this product?" required name="price" placeholder="Price" class="form-control">
                            @error('price') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-2 mb-3">
                            <label for="">Product Photo <span class="text-danger">*</span></label> <br>
                            <input type="file" data-parsley-required-message="Choose product photo to upload" required name="photo" class="form-control-file">
                            @error('photo') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group mt-2 mb-3">
                            <label for="">Stock Keeping Unit (SKU)</label>
                            <input type="text" name="sku" placeholder="SKU" class="form-control">
                            @error('sku') <i class="text-danger">{{$message}}</i>@enderror
                        </div>

                        <div class="form-group mt-2 mb-3">
                            <label for="">Description</label>
                            <textarea name="description" style="resize: none;" placeholder="Type description here..." class="form-control"></textarea>
                            @error('description') <i class="text-danger">{{$message}}</i>@enderror
                        </div>

                        <div class="form-group mt-2">
                            <label for="">Track Inventory? <span class="text-danger">*</span></label>
                            <div>
                                <input type="checkbox" id="trackInventoryToggler" name="trackInventory" switch="primary" >
                                <label for="trackInventoryToggler" data-on-label="Yes" data-off-label="No"></label>
                            </div>
                            @error('trackInventory') <i class="text-danger">{{$message}}</i>@enderror
                        </div>
                        <div class="form-group">
                            <div class="row" id="trackingWrapper">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Stock Alert</label>
                                        <input type="number" name="lowInventoryNotice" placeholder="Low Inventory Notice" value="5" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Amount in Stock</label>
                                        <input type="number" name="stock" placeholder="Amount in Stock" value="0" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center mt-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary  waves-effect waves-light">Save Product <i class="bx bxs-right-arrow"></i> </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @foreach($categories as $cat)
        <div class="modal fade" id="editGroup_{{$cat->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" >
                        <h6  class="modal-title text-uppercase text-info" id="myModalLabel2">Edit Category</h6>
                        <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{route('edit-product-category')}}" method="post" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <label for="">Category Name</label>
                                <input type="text" name="name" value="{{$cat->name ?? '' }}" placeholder="Ex: Beverages" class="form-control">
                                @error('name') <i class="text-danger">{{$message}}</i>@enderror
                                <input type="hidden" name="categoryId" value="{{$cat->id}}">
                            </div>
                            <div class="form-group d-flex justify-content-center mt-3">
                                <button type="submit" class="btn btn-primary">Save changes <i class="bx bxs-right-arrow"></i> </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($products as $product)
        <div class="modal right fade" id="editProduct_{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" >
                        <h6 class="modal-title text-info text-uppercase" id="myModalLabel2">Edit Product</h6>
                        <button type="button" style="margin: 0px; padding: 0px;" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form autocomplete="off" action="{{route('edit-product')}}" data-parsley-validate="" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mt-1">
                                <label for="">Product Name <span class="text-danger">*</span></label>
                                <input type="text" data-parsley-required-message="Enter product name"  required name="productName" placeholder="Product Name" value="{{old('productName', $product->product_name)}}" class="form-control">
                                @error('productName') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-1">
                                <label for="">Category</label>
                                <select name="productCategory" data-parsley-required-message="Select product category" required class="form-control">
                                    @foreach($categories as $c)
                                        <option value="{{$c->id}}" {{$c->id == $product->category_id ? 'selected' : '' }}>{{$c->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-1">
                                <label for="">Cost-of-Goods <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" value="{{$product->cost ?? 0 }}" data-parsley-required-message="What's the cost price?" required name="cost" placeholder="Cost-of-Goods" class="form-control">
                                @error('cost') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-1">
                                <label for="">Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" value="{{$product->price ?? 0 }}" data-parsley-required-message="How much do you intend to sell this product?" required name="price" placeholder="Price" class="form-control">
                                @error('price') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-1 mb-3">
                                <br>
                                <label for="">Change Product Photo</label> <br>
                                <input type="file"  name="photo" class="form-control-file">
                                @error('photo') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group mt-1 mb-3">
                                <label for="">Stock Keeping Unit (SKU)</label>
                                <input type="text" name="sku" value="{{$product->sku ?? 0 }}" placeholder="SKU" class="form-control">
                                @error('sku') <i class="text-danger">{{$message}}</i>@enderror
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Stock Alert</label>
                                            <input type="number" value="{{$product->low_inventory_notice ?? 0 }}" name="lowInventoryNotice" placeholder="Low Inventory Notice"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Amount in Stock</label>
                                            <input type="number" value="{{$product->stock ?? 0 }}" name="stock" placeholder="Amount in Stock"  class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="">Product Image</label>
                                <input type="hidden" name="productId" value="{{$product->id}}">
                                <br>
                                <img style="width: 400px; height: 350px;" src="{{url('storage/'.$product->photo)}}" alt="" class="img-fluid">
                            </div>
                            <div class="form-group d-flex justify-content-center mt-3">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-primary  waves-effect waves-light">Save changes <i class="bx bxs-right-arrow"></i> </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('extra-scripts')
    <script src="/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="/assets/js/pages/datatables.init.js"></script>
    <script src="/js/parsley.js"></script>
    <script>
        $(document).ready(function(){
            $('#trackingWrapper').hide();
            $('.trackingWrapper').hide();
            $('#trackInventoryToggler').on('change',function(){
                if ($("#trackInventoryToggler").is(':checked')){
                    $('#trackingWrapper').show();
                } else {
                    $('#trackingWrapper').hide();
                }
            });
            $('.trackInventoryToggler').on('change',function(){
                if ($(".trackInventoryToggler").is(':checked')){
                    $('.trackingWrapper').show();
                } else {
                    $('.trackingWrapper').hide();
                }
            });
            $('#addProductForm').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })
                .on('form:submit', function() {
                    return true;
                });
        });
    </script>
@endsection
