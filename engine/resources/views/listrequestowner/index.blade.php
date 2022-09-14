@extends('layouts.master-sidebar')
@push("css")
<!-- Stylesheets -->
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="{{asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
@endpush
@section('content')
<!-- Page Content -->
<div class="content">
    <div class="row">
        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="float-start">
                    <h3 class="block-title">Daftar Request</h3>
                </div>
                <!-- <div class="float-end">
                    <a href="{{route('retur_pembelian.create')}}" class="btn btn-info block-title">Buat Faktur</a>
                </div> -->

            </div>
            <div class="block-content block-content-full">
                <div class ="table-responsive"><table class="table table-hover table-vcenter js-dataTable-buttons js-table-sections ">
                    <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th>Sales</th>
                            <th>No Request</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    @foreach ($data as $item)
                    <?php
                        $total = 0;
                    ?>
                    <tbody class="js-table-sections-header">
                        <tr>
                            <td class="text-center fs-sm">
                                <i class="fa fa-angle-right text-muted"></i>
                            </td>
                            <td>{{$item->user->displayName ?? ''}}</td>
                            <td>{{$item->intnomorsales}}</td>
                            <td>{{date("d-m-Y H:i:s",strtotime($item->createdOn))}}</td>
                            <td>
                                {{$item->customer->name ?? ''}}
                            </td>
                            <td id="total_{{$item->id}}"></td>
                            <td><div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item" href="javascript:void(0)" id="penerimaanbtn" data-attr="{{ route('listrequestowner.show', $item->id) }}">Penerimaan</a>
                                        <!-- <a class="dropdown-item delete">Void</a>
                                        <form autocomplete="off" action="{{route('listrequestowner.destroy',$item->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form> -->
                                    </div>
                                </div></td>
                            <!-- <td>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item"
                                            href="{{route('retur_pembelian.edit',$item->id)}}">Edit</a>
                                            <a class="dropdown-item"
                                            href="{{route('retur_pembelian.print',$item->id)}}">Print</a>
                                        <a class="dropdown-item delete">Void</a>
                                        <form autocomplete="off" action="{{route('retur_pembelian.destroy',$item->id)}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </td> -->
                        </tr>
                    </tbody>
                    <tbody class="fs-sm">
                        <tr>
                            <td class="text-center"></td>
                            <td class="fw-semibold fs-sm">Product</td>
                            <td class="fw-semibold fs-sm">Harga Satuan</td>
                            <td class="fw-semibold fs-sm">Quantity</td>
                            <td class="fw-semibold fs-sm">Quantity Acc</td>
                            <td class="fw-semibold fs-sm">Harga Total</td>
                            <td class="fw-semibold fs-sm" width="10%">Status</td>
                        </tr>
                        @foreach ($item->line as $line)
                        <tr>
                            <td class="text-center"></td>
                            <td>{{ $line->stock->name ?? "-" }}</td>
                            <td>{{ number_format($line->price_per_satuan_id) ?? "-" }}</td>
                            <td>{{ $line->qty }}</td>
                            <td>{{ $line->qty_acc }}</td>
                            <td>{{ number_format($line->qty * $line->price_per_satuan_id) }}</td>
                            <td><?php
                            if($line->status == '1' || $line->qty == $line->qty_acc){
                                echo "<font style='color:green'>Accept</font>";
                            }elseif($line->status == '0'){
                                echo "<font style='color:orange'>In Review</font>";
                            }elseif($line->status == '2'){
                                echo "<font style='color:red'>Not Accept</font>";
                            }
                            ?></td>
                            <?php
                                $total += ($line->qty * $line->price_per_satuan_id);
                            ?>
                            <!-- <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item"
                                            href="{{route('retur_pembelian_line.edit',$line->id)}}">Edit</a>
                                        <a class="dropdown-item delete">Void</a>
                                        <form autocomplete="off" action="{{route('retur_pembelian_line.destroy',$line->id)}}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </td> -->
                        </tr>
                        @endforeach
                    </tbody>

                    <script>
                        document.getElementById("total_<?php echo $item->id ?>").innerHTML = "<?php echo number_format($total)?>";
                    </script>
                    @endforeach
                </table></div>
                {{$data->links()}}


            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
</div>

