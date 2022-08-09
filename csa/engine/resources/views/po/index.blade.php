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
                    <h3 class="block-title">Purchase Order</h3>
                </div>
                <div class="float-end">
                    <a href="{{route('po.create')}}" class="btn btn-info block-title">Buat PO</a>
                </div>

            </div>
            <div class="block-content block-content-full table-responsive">
                <div class ="table-responsive"><table class="table table-hover table-vcenter  js-dataTable-buttons">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>No PO</th>
                            <th>Supplier</th>
                            <th>Total PO</th>
                            <th>Sisa Bayar</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach ($data as $item)
                        <tr>

                            <td>{{$loop->iteration}}</td>
                            <td>{{date("d-m-Y H:i:s",strtotime($item->createdOn))}}</td>
                            <td>{{$item->po_no}}</td>
                            <td>
                                <a target="_blank" href="{{route('supplier.show', $item->supplier->id ?? 0)}}">
                                    {{$item->supplier->supplier_name ?? ''}}
                                </a>
                            </td>
                            <td>{{number_format($item->po_total)}}</td>
                            <td>{{number_format($item->po_total - $item->po_total_paid)}}</td>

                            <td>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item" href="{{route('penerimaan.create',$item->id)}}">Penerimaan</a>
                                        <a class="dropdown-item" href="{{route('po.show',$item->id)}}">Show</a>
                                        @if($item->line->sum('penerimaan') != count($item->line))
                                        <a class="dropdown-item" href="{{route('po.edit',$item->id)}}">Edit</a>
                                        <a class="dropdown-item delete">Void</a>
                                        <form autocomplete="off" action="{{route('po.destroy',$item->id)}}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {{-- <tbody class="fs-sm">
                        <tr>
                            <td class="text-center"></td>
                            <td colspan="2" class="fw-semibold fs-sm">Product</td>
                            <td class="fw-semibold fs-sm">Harga Satuan</td>
                            <td class="fw-semibold fs-sm">Quantity</td>
                            <td colspan="2" class="fw-semibold fs-sm">Harga Total</td>
                            <td></td>
                        </tr>
                        @foreach ($item->line as $line)
                        <tr>
                            <td class="text-center"></td>
                            <td colspan="2">{{ $line->stock->name ?? "-" }}</td>
                            <td>{{ number_format($line->price_per_satuan_id) ?? "-" }}</td>
                            <td>{{ $line->qty }}</td>
                            <td colspan="2">{{ number_format($line->qty * $line->price_per_satuan_id) }}</td>
                            <td>
                                <div class="dropdown">
                                    @if($line->penerimaan != 1)
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item" href="{{route('po_line.edit',$line->id)}}">Edit</a>
                                        <a class="dropdown-item delete">Void</a>
                                        <form autocomplete="off" action="{{route('po_line.destroy',$line->id)}}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody> --}}
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
