@include('report.header')
<style>
	@media print {
		.notprint {
			display: none;
		}
	}
</style>
<section class=" table-responsive">
	<htmlpageheader name="page-header">
		<h4>Laporan Penjualan Per Tanggal {{date("d-m-Y",strtotime($start)) ." - ". date("d-m-Y",strtotime($end))}}</h4>

		@if(isset($customer))
		Konsumen : <h5>{{$customer->name ?? null}}</h5>
		@endif
		@if(isset($sales))
		Sales : <h5>{{$sales->displayName ?? null}}</h5>
		@endif
		@if(isset($stock))
		Barang:<h5>{{$stock->name ?? null}}</h5>
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
					Tanggal Order
				</th>
				<th class="col-head">
					No Faktur
				</th>
				<th class="col-head">
					Jatuh Tempo
				</th>
				<th class="col-head">
					Konsumen
				</th>
				<th class="col-head">
					Gross Sale
				</th>
				<th class="col-head">
					Diskon
				</th>
				<th class="col-head">
					Retur
				</th>
				<th class="col-head">
					Netto
				</th>
				<th class="col-head">
					Total Bayar
				</th>
				<th class="col-head">
					Sisa Bayar
				</th>
				@if($lihatkomisi =="On")

				<th class="col-head">
					Komisi
				</th>
				@endif
				<th class="col-head">
					Modal
				</th>

				<th class="col-head">
					Laba
				</th>



			</tr>

		</thead>
		<tbody id="item-table">

			@php
			$remain = 0;
			$paid = 0;
			$sales = 0;
			$diskon = 0;
			$retur = 0;
			$komisi = 0;
			$omzet = 0;
			$modal = 0;
			@endphp
			@foreach($data as $key)
			@php
			$remain +=$key->payment_remain;
			$paid +=$key->total_paid;
			$sales +=$key->total_sales;
			$diskon +=$key->diskon;
			$retur +=$key->retur;
			$omzet +=$key->total_sales - $key->modal;
			$modal +=$key->modal;
			
			if($lihatsemuakomisi =="On"){

			$komisi += $key->commision->amount;
			$komisiiterate =$key->commision->amount;

			}else{
			if($key->payment_remain == 0){

			$komisi +=$key->commision->amount;
			$komisiiterate =$key->commision->amount;

			}
			}
			@endphp
			{{-- @if($key->total_sales - $key->modal <=0 && $key->retur ==0) --}}
			<tr onclick="()">
				<td class="">{{$loop->iteration}}</td>
				<td>{{date("d-m-Y",strtotime($key->createdOn))}}</td>
				<td>{{$key->intnomorsales}}</td>
				<td>{{date("d-m-Y",strtotime($key->due_date))}}</td>
				<td>{{$key->customer->name ?? $key->name}}</td>
				<!-- <td class="printAngka">{{$key->total_sales - $key->diskon - $key->retur}}</td> -->
				<td class="printAngka">{{$key->total_sales}}</td>
				<td class="printAngka">{{$key->diskon}}</td>
				<td class="printAngka">{{$key->retur}}</td>
				<td class="printAngka">{{$key->total_sales - $key->diskon - $key->retur}}</td>
				<td class="printAngka">{{$key->total_paid}}</td>
				<td class="printAngka">{{$key->payment_remain}}</td>
				@if($lihatkomisi =="On")
				<td class="printAngka">{{$key->komisi}}</td>
				@endif
				<td class="printAngka">{{$key->modal}}</td>
				<td class="printAngka">{{$key->total_sales - $key->diskon - $key->retur - $key->modal}}</td>
				{{-- <td class="notprint"><a href="{{url('penjualan/'.$key->id)}}">Detail</a></td> --}}
			</tr>
			{{-- @endif --}}
			<!-- <tr>
				<th></th>
				<th colspan="11" style="text-align:center;">Detail Penjualan</th>
			</tr> -->
			{{-- <tr>
				<th></th>
				<th>No</th>
				<th colspan="3">Item</th>
				<th colspan="2">Satuan</th>
				<th>Qty</th>
				<th colspan="2">Harga</th>
				<th colspan="2">Total Harga</th>
			</tr>
			@foreach($key->detail as $detail)
			<tr>
				<td></td>
				<td>{{$loop->iteration}}</td>
			<td colspan="3">{{$detail->stock->inventoryproperty->item->item_name}}</td>
			<td colspan="2">{{$detail->stock->satuan->name}}</td>
			<td>{{$detail->qty}}</td>
			<td colspan="2">{{$detail->price_per_satuan_id}}</td>
			<td colspan="2">{{$detail->qty * $detail->price_per_satuan_id}}</td>
			</tr>
			@endforeach --}}
			@endforeach

			<tr onclick="()">
				<td class=""></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>
					Total Sales
				</td>
				<td>
					Total Diskon
				</td>
				<td class="col-head">
					Total Retur
				</td>
				<td class="col-head">
					Total Netto
				</td>
				<td class="col-head">
					Total Bayar
				</td>
				<td class="col-head">
					Sisa Bayar
				</td>
				@if($lihatkomisi =="On")
				<td class="col-head">
					Total Komisi
				</td>
				@endif

				<td class="col-head">
					Total Modal
				</td>
				<td class="col-head">
					Total Laba
				</td>
			</tr>
			<tr onclick="()">
				<td class=""></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<!-- <td class="printAngka" style="width: auto">{{$sales + $diskon + $retur}}</td> -->
				<td class="printAngka" style="width: auto">{{$sales}}</td>
				<td class="printAngka">{{$diskon}}</td>
				<td class="printAngka">{{$retur}}</td>
				<!-- <td class="printAngka">{{$sales}}</td> -->
				<td class="printAngka">{{$sales - $diskon - $retur}}</td>
				<td class="printAngka">{{$paid}}</td>
				<td class="printAngka">{{$remain}}</td>
				@if($lihatkomisi =="On")
				<td class="printAngka">{{$komisi}}</td>
				@endif
				<td class="printAngka">{{$modal}}</td>
				<td class="printAngka">{{$omzet}}</td>
			</tr>
			@if($total_pengeluaran != null)
			<tr>
				<td class=""></td>
				<td colspan="4">Total Pengeluaran (sesuai filter)</td>
				<td class="printAngka"></td>
				<td class="printAngka"></td>
				<td class="printAngka"></td>
				<td class="printAngka"></td>
				<td class="printAngka"></td>
				<td class="printAngka"></td>
				<td class="printAngka"></td>
				<td class="printAngka">{{$total_pengeluaran}}</td>
			</tr>
			<tr>
				<td class=""></td>
				<td colspan="4">Keuntungan Bersih</td>
				<td class="printAngka"></td>
				<td class="printAngka"></td>
				<td class="printAngka"></td>
				<td class="printAngka"></td>
				<td class="printAngka"></td>
				<td class="printAngka"></td>
				<td class="printAngka"></td>
				<td class="printAngka">{{$omzet - $total_pengeluaran}}</td>
			</tr>
			@endif
		</tbody>
	</table></div>
</section><!-- /.content -->
@include('report.footer')
