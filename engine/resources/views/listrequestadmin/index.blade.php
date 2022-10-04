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
                    <h3 class="block-title">Daftar Request Accept</h3>
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
                                        <a class="dropdown-item" href="javascript:void(0)" id="buatfakturbtn" data-attr="{{ route('listrequestadmin.show', $item->id) }}">Buat Faktur</a>
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
                            <td class="fw-semibold fs-sm" colspan=2>Product</td>
                            <td class="fw-semibold fs-sm">Harga Satuan</td>
                            <td class="fw-semibold fs-sm">Quantity</td>
                            <td class="fw-semibold fs-sm">Harga Total</td>
                            <td></td>
                        </tr>
                        @foreach ($item->line as $line)
                        <tr>
                            <td class="text-center"></td>
                            <td colspan=2>{{ $line->stock->name ?? "-" }}</td>
                            <td>{{ number_format($line->price_per_satuan_id) ?? "-" }}</td>
                            <td>{{ $line->qty }}</td>
                            <td>{{ number_format($line->qty * $line->price_per_satuan_id) }}</td>
                            <td></td>
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

                    <h3 class="block-title">Tambah Faktur</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="block-content fs-sm" id="smallBody">
                  <div class="block block-rounded">
                        <div class="block-content block-content-full">
                        <div class="row gtText">
                                <label class="col-sm-1  col-form-label" for="example-hf-email" id="toggleModal">Total</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="totalan" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 space-y-5">
                                <form autocomplete="off" action="#"
                                        method="post" class="space-y-4 form-header">
                                        @csrf
                                        <div class="row mb-4">
                                            <input type="hidden" name="total_sales" id="total_sales">
                                            <div class="col-lg-6">
                                                <input type='hidden' id='header_id' name='header_id'>
                                                <label class="form-label" for="example-ltf-email">Konsumen</label>
                                                <input type="hidden" name="customer_id" id="customer_id">
                                                <p id="customer_address" style="margin: unset !important"></p>
                                                <span id="customer"></span>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="">
                                                    <label class="form-label" for="example-ltf-email">Alamat</label>
                                                    <p id="customer_address" style="margin: unset !important"></p>
                                                    <span id="customer_sales"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="form-label" for="example-flatpickr-custom">Tanggal Penjualan</label>
                                                <input type="text" class="js-flatpickr form-control" id="order_date"
                                                    name="order_date" placeholder="hari-bulan-tahun"
                                                    value=""
                                                    data-date-format="d-m-Y">
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-label" for="due_date">Jatuh Tempo</label>
                                                <input type="text" class="js-flatpickr form-control" id="due_date" name="due_date"
                                                    placeholder="hari-bulan-tahun"
                                                    value=""
                                                    data-date-format="d-m-Y">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label" for="example-ltf-email">Supir</label>
                                                <select name="supir" id="supir" class="form-control  select-modal form-select ">
                                                <option value="0">Tidak Pakai Supir</option>
                                                    @foreach ($supir as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->displayName }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="form-label" for="example-ltf-email">Kenek</label>
                                                <select name="kenek" id="kenek" class="form-control  select-modal form-select ">
                                                <option value="0">Tidak Pakai Kenek</option>
                                                    @foreach ($kenek as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->displayName }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        </form>
                                        <div id="daftar-id"></div>
                                        <form autocomplete="off" class="form-barang">
                                        @csrf
                                        <div class="col-md-12 box">
                                            <div class="col-md-12" id="bbb">
                                                <table id="" class="table table-bordered table-stripped">
                                                    <thead>
                                                        <th width="2%">No.</th>
                                                        <th width="20%">Barang</th>
                                                        <th width="10%">Harga Satuan</th>
                                                        <th width="10%">QTY</th>
                                                        <th width="10%">Subtotal</th>
                                                    </thead>
                                                    <tbody id="tbl-penerimaan">
                                                    </tbody>
                                                </table></div>

                                            </div>
                                        </div>
                                        </form>
                                        <div class="row">
                                            <input autocomplete="off" type="submit" class="btn btn-info"
                                            value="Simpan" onclick="submitSalesOrder()">
                                        </div>
                                    <!-- </form> -->
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
    


    function day(tgl){
        const yyyy = tgl.getFullYear();
        let mm = tgl.getMonth() + 1;
        let dd = tgl.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        return dd + '-' + mm + '-' + yyyy;
    }

    //display modal penerimaan
    $(document).on('click', '#buatfakturbtn', function(event) {
        event.preventDefault();
        let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                // return the result
                success: function(result) {
                    var obj = JSON.parse(result);
                    var data2 = obj.data2;
                    var data = obj.data;
                    var line = obj.detail;

                    var tgl1 = new Date();
                    var tgl2 = new Date(tgl1.setMonth(tgl1.getMonth()+1));
                    var tglnow = new Date();

                    $("#header_id").val(data['id']);
                    $("#order_date").val(day(tglnow));
                    $("#due_date").val(day(tgl2));
                    $("#customer_id").val(data['customer_id']);
                    $("#customer").html(data2[0]['name']);
                    $("#customer_sales").html(data2[0]['customer_address']);

                    var htmldetail = '';
                    for (let i = 0; i < line.length; i++) {
                        var subtotal = line[i]['qty'] * line[i]['price_per_satuan_id'];

                        htmldetail += '<tr><td>'+(i+1)+'</td>';
                        htmldetail += "<td><input type='hidden' value='" + line[i]['item_stock_id'] + "' name='item_stock_id[]'>" + line[i]['barang'] + "</td>";
                        htmldetail += "<td><input type='hidden' value='" + line[i]['price_per_satuan_id'] + "' name='harga_satuan[]'>" + addDecimal(line[i]['price_per_satuan_id']) + "</td>";
                        htmldetail += "<td><input type='hidden' value='" + line[i]['qty'] + "' name='quantity[]'>" + line[i]['qty'] +' '+line[i]['satuan_name']+ "</td>";
                        htmldetail += "<td><input type='hidden' class='subtotalOrder' value='" + subtotal + "' name='subtotal[]'>" + addDecimal(subtotal) + "</td>";

                        htmldetail += '</tr>';
                    }
                    $("#tbl-penerimaan").html(htmldetail);
                    calcGrandTotal();
                    $('#modal-penerimaan').modal("show");
                }
            });
    });

