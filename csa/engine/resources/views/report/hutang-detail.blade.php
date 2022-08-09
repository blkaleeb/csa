@include ('report.header')

<section class=" table-responsive">

	<h4>Laporan {{ ucfirst($filter) ?? "" }} Hutang  Per Tanggal {{date("d-m-Y",strtotime($tanggalstart))}} - {{date("d-m-Y",strtotime($tanggalend))}}</h4>
	@if (isset($user))
	<h5>Sales: {{ $user->displayName }}</h5>
	@endif

	@if (isset($supplier))
	<h5>Supplier: {{ $supplier->name }}</h5>
	@endif


	<section class=" table-responsive">
		<h4></h4>
		<table id="tabel" class="table table-bordered table-hover box">
			<thead>
				<tr>
					<td class="col-head">
						No</td>
					<td class="col-head">
						Tanggal</td>
					<td class="col-head">
						No Faktur</td>
					<td class="col-head">
						Supplier</td>
					<td class="col-head">
						Total Tagihan
					</td>
					<td class="col-head">
						Total Retur
					</td>
					<td class="col-head">
						Total bayar
					</td>
					<td class="col-head">
						Sisa Bayar
					</td>


			</thead>
			<tbody id="item-table">
				@php
				$totaltagihan = 0; $totalbayar=0; $totalsisabayar=0; $totalretur = 0
				@endphp
				@foreach ($data as $key)
					@if ($filter == "lunas" && ($key->invoice_total - $key->paid_total) > 0)
						@continue
					@endif
			
					@if ($filter == "belumlunas" && ($key->invoice_total - $key->paid_total) <= 0)
						@continue
					@endif 

					@php
						$totaltagihan += $key->invoice_total;
						$totalbayar += $key->paid_total;
						$totalsisabayar += $key->invoice_total - $key->paid_total;
						$totalretur += $key->retur;

						// $cek = $key->invoice_total - $key->paid_total;
						// if($filter == "lunas" && $cek <= 0) { 
						// 	$totaltagihan += $key->invoice_total;
						// 	$totalbayar += $key->paid_total;
						// 	$totalsisabayar += $key->invoice_total - $key->paid_total;
						// 	$totalretur += $key->retur;
						// } elseif($filter == "belumlunas" && ($key->invoice_total - $key->paid_total) > 0){
						// 	$totaltagihan += $key->invoice_total;
						// 	$totalbayar += $key->paid_total;
						// 	$totalsisabayar += $key->invoice_total - $key->paid_total;
						// 	$totalretur += $key->retur;
						// } else{
						// 	$totaltagihan += $key->invoice_total;
						// 	$totalsisabayar += $key->invoice_total - $key->paid_total;
						// 	$totalretur += $key->retur;
						// }
					@endphp

					<tr>
						<td onclick="" class="">{{ $loop->iteration }}</td>
						<td>{{ date('d-m-Y', strtotime($key->createdOn)) }}</td>
						<td>{{ $key->internal_invoice_no }} | {{ $key->supplier_invoice_no }} </td>
						<td>{{ $key->supplier_name }}</td>
						<td class="printAngka">{{ $key->invoice_total }}</td>
						<td class="printAngka">{{ $key->retur }}</td>
						<td class="printAngka">{{ $key->paid_total }}</td>
						<td class="printAngka">{{ $key->invoice_total - $key->paid_total }}</td>
					</tr>

						{{-- @if (($filter == "lunas") && ($cek <= 0)) <tr>
						<td onclick="" class="">{{ $loop->iteration }}</td>
						<td>{{ date("d-m-Y",strtotime($key->createdOn)) }}</td>
						<td>{{ $key->internal_invoice_no }} | {{ $key->supplier_invoice_no }} </td>
						<td>{{ $key->supplier_name }}</td>
						<td class="printAngka">{{ $key->invoice_total }}</td>
						<td class="printAngka">{{ $key->retur }}</td>
						<td class="printAngka">{{ $key->paid_total }}</td>
						<td class="printAngka">{{ $key->invoice_total -$key->paid_total }}</td>
						</tr>

						@elseif ($filter == "belumlunas" && $cek > 0)

						<tr>
							<td onclick="" class="">{{ $loop->iteration }}</td>
						<td>{{ date("d-m-Y",strtotime($key->createdOn)) }}</td>

							<td>{{ $key->internal_invoice_no }} | {{ $key->supplier_invoice_no }} </td>
							<td>{{ $key->supplier_name }}</td>
							<td class="printAngka">{{ $key->invoice_total }}</td>
							<td class="printAngka">{{ $key->retur }}</td>
							<td class="printAngka">{{ $key->paid_total }}</td>
							<td class="printAngka">{{ $key->invoice_total -$key->paid_total }}</td>

						</tr>

						@elseif (isset($filter) == true)
						<tr>
							<td onclick="" class="">{{ $loop->iteration }}</td>

						<td>{{ date("d-m-Y",strtotime($key->createdOn)) }}</td>
							<td>{{ $key->internal_invoice_no }} | {{ $key->supplier_invoice_no }} </td>
							<td>{{ $key->supplier_name }}</td>
							<td class="printAngka">{{ $key->invoice_total }}</td>
							<td class="printAngka">{{ $key->retur }}</td>
							<td class="printAngka">{{ $key->paid_total }}</td>
							<td class="printAngka">{{ $key->invoice_total -$key->paid_total }}</td>

						</tr>
						@endif --}}
				@endforeach
				<tr>
					<td onclick="" class=""></td>
					<td onclick="" class=""></td>
					<td onclick="" class=""></td>
					<td onclick=""></td>
					<td onclick="" class="printAngka">{{ $totaltagihan }}</td>
					<td onclick="" class="printAngka">{{ $totalretur }}</td>
					<td onclick="" class="printAngka">{{ $totalbayar }}</td>
					<td onclick="" class="printAngka">{{ $totalsisabayar }}</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td class="col-head">
						No

					</td>
					<td class="col-head">
						Tanggal

					</td>

					<td class="col-head">
						No Faktur

					</td>

					<td class="col-head">
						Customer

					</td>


					<td class="col-head">
						Total Tagihan

					</td>

					<td class="col-head">
						Total Retur

					</td>
					<td class="col-head">
						Total bayar

					</td>
					<td class="col-head">
						Sisa Bayar

					</td>


			</tfoot>
		</table></div>
	</section><!-- /.content -->
	</div><!-- /.content-wrapper -->


	<!-- Footer -->
	@include ('report.footer')
