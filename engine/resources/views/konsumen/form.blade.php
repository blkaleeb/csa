@extends('layouts.master-sidebar')
@section('content')
<!-- Page Content -->
<div class="content">
    <!-- Form -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{$is_edit?"Ubah":"Tambah"}} konsumen</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-8 space-y-5">
                    <!-- Form Horizontal - Default Style -->
                    <form autocomplete="off" class="space-y-4"
                        action="{{$is_edit?route('konsumen.update',$konsumen->id):route('konsumen.store')}}"
                        method="POST" novalidate>
                        @csrf
                        @if($is_edit)
                        @method('put')
                        @endif
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Nama</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="example-hf-email" name="name"
                                    placeholder="konsumen.." value="{{$konsumen->name ?? ""}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Alamat</label>
                            <div class="col-sm-8">
                                <textarea type="text" class="form-control" id="example-hf-email" name="customer_address"
                                    placeholder="Alamat">{{$konsumen->customer_address ?? ""}}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Telepon/Hp</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="example-hf-email" name="customer_phone_no"
                                    placeholder="08123345678" value="{{$konsumen->customer_phone_no ?? ""}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Limit Kredit</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="example-hf-email" name="creditlimit"
                                    placeholder="10 juta" value="{{$konsumen->creditlimit ?? ""}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Sales</label>
                            <div class="col-sm-8">
                                <select name="sales_id" class="select2 form-control">
                                    <option disabled selected>Pilih Data</option>
                                    @foreach($sales as $sale)
                                    <option value="{{$sale->id}}" @if($is_edit && $konsumen->sales_id == $sale->id) selected @endif>{{$sale->displayName}}</option>
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
