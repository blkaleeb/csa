@extends('layouts.master-sidebar')
@push("css")
<!-- Stylesheets -->
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="{{asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endpush
@section('content')
<!-- Page Content -->
<div class="content w-100">
    <div class="row">
        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="float-start">
                    <h3 class="block-title">Penerimaan</h3>
                </div>

            </div>
            <div class="block-content block-content-full">
                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter js-dataTable-buttons no-footer">
                    <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th>Tanggal</th>
                            <th>No Penerimaan</th>
                            <th>No PO</th>
                            <th>No Faktur Supp</th>
                            <th>Supplier</th>
                            <th>Total Hutang</th>
                            <th>Total Bayar</th>
                            <th>Retur</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="fs-sm">

                        @foreach ($data as $line)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{date("d-m-Y",strtotime($line->invoice_date))}}</td>
                            <td>{{$line->internal_invoice_no}}</td>
                            <td>
                                <a target="_blank" href="{{route('po.show', $line->poheader->id ?? 0)}}">
                                    {{$line->poheader->po_no ?? ''}}
                                </a>
                            </td>
                            <td>{{$line->supplier_invoice_no}}</td>
                            <td>
                                <a target="_blank" href="{{route('supplier.show', $line->poheader->supplier->id ?? 0)}}">
                                    {{$line->poheader->supplier->supplier_name ?? ''}}
                                </a>
                            </td>
                            <td>{{number_format($line->invoice_total)}}</td>
                            <td>{{number_format($line->paid_total)}}</td>
                            <td>{{number_format($line->retur)}}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item" href="{{route('penerimaan.edit',$line->id)}}">Edit</a>
                                        <a class="dropdown-item delete">Void</a>
                                        <form autocomplete="off" action="{{route('penerimaan.destroy',$line->id)}}"
                                            method="post">
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
