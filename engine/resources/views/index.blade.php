{{-- @extends('layouts.master-sidebar-top') --}}
@extends('layouts.master-sidebar')

@section('content')

<!-- Page Content -->
<div class="content">
    <!-- Stats -->
    <div class="row">

        {{--
        <div class="col-6 col-md-6 col-lg-6 col-xl-6">
            <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="fs-sm fw-semibold text-uppercase text-muted">Earnings {{date("d-m-Y")}}</div>
                    <div class="fs-2 fw-normal text-dark">$3,200</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-6 col-lg-6 col-xl-6">
            <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="fs-sm fw-semibold text-uppercase text-muted">Sales {{date("d-m-Y")}}</div>
                    <div class="fs-2 fw-normal text-dark">150</div>
                </div>
            </a>
        </div> --}}
    </div>
    <!-- END Stats -->

    <!-- Dashboard Charts -->
    <div class="row">
        {{-- <div class="col-lg-6">
            <div class="block block-rounded block-mode-loading-oneui">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Earnings in $</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option"
                            data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 text-center">
                    <!-- Chart.js is initialized in js/pages/be_pages_dashboard_v1.min.js which was auto compiled from _js/pages/be_pages_dashboard_v1.js) -->
                    <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                    <div class="pt-3" style="height: 360px;"><canvas id="js-chartjs-dashboard-earnings"></canvas></div>
                </div>
                <div class="block-content">
                    <div class="row items-push text-center py-3">
                        <div class="col-6 col-xl-3">
                            <i class="fa fa-wallet fa-2x text-muted"></i>
                            <div class="text-muted mt-3">$148,000</div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <i class="fa fa-angle-double-up fa-2x text-muted"></i>
                            <div class="text-muted mt-3">+9% Earnings</div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <i class="fa fa-ticket-alt fa-2x text-muted"></i>
                            <div class="text-muted mt-3">+20% Tickets</div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <i class="fa fa-users fa-2x text-muted"></i>
                            <div class="text-muted mt-3">+46% Clients</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="block block-rounded block-mode-loading-oneui">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Sales</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option"
                            data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 text-center">
                    <!-- Chart.js is initialized in js/pages/be_pages_dashboard_v1.min.js which was auto compiled from _js/pages/be_pages_dashboard_v1.js) -->
                    <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                    <div class="pt-3" style="height: 360px;"><canvas id="js-chartjs-dashboard-sales"></canvas></div>
                </div>
                <div class="block-content">
                    <div class="row items-push text-center py-3">
                        <div class="col-6 col-xl-3">
                            <i class="fab fa-wordpress fa-2x text-muted"></i>
                            <div class="text-muted mt-3">+20% Themes</div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <i class="fa fa-font fa-2x text-muted"></i>
                            <div class="text-muted mt-3">+25% Fonts</div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <i class="fa fa-archive fa-2x text-muted"></i>
                            <div class="text-muted mt-3">-10% Icons</div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <i class="fa fa-paint-brush fa-2x text-muted"></i>
                            <div class="text-muted mt-3">+8% Graphics</div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <!-- END Dashboard Charts -->

    <!-- Customers and Latest Orders -->
    <div class="row items-push">
        <!-- Latest Orders -->
        <div class="col-lg-12">
            <div class="block block-rounded block-mode-loading-oneui h-100 mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Order Hari Ini</h3>

                </div>
                <div class="block-content block-content-full">
                    <div class="table-responsive">
                        <div class ="table-responsive"><table class="table table-striped table-hover table-borderless table-vcenter fs-sm mb-0js-dataTable-buttons no-footer">
                            <thead>
                                <tr class="text-uppercase">
                                    <th class="fw-bold">#</th>
                                    <th class="d-sm-table-cell fw-bold">Tanggal</th>
                                    <th class="fw-bold">Konsumen</th>
                                    <th class="d-sm-table-cell fw-bold text-end" style="width: 120px;">
                                        Total</th>
                                    <th class="fw-bold text-center" style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $item)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{$item->intnomorsales}}</span>
                                    </td>
                                    <td class="d-sm-table-cell">
                                        <span class="fs-sm text-muted">{{$item->createdOn->format("d F Y")}}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-warning">{{$item->customer?->name ?? "Customer Dihapus"}}</span>
                                    </td>
                                    <td class="d-sm-table-cell text-end">
                                        Rp {{number_format($item->total_sales)}}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('daftar-piutang.edit',$item->id)}}" data-bs-toggle="tooltip"
                                            data-bs-placement="left"
                                            title="" class="js-bs-tooltip-enabled" data-bs-original-title="Manage">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="block block-rounded block-mode-loading-oneui h-100 mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Hutang</h3>

                </div>
                <div class="block-content block-content-full">
                    <div class="table-responsive">

                        <div class ="table-responsive"><table class="table table-striped table-hover table-borderless table-vcenter fs-sm mb-0js-dataTable-buttons no-footer">
                            <thead>
                                <tr class="text-uppercase">
                                    <th class="fw-bold">#</th>
                                    <th class="d-sm-table-cell fw-bold">Nama</th>
                                    <th class="fw-bold">Telp</th>
                                    <th class="d-sm-table-cell fw-bold text-end" style="width: 300px;">
                                        Hutang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($konsumen as $item)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{$loop->iteration}}</span>
                                    </td>
                                    <td class="d-sm-table-cell">
                                        <span class="fs-sm text-muted">{{$item->customer->name}}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-warning">{{$item->customer->customer_phone_no}}</span>
                                    </td>
                                    <td class="d-sm-table-cell text-end">
                                        Rp {{number_format($item->hutang ?? 0)}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Latest Orders -->
    </div>
    <!-- END Customers and Latest Orders -->
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
