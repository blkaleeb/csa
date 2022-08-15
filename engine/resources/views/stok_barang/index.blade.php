@extends('layouts.master-sidebar')
@push("css")
<!-- Stylesheets -->
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="{{asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<style>
.modal-body {
    height: calc(100vh - 5em);
    overflow-x: auto;
}
</style>
@endpush
@section('content')
<!-- Page Content -->
<div class="content">
    <div class="row">
        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="float-start">
                    <h3 class="block-title">Daftar Stok Barang</h3>
                </div>
                <div class="float-end">
                    <a href="javascript:void(0)" id="tambahbtn" class="btn btn-info block-title">Tambah Stok</a>
                </div>
            </div>
            <br>
            <div class="row items-push">
            <div class="col-sm-4">
              <div class="block block-rounded h-100 mb-0" style='border: 1px solid LightGray;'>
                <div class="block-header block-header-default">
                  <h3 class="block-title">Stok Habis</h3>
                </div>
                <div class="block-content">
                  <p id="stokhabis">{{$stokhabis}}</p>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="block block-rounded h-100 mb-0" style='border: 1px solid LightGray;'>
                <div class="block-header block-header-default">
                  <h3 class="block-title">Stok Perlu Segera Beli</h3>
                </div>
                <div class="block-content">
                <p id="stokbeli">{{$stokbeli}}</p>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="block block-rounded h-100 mb-0" style='border: 1px solid LightGray;'>
                <div class="block-header block-header-default">
                  <h3 class="block-title">Stok Available</h3>
                </div>
                <div class="block-content">
                <p id="stokavail">{{$stokavail}}</p>
                </div>
              </div>
            </div>
          </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full-pagination dataTable no-footer class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter js-dataTable-buttons no-footer">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama</th>
                            <th>Satuan</th>
                            <th>Jumlah Per Satuan</th>
                            <th>Kuantitas</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Gudang</th>
                            <th>Info</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // $stokavail = 0;
                            // $stokhabis = 0;
                            // $stokbeli = 0;
                        ?>
                        @foreach($stocks as $stok)
                        <tr>
                            <td class="text-center fs-sm">{{$loop->iteration}}</td>
                            <td class="fw-semibold fs-sm">
                                <a href="{{route('stok.edit',$stok->id)}}">{{$stok->name}}</a>
                            </td>
                            <td class="fw-semibold fs-sm">
                                {{$stok->satuan->name}}
                            </td>
                            <td class="fw-semibold fs-sm">
                                {{number_format($stok->total_per_satuan)}}
                            </td>
                            <td class="fw-semibold fs-sm">
                                {{number_format($stok->qty)}}
                            </td>
                            <td class="fw-semibold fs-sm">
                                {{number_format($stok->purchase_price)}}
                            </td>
                            <td class="fw-semibold fs-sm">
                                {{number_format($stok->sell_price)}}
                            </td>
                            <td class="fw-semibold fs-sm">
                                {{$stok->gudang->warehouse_name}}
                            </td>
                            <td class="fw-semibold fs-sm">
                                @if($stok->qty > $stok->threshold_bottom)
                                <span
                                class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Stok Aman</span>
                                @elseif($stok->qty == 0)
                                <span
                                class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Stok Habis</span>
                                @else
                                <span
                                class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Segera Pembelian</span>
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
                                        <a class="dropdown-item" href="javascript:void(0)" id="detailbtn" data-attr="{{ route('stok_barang.show', $stok->id) }}">Detail</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table></div>
                {{ $stocks->links() }}
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
</div>

        <!-- Large Block Modal -->
        <div class="modal" id="modal-tambah" role="dialog" aria-labelledby="modal-tambah" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content  modal-body">
                <div class="block block-rounded block-transparent mb-0">
                  <div class="block-header block-header-default">
                    <h3 class="block-title">Tambah Stok</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="block-content fs-sm" id="smallBody">
                  <form autocomplete="off" class="space-y-4"  action="{{ route('stok_barang.store') }}"
                        method="POST">
                        @csrf
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Nama Barang</label>

                            <div class="col-sm-8 ms-auto">
                                <input autocomplete="off" type="text" id="var1" class="form-control" name="name"
                                    value="" required>
                            </div>
                        </div>
                        <div class=" row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Kategori</label>
                            <div class="col-sm-8 ms-auto">
                                <select class="form-control select-modal" name="category_id" required>
                                    <option value="" disabled>Pilih Kategori</option>
                                    @foreach($kategoris as $kategori)
                                    <option value="{{$kategori->id}}">{{$kategori->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=" row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Merk</label>
                            <div class="col-sm-8 ms-auto">
                                <select class="form-control select-modal" name="brand_id" required>
                                    <option value="" disabled>Pilih Merk</option>
                                    @foreach($merks as $merk)
                                    <option value="{{$merk->id}}">{{$merk->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Harga Beli</label>

                            <div class="col-sm-8 ms-auto">
                                <input autocomplete="off" type="number" name="purchase_price"
                                    value="0" class="form-control" required="" required>
                            </div>
                        </div>
                        <hr class="divider">
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Harga Jual</label>

                            <div class="col-sm-8 ms-auto">
                                <input autocomplete="off" type="number" name="sell_price"
                                    value="0" class="form-control" required="" required>
                            </div>
                        </div>
                        <small>isikan 0 jika tidak pakai harga bawah/atas</small>
                        <div class="row">
                            <label class="offset-sm-2 col-sm-4 col-form-label" for="example-hf-email">Harga Jual
                                Bawah</label>
                            <div class="col-sm-6 ms-auto">

                                <input autocomplete="off" type="number" name="bottom_sell_price" min="0"
                                    value="0" class="form-control"
                                    required="" required>
                            </div>
                        </div>
                        <div class="row">
                            <label class="offset-sm-2 col-sm-4 col-form-label" for="example-hf-email">Harga Jual
                                Atas</label>
                            <div class="col-sm-6 ms-auto">
                                <input autocomplete="off" type="number" name="top_sell_price" min="0"
                                    value="0" class="form-control"
                                    required="" required>
                            </div>
                        </div>
                        <hr class="divider">

                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Satuan</label>

                            <div class="col-sm-8 ms-auto">
                                <select class="form-control select-modal" name="satuan_id" required>
                                    <option value="" disabled>Pilih Merk</option>
                                    @foreach($satuan as $satu)
                                    <option value="{{$satu->id}}">{{$satu->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Jumlah per Satuan</label>

                            <div class="col-sm-8 ms-auto">
                                <input autocomplete="off" type="number" id="total_per_satuan" name="total_per_satuan" class="form-control"
                                    required="" value="" required>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">QTY</label>

                            <div class="col-sm-8 ms-auto">
                                <input autocomplete="off" type="number" id="var6" name="qty" class="form-control"
                                    required="" value="" required>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Batas Minimum Stock</label>
                            <div class="col-sm-8 ms-auto">
                                <input autocomplete="off" type="number" id="var6" name="threshold_bottom"
                                    class="form-control" required="" value="" required>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Gudang</label>
                            <div class="col-sm-8 ms-auto">
                                <select class="form-control select-modal" name="warehouse_id" required>
                                    <option value="" disabled>Pilih Merk</option>
                                    @foreach($gudangs as $gudang)
                                    <option value="{{$gudang->id}}">{{$gudang->warehouse_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 ms-auto">
                                <button type="submit" class="btn btn-primary">Simpan</button><br><br>
                            </div>
                        </div>
                    </form>
                  </div>
                  <div class="block-content block-content-full text-end bg-body">
                    <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END Large Block Modal -->

        <!-- Large Block Modal -->
        <div class="modal" id="modal-block-extra-large" tabindex="-1" role="dialog" aria-labelledby="modal-block-extra-large" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content modal-body">
                <div class="block block-rounded block-transparent mb-0">
                  <div class="block-header block-header-default">
                    <h3 class="block-title">Detail Stok Barang</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="block-content fs-sm" id="smallBody">

                        <!-- Dynamic Table with Export Buttons -->
                        <div class="block block-rounded">
                            <div class="block-header block-header-default">
                                <div class="float-start">
                                    <h3 class="block-title"><div id="titlefilter"></div></h3>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <!-- <form autocomplete="off"> -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="hidden" id="datahref">
                                        <label class="form-label" for="due_date">Dari</label>
                                        <input type="text" class="js-flatpickr form-control dstart" id="example-flatpickr-custom" name="date_start"
                                            placeholder="hari-bulan-tahun"
                                            data-date-format="d F Y">
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label" for="due_date">Sampai</label>
                                        <input type="text" class="js-flatpickr form-control dend" id="example-flatpickr-custom" name="date_end"
                                            placeholder="hari-bulan-tahun" data-date-format="d F Y">
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <button type="button" class="btn btn-info form-control" id="btnfilter">Filter</button>
                                    </div>
                                </div>
                                <!-- </form> -->
                            </div>
                        </div>
                        <!-- END Dynamic Table with Export Buttons -->
                        <div class="block block-rounded">
                            <ul class="nav nav-tabs nav-tabs-block align-items-center" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" id="btabswo-static-overview-tab" data-bs-toggle="tab"
                                        data-bs-target="#btabswo-static-overview" role="tab" aria-controls="btabswo-static-overview"
                                        aria-selected="true">Overview</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="btabswo-static-detail-tab" data-bs-toggle="tab"
                                        data-bs-target="#btabswo-static-detail" role="tab" aria-controls="btabswo-static-detail"
                                        aria-selected="false">Detail</button>
                                </li>
                                <li class="nav-item ms-auto">
                                    <div class="block-options ps-3 pe-2">
                                        <button type="button" class="btn-block-option" data-toggle="block-option"
                                            data-action="fullscreen_toggle"></button>
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle"
                                            data-action-mode="demo">
                                            <i class="si si-refresh"></i>
                                        </button>
                                        <button type="button" class="btn-block-option" data-toggle="block-option"
                                            data-action="content_toggle"></button>
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="close">
                                            <i class="si si-close"></i>
                                        </button>
                                    </div>
                                </li>
                            </ul>
                            <div class="block-content tab-content">
                                <div class="tab-pane active" id="btabswo-static-overview" role="tabpanel"
                                    aria-labelledby="btabswo-static-overview-tab">
                                    {{-- Overview --}}
                                    <div class="row">
                                        <!-- Dynamic Table with Export Buttons -->
                                        <div class="block block-rounded">
                                            <div class="block-header block-header-default">
                                                <div class="float-start">
                                                    <h3 class="block-title">Overview</h3>
                                                </div>
                                            </div>
                                            <div class="block-content block-content-full">
                                                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Pembelian</td>
                                                            <td><span id='totalpembelian'>0</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Penjualan</td>
                                                            <td><span id='totalpenjualan'>0</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Retur Pembelian</td>
                                                            <td><span id='totalreturpembelian'>0</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Retur Penjualan</td>
                                                            <td><span id='totalreturpenjualan'>0</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table></div>
                                            </div>
                                        </div>
                                        <!-- END Dynamic Table with Export Buttons -->
                                    </div>
                                </div>
                                <div class="tab-pane" id="btabswo-static-detail" role="tabpanel"
                                    aria-labelledby="btabswo-static-detail-tab">

                                    <div class="row">
                                        <!-- Dynamic Table with Export Buttons -->
                                        <div class="block block-rounded">
                                            <div class="block-header block-header-default">
                                                <div class="float-start">
                                                    <h3 class="block-title">Opname</h3>
                                                </div>
                                            </div>
                                            <div class="block-content block-content-full">
                                                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter" id="opname">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>Tanggal</th>
                                                            <th>Qty</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tblopname">

                                                    </tbody>
                                                </table></div>
                                            </div>
                                        </div>
                                        <!-- END Dynamic Table with Export Buttons -->
                                    </div>

                                    <!-- Penjualan -->
                                    <div class="row">
                                        <!-- Dynamic Table with Export Buttons -->
                                        <div class="block block-rounded">
                                            <div class="block-header block-header-default">
                                                <div class="float-start">
                                                    <h3 class="block-title">Penjualan</h3>
                                                </div>
                                            </div>
                                            <div class="block-content block-content-full">
                                                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter" id="penjualan">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>Tanggal</th>
                                                            <th>No Faktur</th>
                                                            <th>Konsumen</th>
                                                            <th>Qty</th>
                                                            <th>Harga Satuan</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tblpenjualan">

                                                    </tbody>
                                                </table></div>
                                            </div>
                                        </div>
                                        <!-- END Dynamic Table with Export Buttons -->
                                    </div>

                                    <!-- Retur Penjualan  -->
                                    <div class="row">
                                        <!-- Dynamic Table with Export Buttons -->
                                        <div class="block block-rounded">
                                            <div class="block-header block-header-default">
                                                <div class="float-start">
                                                    <h3 class="block-title">Retur Penjualan</h3>
                                                </div>
                                            </div>
                                            <div class="block-content block-content-full">
                                                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter" id="returpenjualan">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>Tanggal</th>
                                                            <th>No Faktur</th>
                                                            <th>Qty</th>
                                                            <th>Harga Satuan</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tblreturpenjualan">

                                                </tbody>
                                                </table></div>
                                            </div>
                                        </div>
                                        <!-- END Dynamic Table with Export Buttons -->
                                    </div>

                                    <!-- Pembelian -->
                                    <div class="row">
                                        <!-- Dynamic Table with Export Buttons -->
                                        <div class="block block-rounded">
                                            <div class="block-header block-header-default">
                                                <div class="float-start">
                                                    <h3 class="block-title">Pembelian</h3>
                                                </div>
                                            </div>
                                            <div class="block-content block-content-full">
                                                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter" id="pembelian">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>Tanggal</th>
                                                            <th>No Faktur</th>
                                                            <th>Qty Pesan</th>
                                                            <th>Qty Terima</th>
                                                            <th>Harga Satuan</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody  id="tblpembelian">

                                                    </tbody>
                                                </table></div>
                                            </div>
                                        </div>
                                        <!-- END Dynamic Table with Export Buttons -->
                                    </div>

                                    <!-- Retur Pembelian -->
                                    <div class="row">
                                        <!-- Dynamic Table with Export Buttons -->
                                        <div class="block block-rounded">
                                            <div class="block-header block-header-default">
                                                <div class="float-start">
                                                    <h3 class="block-title">Retur Pembelian</h3>
                                                </div>
                                            </div>
                                            <div class="block-content block-content-full">
                                                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter" id="returpembelian">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>Tanggal</th>
                                                            <th>No Faktur</th>
                                                            <th>Qty</th>
                                                            <th>Harga Satuan</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tblreturpembelian">

                                                    </tbody>
                                                </table></div>
                                            </div>
                                        </div>
                                        <!-- END Dynamic Table with Export Buttons -->
                                    </div>
                                </div>
                            </div>
                        </div>

                  </div>
                  <div class="block-content block-content-full text-end bg-body">
                    <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END Large Block Modal -->

<script>
    function day(tgl){
        const yyyy = tgl.getFullYear();
        let mm = tgl.getMonth() + 1;
        let dd = tgl.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        return dd + '-' + mm + '-' + yyyy;
    }
    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }

    // document.getElementById("stokhabis").innerHTML = <?php echo number_format($stokhabis)?>;
    // document.getElementById("stokbeli").innerHTML = <?php echo number_format($stokbeli)?>;
    // document.getElementById("stokavail").innerHTML = <?php echo number_format($stokavail)?>;

    //display modal tambah
    $(document).on('click', '#tambahbtn', function(event) {
        event.preventDefault();
        $('#modal-tambah').modal("show");
    });
    // display modal detail
    $(document).on('click', '#detailbtn', function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $("#datahref").val(href);
            var mL = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);
            var month = now.getMonth();
            var today = (day)+" "+(mL[month])+" "+now.getFullYear() ;

            $(".dstart").val("08 September 2021");
            $(".dend").val(today);

            $("#titlefilter").html("Filter "+"08 September 2021"+" - "+ today);

            tampildetail(href);
        });

        $(document).on('click', '#btnfilter', function(event) {
            event.preventDefault();
            let href = $("#datahref").val();
            $("#titlefilter").html("Filter "+$(".dstart").val()+" - "+ $(".dend").val());
            tampildetail(href);
        });

    function tampildetail(href){
            $.ajax({
                url: href+"?date_start="+$(".dstart").val()+"&date_end="+$(".dend").val(),
                // return the result
                success: function(result) {
                    $('#modal-block-extra-large').modal("show");
                    var obj = JSON.parse(result);
                    var tglstart = new Date(obj.date_start);
                    var tglend = new Date(obj.date_end);

                    var pembelian = obj.pembelian;
                    var returpembelian = obj.retur_pembelian;

                    var penjualan = obj.penjualan;
                    var returpenjualan = obj.retur_penjualan;

                    var opname = obj.opname;


                    //opname
                    var htmlopname = '';
                    for (let i = 0; i < opname.length; i++) {
                        var tgl = new Date(opname[i]['createdOn']);
                        htmlopname += '<tr><td>'+(i+1)+'</td>';
                        htmlopname += '<td>'+day(tgl)+'</td>';
                        htmlopname += '<td></td><td></td></tr>';
                    }
                    $("#tblopname").html(htmlopname);
                    // $('#opname').DataTable();

                    //pembelian
                    var htmlpembelian = '';
                    var totalpembelian = 0;
                    for (let i = 0; i < pembelian.length; i++) {
                        var tgl = new Date(pembelian[i]['createdOn']);
                        htmlpembelian += '<tr><td>'+(i+1)+'</td>';
                        htmlpembelian += '<td>'+day(tgl)+'</td>';
                        htmlpembelian += '<td>'+pembelian[i]['header']['internal_invoice_no']+' | ';
                        if(pembelian[i]['poheader']['po_no'] != ''){
                            htmlpembelian += pembelian[i]['poheader']['po_no'];
                        }else{
                            htmlpembelian += 'void';
                        }
                        htmlpembelian += '</td>';
                        htmlpembelian += '<td>'+formatNumber(pembelian[i]['poline']['qty'])+'</td>';
                        htmlpembelian += '<td>'+formatNumber(pembelian[i]['qty'])+'</td>';
                        htmlpembelian += '<td>'+formatNumber(pembelian[i]['price_per_satuan_id'])+'</td>';
                        htmlpembelian += '<td></td></tr>';
                        totalpembelian += pembelian[i]['qty'];
                    }
                    $("#totalpembelian").html(totalpembelian);
                    $("#tblpembelian").html(htmlpembelian);
                    // $('#pembelian').DataTable();

                    //retur pembelian
                    var htmlreturpembelian = '';
                    var totalreturpembelian = 0;
                    for (let i = 0; i < returpembelian.length; i++) {
                        var tgl = new Date(returpembelian[i]['createdOn']);
                        htmlreturpembelian += '<tr><td>'+(i+1)+'</td>';
                        htmlreturpembelian += '<td>'+day(tgl)+'</td>';
                        htmlreturpembelian += '<td>'+returpembelian[i]['header']['no_invoice']+'</td>';
                        htmlreturpembelian += '<td>'+formatNumber(returpembelian[i]['poline']['qty'])+'</td>';
                        htmlreturpembelian += '<td>'+formatNumber(returpembelian[i]['qty'])+'</td>';
                        htmlreturpembelian += '<td>'+formatNumber(returpembelian[i]['retur_price'])+'</td>';
                        htmlreturpembelian += '<td></td></tr>';
                        totalreturpembelian += returpembelian[i]['qty'];
                    }
                    $("#totalreturpembelian").html(totalreturpembelian);
                    $("#tblreturpembelian").html(htmlreturpembelian);
                    // $('#returpembelian').DataTable();

                    //penjualan
                    var htmlpenjualan = '';
                    var totalpenjualan = 0;
                    for (let i = 0; i < daftar-piutang.length; i++) {
                        var tgl = new Date(penjualan[i]['createdOn']);
                        htmlpenjualan += '<tr><td>'+(i+1)+'</td>';
                        htmlpenjualan += '<td>'+day(tgl)+'</td>';
                        htmlpenjualan += '<td>'+penjualan[i]['header']['intnomorsales']+'</td>';
                        // htmlpenjualan += '<td>'+penjualan[i]['header']['customer']['name']+'</td>';
                        htmlpenjualan += '<td>'+penjualan[i]['name']+'</td>';
                        htmlpenjualan += '<td>'+formatNumber(penjualan[i]['qty'])+'</td>';
                        htmlpenjualan += '<td>'+formatNumber(penjualan[i]['price_per_satuan_id'])+'</td>';
                        htmlpenjualan += '<td></td></tr>';
                        totalpenjualan += penjualan[i]['qty'];
                    }
                    $("#totalpenjualan").html(totalpenjualan);
                    $("#tblpenjualan").html(htmlpenjualan);
                    // $('#penjualan').DataTable();

                    //retur penjualan
                    var htmlreturpenjualan = '';
                    var totalreturpenjualan = 0;
                    for (let i = 0; i < returdaftar-piutang.length; i++) {
                        var tgl = new Date(returpenjualan[i]['createdOn']);
                        htmlreturpenjualan += '<tr><td>'+(i+1)+'</td>';
                        htmlreturpenjualan += '<td>'+day(tgl)+'</td>';
                        htmlreturpenjualan += '<td>';
                        if(returpenjualan[i]['header']['no_invoice'] == ''){
                            htmlreturpenjualan += 'void';
                        }else{
                            htmlreturpenjualan += returpenjualan[i]['header']['no_invoice'];
                        }
                        htmlreturpenjualan += '</td>';
                        // htmlpenjualan += '<td>'+penjualan[i]['header']['customer']['name']+'</td>';
                        htmlreturpenjualan += '<td>'+formatNumber(returpenjualan[i]['qty'])+'</td>';
                        htmlreturpenjualan += '<td>'+formatNumber(returpenjualan[i]['returprice'])+'</td>';
                        htmlreturpenjualan += '<td></td></tr>';
                        totalreturpenjualan += returpenjualan[i]['qty'];
                    }
                    $("#totalreturpenjualan").html(totalreturpenjualan);
                    $("#tblreturpenjualan").html(htmlreturpenjualan);
                    // $('#returpenjualan').DataTable();

                    // console.log(penjualan);
                    // $('#smallBody').html(result.pembelian).show();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                }
            })
    }
</script>
<!-- END Page Content -->

@endsection
@push("js")
<script>
    $(".select-modal").select2({ dropdownParent: "#modal-tambah",width: '100%' });
</script>
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
