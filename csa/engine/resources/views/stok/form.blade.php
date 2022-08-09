@extends('layouts.master-sidebar')
@section('content')
<!-- Page Content -->
<div class="content">
    <!-- Form -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{$is_edit?"Ubah":"Tambah"}} Stok {{$is_edit? "| ".$stok->name : ""}}</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-8 space-y-5">
                    <!-- Form Horizontal - Default Style -->
                    <form autocomplete="off" class="space-y-4" action="{{$is_edit?route('stok.update',$stok->id):route('stok.store')}}"
                        method="POST" novalidate>
                        @csrf
                        @if($is_edit)
                        @method('put')
                        @endif

                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Nama Barang:</label>

                            <div class="col-sm-8 ms-auto">
                                <input autocomplete="off" type="text" id="var1" class="form-control" name="name"
                                    value="{{$stok->name ?? ""}}">
                            </div>
                        </div>
                        <div class=" row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Kategori</label>
                            <div class="col-sm-8 ms-auto">
                                <select class="select2 form-control" name="category_id">
                                    <option value="" disabled>Pilih Kategori</option>
                                    @foreach($kategoris as $kategori)
                                    <option value="{{$kategori->id}}" @if($is_edit && $stok->category_id ==
                                        $kategori->id)selected @endif>{{$kategori->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=" row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Merk</label>
                            <div class="col-sm-8 ms-auto">
                                <select class="select2 form-control" name="brand_id">
                                    <option value="" disabled>Pilih Merk</option>
                                    @foreach($merks as $merk)
                                    <option value="{{$merk->id}}" @if($is_edit && $stok->brand_id == $merk->id)selected
                                        @endif>{{$merk->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Harga Beli</label>

                            <div class="col-sm-8 ms-auto">
                                <input autocomplete="off" type="number" name="purchase_price"
                                    value="{{$stok->purchase_price ?? 0}}" class="form-control" required="">
                            </div>
                        </div>
                        <hr class="divider">
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Harga Jual</label>

                            <div class="col-sm-8 ms-auto">
                                <input autocomplete="off" type="number" name="sell_price"
                                    value="{{$stok->sell_price ?? 0}}" class="form-control" required="">
                            </div>
                        </div>
                        <small>isikan 0 jika tidak pakai harga bawah/atas</small>
                        <div class="row">
                            <label class="offset-sm-2 col-sm-4 col-form-label" for="example-hf-email">Harga Jual
                                Bawah</label>
                            <div class="col-sm-6 ms-auto">

                                <input autocomplete="off" type="number" name="bottom_sell_price" min="0"
                                    value="{{$stok->bottom_sell_price ?? $stok->sell_price ?? 0}}" class="form-control"
                                    required="">
                            </div>
                        </div>
                        <div class="row">
                            <label class="offset-sm-2 col-sm-4 col-form-label" for="example-hf-email">Harga Jual
                                Atas</label>
                            <div class="col-sm-6 ms-auto">
                                <input autocomplete="off" type="number" name="top_sell_price" min="0"
                                    value="{{$stok->top_sell_price?? $stok->sell_price ?? 0}}" class="form-control"
                                    required="">
                            </div>
                        </div>
                        <hr class="divider">

                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Satuan:</label>

                            <div class="col-sm-8 ms-auto">
                                <select class="select2 form-control" name="satuan_id">
                                    <option value="" disabled>Pilih Merk</option>
                                    @foreach($satuan as $satu)
                                    <option value="{{$satu->id}}" @if($is_edit && $stok->satuan_id == $satu->id)selected
                                        @endif>{{$satu->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Jumlah per Satuan</label>

                            <div class="col-sm-8 ms-auto">
                                <input autocomplete="off" type="number" id="total_per_satuan" name="total_per_satuan" class="form-control"
                                    required="" value="{{$stok->total_per_satuan ?? ""}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">QTY</label>

                            <div class="col-sm-8 ms-auto">
                                <input autocomplete="off" type="number" id="var6" name="qty" class="form-control"
                                    required="" value="{{$stok->qty ?? ""}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Batas Minimum Stock</label>
                            <div class="col-sm-8 ms-auto">
                                <input autocomplete="off" type="number" id="var6" name="threshold_bottom"
                                    class="form-control" required="" value="{{$stok->threshold_bottom ?? ""}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Gudang</label>
                            <div class="col-sm-8 ms-auto">
                                <select class="select2 form-control" name="warehouse_id">
                                    <option value="" disabled>Pilih Merk</option>
                                    @foreach($gudangs as $gudang)
                                    <option value="{{$gudang->id}}" @if($is_edit && $stok->warehouse_id == $gudang->id)selected
                                        @endif>{{$gudang->warehouse_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 ms-auto">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                    <!-- END Form Horizontal - Default Style -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Form -->
</div>
<!-- END Page Content -->
@endsection