</script>    
<!-- END Page Content -->
@endsection
@push("js")
<script>
    function calcGrandTotal() {
            var gt = 0;
            $(".subtotalOrder").map(function(k, v) {
                gt += parseInt($(v).val())
            })
            $("#totalan").val(addDecimal(gt));
            $("#total_sales").val(gt);
        }

    function submitSalesOrder() {
            var formheader = $(".form-header").serialize();
            var formbarang = $(".form-barang").serialize();
                var param = formheader + "&" + formbarang
                var method = "post"
                $.ajax({
                        method: method,
                        url: "{{ route('listrequestadmin.store') }}",
                        data: param
                    })
                    .done(function(msg) {
                        toastr.success("Success");
                        location.reload();
                        // popupTimer();
                    }).fail(function(msg) {
                        toastr.error("Error");
                    });
        }

    $(".select-modal").select2({ dropdownParent: "#modal-penerimaan",width: '100%' });
    
        $(".customer").change(function() {
            var customer = $(this).find('option').filter(':selected');
            var datacustomer = customer.data("item");
            var datasales = customer.data("sales");
            var block = parseInt(customer.data("block"));
            $("#customer_address").text(datacustomer.customer_address)
            $("#customer_sales").text(datasales.displayName)
            if (block === 1) {
                Swal.fire('Silahkan lunasi dulu faktur sebelumnya')

                $("#btn_simpan_cetak").attr("disabled", true)
                $("#btn_simpan").attr("disabled", true)
            } else {
                $("#btn_simpan_cetak").attr("disabled", false)
                $("#btn_simpan").attr("disabled", false)
            }
        })
</script>
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
