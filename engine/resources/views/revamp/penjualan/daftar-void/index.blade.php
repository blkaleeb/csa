@extends('layouts.master-sidebar')
@push('css')
<!-- Stylesheets -->
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endpush
@section('content')
<!-- Page Content -->
<div class="">
    <div class="row">
        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="float-start">
                    <h3 class="block-title">Daftar Void </h3>
                </div>

            </div>

            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-hover table-vcenter js-dataTable-buttons">
                        <thead>
                            {{-- No, Kode Faktur Penjualan, Nama Customer, Total Penjualan, Alasan void, Status Void
                            (menunggu acc / approved). --}}
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>No Faktur</th>
                                <th>Customer</th>
                                <th>Total Faktur</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                <td>{{ date('d-m-Y H:i:s', strtotime($item->createdOn)) }}</td>

                                @if($item->type == 'SalesOrderHeader')
                                @php
                                $modeldata = App\Models\SalesOrderHeader::withTrashed()->find($item->model_id);
                                @endphp
                                <td>Penjualan</td>
                                <td>{{ $modeldata->intnomorsales }}</td>
                                <td>{{ $modeldata->customer->name}}</td>
                                <td>{{ number_format($modeldata->total_sales)}}</td>
                                @elseif($item->type == 'SalesInvoicePayment')
                                @php
                                $modeldata = App\Models\SalesInvoicePayment::withTrashed()->find($item->model_id);
                                @endphp
                                <td>Pembayaran</td>
                                <td>{{ $modeldata->salesorderheader->intnomorsales }}</td>
                                <td>{{ $modeldata->salesorderheader->customer->name}}</td>
                                <td>{{ number_format($modeldata->salesorderheader->total_sales)}}</td>
                                @endif

                                <td>{{ $item->reason }}</td>
                                <td>@switch($item->status)
                                    @case(0)
                                        Menunggu
                                        @break
                                    @case(1)
                                        Diterima
                                        @break
                                    @default
                                        
                                @endswitch</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                            @if(Auth::user()->role_id == 1)
                                            <a class="dropdown-item"
                                                href="{{ route('penjualan-new.daftar-void.approve', ['id' => $item->id]) }}">Approve</a>
                                            <a class="dropdown-item"
                                                href="{{ route('penjualan-new.daftar-void.cancel', ['id' => $item->id]) }}">Cancel</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
</div>
<!-- END Page Content -->
@endsection
@push('js')
<!-- Page JS Plugins -->
<script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
<!-- Page JS Code -->
<script src="{{ asset('assets/js/pages/be_tables_datatables.min.js') }}"></script>
<script>
    One.helpersOnLoad(['one-table-tools-checkable', 'one-table-tools-sections']);
</script>
@endpush
