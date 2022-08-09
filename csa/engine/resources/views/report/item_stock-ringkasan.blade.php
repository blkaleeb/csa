@include('report.header')
<div style="display: inline-flex;">
    <div class ="table-responsive"><table class="table ">
        <tr>

            @if (isset($suppliernames))
                <td>Supplier: {{ $suppliernames }}</td>
            @endif
            @if (isset($customernames))
                <td>Konsumen: {{ $customernames }}</td>
            @endif
        </tr>
    </table></div>
</div>
<section class=" table-responsive">
    <table id="tabels" class="table table-bordered table-hover box">
        <thead>
            <th>No</th>


            <th>Nama Barang</th>

            <th>Satuan</th>

            <th>Sisa Stok</th>
            @if ($hargabeli == 'on')
                <th>Harga Beli</th>
            @endif

            @if ($hargajual == 'on')
                <th>Harga Jual</th>
            @endif
            @if ($hargabelitotal == 'on')
                <th>Harga Beli Total</th>
            @endif

            @if ($hargajualtotal == 'on')
                <th>Harga Jual Total</th>
            @endif


            <th>Gudang</th>
        </thead>
        <tbody>
            @php
                $totalbeli = 0;
                $totaljual = 0;
            @endphp
            @foreach ($data as $key)
                @php
                    $totalbeli += $key->qty * $key->purchase_price;
                    $totaljual += $key->qty * $key->sell_price;
                @endphp
                <tr>
                    <td class="">{{ $loop->iteration }}</td>
                    <td>{{ $key->name ?? null }}</td>
                    <td>{{ $key->satuan->name ?? '' }}</td>
                    <td class="">{{ $key->qty ?? null }}</td>
                    @if ($hargabeli == 'on')
                        <td>{{ number_format($key->purchase_price) ?? null }}</td>
                    @endif
                    @if ($hargajual == 'on')
                        <td>{{ number_format($key->sell_price) ?? null }}</td>
                    @endif
                    @if ($hargabelitotal == 'on')
                        <td>{{ number_format($key->qty * $key->purchase_price) ?? null }}</td>
                    @endif
                    @if ($hargajualtotal == 'on')
                        <td>{{ number_format($key->qty * $key->sell_price) ?? null }}</td>
                    @endif
                    <td>{{ $key->warehouse_name ?? null }}</td>
                </tr>

            @endforeach
        </tbody>
        <tfoot>
            @if ($hargabelitotal == 'on' || $hargajualtotal == 'on')
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @if ($hargabeli == 'on')
                        <td></td>
                    @endif
                    @if ($hargajual == 'on')
                        <td></td>
                    @endif
                    <td>Total Beli</td>
                    <td>Total Jual</td>
                    <td></td>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @if ($hargabeli == 'on')
                        <td></td>
                    @endif
                    @if ($hargajual == 'on')
                        <td></td>
                    @endif
                    @if ($hargabelitotal == 'on')
                        <td>{{ number_format($totalbeli) ?? null }}</td>
                    @endif
                    @if ($hargajualtotal == 'on')
                        <td>{{ number_format($totaljual) ?? null }}</td>
                    @endif
                    <td></td>
            @endif

            </tr>
        </tfoot>
    </table></div>
</section><!-- /.content -->
@include('report.footer')
