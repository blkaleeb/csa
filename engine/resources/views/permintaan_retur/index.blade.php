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
                    <h3 class="block-title">Daftar Permintan Retur</h3>
                </div>
                <!-- <div class="float-end">
                    <a href="{{route('retur_pembelian.create')}}" class="btn btn-info block-title">Buat Faktur</a>
                </div> -->

            </div>
            <div class="block-content block-content-full">
                <div class ="table-responsive"><table class="table table-hover table-vcenter js-dataTable-buttons js-table-sections ">
                    <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th>No Faktur</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Total Retur</th>
                            <!-- <th></th> -->
                        </tr>
                    </thead>
                    @foreach ($data as $item)
                    <?php
                        $total = 0;
                    ?>
                    <tbody class="js-table-sections-header">
                        <tr>
                            <td class="text-center fs-sm">
                                <i class="fa fa-angle-right text-muted"></i>
                            </td>
                            <td>{{$item->no_invoice}}</td>
                            <td>{{date("d-m-Y H:i:s",strtotime($item->createdOn))}}</td>
                            <td>
                                <a target="_blank" href="{{route('supplier.show', $item->supplier->id ?? 0)}}">
                                    {{$item->supplier->supplier_name ?? ''}}
                                </a>
                            </td>
                            <td id="total_{{$item->id}}"></td>
                            <!-- <td>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item"
                                            href="{{route('retur_pembelian.edit',$item->id)}}">Edit</a>
                                            <a class="dropdown-item"
                                            href="{{route('retur_pembelian.print',$item->id)}}">Print</a>
                                        <a class="dropdown-item delete">Void</a>
                                        <form autocomplete="off" action="{{route('retur_pembelian.destroy',$item->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </td> -->
                        </tr>
                    </tbody>
                    <tbody class="fs-sm">
                        <tr>
                            <td class="text-center"></td>
                            <td class="fw-semibold fs-sm">Product</td>
                            <td class="fw-semibold fs-sm">Harga Satuan</td>
                            <td class="fw-semibold fs-sm">Quantity</td>
                            <td class="fw-semibold fs-sm">Harga Total</td>
                            <td></td>
                        </tr>
                        @foreach ($item->line as $line)
                        <tr>
                            <td class="text-center"></td>
                            <td>{{ $line->stock->name ?? "-" }}</td>
                            <td>{{ number_format($line->retur_price) ?? "-" }}</td>
                            <td>{{ $line->qty }}</td>
                            <td>{{ number_format($line->qty * $line->retur_price) }}</td>
                            <?php
                                $total += ($line->qty * $line->retur_price);
                            ?>
                            <!-- <td>
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
                                        <form autocomplete="off" action="{{route('retur_pembelian_line.destroy',$line->id)}}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </td> -->
                        </tr>
                        @endforeach
                    </tbody>

                    <script>
                        document.getElementById("total_<?php echo $item->id ?>").innerHTML = "<?php echo number_format($total)?>";
                    </script>
                    @endforeach
                </table></div>
                {{$data->links()}}


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