<!-- Large Block Modal -->
<div class="modal" id="modal-penerimaan" role="dialog" aria-labelledby="modal-penerimaan" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content modal-body">
                <div class="block block-rounded block-transparent mb-0">

                  <div class="block-header block-header-default">

                    <h3 class="block-title">Tambah Penerimaan Request</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="block-content fs-sm" id="smallBody">
                  <div class="block block-rounded">
                        <div class="block-content block-content-full">
                            <div class="row">
                                <div class="col-lg-12 space-y-5">
                                <form autocomplete="off" action="{{route('listrequestowner.store') }}"
                                        method="post" class="space-y-4">
                                        @csrf
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="example-hf-email">Sales</label>
                                            <div class="col-sm-8">
                                                <input type="hidden" id="id" name="id">
                                                <input autocomplete="off" type="text" disabled maxlength="15" id="sales" name="sales"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="example-hf-email">No Request</label>
                                            <div class="col-sm-8">
                                                <input autocomplete="off" type="text" disabled maxlength="15" id="noreq" name="noreq"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="example-hf-email">Tanggal</label>
                                            <div class="col-sm-8">
                                                <input autocomplete="off" type="text" disabled maxlength="15" id="tanggal" name="tanggal"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="example-hf-email">Customer</label>
                                            <div class="col-sm-8">
                                                <input autocomplete="off" type="text" disabled maxlength="15" id="customer" name="customer"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div id="daftar-id"></div>
                                        <div class="col-md-12 box">
                                            <div class="col-md-12" id="bbb">
                                                <table id="" class="table table-bordered table-stripped">
                                                    <thead>
                                                        <th width="2%">No.</th>
                                                        <th width="20%">Barang</th>
                                                        <th width="10%">QTY</th>
                                                        <th width="10%">QTY Accept</th>
                                                        <th width="10%">Status</th>
                                                    </thead>
                                                    <tbody id="tbl-penerimaan">
                                                    </tbody>
                                                </table></div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <input autocomplete="off" type="submit" class="btn btn-info"
                                            value="Buat Penerimaan">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="block-content block-content-full text-end bg-body">
                    <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END Large Block Modal -->
<script>
    //display modal penerimaan
    $(document).on('click', '#penerimaanbtn', function(event) {
        event.preventDefault();
        let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                // return the result
                success: function(result) {
                    var obj = JSON.parse(result);
                    var data = obj.data;
                    var data2 = obj.data2;
                    var line = obj.detail;
                    
                    //master
                    var id = data['id'];
                    var sales = data2[0]['displayName'];
                    var tgl = line[0]['tgl'];
                    var customer = data2[0]['name'];
                    var noreq = data['intnomorsales'];

                    $("#id").val(id);
                    $("#sales").val(sales);
                    $("#tanggal").val(tgl);
                    $("#customer").val(customer);
                    $("#noreq").val(noreq);

                    var htmldetail = '';
                    for (let i = 0; i < line.length; i++) {
                        if(line[i]['status'] != '2' && line[i]['status'] != '1'){
                            htmldetail += '<tr><td>'+(i+1)+'</td>';
                            htmldetail += '<td>'+line[i]['barang']+'</td>';
                            htmldetail += '<td><input type="hidden" name="qtyawal[]" id"qty-'+(i+1)+'" value="'+line[i]['qty']+'"><input type="hidden" name="qtyawal2[]" id"qty2-'+(i+1)+'" value="'+line[i]['qty_acc']+'">'+line[i]['qty']+' '+line[i]['satuan_name']+'</td>';
                            htmldetail += '<td><input type="number" name="qty[]" id="qty-acc-'+(i+1)+'" max="'+(line[i]['qty']-line[i]['qty_acc'])+'" value="'+(line[i]['qty']-line[i]['qty_acc'])+'" style="width:50%" ';
                            if((line[i]['qty'] == line[i]['qty_acc'])){
                                htmldetail += ' disabled';
                            }
                            htmldetail += '> '+line[i]['satuan_name']+'</td>';
                            htmldetail += '<td><select class="form-control" style="width:100%" name="status[]" id="status-get-'+(i+1)+'" ';
                            if((line[i]['qty'] == line[i]['qty_get'])){
                                htmldetail += ' disabled';
                            }
                            htmldetail += '>';
                            htmldetail += '<option value="0">In Review</option> ';
                            htmldetail += '<option value="1">Accept</option> ';
                            htmldetail += '<option value="2">Not Accept</option> ';
                            htmldetail += '</select> ';

                            htmldetail += '<input type="hidden" name="line[]" id="data-line-id-'+(i+1)+'" value="'+line[i]['id']+'" ';
                            if((line[i]['qty']) == (line[i]['qty_acc'])){
                                htmldetail += ' disabled';
                            }
                            htmldetail += ' >';
                            htmldetail += '</td></tr>';
                        }
                    }
                    $("#tbl-penerimaan").html(htmldetail);

                    $('#modal-penerimaan').modal("show");
                }
            });
    });
</script>    
<!-- END Page Content -->
@endsection
@push("js")
<!-- Page JS Plugins -->
<script src="{{asset('assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons-jszip/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/datatables-buttons/buttons.html5.min.js')}}"></script>

<!-- Page JS Code -->
<script src="{{asset('assets/js/pages/be_tables_datatables.min.js')}}"></script>

<script>
    One.helpersOnLoad(['one-table-tools-checkable', 'one-table-tools-sections']);
</script>
@endpush
