@extends('layouts.master-sidebar')
@push('css')
    <!-- Stylesheets -->
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endpush
@section('content')
    @include('includes.modal.form')
    <!-- Page Content -->
    <div class="">
        <div class="row">
            <!-- Dynamic Table with Export Buttons -->
            <div class="block block-rounded">
                <div class="block-header block-header-default justify-content-between">
                    <div class="float-start">
                        <h3 class="block-title">Daftar Piutang {{ isset($void) ? 'Dihapus Sejak' : '' }}
                            {{ $date_start->format('d F Y') }} - {{ $date_end->format('d F Y') }}</h3>
                    </div>
                    <div class="float-end">
                        <button type="button" class="btn btn-info form-modal-btn" data-bs-target="#formModal"
                            data-url="{{ route('daftar-piutang.create') }}">
                            Buat Faktur
                        </button>

                        {{-- <a href="{{ route('daftar-piutang.create') }}" class="btn btn-info">Buat Faktur</a> --}}
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <div class="table-responsive" style="min-height: 50vh">
                        <table class="table table-hover table-vcenter js-dataTable-buttons">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No Faktur</th>
                                    @if (isset($void))
                                        <th>Tanggal Hapus</th>
                                    @endif
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
                                                        href="{{ route('laporan-penjualan.create', ['salesid' => $item->id]) }}">Bayar</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('retur-penjualan.create', ['salesid' => $item->id]) }}">Buat
                                                        Retur
                                                        Faktur</a>

                                                    <a class="dropdown-item"
                                                        href="{{ route('daftar-piutang.edit', $item->id) }}">Edit</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('daftar-piutang.print', $item->id) }}">Print</a>
                                                    <a class="dropdown-item delete">Void</a>
                                                    <form autocomplete="off"
                                                        action="{{ route('daftar-piutang.destroy', $item->id) }}"
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
                        </table>
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
            <!-- END Dynamic Table with Export Buttons -->
        </div>
    </div>
    <!-- END Page Content -->
    {{-- @include("daftar-piutang.modal") --}}
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
    <script src="{{ asset('assets/js/pages/be_tables_datatables.min.js?v=' . time()) }}"></script>
    <script>
        One.helpersOnLoad(['one-table-tools-checkable', 'one-table-tools-sections']);
    </script>
@endpush
