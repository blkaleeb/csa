@extends('layouts.master-sidebar')
@push("css")
<link rel="stylesheet" href="{{asset('assets/js/plugins/flatpickr/flatpickr.min.css')}}">

@endpush
@section('content')
<div class="content w-100">
    <!-- Form -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{$is_edit?"Ubah":"Tambah"}} Penjualan</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12 ">
                    <hr>
                    <form autocomplete="off" class="form-barang" action="{{route('penjualan_line.update',$data->id)}}" method="post">
                        @csrf
                        @method("put")
                        <div class="row space-y-5">
                            <div class="col-lg-12">
                                <div class="row mb-4">
                                    <div class="col-xl-12">
                                        <label class="form-label" for="">Barang:</label>
                                        <select class="stock select2 form-control" name="item_stock_id"
                                            style="width: 100%">
                                            @foreach ($stock as $item)
                                            <option value="{{$item->id}}" @if($data->item_stock_id == $item->id)
                                                selected @endif data-item = "{{$item}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-xl-3">
                                        <label class="form-label" for="">QTY:</label>
                                        <input autocomplete="off" min="1" type="number" name="quantity"
                                            class="quantity form-control" value="{{$data->qty}}">
                                    </div>

                                    <div class="col-xl-3">
                                        <label class="form-label" for="">Harga Satuan:</label>
                                        <input autocomplete="off" type="number" name="harga_satuan"
                                            class="harga_satuan form-control" value="{{$data->price_per_satuan_id}}"
                                            data-min={{$data->stock->bottom_sell_price}}
                                        data-max={{$data->stock->top_sell_price}}>
                                    </div>

                                    <div class="col-xl-3" id="modal" style="">
                                        <label class="form-label" for="">Harga Modal:</label>
                                        <input autocomplete="off" readonly type="number"
                                            class="harga_modal form-control" name="harga_modal"
                                            value="{{$data->sales_per_satuan_id}}">
                                    </div>

                                    <div class="col-xl-3">
                                        <label class="form-label" for="">Diskon</label>
                                        <input autocomplete="off" type="number" name="discount"
                                            class="diskon form-control" value="0" min="0" value="{{$data->diskon}}">
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <input autocomplete="off" type="submit" onclick=""
                                        class="form-control btn btn-info dis" value="Ubah Barang">
                                </div>
                            </div>
                        </div>
                    </form>
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

	});
    One.helpersOnLoad([
        'js-flatpickr',
        'jq-datepicker',
        // 'jq-maxlength',
        'jq-select2',
        // 'jq-masked-inputs',
        // 'jq-rangeslider',
        // 'jq-colorpicker'
]);



$(".stock").change(function(){
    var stock = $(this).find('option').filter(':selected');
    var datastock = stock.data("item");
    console.log(datastock)
    $(".quantity").attr("min",1);
    $(".quantity").attr("max",datastock.qty);
    $(".harga_satuan").val(datastock.sell_price)
    $(".harga_modal").val(datastock.purchase_price)
    $(".diskon").val()

    $(".harga_satuan").attr("data-min",datastock.bottom_sell_price)
    $(".harga_satuan").attr("data-max",datastock.top_sell_price)
})

$(".harga_satuan").change(function(){
    var price = $(this)
    if(price.data("min")>0){
    if(price.val() <= price.data("min")){
        toastr.error("Harga dibawah batas harga jual");
    }
}
if(price.data("max")>0){

    if(price.val() > price.data("max")){
        toastr.error("Harga diatas batas harga jual");

    }
}
})

// function addToCart(){

//     var stock = $('.stock').find('option').filter(':selected');
//     var datastock = stock.data("item");
//     var table = $(".cart")
//     var count = table.find("tr"); //sudah isi berapa sebelum append
//     console.log(count.length);
//     if(count.length <8 && $(".quantity").val() != 0){ // 8 barang 1 faktur, iya "<"
//     // ////////////////////////////////////////////////////////////////////////
//     subtotal = Math.floor($(".quantity").val() * ($(".harga_satuan").val() * ((100-$(".diskon").val())/100)))
//     diskon = Math.floor($(".quantity").val() * $(".harga_satuan").val()-subtotal)
// // ////////////////////////////////////////////////////////////////////////
//     var no = `<td class="number">1</td>`
//     var kodebarang = `<td><input type='hidden' value='`+datastock.id+`' name="item_stock_id[]">`+datastock.name+`</td>`
//     var qty = `<td><input type='hidden' value='`+$(".quantity").val()+`' name="quantity[]">`+$(".quantity").val()+`</td>`
//     var modal = `<input type='hidden' value='`+$(".harga_modal").val()+`' name="harga_modal[]">`
//     var hargasatuan = `<td>`+modal+`<input type='hidden' value='`+$(".harga_satuan").val()+`' name="harga_satuan[]">`+addDecimal($(".harga_satuan").val())+`</td>`
//     var diskon = `<td><input type='hidden' value='`+$(".diskon").val()+`' name="discount[]">`+addDecimal(diskon)+`</td>`
//     var subtotal = `<td><input type='hidden' class='subtotalOrder' value='`+subtotal+`' name="subtotal[]">`+addDecimal(subtotal)+`</td>`
//     var option = `<td><button type='button' class='btn btn-sm btn-danger void'>Void</button></td>`
//     table.append(`<tr>`+no+kodebarang+qty+hargasatuan+diskon+subtotal+option+`</tr>`);
//     updateRowOrder();
//     calcGrandTotal();
//     }else{
//         toastr.error("Limit 1 Faktur, 8 barang");

