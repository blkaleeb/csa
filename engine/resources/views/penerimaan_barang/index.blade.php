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
<div class="content w-100">
    <div class="row">
        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="float-start">
                    <h3 class="block-title">Daftar Penerimaan Barang</h3>
                </div>
                <div class="float-end">
                    <!-- <a href="{{route('po.create')}}" class="btn btn-info block-title">Buat PO</a> -->
                </div>

            </div>
            <br>
            <form autocomplete="off" action="{{route('penerimaan_barang.index')}}">
            <div class="row">
                <div class="col-lg-6">
                    <input type="hidden" id="datahref">
                        <label class="form-label" for="due_date">Dari</label>
                        <input type="text" class="js-flatpickr form-control dstart" id="example-flatpickr-custom" name="date_start" placeholder="hari-bulan-tahun"  data-date-format="d F Y" value="{{ $date_start->format('d F Y') }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="due_date">Sampai</label>
                            <input type="text" class="js-flatpickr form-control dend" id="example-flatpickr-custom" name="date_end" placeholder="hari-bulan-tahun" data-date-format="d F Y" value="{{ $date_end->format('d F Y') }}">
                        </div>
                    <div class="col-lg-12 mt-3">
                        <button type="submit" class="btn btn-info form-control" id="btnfilter">Filter</button>
                    </div>
                </div>
                <br>
            </form>
            <div class="block-content block-content-full table-responsive">
                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter js-dataTable-buttons no-footer">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>No PO</th>
                            <th>Supplier</th>
                            <th>Jumlah Item</th>
                            <th>Tanggal Diterima</th>
                            <th>Lokasi Gudang</th>
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
                            <td>{{ number_format($item->totalqty) }}</td>
                            <td>@if($item->rencana_diterima !== NULL)
                                {{ date("d-m-Y",strtotime($item->rencana_diterima)) }}
                            @endif</td>
                            <td>{{$item->warehouse_name}}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item"  href="javascript:void(0)" id="penerimaanbtn" data-attr="{{ route('penerimaan_barang.show', $item->id) }}">Penerimaan</a>
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

        <!-- Large Block Modal -->
        <div class="modal" id="modal-penerimaan" role="dialog" aria-labelledby="modal-penerimaan" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content modal-body">
                <div class="block block-rounded block-transparent mb-0">

                  <div class="block-header block-header-default">

                    <h3 class="block-title">Tambah Penerimaan Barang</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="block-content fs-sm" id="smallBody">
                  <div class="block block-rounded">
                        <div class="block-content block-content-full">
                            <div class="row">
                                <div class="col-lg-12 space-y-5">
                                <form autocomplete="off" action="{{route('penerimaan_barang.store') }}"
                                        method="post" class="space-y-4">
                                        @csrf
                                        <div class="row gtText">
                                            <label class="col-sm-1 col-form-label" for="example-hf-email">Total</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="grand_total1" readonly="">
                                            </div>
                                            <label class="col-sm-1 col-form-label" for="example-hf-email"></label>
                                            <label class="col-sm-2 col-form-label" for="example-hf-email">Total dengan PPN</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="grand_total_ppn1" readonly="">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="example-hf-email">Supplier</label>
                                            <div class="col-sm-8">
                                                <input autocomplete="off" type="text" disabled maxlength="15" id="supplier" name="supplier"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="example-hf-email">Nomor Faktur Supplier</label>
                                            <div class="col-sm-8">
                                                <input autocomplete="off" type="text" maxlength="15" id="supplier_invoice_no"
                                                    name="supplier_invoice_no" class="form-control" required="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="example-hf-email">PO No</label>
                                            <div class="col-sm-8">
                                                <input autocomplete="off" type="text" readonly maxlength="15" id="no_po" name="no_po"
                                                    class="form-control" >
                                                <input autocomplete="off" type="hidden" maxlength="15" id="po_header_id" name="po_header_id"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="example-hf-email">Tanggal Faktur</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="js-flatpickr form-control" id="invoice_date"
                                                name="invoice_date" placeholder="hari-bulan-tahun"
                                                 data-date-format="d-m-Y">

                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="example-hf-email">Tanggal diterima</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="js-flatpickr form-control" id="rencana_diterima"
                                                name="rencana_diterima" placeholder="hari-bulan-tahun"
                                                 data-date-format="d-m-Y">

                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="example-hf-email">PPN</label>
                                            <div class="col-sm-8">
                                                <input autocomplete="off" type="number" min="0" id="ppn" name="ppn" class="form-control" required=""
                                                     onchange="calculatetotal()" value="0">
                                            </div>
                                        </div>
                                        <div class="row d-none">
                                            <label class="col-sm-4 col-form-label" for="example-hf-email">Nilai Faktur</label>
                                            <div class="col-sm-8">
                                                <input autocomplete="off" type="text" id="nilaifak" maxlength="10" name="invoice_total"
                                                    value="0" class="form-control nilaifak" required="">
                                            </div>

                                            <input type="hidden" name="count" id="count" class="count" >
                                        </div>
                                        <div id="daftar-id"></div>
                                        <div class="col-md-12 box">
                                            <div class="col-md-12" id="bbb">
                                                <table id="" class="table table-bordered table-stripped">
                                                    <thead>
                                                        <th width="2%">No.</th>
                                                        <th width="20%">Barang</th>
                                                        <th width="10%">QTY Buy</th>
                                                        <th width="10%">QTY Get</th>
                                                        <th width="15%">Harga Beli per Barang</th>
                                                        <th width="15%">Harga Jual per Barang</th>
                                                        <th width="10%">Gudang</th>

                                                    </thead>
                                                    <tbody id="tbl-penerimaan">
                                                    </tbody>
                                                </table></div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <input autocomplete="off" type="submit" class="btn btn-info"
                                            value="Buat Penerimaan">
                                        </div>
                                    </form>
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

          <div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="border: lightgray 1px solid">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Diskon</h5>
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Input Diskon (Pisahkan dengan Koma, tanpa
                                persen(5,10,20))</label>
                            <input type="text" class="form-control" id="diskonpenerimaan">
                            <input type="hidden" id="targetDiskon">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-alt-secondary me-1" id="closediskon" data-bs-dismiss="modal">Hitung
                            Diskon</button>
                    </div>
                </div>
            </div>
        </div>
