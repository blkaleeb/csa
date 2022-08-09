@extends('layouts.master-sidebar')
@push('css')
    <!-- Stylesheets -->
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endpush
@section('content')
    <!-- Page Content -->
    <div class="content">
        {{-- <div class="row">
        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="float-start">
                    <h3 class="block-title">Retur Penjualan</h3>
                </div>
                <div class="float-end">
                    <a href="{{route('retur_penjualan.create')}}" class="btn btn-info block-title">Buat Faktur</a>
                </div>

            </div>
            <div class="block-content block-content-full">
                <div class ="table-responsive"><table class="table table-hover table-vcenter js-dataTable-buttons js-table-sections ">
                    <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th>No Faktur</th>
                            <th>Tanggal</th>
                            <th>Konsumen</th>
                            <th>Total Retur</th>
                            <th></th>
                        </tr>
                    </thead>
                    @foreach ($data as $item)
                    <tbody class="js-table-sections-header">
                        <tr>
                            <td class="text-center fs-sm">
                                <i class="fa fa-angle-right text-muted"></i>
                            </td>
                            <td>{{$item->no_invoice}}</td>
                            <td>{{date("d-m-Y H:i:s",strtotime($item->createdOn))}}</td>
                            <td>
                                <a target="_blank" href="{{route('konsumen.show', $item->customer->id ?? 0)}}">
                                    {{$item->customer->name ?? "Nama Belum Set"}}
                                </a>
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
                                            href="{{route('retur_penjualan.edit',$item->id)}}">Edit</a>
                                            <a class="dropdown-item"
                                            href="{{route('retur_penjualan.print',$item->id)}}">Print</a>
                                        <a class="dropdown-item delete">Void</a>
                                        <form autocomplete="off" action="{{route('retur_penjualan.destroy',$item->id)}}" method="post">
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
                            <td>{{ number_format($line->returprice) ?? "-" }}</td>
                            <td>{{ $line->qty }}</td>
                            <td>{{ number_format($line->qty * $line->returprice) }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item"
                                            href="{{route('retur_penjualan_line.edit',$line->id)}}">Edit</a>
                                        <a class="dropdown-item delete">Void</a>
                                        <form autocomplete="off" action="{{route('retur_penjualan_line.destroy',$line->id)}}"
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
                    @endforeach
                </table></div>



            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div> --}}
        <div class="row">
            <!-- Dynamic Table with Export Buttons -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <div class="float-start">
                        <h3 class="block-title">Retur Penjualan </h3>
                    </div>
                    <div class="float-end">
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            Filter
                        </button>
                        <a href="{{ route('penjualan.create') }}" class="btn btn-info">Buat Faktur</a>
                    </div>
                </div>
                <div class="block-content block-content-full">

                    <form autocomplete="off" action="{{ route('penjualan.index') }}" class="collapse" id="collapseExample">

                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label" for="customer_filter">Customer</label>
                                <input type="text" class="form-control" id="customer_filter" name="customer"
                                    value="{{ $customer_filter ?? '' }}">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="invoice_filter">No Faktur</label>
                                <input type="text" class="form-control" id="invoice_filter" name="invoice"
                                    value="{{ $invoice_filter ?? '' }}">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label" for="due_date">Dari</label>
                                <input type="text" class="js-flatpickr form-control" id="example-flatpickr-custom"
                                    name="date_start" placeholder="hari-bulan-tahun"
                                    value="" data-date-format="d F Y">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="due_date">Sampai</label>
                                <input type="text" class="js-flatpickr form-control" id="example-flatpickr-custom"
                                    name="date_end" placeholder="hari-bulan-tahun"
                                    value="" data-date-format="d F Y">
                            </div>

                        </div>
                        <div class="col-lg-12 mt-3">
                            <button type="submit" class="btn btn-info form-control">Filter</button>
                        </div>
                    </form>
                </div>
                <div class="block-content block-content-full">
                    <div class="table-responsive">
                        <table class="table table-hover table-vcenter js-dataTable-buttons">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No Faktur</th>

                                    <th>Tanggal</th>
                                    <th>Konsumen</th>
                                    <th>Total Faktur</th>
                                    <th>Total Retur</th>
                                    <th>Sisa Bayar</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                        <td>{{ $item->intnomorsales }}</td>
                                        @if (isset($void))
                                            <td>{{ date('d-m-Y H:i:s', strtotime($item->deletedOn)) }}</td>
                                        @endif
                                        <td>{{ date('d-m-Y H:i:s', strtotime($item->createdOn)) }}</td>
                                        <td>
                                            <a target="_blank"
                                                href="{{ route('konsumen.show', $item->customer->id ?? 0) }}">
                                                {{ $item->customer->name ?? 'Nama Belum Set' }}
                                            </a>
                                        </td>
                                        <td>{{ number_format($item->total_sales) }}</td>
                                        <td>{{ number_format($item->retur) }}</td>
                                        <td>{{ number_format($item->payment_remain) }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    id="dropdown-default-primary" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                                    <a class="dropdown-item"
                                                        href="{{ route('retur_penjualan.create', ['salesid' => $item->id]) }}">Retur Faktur ini</a>
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
