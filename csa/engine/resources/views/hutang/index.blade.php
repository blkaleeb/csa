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
                    <h3 class="block-title">Hutang</h3>
                </div>
                <div class="float-end">
                    <a href="{{route('hutang.create')}}" class="btn btn-info block-title">Buat Pembayaran</a>
                </div>

            </div>
            <div class="block-content block-content-full table-responsive">
                <div class ="table-responsive"><table class="table table-responsive table-bordered table-striped table-vcenter js-dataTable-buttons">
                    <thead>
                        <th class="col-head">
                            No
                        </th>
                        <th class="col-head">
                            No Faktur Supplier
                        </th>
                        <th class="col-head">
                            Tanggal Pembayaran
                        </th>
                        <th class="col-head">
                            Supplier
                        </th>
                        <th class="col-head">
                            Diskon
                        </th>
                        <th class="col-head">
                            Jumlah Pembayaran
                        </th>
                        <th class="col-head">
                            Total Hutang
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
                    </thead>
                    <tbody id="item-table">
                        @foreach($data as $key)<tr>
                            <td class="fw-semibold fs-sm">{{ $loop->iteration }}</td>
                            <td class="fw-semibold fs-sm">{{$key->purchaseinvoiceheader->poheader->po_no." ".$key->purchaseinvoiceheader->internal_invoice_no ?? "error"}}
                            </td>
                            <td class="fw-semibold fs-sm">{{date("d-m-Y",strtotime($key->createdOn))}}</td>
                            <td class="fw-semibold fs-sm">
                                <a target="_blank" href="{{route('supplier.show', $key->purchaseinvoiceheader->poheader->supplier->id ?? 0)}}">
                                    {{$key->purchaseinvoiceheader->poheader->supplier->supplier_name ?? ""}}
                                </a>
                            </td>
                            <td class="fw-semibold fs-sm">Rp. {{number_format($key->diskon)}}</td>
                            <td class="fw-semibold fs-sm">Rp. {{number_format($key->payment_value)}}</td>
                            <td class="fw-semibold fs-sm">Rp. {{number_format($key->payment_value+$key->diskon)}}</td>
                            @php
                            $notes="";
                            if(isset($key->note)){
                            $notes = explode("||",$key->note);
                            }
                            @endphp
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
                                        <a class="dropdown-item" href="{{route('hutang.edit',$key->id)}}">Edit</a>
                                        <a class="dropdown-item delete">Void</a>
                                        <form autocomplete="off" action="{{route('hutang.destroy',$key->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table></div>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
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
