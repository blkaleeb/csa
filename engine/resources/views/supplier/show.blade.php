@extends('layouts.master-sidebar')
@push('css')
    <!-- Stylesheets -->
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
@endpush
@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="float-start">
                    <h3 class="block-title">Filter
                        {{ $date_start->format('d F Y') . '  -  ' . $date_end->format('d F Y') }}</h3>
                </div>
            </div>
            <div class="block-content block-content-full">
                <form autocomplete="off" action="{{ route('supplier.show', $supplier->id) }}">
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="form-label" for="due_date">Dari</label>
                            <input type="text" class="js-flatpickr form-control" id="example-flatpickr-custom"
                                name="date_start" placeholder="hari-bulan-tahun"
                                value="{{ $date_start->format('d F Y') }}" data-date-format="d F Y">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="due_date">Sampai</label>
                            <input type="text" class="js-flatpickr form-control" id="example-flatpickr-custom"
                                name="date_end" placeholder="hari-bulan-tahun" value="{{ $date_end->format('d F Y') }}"
                                data-date-format="d F Y">
                        </div>
                        <div class="col-lg-12 mt-3">
                            <button type="submit" class="btn btn-info form-control">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <ul class="nav nav-tabs nav-tabs-block align-items-center" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="btabswo-static-overview-tab" data-bs-toggle="tab"
                        data-bs-target="#btabswo-static-overview" role="tab" aria-controls="btabswo-static-overview"
                        aria-selected="true">Overview</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabswo-static-detail-tab" data-bs-toggle="tab"
                        data-bs-target="#btabswo-static-detail" role="tab" aria-controls="btabswo-static-detail"
                        aria-selected="false">Detail</button>
                </li>
                <li class="nav-item ms-auto">
                    <div class="block-options ps-3 pe-2">
                        <button type="button" class="btn-block-option" data-toggle="block-option"
                            data-action="fullscreen_toggle"></button>
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle"
                            data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option" data-toggle="block-option"
                            data-action="content_toggle"></button>
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </li>
            </ul>
            <div class="block-content tab-content">
                <div class="tab-pane active" id="btabswo-static-overview" role="tabpanel"
                    aria-labelledby="btabswo-static-overview-tab">
                    {{-- Overview --}}
                    <div class="row">
                        <!-- Dynamic Table with Export Buttons -->
                        <div class="block block-rounded">
                            <div class="block-header block-header-default">
                                <div class="float-start">
                                    <h3 class="block-title">Overview</h3>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-vcenter">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Pembelian</td>
                                                <td>{{ $pembelian->sum('qty') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Retur Pembelian</td>
                                                <td>{{ $retur_pembelian->sum('qty') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- END Dynamic Table with Export Buttons -->
                    </div>
                </div>
                <div class="tab-pane" id="btabswo-static-detail" role="tabpanel"
                    aria-labelledby="btabswo-static-detail-tab">

                    {{-- Pembelian --}}
                    <div class="row">
                        <!-- Dynamic Table with Export Buttons -->
                        <div class="block block-rounded">
                            <div class="block-header block-header-default">
                                <div class="float-start">
                                    <h3 class="block-title">Pembelian</h3>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Tanggal</th>
                                                <th>No Faktur</th>
                                                <th>Qty Pesan</th>
                                                <th>Qty Terima</th>
                                                <th>Harga Satuan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pembelian as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->createdOn->format('d-m-Y') }}</td>
                                                    <td>
                                                        <a target="_blank" href="{{ route('penerimaan.edit', $item->id) }}">
                                                            {{ $item->header->internal_invoice_no . ' | ' . $item->poline->header->po_no ?? 'void' }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->poline->qty }}</td>
                                                    <td>{{ $item->qty ?? 0 }}</td>
                                                    <td>{{ number_format($item->price_per_satuan_id ?? 0) }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- END Dynamic Table with Export Buttons -->
                    </div>
                    {{-- Retur Pembelian --}}
                    <div class="row">
                        <!-- Dynamic Table with Export Buttons -->
                        <div class="block block-rounded">
                            <div class="block-header block-header-default">
                                <div class="float-start">
                                    <h3 class="block-title">Retur Pembelian</h3>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Tanggal</th>
                                                <th>No Faktur</th>
                                                <th>Qty</th>
                                                <th>Harga Satuan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($retur_pembelian as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->createdOn->format('d-m-Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('retur_pembelian.edit', $item->id) }}">
                                                            {{ $item->header->no_invoice ?? 'void' }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->qty }}</td>
                                                    <td>{{ $item->retur_price }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- END Dynamic Table with Export Buttons -->
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- END Page Content -->
@endsection
@push('js')
    <!-- Page JS Plugins -->
    <script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
@endpush
