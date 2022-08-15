@extends('layouts.master-sidebar')
@section('content')
<!-- Page Content -->
<div class="content">
    <!-- Form -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{$is_edit?"Ubah Pembayaran":"Bayar"}} Faktur</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12 space-y-5">
                    <form autocomplete="off" action="{{$is_edit?url('/').'/hutang/'.$data->id:url('/').'/hutang'}}" method="post">
                        {{csrf_field()}}
                        @if($is_edit)
                        @method("PUT")
                        @endif
                        <div class="form-group">
                            Nomor Sales
                            @if($is_edit)
                            <p>{{$data->purchaseinvoiceheader->internal_invoice_no}}
                                {{$data->purchaseinvoiceheader->poheader->supplier->supplier_name}}
                            </p>
                            @else
                            <select name="purchase_invoice_header_id" id="" class="form-control select2-modal faktur">
                                <option value="" disabled selected>Pilih Faktur</option>
                                @foreach($sales as $item)
                                @if($item->invoice_total > $item->paid_total)
                                    <option value="{{$item->id}}" data-invoice_total={{$item->invoice_total}} data-paid_total={{$item->paid_total}}>{{$item->internal_invoice_no}}
                                        {{$item->poheader->supplier?->supplier_name ?? "error"}}</option>
                                    @endif
                                    @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="form-group">
                            Cara Pembayaran:
                            <select id="var6" name="payment_id" class="form-control"
                                onchange="changePembayaran(this.value)">
                                <option selected @if($is_edit && $data->payment_id == "C") selected @endif
                                    value="C">Cash</option>
                                <option @if($is_edit && $data->payment_id == "TR") selected @endif value="TR">Transfer
                                </option>
                                <option @if($is_edit && $data->payment_id == "G") selected @endif value="G">Giro
                                </option>
                                <option @if($is_edit && $data->payment_id == "CH") selected @endif value="CH">Check
                                </option>
                            </select>
                        </div>
                        <div class="form-group" id="nomorgiro" style="">
                            <span id="textNomorGiro">Nomor Giro/Cek:</span>
                            <input autocomplete="off" type="text" id="check" name="giro" class="form-control"
                                value="{{$data->giro ?? ""}}">
                        </div>

                        <div class="form-group" id="jatuhtempo" style="">
                            <span id="textJatuhTempo">Jatuh Tempo Giro/Cek:</span>
                            <input autocomplete="off" type="date" id="due_date" name="jatuhtempo" class="form-control"
                                value="{{$data->jatuhtempo ?? ""}}">
                        </div>
                        <div class="form-group">
                            Nilai Diskon (%):
                            <input autocomplete="off" type="text" name="diskon" id="diskonsales" class="form-control"
                                value="{{$is_edit?(($data->diskon/($data->payment_value + $data->diskon))*100):0}}">
                        </div>
                        <div class="form-group">
                            Nilai Pembayaran:
                            <input autocomplete="off" type="text" id="pembayaran" class="form-control" required=""
                                value="{{($data->payment_value)??0}}">
                            <input autocomplete="off" type="hidden" id="pembayaranhide" name="nilaibayar"
                                class="form-control" required=""
                                value="{{$is_edit?($data->payment_value + $data->diskon):0}}">
                        </div>

                        <div class="form-group">
                            Note:
                            <textarea id="var5" class="form-control" name="note">{{$data->note ?? "-"}}</textarea>
                        </div>

                        <div class="form-group col-md-12">
                            <!-- <input autocomplete="off" type="button" onclick="createpo_dp()" class="form-control btn btn-info dis" value="Create"> -->
                            <input autocomplete="off" type="submit" class="form-control btn btn-info dis"
                                value="Create">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- END Form -->
</div>
<!-- END Page Content -->
@endsection

@push("js")
<script>
    $(".faktur").change(function(){
    var faktur = $(this).find('option').filter(':selected');
    var invoice_total = faktur.data("invoice_total");
    var paid_total = faktur.data("paid_total");
    $("#pembayaran").val(parseInt(invoice_total) - parseInt(paid_total));
    $("#pembayaranhide").val(parseInt(invoice_total) - parseInt(paid_total));
})

$("#diskonsales").change(function(){
    var remain = parseInt($("#pembayaranhide").val());
    var diskon = parseInt($(this).val());
    remain = remain * ((100-diskon)/100);
    $("#pembayaran").val(Math.ceil(parseInt(remain)));
})

$("#pembayaran").change(function(){
    $("#pembayaranhide").val($(this).val());
})
</script>
@endpush
