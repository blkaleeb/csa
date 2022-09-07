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
                    <h3 class="block-title">Data Log Aktivitas</h3>
                </div>

            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full-pagination dataTable no-footer class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter js-dataTable-buttons no-footer">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Auditable Type</th>
                            <th>URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                            <tr>
                                <td class="text-center fs-sm">{{$loop->iteration}}</td>
                                <td class="fs-sm">{{date("d-m-Y H:i:s",strtotime($d->created_at))}}</td>
                                <td class="fs-sm">{{$d->displayName}}</td>
                                <td class="fs-sm">{{ucfirst($d->event)}}</td>
                                <td class="fs-sm">{{$d->auditable_type}}</td>
                                <td class="fs-sm"><a href="{{$d->url}}" target="_blank">{{$d->url}}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
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
@endpush
