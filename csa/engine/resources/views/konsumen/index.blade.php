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
                    <h3 class="block-title">Data Konsumen</h3>
                </div>
                <div class="float-end">
                    <a href="{{route("konsumen.create")}}" class="btn btn-info block-title">Tambah Konsumen</a>
                </div>

            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Konsumen</th>
                            <th class="d-none d-sm-table-cell"> Alamat</th>
                            <th class="d-none d-sm-table-cell"> Telp</th>
                            <th class="d-none d-sm-table-cell"> Sales</th>
                            <th class="d-none d-sm-table-cell"> Utang</th>
                            <th class="d-none d-sm-table-cell"> Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($konsumens as $konsumen)
                        <tr>
                            <td class="text-center fs-sm">{{$loop->iteration}}</td>
                            <td class="fw-semibold fs-sm">
                                <a href="{{route('konsumen.show', $konsumen->id)}}">{{$konsumen->name}}</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                {{$konsumen->customer_address}}
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                {{$konsumen->customer_phone_no}}
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                {{$konsumen->sales->displayName}}
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                Rp {{number_format($konsumen->salesorder->sum('payment_remain'))}}
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Active</span>
                                {{-- <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Blacklist</span>
                                --}}
                            </td>
                            <td>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item"
                                            href="{{route('konsumen.edit',$konsumen->id)}}">Edit</a>
                                        <a class="dropdown-item delete">Delete</a>
                                        <form autocomplete="off" action="{{route('konsumen.destroy',$konsumen->id)}}" method="post">
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
@endpush
