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
        <div class="row">
            <!-- Dynamic Table with Export Buttons -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <div class="float-start">
                        <h3 class="block-title">Pengeluaran</h3>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('pengeluaran.create') }}" class="btn btn-info block-title">Buat Pengeluaran</a>
                    </div>

                </div>
                <div class="block-content block-content-full">
                    <div class ="table-responsive"><table class="table table-hover table-vcenter js-table-sections js-dataTable-buttons">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Nomor Bukti</th>
                                <th>Pengeluaran</th>
                                <th>Jumlah</th>
                                <th>Kategori</th>
                                <th>Catatan</th>
                                <th></th>
                            </tr>
                        </thead>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d-m-Y H:i:s', strtotime($item->tanggal)) }}</td>
                                <td>{{ $item->no_bukti }}</td>
                                <td>
                                    @if ($item->user != null)
                                        {{ $item->user->displayName }}
                                    @elseif ($item->inventoris != null)
                                        {{ $item->inventoris->name }}
                                    @elseif ($item->itemstock != null)
                                        {{ $item->itemstock->name }}
                                    @endif
                                </td>
                                <td>{{ number_format($item->jumlah) }}</td>
                                <td>{{ $item->category->name?? "-" }}</td>
                                <td>{{ $item->detail }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                            <a class="dropdown-item"
                                                href="{{ route('pengeluaran.edit', $item->id) }}">Edit</a>
                                            <a class="dropdown-item delete">Delete</a>
                                            <form autocomplete="off" action="{{ route('pengeluaran.destroy', $item->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table></div>



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
