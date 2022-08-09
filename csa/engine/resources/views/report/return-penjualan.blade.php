@include('report.header')
<style>
</style>
<section class=" table-responsive">
	<h4>Laporan Retur Penjualan Per Tanggal {{date("d-m-Y",strtotime($start)) ." - ". date("d-m-Y",strtotime($end))}}
	</h4>
	<div class="col-md-12">
		@if(isset($customer))
		<div class="col-md-6">
			Konsumen : <h5>{{$customer}}</h5>
		</div>
		@endif
		@if(isset($item))
		<div class="col-md-6">
			Barang:<h5>{{$item}}</h5>
		</div>
		@endif
	</div>
	<table id="tabel" class="table-sm table-stripped table-bordered  table-hover box">
		<thead>
			<th>No.</th>
			<th>Tanggal</th>
			<th>No Retur</th>
			<th>Konsumen</th>
			<th>Total</th>
			@if($detail==true)
			<th></th>
			@endif
		</thead>
		<tbody id="item-table">
			@php
			$totalRetur = 0;
			@endphp
			@foreach($data as $key)
			@php
			$total = 0;
			@endphp
			@foreach($key->line as $detail)
			@php
			$total+=$detail->qty*$detail->returprice;
			$totalRetur +=$total;
			@endphp
			</tr>
			@endforeach
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{date("d-m-Y", strtotime($key->createdOn))}}</td>
				<td>{{$key->no_invoice}}</td>
				<td>{{$key->customer->name}}</td>
				<td>{{number_format($total)}}</td>
				@if($detail==true)
				<td></td>
				@endif
			</tr>
			@if($detail==true)
			<tr>
				<th></th>
				<th>No</th>
				<th>Item</th>
				<th>Qty</th>
				<th>Harga</th>
				<th>Total Harga</th>
			</tr>
			@php
			$total = 0;
			@endphp
			@foreach($key->line as $detail)
			<tr>
				<td></td>
				<td>{{$loop->iteration}}</td>
				<td>{{$detail->stock?->name ?? "Barang Dihapus"}}</td>
				<td>{{$detail->qty}}</td>
				<td>{{number_format($detail->returprice)}}</td>
				<td>{{number_format($detail->qty*$detail->returprice)}}</td>
				@php
				$total+=$detail->qty*$detail->returprice;
				$totalRetur +=$total;
				@endphp
			</tr>
			@endforeach
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>Total</td>
				<td>{{number_format($total)}}</td>
			</tr>
			@endif
			@endforeach
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>Total Retur</td>
				<td>{{number_format($totalRetur)}}</td>
			</tr>
		</tbody>
	</table></div>
</section><!-- /.content -->
@include('report.footer')
