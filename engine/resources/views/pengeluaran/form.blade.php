@extends('layouts.master-sidebar')
@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Form -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ $is_edit ? 'Edit Pengeluaran' : 'Tambah Pengeluaran' }}</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-8 space-y-5">
                        <!-- Form Horizontal - Default Style -->
                        <form autocomplete="off" class="space-y-4"
                            action="{{ $is_edit ? route('pengeluaran.update', $pengeluaran->id) : route('pengeluaran.store') }}"
                            method="POST" novalidate>
                            @csrf
                            @if ($is_edit)
                                @method('put')
                            @endif
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="no_bukti">Nomor Bukti</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="no_bukti" name="no_bukti"
                                        placeholder="Nomor Bukt.."
                                        value="{{ $pengeluaran->no_bukti ?? old('no_bukti') }}">
                                    @error('no_bukti')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="tanggal">Tanggal</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="tanggal" name="tanggal"
                                        placeholder="Tanggal.."
                                        value="{{ $is_edit ? Carbon\Carbon::parse($pengeluaran->tanggal)->toDateString() : old('tanggal') }}">
                                    @error('tanggal')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="city_id">Subject Pengeluaran</label>
                                <div class="col-sm-8 mb-3">
                                    <button type="button" class="btn btn-primary subject" data-item="user_id">Karyawan</button>
                                    <button type="button" class="btn btn-primary subject" data-item="inventaris_id">Inventaris</button>
                                    <button type="button" class="btn btn-primary subject" data-item="itemstock_id">Stock</button>
                                </div>
                                <div class="col-sm-8 offset-sm-4 subject-select" id="user_id">
                                    <select name="user_id" class="select2 form-select" style="width: 100% !important;">
                                        <option value=""></option>
                                        @foreach ($karyawan as $user)
                                            <option value="{{ $user->id }}" @if ($is_edit) {{ $pengeluaran->user_id == $user->id ? 'selected' : '' }} @else {{ old('user_id') == $user->id ? 'selected' : '' }} @endif>
                                                {{ $user->displayName }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-sm-8 offset-sm-4 subject-select d-none" id="inventaris_id">
                                    <select name="inventaris_id" class="select2 form-select" style="width: 100% !important;">
                                        <option value=""></option>
                                        @foreach ($inventoris as $inven)
                                            <option value="{{ $inven->id }}" @if ($is_edit) {{ $pengeluaran->inventaris_id == $inven->id ? 'selected' : '' }} @else {{ old('inventaris_id') == $inven->id ? 'selected' : '' }} @endif>
                                                {{ $inven->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('inventaris_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-sm-8 offset-sm-4 subject-select d-none" id="itemstock_id">
                                    <select name="itemstock_id" class="select2 form-select" style="width: 100% !important;">
                                        <option value=""></option>
                                        @foreach ($stock as $itemstock)
                                            <option value="{{ $itemstock->id }}" @if ($is_edit) {{ $pengeluaran->itemstock_id == $itemstock->id ? 'selected' : '' }} @else {{ old('itemstock_id') == $itemstock->id ? 'selected' : '' }} @endif>
                                                {{ $itemstock->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('itemstock_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="jumlah">Jumlah</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="jumlah" name="jumlah"
                                        placeholder="jumlah.." value="{{ $pengeluaran->jumlah ?? old('jumlah') }}">
                                    @error('jumlah')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="kategori_pengeluaran_id">Kategori</label>
                                <div class="col-sm-8">
                                    <select name="kategori_pengeluaran_id" id="kategori_pengeluaran_id" class="select2 form-select">
                                        @foreach ($kategori_pengeluaran as $kategori)
                                            <option value="{{ $kategori->id }}" @if ($is_edit) {{ $pengeluaran->kategori_pengeluaran_id == $kategori->id ? 'selected' : '' }} @else {{ old('kategori_pengeluaran_id') == $kategori->id ? 'selected' : '' }} @endif>
                                                {{ $kategori->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('kategori_pengeluaran_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-4 col-form-label" for="detail">Detail</label>
                                <div class="col-sm-8">
                                    <textarea type="text" class="form-control" id="detail"
                                        name="detail"
                                        placeholder="Alamat.."> {{ $pengeluaran->detail ?? old('detail') }}</textarea>
                                    @error('detail')
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
@push('js')
    <script>
        $('.subject').click(function(){
            $('.subject-select').addClass('d-none')
            $('#'+$(this).data('item')).removeClass('d-none')

            $('select[name="user_id"]').val('').trigger('change')
            $('select[name="inventaris_id"]').val('').trigger('change')
            $('select[name="itemstock_id"]').val('').trigger('change')
        })
    </script>
@endpush
