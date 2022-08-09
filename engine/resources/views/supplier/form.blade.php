@extends('layouts.master-sidebar')
@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Form -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ $is_edit ? 'Edit Supplier' : 'Tambah Supplier' }}</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">

                    <div class="col-lg-8 space-y-5">
                        <!-- Form Horizontal - Default Style -->
                        <form autocomplete="off" class="space-y-4"
                            action="{{ $is_edit ? route('supplier.update', $supplier->id) : route('supplier.store') }}"
                            method="POST" novalidate>
                            @csrf
                            @if ($is_edit)
                                @method('put')
                            @endif
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="suppliercode">Kode Supplier</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="suppliercode" name="suppliercode"
                                        placeholder="Kode Supplier.."
                                        value="{{ $supplier->suppliercode ?? old('suppliercode') }}">
                                    @error('suppliercode')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="supplier_name">Nama Supplier</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="supplier_name" name="supplier_name"
                                        placeholder="Supplier.."
                                        value="{{ $supplier->supplier_name ?? old('supplier_name') }}">
                                    @error('supplier_name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="supplier_address">Alamat</label>
                                <div class="col-sm-8">
                                    <textarea type="text" class="form-control" id="supplier_address"
                                        name="supplier_address"
                                        placeholder="Alamat.."> {{ $supplier->supplier_address ?? old('supplier_address') }}</textarea>
                                    @error('supplier_address')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="email">Email</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email.."
                                        value="{{ $supplier->email ?? old('email') }}">
                                    @error('email')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="phone_num">Telepon</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="phone_num" name="phone_num"
                                        placeholder="Telepon.." value="{{ $supplier->phone_num ?? old('phone_num') }}">
                                    @error('phone_num')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="city_id ">Kota</label>
                                <div class="col-sm-8">
                                    <select name="city_id" id="city_id " class="select2 form-select">
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}" @if ($is_edit) {{ $supplier->city_id == $city->id ? 'selected' : '' }} @else {{ old('city_id') == $city->id ? 'selected' : '' }} @endif>
                                                {{ $city->city_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
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
