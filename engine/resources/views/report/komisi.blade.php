@include('report.header')
<style>
</style>
<section class=" table-responsive">
	<htmlpageheader name="page-header">
		<h4>Laporan Komisi Per Tanggal {{date("d-m-Y",strtotime($start)) ." - ". date("d-m-Y",strtotime($end))}}</h4>

		@if(isset($customer))
		Konsumen : <h5>{{$customer->name ?? null}}</h5>
		@endif
		@if(isset($sales))
		Sales : <h5>{{$sales->displayName ?? null}}</h5>
		@endif

	</htmlpageheader>

	<htmlpagefooter name="page-footer">
	</htmlpagefooter>
	<table id="tabel" class="table-sm table-stripped table-bordered  table-hover box">
		<thead>
			<tr>
				<td style="width:auto" class="col-head">
					No
				</td>
				<td class="col-head">
					Tanggal Order
				</td>
				<td class="col-head">
					No Faktur
				</td>
				<td class="col-head">
					Konsumen
				</td>
				<td class="col-head">
					Gross Sale
				</td>
				<td class="col-head">
					Diskon
				</td>
				<td class="col-head">
					Retur
				</td>
				<td class="col-head">
					Netto
				</td>
				<td class="col-head">
					Total Bayar
				</td>
				<td class="col-head">
					Sisa Bayar
				</td>
				<td class="col-head">
					Komisi
				</td>




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
			$komisiiterate = 0;

			// if($lihatsemuakomisi == "on"){
			// $komisiiterate = $key->komisi;
			// }else{
			// if($key->payment_remain == 0){
			// $komisiiterate = $key->komisi;
			// }
			if($lihatsemuakomisi == "on"){
			$komisiiterate = $key->commision->amount;
			}else{
			if($key->payment_remain == 0){
			$komisiiterate = $key->commision->amount;
			}
			}
			@endphp
			<tr onclick="()">
				<td class="">{{$loop->iteration}}</td>
				<td>{{date("d-m-Y",strtotime($key->createdOn))}}</td>
				<td>{{$key->intnomorsales}}</td>
				<td>{{$key->customer->name ?? $key->name}}</td>
				<td class="printAngka">{{$key->total_sales + $key->diskon + $key->retur}}</td>
				<td class="printAngka">{{$key->diskon}}</td>
				<td class="printAngka">{{$key->retur}}</td>
				<td class="printAngka">{{$key->total_sales}}</td>
				<td class="printAngka">{{$key->total_paid}}</td>
				<td class="printAngka">{{$key->payment_remain}}</td>
				<td class="printAngka">{{$komisiiterate}}</td>
				@php
				$komisi +=$komisiiterate;
				@endphp
			</tr>
			@endforeach

			<tr onclick="()">
				<td class=""></td>
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

				<td class="col-head">
					Total Komisi
				</td>



			</tr>
			<tr onclick="()">
				<td class=""></td>
				<td></td>
				<td></td>
				<td></td>
				<!-- <td class="printAngka" style="width: auto">{{$sales + $diskon + $retur}}</td> -->
				<td class="printAngka" style="width: auto">{{$sales}}</td>
				<td class="printAngka">{{$diskon}}</td>
				<td class="printAngka">{{$retur}}</td>
				<td class="printAngka">{{$sales - $diskon - $retur}}</td>
				<td class="printAngka">{{$paid}}</td>
				<td class="printAngka">{{$remain}}</td>
				<td class="printAngka">{{$komisi}}</td>
			</tr>
		</tbody>
	</table></div>
</section><!-- /.content -->
@include('report.footer')
