@extends('layouts.master-sidebar')
@push("css")
<!-- Stylesheets -->
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="{{asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endpush
@section('content')
<!-- Page Content -->
<div class="content">
    <div class="row">
        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="float-start">
                    <h3 class="block-title">Laporan Penjualan</h3>
                </div>
                <div class="float-end">
                    {{-- <a href="{{route('retur_pembelian.create')}}" class="btn btn-info block-title">Buat Faktur</a>
                    --}}

                    <!-- <button class="btn btn-warning" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        Filter
                    </button> -->
                    <!-- <a href="{{route('laporan-penjualan.create')}}" class="btn btn-info">Buat Pembayaran</a> -->
                </div>
            </div>
            <div class="block-content block-content-full">
            <div class ="table-responsive">
                <table class="table table-hover table-vcenter js-dataTable-buttons js-table-sections ">
                        <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th class="col-head">
                                    No
                                </th>
                                <th class="col-head">
                                    No Faktur Sales
                                </th>
                                <th class="col-head">
                                    Tanggal Pembayaran
                                </th>
                                <th class="col-head">
                                    Customer
                                </th>
                                <th class="col-head">
                                    Diskon
                                </th>
                                <th class="col-head">
                                    Jumlah Pembayaran
                                </th>
                                <th class="col-head">
                                    Total Pembayaran
                                </th>
                                <th class="col-head">
                                    Total Retur <i class="fa-solid fa-question" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                        data-bs-title="Retur Yang Terjadi Perfaktur Penjualan"></i>
                                </th>
                                <th class="col-head">
                                    Notes
                                </th>
                                <th class="col-head">
                                    Payment
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        @foreach ($data as $key)
                        <tbody class="js-table-sections-header">
                        <tr @if($key->void_status == 1) style="background-color: darkred; color:white" @endif>
                            <td class="text-center fs-sm">
                                <i class="fa fa-angle-right text-muted"></i>
                            </td>
                            <td class="fw-semibold fs-sm">{{ $loop->iteration }}</td>
                            <td class="fw-semibold fs-sm">{{$key->salesorderheader->intnomorsales ?? null}}
                            </td>
                            <td class="fw-semibold fs-sm">{{date("d-m-Y",strtotime($key->createdOn))}}</td>
                            <td>
                                <a target="_blank"
                                    href="{{route('konsumen.show', $key->salesorderheader->customer->id ?? 0)}}">
                                    {{$key->salesorderheader->customer->name ?? null}}
                                </a>
                            </td>
                            <td class="fw-semibold fs-sm">Rp. {{number_format($key->diskon)}}</td>
                            <td class="fw-semibold fs-sm">Rp. {{number_format($key->payment_value)}}</td>
                            <td class="fw-semibold fs-sm">Rp. {{number_format($key->payment_value+$key->diskon)}}
                            </td>
                            @php
                            $notes="";
                            if(isset($key->note)){
                            $notes = explode("||",$key->note);
                            }
                            @endphp
                            <td>{{$key->salesorderheader->retur}}</td>
                            <td class="fw-semibold fs-sm">{{$notes[0] ?? null}}</td>
                            <td class="fw-semibold fs-sm">@if($key->payment_id=="C")
                                Cash
                                @elseif($key->payment_id=="G")
                                Giro
                                @elseif($key->payment_id=="CH")
                                Cek
                                @elseif($key->payment_id=="TR")
                                Transfer
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item" href="{{route('laporan-penjualan.edit',$key->id)}}">Edit</a>
                                        <a class="dropdown-item delete">Void</a>
                                        <form autocomplete="off" action="{{route('laporan-penjualan.destroy',$key->id)}}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </td>
                            </tr>
                        </tbody>
                        <tbody class="fs-sm">
                        <tr>
                        <td class="text-center"></td>
                            <td colspan="5" class="fw-semibold fs-sm">Product</td>
                            <td colspan="2" class="fw-semibold fs-sm">Harga Satuan</td>
                            <td colspan="2" class="fw-semibold fs-sm">Quantity</td>
                            <td colspan="2" class="fw-semibold fs-sm">Harga Total</td>
                        </tr>
                        @foreach ($key->salesorderheader->line as $line)
                        <tr>
                            <td class="text-center"></td>
                            <td colspan="5">{{ $line->stock->name ?? "-" }}</td>
                            <td colspan="2">{{ number_format($line->retur_price) ?? "-" }}</td>
                            <td colspan="2">{{ $line->qty }}</td>
                            <td colspan="2">{{ number_format($line->qty * $line->retur_price) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    @endforeach

                    </table>
                    {{$data->appends(request()->input())->links();}}
                </div>
            </div>
        </div>
    </div>
    <!-- END Dynamic Table with Export Buttons -->
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