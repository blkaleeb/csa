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
                    <h3 class="block-title">Daftar Piutang</h3>
                </div>
                <div class="float-end">
                </div>
            </div>


            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-hover table-vcenter js-dataTable-buttons ">
                        <thead>
                            <tr>
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
                                    Sisa Bayar
                                </th>
                                <th class="col-head">
                                    Jumlah Pembayaran
                                </th>
                                <th class="col-head">
                                    Total Sales
                                </th>
                                <th class="col-head">
                                    Total Retur <i class="fa-solid fa-question" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                        data-bs-title="Retur Yang Terjadi Perfaktur Penjualan"></i>
                                </th>
                                <th class="col-head">
                                    Notes
                                </th>
                              
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="">

                        @foreach ($data as $key)
                        <tr @if($key->void_status == 1) style="background-color: red" @endif>
                  
                            <td class="fw-semibold fs-sm">{{ $loop->iteration }}</td>
                            <td class="fw-semibold fs-sm">{{$key->intnomorsales ?? null}}
                            </td>
                            <td class="fw-semibold fs-sm">{{date("d-m-Y",strtotime($key->createdOn))}}</td>
                            <td>
                                <a target="_blank"
                                    href="{{route('konsumen.show', $key->customer->id ?? 0)}}">
                                    {{$key->customer->name ?? null}}
                                </a>
                            </td>
                            <td class="fw-semibold fs-sm">Rp. {{number_format($key->diskon)}}</td>
                            <td class="fw-semibold fs-sm">Rp. {{number_format($key->payment_remain)}}</td>
                            <td class="fw-semibold fs-sm">Rp. {{number_format($key->total_paid)}}</td>
                            <td class="fw-semibold fs-sm">Rp. {{number_format($key->total_sales)}}
                            </td>
                            @php
                            $notes="";
                            if(isset($key->note)){
                            $notes = explode("||",$key->note);
                            }
                            @endphp
                            <td>{{$key->retur ?? 0}}</td>
                            <td class="fw-semibold fs-sm">{{$notes[0] ?? null}}</td>
                          
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item"
                                            href="{{ route('penjualan-new.daftar-piutang.create', ['salesid' => $key->id]) }}">Bayar</a>
                                        <a class="dropdown-item"
                                            href="{{ route('penjualan-new.retur.create', ['salesid' => $key->id]) }}">Buat
                                            Retur
                                            Faktur</a>
                                        <a class="dropdown-item"
                                            href="{{ route('penjualan-new.daftar-piutang.print', $key->id) }}">Print</a>
                                        <a class="dropdown-item delete">Void</a>
                                        <form autocomplete="off"
                                            action="{{ route('penjualan-new.daftar-piutang.destroy', $key->id) }}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </td>
                            {{-- <tbody class="fs-sm">
                            <tr>
                                <td class="text-center"></td>
                                <td colspan="3" class="fw-semibold fs-sm">Product</td>
                                <td colspan="2" class="fw-semibold fs-sm">Harga Satuan</td>
                                <td colspan="2" class="fw-semibold fs-sm">Quantity</td>
                                <td colspan="2" class="fw-semibold fs-sm">Harga Total</td>
                                <td colspan="2"></td>
                            </tr>
                            @if($key)
                            @foreach ($key->line as $line)
                            <tr>
                                <td class="text-center"></td>
                                <td colspan="3">{{ $line->stock->name ?? "-" }}</td>
                                <td colspan="2">{{ number_format($line->price_per_satuan_id) ?? "-" }}</td>
                                <td colspan="2">{{ $line->qty }}</td>
                                <td colspan="2">{{ number_format($line->qty * $line->price_per_satuan_id) }}</td>
                                <td colspan="2">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                            <a class="dropdown-item"
                                                href="{{route('retur_pembelian_line.edit',$line->id)}}">Edit</a>
                                            <a class="dropdown-item delete">Void</a>
                                            <form autocomplete="off"
                                                action="{{route('retur_pembelian_line.destroy',$line->id)}}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody> --}}

                    </tr>
                        @endforeach
                    </tbody>

                    </table>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- END Dynamic Table with Export Buttons -->
</div>
<!-- END Page Content -->
@endsection
@push("js")
<!-- Page JS Plugins -->
<script src="{{asset('assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons-jszip/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons/buttons.html5.min.js')}}"></script>
<!-- Page JS Code -->
<script src="{{asset('assets/js/pages/be_tables_datatables.min.js')}}"></script>
<script>
    One.helpersOnLoad(['one-table-tools-checkable', 'one-table-tools-sections']);
</script>
@endpush
