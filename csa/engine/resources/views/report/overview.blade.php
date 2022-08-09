@include('report.header')

<style>
    @media print {
        .notprint {
            display: none;
        }
    }

</style>
<section class=" table-responsive">
    <header name="page-header">
        <h4>Laporan Ringkasan Per Tanggal {{ date('d-m-Y', strtotime($start)) . ' - ' . date('d-m-Y', strtotime($end)) }}
        </h4>
    </header>
    <footer name="page-footer">
    </footer>
    <div class ="table-responsive"><table class="table table-bordered table-stripped">
        <tr>
            <td>Omzet</td>
            <td>{{ 'Rp.' . number_format($omzet) }}</td>
        </tr>
        <tr>
            <td>Pengeluaran</td>
            <td> {{ 'Rp.' . number_format($pengeluaran) }}</td>
        </tr>

        <tr>
            <td>Profit</td>
            <td> {{ 'Rp.' . number_format($profit) }}</td>
        </tr>

        <tr>
            <td>Persediaan barang</td>
            <td>{{ 'Rp.' . number_format($stock->stock) }}</td>
        </tr>

        <tr>
            <td>Piutang</td>
            <td>{{ 'Rp.' . number_format($piutang->piutang) }}</td>
        </tr>

        <tr>
            <td>Hutang</td>
            <td>{{ 'Rp.' . number_format($hutang->hutang) }}</td>
        </tr>

    </table></div>
</section><!-- /.content -->
@include('report.footer')
