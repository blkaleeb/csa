@extends('layouts.master-sidebar')
@push("css")
<link rel="stylesheet" href="{{asset('assets/js/plugins/flatpickr/flatpickr.min.css')}}">
@endpush
@section('content')
<!-- Page Content -->
<div class="content w-100">
    <!-- Form -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{$is_edit?"Ubah":"Tambah"}} Penerimaan</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12 space-y-5">
                    <form autocomplete="off" action="{{$is_edit ?route('penerimaan.update',$data->id): route('penerimaan.store') }}"
                        method="post" class="space-y-4">
                        @csrf
                        @if($is_edit)
                        @method("put")
                        @endif
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Supplier</label>
                            <div class="col-sm-8">
                                <input autocomplete="off" type="text" disabled maxlength="15" id="var1" name=""
                                    class="form-control"
                                    value="{{$data->supplier->supplier_name ?? $data->poheader->supplier->supplier_name}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Nomor Faktur Supplier:</label>
                            <div class="col-sm-8">
                                <input autocomplete="off" type="text" maxlength="15" id="var1"
                                    name="supplier_invoice_no" class="form-control" required=""
                                    value={{$data->supplier_invoice_no ?? ""}}>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">PO No</label>
                            <div class="col-sm-8">
                                <input autocomplete="off" type="text" readonly maxlength="15" id="var1" name=""
                                    class="form-control" value="{{$data->po_no ?? $data->poheader->po_no}}">
                                <input autocomplete="off" type="hidden" maxlength="15" id="var1" name="po_header_id"
                                    class="form-control" value="{{$is_edit ? $data->poheader->id :$data->id}}">
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Tanggal Faktur:</label>
                            <div class="col-sm-8">
                                <input type="text" class="js-flatpickr form-control" id="example-flatpickr-custom"
                                name="invoice_date" placeholder="hari-bulan-tahun"
                                value="{{\Carbon\Carbon::parse($is_edit?$data->invoice_date:date('Y-m-d'))->format('d-m-Y');}}" data-date-format="d-m-Y">

                            </div>
                        </div>

                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Tanggal diterima</label>
                            <div class="col-sm-8">
                                <input type="text" class="js-flatpickr form-control" id="rencana_diterima"
                                name="rencana_diterima" placeholder="hari-bulan-tahun"
                                data-date-format="d-m-Y"  value="{{\Carbon\Carbon::parse($is_edit?$data->rencana_datang:date('Y-m-d'))->format('d-m-Y');}}">

                            </div>
                        </div>
                                        
                        <div class="row">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">PPN:</label>
                            <div class="col-sm-8">
                                <input autocomplete="off" type="number" min="0" id="ppn" name="ppn" class="form-control" required=""
                                    value="{{$data->ppn ?? 0}}" onchange="calculatetotal()">
                            </div>
                        </div>
                        <div class="row d-none">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Nilai Faktur:</label>
                            <div class="col-sm-8">
                                <input autocomplete="off" type="text" id="nilaifak" maxlength="10" name="invoice_total"
                                    value="0" class="form-control nilaifak" required="">
                            </div>
                           
                            <input type="hidden" name="count" id="count" class="count" value="{{count($data->line)}}">
                        </div>

                        <div class="col-md-12 box">
                            <div class="col-md-12" id="bbb">
                                <table id="" class="table table-bordered table-stripped">
                                    <thead>
                                        <th>No.</th>
                                        <th>Barang</th>
                                        <th>QTY Buy</th>
                                        <th>QTY Get</th>
                                        <th>Harga Beli per Barang</th>
                                        <th>Harga Jual per Barang</th>
                                        <th>Gudang</th>

                                    </thead>
                                    @if($is_edit)
                                    <tbody id="myTable">
                                        @foreach($data->line as $key)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$key->poline->stock->name ?? "-"}}</td>
                                            <td>{{$key->poline->qty}} {{$key->poline->stock->satuan->name ?? "-"}}</td>
                                            <td><input type="number" onchange="calculatetotal()" name="qty[]" class="form-control"
                                                    id="qty-get-{{$loop->iteration}}"
                                                    min="0"
                                                    max="{{$key->poline->qty}}"
                                                    value="{{$key->qty}}" style="width:50%">
                                                {{$key->poline->stock->satuan->name ?? "-"}}</td>
                                            <td><input type="number" value="{{$key->price_per_satuan_id?? 0}}" class="form-control"
                                                    onchange="calculatetotal()" name="harga_beli[]"
                                                    id="pp-get-{{$loop->iteration}}" >
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal"
                                                    data-name="{{$key->poline->stock->name}}"
                                                    data-whatever="pp-get-{{$loop->iteration}}">Diskon</button>
                                            </td>
                                            <td><input type="number" value="{{$key->sell_per_satuan_id?? 0}}" class="form-control"
                                                    name="harga_jual[]" id="sp-get-{{$loop->iteration}}">
                                            </td>
                                            <td>
                                                <select class="warehouse form-control" style="width:100%"
                                                    name="gudang[]" id="gudang-get-{{$loop->iteration}}">
                                                    @foreach($warehouses as $warehouse)
                                                    <option value="{{$warehouse->id}}" @if($warehouse->id ==
                                                        $key->warehouse_id)selected @endif>
                                                        {{$warehouse->warehouse_name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                        </tr>
                                        <input type="hidden" name="line[]" id="data-line-id-{{$loop->iteration}}"
                                            value="{{$key->id}}">
                                        @endforeach
                                    </tbody>
                                    @else
                                    <tbody id="myTable">
                                        @foreach($data->line as $key)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$key->stock->name ?? "-"}}</td>
                                            <td>{{$key->qty}} {{$key->stock->satuan->name}}</td>
                                            <td><input type="number" onchange="calculatetotal()" name="qty[]"
                                                    id="qty-get-{{$loop->iteration}}"
                                                    max="{{$key->qty - $key->qty_get}}"
                                                    value="{{$key->qty - $key->qty_get}}" style="width:50%"
                                                    @if($key->qty - $key->qty_get == 0) disabled @endif >
                                                {{$key->stock->satuan->name}}</td>
                                            <td><input type="number" value="{{$key->stock->purchase_price?? 0}}"
                                                    onchange="calculatetotal()" name="harga_beli[]" class="form-control"
                                                    id="pp-get-{{$loop->iteration}}" @if($key->qty - $key->qty_get == 0)
                                                disabled @endif >
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal" data-name="{{$key->stock->name}}"
                                                    data-whatever="pp-get-{{$loop->iteration}}" @if($key->qty -
                                                    $key->qty_get == 0) disabled @endif >Diskon</button>
                                            </td>
                                            <td><input type="number" value="{{$key->stock->sell_price?? 0}}" class="form-control"
                                                    name="harga_jual[]" id="sp-get-{{$loop->iteration}}" @if($key->qty -
                                                $key->qty_get == 0) disabled @endif >
                                            </td>
                                            <td>
                                                <select class="warehouse form-control" style="width:100%"
                                                    name="gudang[]" id="gudang-get-{{$loop->iteration}}" @if($key->qty -
                                                    $key->qty_get == 0) disabled @endif >
                                                    @foreach($warehouses as $warehouse)
                                                    <option value="{{$warehouse->id}}" @if($warehouse->id ==
                                                        $key->warehouse->id)selected @endif>
                                                        {{$warehouse->warehouse_name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                        </tr>
                                        <input type="hidden" name="line[]" id="data-line-id-{{$loop->iteration}}"
                                            value="{{$key->id}}" @if($key->qty - $key->qty_get == 0) disabled @endif>
                                        @endforeach
                                    </tbody>
                                    @endif
                                </table></div>

                            </div>
                        </div>
                        <div class="row">
                            <input autocomplete="off" type="submit" class="btn btn-info"
                            value="{{$is_edit?" Edit Penerimaan":"Buat Penerimaan"}}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Form -->
</div>
<!-- END Page Content -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Hitung
                    Diskon</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push("js")
<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        calculatetotal();
        $(".gtText").show();
        $(".gtPPNText").show();
    })
    One.helpersOnLoad([
        'js-flatpickr',
        // 'jq-datepicker',
        // 'jq-maxlength',
        // 'jq-select2',
        // 'jq-masked-inputs',
        // 'jq-rangeslider',
        // 'jq-colorpicker'
]);
function calculatetotal() {
    $("#nilaifak").val(0);
    $("#grand_total").val(0);
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
    $("#grand_total").val(gt);
    $('#grand_total_ppn').val(gtppn)
}

 $('.warehouse').select2({
    selectOnClose: true,
    placeholder: 'Pilih Gudang',
    ajax: {
        url: "{!! url('/') !!}" + '/ajax/gudang',
        dataType: 'json',
        delay: 1000,
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.warehouse_name,
                        id: item.id
                    }
                })
            };
        },
        cache: true
    }
});
$('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var recipient = button.data('whatever') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-body #targetDiskon').val(recipient)
    })

    $('#exampleModal').on('hide.bs.modal', function (event){
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


    })
</script>

@endpush
