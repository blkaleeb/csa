@extends('layouts.master-sidebar')
@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Form -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ $is_edit ? 'Edit Kategori Pengeluaran' : 'Tambah Kategori Pengeluaran' }}</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">

                    <div class="col-lg-8 space-y-5">
                        <!-- Form Horizontal - Default Style -->
                        <form autocomplete="off" class="space-y-4"
                            action="{{ $is_edit ? route('kategori_pengeluaran.update', $pengeluaran->id) : route('kategori_pengeluaran.store') }}"
                            method="POST" novalidate>
                            @csrf
                            @if ($is_edit)
                                @method('put')
                            @endif
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="name">Nama</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Name.."
                                        value="{{ $pengeluaran->name ?? old('name') }}">
                                    @error('name')
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
