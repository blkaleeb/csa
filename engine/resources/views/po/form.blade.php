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
            <h3 class="block-title">{{$is_edit?"Ubah":"Tambah"}} Purchase Order</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12 space-y-5">
                    <!-- Form Horizontal - Default Style -->
                    <form autocomplete="off" class="space-y-4 form-header" action="{{$is_edit?route('po.update',$data->id):'#'}}"
                        method="POST" novalidate>
                        @csrf
                        @if($is_edit)
                        @method('put')
                        <input type="hidden" name="update_header" value="1">
                        @endif
                        <div class="row mb-4">
                            <div class="col-lg-12">
                                <label class="form-label" for="example-ltf-email">Supplier</label>
                                <select name="supplier_id" id="" class="supplier form-control select2-modal">
                                    <option value="" disabled @if(!$is_edit) selected @endif>Pilih Supplier</option>

                                    @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}" @if($is_edit && $data->supplier_id ==
                                        $supplier->id) selected @endif data-item="{{$supplier}}">
                                        {{$supplier->supplier_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <div class="">
                                    <label class="form-label" for="example-ltf-email">Alamat</label>
                                    <p id="supplier_address" style="margin: unset !important">
                                        {{$data->supplier->supplier_address ?? ""}} </p>
                                </div>
                            </div>
                        </div>

                        @if($is_edit)
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-info form-control">Update</button>
                            </div>
                        </div>
                        @endif
                    </form>
                    <!-- END Form Horizontal - Default Style -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Form -->
</div>

@if($is_edit)
<div class="content w-100">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Ubah Barang</h3>
        </div>
        <div class="block-content block-content-full">
            <div class ="table-responsive"><table class="table table-hover table-vcenter js-dataTable-buttons js-table-sections ">
                <thead>
                    <th>No</th>
                    <th>Barang</th>
                    <th>QTY Beli</th>
                    <th>Gudang</th>
                    <th></th>
                </thead>
                <tbody class="fs-sm">
                    @foreach ($data->line as $line)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td>{{ $line->stock->name ?? "-" }}</td>
                        <td>{{ $line->qty." ".$line->stock->satuan->name}}</td>
                        <td>{{ $line->warehouse->warehouse_name}}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle"
                                    id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                    <a class="dropdown-item" href="{{route('po_line.edit',$line->id)}}">Edit</a>
                                    <a class="dropdown-item delete">Void</a>
                                    <form autocomplete="off" action="{{route('po_line.destroy',$line->id)}}" method="post">
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
</div>
<div class="content w-100">
    <!-- Form -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Tambah Barang PO</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12 ">
                    <hr>
                    <form autocomplete="off" class="form-barang">
                        @csrf
                        @if($is_edit)
                        @method('put')
                        <input type="hidden" name="update_line" value="1">
                        @endif
                        <div class="row space-y-5">
                            <div class="col-lg-12">
                                <div class="row mb-4">
                                    <div class="col-xl-12">
                                        <label class="form-label" for="">Barang:</label>
                                        <select class="stock select2-modal form-control" style="width: 100%">
                                            <option value="" disabled selected>Pilih Barang</option>
                                            @foreach ($stock as $item)
                                            <option value="{{$item->id}}" data-item="{{$item}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-xl-12">
                                        <label class="form-label" for="">Gudang:</label>
                                        <select class="warehouse select2-modal form-control" style="width: 100%">
                                            <option value="" disabled selected>Pilih Gudang</option>
                                            @foreach ($warehouses as $item)
                                            <option value="{{$item->id}}" data-item="{{$item}}">
                                                {{$item->warehouse_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-xl-6">
                                        <label class="form-label" for="">QTY Beli:</label>
                                        <input autocomplete="off" min="1" type="number" class="quantity form-control"
                                            value="1">
                                    </div>
                                    <div class="col-xl-6">
                                        <label class="form-label" for="">Bonus?</label>
                                        <select class="bonus select2-modal form-control" style="width: 100%">
                                            <option value="0" selected>Tidak</option>
                                            <option value="1">Iya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <input autocomplete="off" type="button" onclick="addToCart()"
                                        class="form-control btn btn-info dis" value="Tambah Barang">
                                </div>
                                <input autocomplete="off" type="hidden" id="pur">
                                <input autocomplete="off" type="hidden" id="gtx">

                            </div>
                            <div class="col-lg-12" style="overflow-y: auto; max-height: 500px">
                                <div>
                                    <table id="" class="table table-bordered table-stripped">
                                        <thead>
                                            <th>No</th>
                                            <th>Barang</th>
                                            <th>QTY Beli</th>
                                            <th>Gudang</th>
                                            <th></th>
                                        </thead>
                                        <tbody class="cart">

                                        </tbody>
                                    </table></div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="total_sales" id="total_sales">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@else
<div class="content w-100">
    <!-- Form -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{$is_edit?"Ubah":"Tambah"}} Barang PO</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12 ">
                    <hr>
                    <form autocomplete="off" class="form-barang">
                        <div class="row space-y-5">
                            <div class="col-lg-12">
                                <div class="row mb-4">
                                    <div class="col-xl-12">
                                        <label class="form-label" for="">Barang:</label>
                                        <select class="stock select2-modal form-control" style="width: 100%">
                                            <option value="" disabled selected>Pilih Barang</option>
                                            @foreach ($stock as $item)
                                            <option value="{{$item->id}}" data-item="{{$item}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-xl-12">
                                        <label class="form-label" for="">Gudang:</label>
                                        <select class="warehouse select2-modal form-control" style="width: 100%">
                                            <option value="" disabled selected>Pilih Gudang</option>
                                            @foreach ($warehouses as $item)
                                            <option value="{{$item->id}}" data-item="{{$item}}">
                                                {{$item->warehouse_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-xl-6">
                                        <label class="form-label" for="">QTY Beli:</label>
                                        <input autocomplete="off" min="1" type="number" class="quantity form-control"
                                            value="1">
                                    </div>
                                    <div class="col-xl-6">
                                        <label class="form-label" for="">Bonus?</label>
                                        <select class="bonus select2-modal form-control" style="width: 100%">
                                            <option value="0" selected>Tidak</option>
                                            <option value="1">Iya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <input autocomplete="off" type="button" onclick="addToCart()"
                                        class="form-control btn btn-info dis" value="Tambah Barang">
                                </div>
                                <input autocomplete="off" type="hidden" id="pur">
                                <input autocomplete="off" type="hidden" id="gtx">

                            </div>
                            <div class="col-lg-12" style="overflow-y: auto; max-height: 500px">
                                <div>
                                    <table id="" class="table table-bordered table-stripped">
                                        <thead>
                                            <th>No</th>
                                            <th>Barang</th>
                                            <th>QTY Beli</th>
                                            <th>Gudang</th>
                                            <th></th>
                                        </thead>
                                        <tbody class="cart">

                                        </tbody>
                                    </table></div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="total_sales" id="total_sales">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="content w-100">
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="row mb-2 mt-3">
                    <div class="col-xl-12">
                        <button type="button" onclick="submitPoOrder(0)" class="btn btn-info form-control"
                            id="btn_simpan">Simpan</button>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-xl-12">
                        <button type="button" onclick="submitPoOrder(1)" class="btn btn-info form-control"
                            id="btn_simpan_cetak">Simpan &
                            Cetak</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->
@endsection

@include("includes.modal.riwayat")
@push("js")
<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>

<script>
    $(document).ready(function () {
		$(window).keydown(function (event) {
			if (event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
        // $(".gtText").show();
        // $(".history").show();
        // calcGrandTotal();
	});
    One.helpersOnLoad([
        'js-flatpickr',
        'jq-datepicker',
        // 'jq-maxlength',
        'jq-select2-modal',
        // 'jq-masked-inputs',
        // 'jq-rangeslider',
        // 'jq-colorpicker'
]);

$(".supplier").change(function(){
    var supplier = $(this).find('option').filter(':selected');
    var datasupplier = supplier.data("item");
    var datasales = supplier.data("sales");
    $("#supplier_address").text(datasupplier.supplier_address)
})

// $(".stock").change(function(){
//     var stock = $(this).find('option').filter(':selected');
//     var datastock = stock.data("item");
//     console.log(datastock)
//     $(".quantity").attr("min",1);
//     $(".quantity").attr("max",datastock.qty);
//     $(".harga_satuan").val(datastock.sell_price)
//     $(".harga_modal").val(datastock.purchase_price)
//     $(".diskon").val()

//     $(".harga_satuan").attr("data-min",datastock.bottom_sell_price)
//     $(".harga_satuan").attr("data-max",datastock.top_sell_price)
// })

// $(".harga_satuan").change(function(){
//     var price = $(this)
//     if(price.val() <= price.data("min")){
//         toastr.error("Harga dibawah batas harga jual");
//     }
//     if(price.val() > price.data("max")){
//         toastr.error("Harga diatas batas harga jual");

//     }
// })

function addToCart(){

    var stock = $('.stock').find('option').filter(':selected');
    var datastock = stock.data("item");
    var warehouse = $('.warehouse').find('option').filter(':selected');
    var datawarehouse = warehouse.data("item");
    var table = $(".cart")
    var count = table.find("tr"); //sudah isi berapa sebelum append
    var bonus = $('.bonus').find('option').filter(':selected').val();
    var htmlbonus = '';
    console.log(bonus);
    if(bonus === "1"){
        htmlbonus = `(Bonus)`;
    }
    console.log(count.length);
    // count.length <8 &&
    if($(".quantity").val() != 0 && warehouse.val()!=""){ // 8 barang 1 faktur, iya "<"
    // ////////////////////////////////////////////////////////////////////////
    subtotal = Math.floor($(".quantity").val() * ($(".harga_satuan").val() * ((100-$(".diskon").val())/100)))
    diskon = Math.floor($(".quantity").val() * $(".harga_satuan").val()-subtotal)
    // ////////////////////////////////////////////////////////////////////////
    var no = `<td class="number">1</td>`
    var bonushidden = `<input type='hidden' value='`+bonus+`' name='bonus[]''>`
    var kodebarang = `<td>`+bonushidden+`<input type='hidden' value='`+datastock.id+`' name="item_stock_id[]">`+datastock.name+htmlbonus+`</td>`
    var qty = `<td><input type='hidden' value='`+$(".quantity").val()+`' name="quantity[]">`+$(".quantity").val()+` `+datastock.satuan.name+`</td>`
    var gudang =  `<td><input type='hidden' value='`+datawarehouse.id+`' name="warehouse_id[]">`+datawarehouse.warehouse_name+`</td>`
    var option = `<td><button type='button' class='btn btn-sm btn-danger void'>Void</button></td>`
    table.append(`<tr>`+no+kodebarang+qty+gudang+option+`</tr>`);
    updateRowOrder();
    }else{
        toastr.error("Limit 1 Faktur, 8 barang");
    }
    // ////////////////////////////////////////////////////////////////////////
$(".void").click(remove);
// ////////////////////////////////////////////////////////////////////////
    var selectstock = $(".stock").select2-modal();
    selectstock.select2-modal('open');
    $(".select2-modal-search__field").focus();
    $(".quantity").val(1)
}

$(window).keydown(function (event) {
    if (event.keyCode == 13) {
        addToCart();
    }
});

function remove(){
    //parent 1 td
    //parent 2 tr
    $(this).parent().parent().remove();
    updateRowOrder();
}

function submitPoOrder(print){
    var formheader = $(".form-header").serialize();
    var formbarang = $(".form-barang").serialize();
    @if($is_edit)
    var param = formbarang
    var method = "put"
    $.ajax({
    method: method,
    url: "{{url('po').'/'.$data->id}}",
    data: param
    })
    .done(function( msg ) {
    toastr.success("Success");
        // location.reload(true)
    }).fail(function (msg){
    toastr.error("Error");
    });
    @else
    var param = formheader+"&"+formbarang
    var method ="post"
    $.ajax({
    method: method,
    url: "{{route('po.store')}}",
    data: param
    })
    .done(function( msg ) {
    toastr.success("Success");
        location.reload(true)
    }).fail(function (msg){
    toastr.error("Error");
    });
    @endif

}
</script>
@endpush
