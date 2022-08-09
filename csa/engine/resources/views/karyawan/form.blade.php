@extends('layouts.master-sidebar')
@section('content')

<!-- Page Content -->
<div class="content">
    <!-- Form -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Tambah Karyawan</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">

                <div class="col-lg-8 space-y-5">
                    <!-- Form Horizontal - Default Style -->
                    <form autocomplete="off" class="space-y-4"
                        action="{{$is_edit?route('karyawan.update',$user->id):route('karyawan.store')}}" method="POST"
                        novalidate>
                        @csrf
                        @if($is_edit)
                        @method('put')
                        @endif
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Nama</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="example-hf-email" name="displayName"
                                    placeholder="karyawan Barang.." value="{{$user->displayName ?? ""}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Username</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="example-hf-email" name="username"
                                    placeholder="username" value="{{$user->username ?? ""}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Alamat</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="example-hf-email" name="address"
                                    placeholder="alamat" value="{{$user->address ?? ""}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Nomor HP/Telfon</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="example-hf-email" name="telephone"
                                    placeholder="Nomor HP/Telfon" value="{{$user->telephone ?? ""}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="example-hf-email" name="password"
                                    value="">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Konfirmasi Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="example-hf-email"
                                    name="password_confirmation" value="">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Jabatan</label>
                            <div class="col-sm-8">
                                <select name="role_id" id="" class="form-control select2">
                                    @foreach($roles as $role)
                                    <option value="{{$role->id}}" @if($is_edit && $role->id == $user->rol_id) selected @endif>{{$role->name}}</option>
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
