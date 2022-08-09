@extends('layouts.master-sidebar')
@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Form -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ $is_edit ? 'Edit Gudang' : 'Tambah Gudang' }}</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">

                    <div class="col-lg-8 space-y-5">
                        <!-- Form Horizontal - Default Style -->
                        <form autocomplete="off" class="space-y-4"
                            action="{{ $is_edit ? route('gudang.update', $warehouse->id) : route('gudang.store') }}"
                            method="POST" novalidate>
                            @csrf
                            @if ($is_edit)
                                @method('put')
                            @endif
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="kode">Kode Gudang</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="kode" name="warehouse_code"
                                        placeholder="Kode.."
                                        value="{{ $warehouse->warehouse_code ?? old('warehouse_code') }}">
                                    @error('warehouse_code')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="example-hf-email">Nama Gudang</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="example-hf-email" name="warehouse_name"
                                        placeholder="Gudang Barang.."
                                        value="{{ $warehouse->warehouse_name ?? old('warehouse_name') }}">
                                    @error('warehouse_name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="alamat">Alamat</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="alamat" name="warehouse_address"
                                        placeholder="Alamat.."
                                        value="{{ $warehouse->warehouse_address ?? old('warehouse_address') }}">
                                    @error('warehouse_address')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="example-hf-password">Telepon</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="example-hf-password"
                                        name="warehouse_phone_no" placeholder="Telepon.."
                                        value="{{ $warehouse->warehouse_phone_no ?? old('warehouse_phone_no') }}">
                                    @error('warehouse_phone_no')
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