//     }
// // ////////////////////////////////////////////////////////////////////////
// $(".void").click(remove);
// // ////////////////////////////////////////////////////////////////////////
//     var selectstock = $(".stock").select2();
//     selectstock.select2('open');
//     $(".select2-search__field").focus();
//     $(".diskon").val(0);
//     $(".quantity").val(1)
// }

// $(window).keydown(function (event) {
//     if (event.keyCode == 13) {
//         addToCart();
//     }
// });

// function calcGrandTotal(){
//     var gt = 0;
//     $(".subtotalOrder").map(function(k,v){
//         gt +=parseInt($(v).val())
//     })
//     $("#grand_total").val(addDecimal(gt));
//     $("#total_sales").val(gt);
// }



// function remove(){
//     //parent 1 td
//     //parent 2 tr
//     $(this).parent().parent().remove();
//     updateRowOrder();
//     calcGrandTotal();
// }

// $('#salesorderhistory').on('show.bs.modal', function (e) {
//   // do something...
//   var customerid = $(".customer").find('option').filter(':selected').val();
//   if(customerid != ""){
//     $.ajax({
//     method: "post",
//     url: "{{url('api/salesorder/history/customer')}}",
//     data: { customer_id: customerid}
//     })
//     .done(function( msg ) {
//         var table = $("#riwayat_sales")
//         table.empty()
//         $.each(msg,function(k,v){
//             var no = `<td class="number">1</td>`
//             var nosales = `<td>`+v.intnomorsales+`</td>`
//             var tanggalorder = `<td>`+v.order_date+`</td>`
//             var totalfaktur = `<td>`+v.total_sales+`</td>`
//             var totalbayar = `<td>`+v.total_paid+`</td>`
//             var retur = `<td>`+v.retur+`</td>`
//             var sisabayar = `<td>`+v.payment_remain+`</td>`
//             table.append(`<tr>`+no+nosales+tanggalorder+totalfaktur+totalbayar+retur+sisabayar+`</tr>`);
//         })
//     });
//   }else{
//       $("#salesorderhistory").modal("hide");
//     toastr.error("Pilih Konsumen terlebih dahulu");
//   }
// })

// $('#saleslinehistory').on('show.bs.modal', function (e) {
//   // do something...
//   var stockid = $(".stock").find('option').filter(':selected').val();
//   var customerid = $(".customer").find('option').filter(':selected').val();

//   if(stockid != "" && customerid != ""){
//     $.ajax({
//     method: "post",
//     url: "{{url('api/salesline/history/customer')}}",
//     data: {
//         item_stock_id: stockid,
//         customer_id: customerid
//     }
//     })
//     .done(function( msg ) {
//         var table = $("#riwayat_item")
//         table.empty()
//         $.each(msg,function(k,v){
//             var tanggal = `<td>`+v.createdOn+`</td>`
//             var barang = `<td>`+v.stock.name+`</td>`
//             var qty = `<td>`+v.qty+`</td>`
//             var hargasatuan = `<td>`+v.price_per_satuan_id+`</td>`
//             table.append(`<tr>`+tanggal+barang+qty+hargasatuan+`</tr>`);
//         })
//     });
//   }else{
//       $("#salesorderhistory").modal("hide");
//     toastr.error("Pilih Konsumen terlebih dahulu");
//   }
// })

// function submitSalesOrder(print){
//     var formheader = $(".form-header").serialize();
//     var formbarang = $(".form-barang").serialize();
//     @if($is_edit)
//     var param = formbarang
//     var method = "put"
//     $.ajax({
//     method: method,
//     url: "{{url('penjualan').'/'.$data->id}}",
//     data: param
//     })
//     .done(function( msg ) {
//     toastr.success("Success");
//         // location.reload(true)
//     }).fail(function (msg){
//     toastr.error("Error");
//     });
//     @else
//     var param = formheader+"&"+formbarang
//     var method ="post"
//     $.ajax({
//     method: method,
//     url: "{{route('daftar-piutang.store')}}",
//     data: param
//     })
//     .done(function( msg ) {
//     toastr.success("Success");
//         location.reload(true)
//     }).fail(function (msg){
//     toastr.error("Error");
//     });
//     @endif

// }
</script>
@endpush
