@include('report.header')
<style>
</style>
<section class=" table-responsive">
    <htmlpageheader name="page-header">
        <h4>Laporan Pembelian Per Tanggal {{date("d-m-Y",strtotime($start)) ." - ". date("d-m-Y",strtotime($end))}}</h4>
        @if(isset($customer->name ))
        Pelanggan : <h5>{{$customer->name ?? null}}</h5>
        @endif

        @if(isset($namasales->displayName ))
        Sales : <h5>{{$namasales->displayName ?? null}}</h5>
        @endif
        @if(isset($supplier))
        Supplier :<h5>{{$data[0]->supplier_name ?? null}}</h5>
        @endif
        @if(isset($itemstock))
        Barang :<h5>{{$data[0]->item_name ?? null}}</h5>
        @endif
    </htmlpageheader>
    <htmlpagefooter name="page-footer">
    </htmlpagefooter>
    <table id="tabel" class="table table-sm table-stripped table-bordered  table-hover box">
        <thead>
            <tr>
                <td style="width:auto" class="col-head">
                    No
                </td>
                <td class="col-head">
                    No Faktur Internal
                </td>
                <td class="col-head">
                    No Faktur Supplier
                </td>
                <td class="col-head">
                    Tanggal Order
                </td>
                <td class="col-head">
                    Supplier
                </td>
                <td class="col-head">
                    Barang
                </td>

                <td class="col-head">
                    Qty
                </td>
                <td class="col-head">
                    Harga Satuan
                </td>
                <td class="col-head">
                    Harga Total
                </td>

            </tr>

        </thead>

        <tbody id="item-table">
            @php
            $qty = 0;
            $total = 0;
            @endphp

            @foreach($data as $key)
            @php
            $qty +=$key->qty;
            $total +=($key->qty * $key->price_per_satuan_id);
            @endphp
            <tr>
                <td class="">{{$loop->iteration}}</td>
                <td>{{$key->header->poheader->po_no}}</td>
                <td>{{$key->header->supplier_invoice_no ?? "-"}}</td>
                <td>{{$key->createdOn->format("d F Y")}}</td>
                <td>{{$key->header->poheader->supplier->supplier_name}}</td>
                <td class="">{{$key->poline->stock->name}}</td>
                <td class="printAngka">{{$key->poline->qty}}</td>
                <td class="printAngka">{{$key->price_per_satuan_id}}</td>

                <td class="printAngka">{{($key->qty * $key->price_per_satuan_id)}}</td>

            </tr>
            @endforeach

            <tr onclick="()">
                <td class=""></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="printAngka">{{$qty}}</td>
                <td></td>
                <td class="printAngka">{{$total}}</td>
            </tr>

        </tbody>
    </table></div>
</section><!-- /.content -->
@include('report.footer')
