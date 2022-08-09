@include('report.header')
<style>
</style>
<section class=" table-responsive">
    <htmlpageheader name="page-header">
        <h4>Daftar Harga Jual Per Tanggal {{ date('d-m-Y') }}</h4>
    </htmlpageheader>
    <htmlpagefooter name="page-footer">
    </htmlpagefooter>
    <div class="table-responsive">
        <table id="tabels" class="table-sm table-stripped table-bordered  table-hover box">
            <thead>
                <tr>
                    <td style="width:auto" class="col-head">
                        No
                    </td>
                    <td class="col-head">
                        Nama Barang
                    </td>

                    @if (request()->hargabeli == 'on')
                        <td class="col-head">
                            Harga Beli
                        </td>
                    @endif
                    @if (request()->hargajual == 'on')
                        <td class="col-head">
                            Harga Jual
                        </td>
                    @endif
                </tr>
            </thead>
            <tbody id="item-table">
                @foreach ($data as $key)
                    <tr onclick="()">
                        <td class="">{{ $loop->iteration }}</td>
                        <td class="">{{ $key->name }}</td>
                        @if (request()->hargabeli == 'on')
                            <td class="">Rp. {{ number_format($key->purchase_price) }}</td>
                        @endif
                        @if (request()->hargajual == 'on')
                            @if ($key->top_sell_price != 0)
                                <td class="">Rp. {{ number_format($key->bottom_sell_price) }}
                                    <hr>Rp. {{ number_format($key->top_sell_price) }}
                                </td>

                            @else
                                <td class="">Rp. {{ number_format($key->sell_price) }}</td>
                            @endif
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table></div>
    </div>
</section><!-- /.content -->
@include('report.footer')
