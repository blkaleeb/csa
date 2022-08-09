@include('report.header')
<section class=" table-responsive">
    <htmlpageheader name="page-header">
        <h4>Laporan Pengeluaran Per Tanggal {{date("d-m-Y", strtotime($tanggalstart))}} - {{date("d-m-Y",
            strtotime($tanggalend))}}</h4>

    </htmlpageheader>


    <table id="tabel" class="table table-bordered table-hover box">
        <thead>
            <tr>
                <td class="col-head">
                    No

                </td>
                <td class="col-head">
                    Tanggal

                </td>
                <td class="col-head">
                    Nama Biaya

                </td>

                <td class="col-head">
                    No Bukti

                </td>
                <td class="col-head">
                    Jumlah

                </td>

                <td class="col-head">
                    Catatan

                </td>
                <td class="col-head">
                    Subjek Pengeluaran

                </td>


            </tr>

        </thead>

        <tbody id="item-table">
            @php
            $total =0;
            @endphp
            @foreach($data as $key)
            @if($key->itemstock_id == null)
                @if((Auth::User()->role_id != 1) && ($key->ktname == "Gaji" || $key->ktname == "Komisi" || $key->ktname ==
                "Thr") )
                @else
                @php
                $total += $key->jumlah;
                @endphp
                <tr onclick="">
                    <td class="">{{$loop->iteration}}</td>
                    <td>{{date("d-m-Y",strtotime($key->tanggal))}}</td>
                    <td>{{$key->ktname}}</td>
                    <td>{{$key->no_bukti}}</td>
                    <td class="printAngka">{{$key->jumlah}}</td>
                    <td>{{$key->detail ?? "-"}}</td>
                    <td>{{$key->displayName ?? $key->name}}</td>

                </tr>
                @endif
            @else
                @if((Auth::User()->role_id != 1) && ($key->ktname == "Gaji" || $key->ktname == "Komisi" || $key->ktname ==
                "Thr") )
                @else
                @php
                $total += $key->stock?->purchase_price * $key->jumlah;
                @endphp
                <tr onclick="">
                    <td class="">{{$loop->iteration}}</td>
                    <td>{{date("d-m-Y",strtotime($key->tanggal))}}</td>
                    <td>{{$key->ktname}}</td>
                    <td>{{$key->no_bukti}}</td>
                    <td class="printAngka">{{$key->stock?->purchase_price * $key->jumlah}}</td>
                    <td>{{$key->detail ?? $key->jumlah}}</td>
                    <td>{{$key->stock?->name}}</td>
                </tr>
                @endif
            @endif

            @endforeach
            <tr onclick="">
                <td class=""></td>
                <td></td>
                <td></td>
                <td></td>

                <td class="printAngka">{{$total}}</td>
                <td></td>
                <td></td>


            </tr>
        </tbody>
    </table></div>
</section><!-- /.content -->
@include('report.footer')
