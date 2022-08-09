@include('report.header')
<style>
</style>
<section class=" table-responsive">
    <htmlpageheader name="page-header">
        <h4>Laporan Stock Opname Per Tanggal {{date("d-m-Y",strtotime($start)) ." - ". date("d-m-Y",strtotime($end))}}
        </h4>

        @if(isset($item_name))
        Barang:<h5>{{$item_name ?? null}}</h5>
        @endif
    </htmlpageheader>

    <htmlpagefooter name="page-footer">
    </htmlpagefooter>
    <table id="tabel" class="table-sm table-stripped table-bordered  table-hover box">
        <thead>
            <tr>
                <th style="width:auto" class="col-head">
                    No
                </th>
                <th class="col-head">
                    Tanggal Opname
                </th>
                <th class="col-head">
                    Barang
                </th>
                <th class="col-head">
                    QTY Sebelum
                </th>
                <th class="col-head">
                    QTY Sesudah
                </th>

            </tr>

        </thead>
        <tbody id="item-table">


            @foreach($data as $key)
<tr>
            <td class="">{{$loop->iteration}}</td>
            <td>{{date("d-m-Y",strtotime($key->createdOn))}}</td>
            <td>{!!$key->stock?->name ?? "<strong>Barang Dihapus</strong>"!!}</td>
            <td>{{$key->qty_before}}</td>
            <td class="printAngka">{{$key->qty}}</td>
        </tr>
            @endforeach
        </tbody>
    </table></div>
</section><!-- /.content -->
@include('report.footer')