<script>
    function calculatetotal() {
        $("#nilaifak").val(0);
        $("#grand_total1").val(0);
        var gt = 0;
        var ppn = $('#ppn').val() / 100;
        var gtppn = 0;
        for (var i = 1; i <= $(".count").val(); i++) {
            var harga = $("#pp-get-" + i).val();
            var qty = $("#qty-get-" + i).val();
            gt += parseInt(harga) * parseInt(qty);
        }
        gtppn = gt + (gt * ppn)

        $("#nilaifak").val(gtppn);
        $("#grand_total1").val(gt);
        $('#grand_total_ppn1').val(gtppn);
    }

    function day(tgl){
        const yyyy = tgl.getFullYear();
        let mm = tgl.getMonth() + 1;
        let dd = tgl.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        return dd + '-' + mm + '-' + yyyy;
    }

    //display modal diskon
    $(document).on('click', '.btndiskon', function(event) {
        var recipient = $(this).data('whatever');
        var modal = $('#exampleModal');
        modal.find('.modal-body #targetDiskon').val(recipient)
        $('#exampleModal').modal("show");
    });

    $(document).on('click', '#closediskon', function(event) {
        var id = $("#targetDiskon").val();
        var arraydiskon = $("#diskonpenerimaan").val();
        arraydiskon = arraydiskon.split(",");
        var price = parseInt($("#"+id).val())
        for (let i = 0; i < arraydiskon.length; ++i) {
            var diskon = parseInt(arraydiskon[i]);
            price = price - (price* arraydiskon[i]/100);
        }
        price = Math.floor(price);
        $("#"+id).val(price).change();
    });

    //display modal penerimaan
    $(document).on('click', '#penerimaanbtn', function(event) {
        event.preventDefault();
        let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                // return the result
                success: function(result) {
                    var obj = JSON.parse(result);
                    var data = obj.data;
                    var line = obj.detail;
                    var warehouse = obj.warehouses;

                    var tglrencanadtg = new Date(data['rencana_diterima']);
                    var tglnow = new Date();

                    $("#supplier").val(obj.data2[0]['supplier_name']);
                    $("#no_po").val(data['po_no']);
                    $("#po_header_id").val(data['id']);
                    $("#invoice_date").val(day(tglnow));
                    $("#rencana_diterima").val(day(tglrencanadtg));
                    if(day(tglrencanadtg) == 'NaN-NaN-NaN'){
                        $("#rencana_diterima").val('');
                    }
                    if(day(tglrencanadtg) == 'NULL'){
                        $("#rencana_diterima").val('');
                    }
                    if(day(tglrencanadtg) == '01-01-1970'){
                        $("#rencana_diterima").val('');
                    }

                    var htmldetail = '';
                    var htmlid = '';
                    var totaldetail = 0;
                    for (let i = 0; i < line.length; i++) {
                    // console.log(line[i]);

                        htmldetail += '<tr><td>'+(i+1)+'</td>';
                        htmldetail += '<td>'+line[i]['barang']+'</td>';
                        htmldetail += '<td>'+line[i]['qty']+' '+line[i]['satuan_name']+'</td>';
                        htmldetail += '<td><input type="number" onchange="calculatetotal()" name="qty[]" id="qty-get-'+(i+1)+'" max="'+(line[i]['qty']-line[i]['qty_get'])+'" value="'+(line[i]['qty']-line[i]['qty_get'])+'" style="width:50%" ';
                        if((line[i]['qty']-line[i]['qty_get']) == 0){
                            htmldetail += ' disabled';
                        }
                        htmldetail += '> '+line[i]['satuan_name']+'</td>';

                        htmldetail += '<td><input type="number" value="'+line[i]['purchase_price']+'" onchange="calculatetotal()" name="harga_beli[]" class="form-control" id="pp-get-'+(i+1)+'" ';
                        if((line[i]['qty']-line[i]['qty_get']) == 0){
                            htmldetail += ' disabled';
                        }
                        htmldetail += '  ><button type="button" data-toggle="modal"class="btn btn-primary btndiskon" data-whatever="pp-get-'+(i+1)+'" data-name="'+line[i]['barang']+'" ';
                        if((line[i]['qty']-line[i]['qty_get']) == 0){
                            htmldetail += ' disabled';
                        }
                        htmldetail += ' >Diskon</button></td>';

                        htmldetail += '<td><input type="number" value="'+line[i]['sell_price']+'" class="form-control" name="harga_jual[]" id="sp-get-'+(i+1)+'" ';
                        if((line[i]['qty']-line[i]['qty_get']) == 0){
                            htmldetail += ' disabled';
                        }
                        htmldetail += ' ></td>';

                        htmldetail += '<td><select class="form-control" style="width:100%" name="gudang[]" id="gudang-get-'+(i+1)+'" ';
                        if((line[i]['qty']-line[i]['qty_get']) == 0){
                            htmldetail += ' disabled';
                        }
                        htmldetail += '>';
                        for (let j = 0; j < warehouse.length; j++) {
                            htmldetail += '<option value="'+warehouse[j]['id']+'">'+warehouse[j]['warehouse_name']+'</option> ';
                        }
                        htmldetail += '</select> ';

                        htmldetail += '<input type="hidden" name="line[]" id="data-line-id-'+(i+1)+'" value="'+line[i]['id']+'" ';
                        if((line[i]['qty'])-(line[i]['qty_get']) == 0){
                            htmldetail += ' disabled';
                        }
                        htmldetail += ' >';
                        htmldetail += '</td></tr>';
                        $('#count').val(i+1);
                    }
                    $("#tbl-penerimaan").html(htmldetail);
                    // $("#daftar-id").html(htmlid);

                    $('#modal-penerimaan').modal("show");
                    calculatetotal();
                }
            });
    });
</script>
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
