@extends('layouts.master-sidebar')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/flatpickr/flatpickr.min.css') }}">
@endpush

@section('content')
    <!-- Page Content -->
    <div class="content w-100">
        <!-- Form -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Request For Sales</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-12 space-y-5">
                        <!-- Form Horizontal - Default Style -->
                        <form autocomplete="off" class="space-y-4 form-header"
                            action="{{ $is_edit ? route('daftar-piutang.update', $data->id) : '#' }}" method="POST"
                            novalidate>
                            @csrf
                            @if ($is_edit)
                                @method('put')
                                <input type="hidden" name="update_header" value="1">
                            @endif
                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    <label class="form-label" for="example-ltf-email">Konsumen</label>
                                    <select name="customer_id" id="" class="customer js-select2 form-select ">
                                        <option value="" disabled @if (!$is_edit) selected @endif>
                                            Pilih Konsumen</option>

                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                @if ($is_edit && $data->customer_id == $customer->id) selected @endif
                                                data-item="{{ $customer }}" data-block="{{ $customer->block }}"
                                                data-sales="{{ $customer->sales }}">
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="form-label" for="example-ltf-email">Alamat</label>
                                        <p id="customer_address" style="margin: unset !important">
                                            {{ $data->customer->customer_address ?? '' }} </p>
                                        <span id="customer_sales">{{ $data->customer->sales->displayName ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- <div class="col-lg-6">
                                    <label class="form-label" for="example-flatpickr-custom">Tanggal Penjualan</label>
                                    <input type="text" class="js-flatpickr form-control" id="example-flatpickr-custom"
                                        name="order_date" placeholder="hari-bulan-tahun"
                                        value="{{ \Carbon\Carbon::parse($is_edit ? $data->order_date : $orderdate)->format('d-m-Y') }}"
                                        data-date-format="d-m-Y">
                                </div>
                                <div class="col-xl-6">
                                    <label class="form-label" for="due_date">Jatuh Tempo</label>
                                    <input type="text" class="js-flatpickr form-control" id="due_date" name="due_date"
                                        placeholder="Jatuh Tempo"
                                        value="{{ \Carbon\Carbon::parse($is_edit ? $data->due_date : $jatuhtempo)->format('d-m-Y') }}"
                                        data-date-format="d-m-Y">
                                </div> -->
                                <!-- <div class="col-lg-6">
                                    <label class="form-label" for="example-ltf-email">Supir</label>
                                    <select name="supir" id="" class="form-control  select2-modal form-select ">
                                        <option value="0">Tidak Pakai Supir</option>
                                        @foreach ($supir as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($is_edit && $data->supir == $item->id) selected @endif>
                                                {{ $item->displayName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label class="form-label" for="example-ltf-email">Kenek</label>
                                    <select name="kenek" id="" class="form-control  select2-modal form-select ">
                                        <option value="0">Tidak Pakai Kenek</option>

                                        @foreach ($kenek as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($is_edit && $data->kenek == $item->id) selected @endif>
                                                {{ $item->displayName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> -->
                            </div>
                            @if ($is_edit)
                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-info form-control">Update</button>
                                    </div>
                                </div>
                            @endif
                        </form>
                        <!-- END Form Horizontal - Default Style -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END Form -->
    </div>

    @if ($is_edit)
        <div class="content w-100">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Ubah Barang</h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="table-responsive">
                        <table class="table table-hover table-vcenter js-dataTable-buttons js-table-sections ">
                            <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="fw-semibold fs-sm">Product</th>
                                    <th class="fw-semibold fs-sm">Harga Satuan</th>
                                    <th class="fw-semibold fs-sm">Quantity</th>
                                    <th class="fw-semibold fs-sm">Harga Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="fs-sm">
                                @foreach ($data->line as $line)
                                    <tr>
                                        <td class="text-center"></td>
                                        <td>{{ $line->stock->name ?? '-' }}</td>
                                        <td>{{ number_format($line->price_per_satuan_id) ?? '-' }}</td>
                                        <td>{{ $line->qty }}</td>
                                        <td>{{ number_format($line->qty * $line->price_per_satuan_id) }} <input
                                                class="subtotalOrder d-none" type="hidden" readonly
                                                value="{{ $line->qty * $line->price_per_satuan_id }}"></td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    id="dropdown-default-primary" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu fs-sm"
                                                    aria-labelledby="dropdown-default-primary">
                                                    <a class="dropdown-item"
                                                        href="{{ route('penjualan_line.edit', $line->id) }}">Edit</a>
                                                    <a class="dropdown-item delete">Void</a>
                                                    <form autocomplete="off"
                                                        action="{{ route('penjualan_line.destroy', $line->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="content w-100">
            <!-- Form -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Detail Barang</h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-lg-12 ">
                            <hr>
                            <form autocomplete="off" class="form-barang">

                                @csrf
                                @if ($is_edit)
                                    <input type="hidden" name="update_line" value="1" id="">
                                @endif
                                <div class="row space-y-5">
                                    <div class="col-lg-12">
                                        <div class="row mb-4">
                                            <div class="col-xl-12">
                                                <label class="form-label" for="">Barang:</label>
                                                <select class="stock  select2-modal form-select  form-control"
                                                    style="width: 100%">
                                                    <option value="" disabled selected>Pilih Barang</option>
                                                    @foreach ($stock as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-item="{{ $item }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-xl-3">
                                                <label class="form-label" for="">QTY:</label>
                                                <input autocomplete="off" min="1" type="number"
                                                    class="quantity form-control" value="1">
                                            </div>

                                            <div class="col-xl-3">
                                                <label class="form-label" for="">Harga Satuan:</label>
                                                <input autocomplete="off" type="number"
                                                    class="harga_satuan form-control">
                                            </div>

                                            <div class="col-xl-3" id="modal" style="display:none">
                                                <label class="form-label" for="">Harga Modal:</label>
                                                <input autocomplete="off" readonly type="number"
                                                    class="harga_modal form-control">
                                            </div>

                                            <div class="col-xl-3">
                                                <label class="form-label" for="">Diskon %</label>
                                                <input autocomplete="off" type="number" class="diskon form-control"
                                                    value="0" min="0">
                                            </div>
                                        </div>
                                        <div class="form-group hide">
                                            <input autocomplete="off" type="button" onclick="addToCart()"
                                                class="form-control btn btn-info dis" value="Tambah Barang">
                                        </div>
                                        <input autocomplete="off" type="hidden" id="pur">
                                        <input autocomplete="off" type="hidden" id="gtx">

                                    </div>
                                    <div class="col-lg-12" style="overflow-y: auto; max-height: 500px">
                                        <div>
                                            <table id="" class="table table-bordered table-stripped">
                                                <thead>
                                                    <th>No</th>
                                                    <th>Kode Barang</th>
                                                    <th>QTY</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Diskon</th>
                                                    <th>Subtotal</th>
                                                    <th>Void</th>

                                                </thead>
                                                <tbody class="cart">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <input type="hidden" name="total_sales" id="total_sales">
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="row mb-2 mt-3">
                        <div class="col-xl-12">
                            <button type="button" onclick="submitRequestSales(0)" class="btn btn-info form-control"
                                id="btn_simpan">Simpan</button>
                        </div>
                    </div>
                    <!-- <div class="row mb-2">
                        <div class="col-xl-12">
                            <button type="button" onclick="submitRequestSales(1)" class="btn btn-info form-control"
                                id="btn_simpan_cetak">Simpan &
                                Cetak</button>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        </div>
    @else
        <div class="content w-100">
            <!-- Form -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">{{ $is_edit ? 'Ubah Rincian' : 'Rincian' }} Request For Sales</h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-lg-12 ">
                            <hr>
                            <form autocomplete="off" class="form-barang">
                                <div class="row space-y-5">
                                    <div class="col-lg-12">
                                        <div class="row mb-4">
                                            <div class="col-xl-12">
                                                <label class="form-label" for="">Barang:</label>
                                                <select class="stock  select2 form-select  form-control"
                                                    style="width: 100%">
                                                    <option value="" disabled selected>Pilih Barang</option>
                                                    @foreach ($stock as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-item="{{ $item }}">{{ $item->name }}
                                                            (Stock:{{ $item->qty }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-xl-3">
                                                <label class="form-label" for="">QTY:</label>
                                                <input autocomplete="off" min="1" type="number"
                                                    class="quantity form-control" value="1">
                                            </div>

                                            <div class="col-xl-3">
                                                <label class="form-label" for="">Harga Satuan:</label>
                                                <input autocomplete="off" type="number"
                                                    class="harga_satuan form-control">
                                            </div>

                                            <div class="col-xl-3" id="modal" style="display:none">
                                                <label class="form-label" for="">Harga Modal:</label>
                                                <input autocomplete="off" readonly type="number"
                                                    class="harga_modal form-control">
                                            </div>

                                            <div class="col-xl-3">
                                                <label class="form-label" for="">Diskon %</label>
                                                <input autocomplete="off" type="number" class="diskon form-control"
                                                    value="0" min="0">
                                            </div>
                                        </div>
                                        <div class="form-group hide">
                                            <input autocomplete="off" type="button" onclick="addToCart()"
                                                class="form-control btn btn-info dis" value="Tambah Barang">
                                        </div>
                                        <input autocomplete="off" type="hidden" id="pur">
                                        <input autocomplete="off" type="hidden" id="gtx">

                                    </div>
                                    <div class="col-lg-12" style="overflow-y: auto; max-height: 500px">
                                        <div>
                                            <table id="" class="table table-bordered table-stripped">
                                                <thead>
                                                    <th>No</th>
                                                    <th>Kode Barang</th>
                                                    <th>QTY</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Diskon</th>
                                                    <th>Subtotal</th>
                                                    <th>Void</th>

                                                </thead>
                                                <tbody class="cart">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <input type="hidden" name="total_sales" id="total_sales">
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="row mb-2 mt-3">
                        <div class="col-xl-12">
                            <button type="button" onclick="submitRequestSales(0)" class="btn btn-info form-control"
                                id="btn_simpan">Simpan</button>
                        </div>
                    </div>
                    <!-- <div class="row mb-2">
                        <div class="col-xl-12">
                            <button type="button" onclick="submitRequestSales(1)" class="btn btn-info form-control"
                                id="btn_simpan_cetak">Simpan &
                                Cetak</button>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        </div>
    @endif

    <!-- END Page Content -->
@endsection

@include('includes.modal.riwayat')
@push('js')
    <script src="{{ asset('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $(window).keydown(function(event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
            $(".gtText").show();
            $(".history").show();
            calcGrandTotal();
        });

        One.helpersOnLoad([
            'js-flatpickr',
            'jq-datepicker',
            // 'jq-maxlength',
            'jq-select2',
            // 'jq-masked-inputs',
            // 'jq-rangeslider',
            // 'jq-colorpicker'
        ]);

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

        $(".stock").change(function() {
            var stock = $(this).find('option').filter(':selected');
            var datastock = stock.data("item");
            if (datastock.qty <= 0) {
                Swal.fire('Stock Habis')
            } else {
                $(".quantity").attr("min", 1);
                $(".quantity").attr("max", datastock.qty);
                $(".harga_satuan").val(datastock.sell_price)
                $(".harga_modal").val(datastock.purchase_price)
                $(".diskon").val()

                $(".harga_satuan").attr("data-min", datastock.bottom_sell_price)
                $(".harga_satuan").attr("data-max", datastock.top_sell_price)
            }
        })

        $(".harga_satuan").change(function() {
            var price = $(this)
            if (price.val() <= price.data("min")) {
                toastr.error("Harga dibawah batas harga jual");
            }
            if (price.val() > price.data("max")) {
                toastr.error("Harga diatas batas harga jual");

            }
        })

        function addToCart() {

            var stock = $('.stock').find('option').filter(':selected');
            var datastock = stock.data("item");
            var table = $(".cart")
            var count = table.find("tr"); //sudah isi berapa sebelum append
            console.log(count.length);
            if (count.length < 8 && $(".quantity").val() != 0) { // 8 barang 1 faktur, iya "<"
                // ////////////////////////////////////////////////////////////////////////
                subtotal = Math.floor($(".quantity").val() * ($(".harga_satuan").val() * ((100 - $(".diskon").val()) /
                    100)))
                diskon = Math.floor($(".quantity").val() * $(".harga_satuan").val() - subtotal)
                // ////////////////////////////////////////////////////////////////////////
                var no = `<td class="number">1</td>`
                var kodebarang = `<td><input type='hidden' value='` + datastock.id + `' name="item_stock_id[]">` + datastock
                    .name + `</td>`
                var qty = `<td><input type='hidden' value='` + $(".quantity").val() + `' name="quantity[]">` + $(
                    ".quantity").val() + `</td>`
                var modal = `<input type='hidden' value='` + $(".harga_modal").val() + `' name="harga_modal[]">`
                var hargasatuan = `<td>` + modal + `<input type='hidden' value='` + $(".harga_satuan").val() +
                    `' name="harga_satuan[]">` + addDecimal($(".harga_satuan").val()) + `</td>`
                var diskon = `<td><input type='hidden' value='` + $(".diskon").val() + `' name="discount[]">` + addDecimal(
                    diskon) + `</td>`
                var subtotal = `<td><input type='hidden' class='subtotalOrder' value='` + subtotal +
                    `' name="subtotal[]">` + addDecimal(subtotal) + `</td>`
                var option = `<td><button type='button' class='btn btn-sm btn-danger void'>Void</button></td>`
                table.append(`<tr>` + no + kodebarang + qty + hargasatuan + diskon + subtotal + option + `</tr>`);
                updateRowOrder();
                calcGrandTotal();
            } else {
                toastr.error("Limit 1 Faktur, 8 barang");

            }
            // ////////////////////////////////////////////////////////////////////////
            $(".void").click(remove);
            // ////////////////////////////////////////////////////////////////////////
            // var selectstock = $(".stock").select2({
            //     dropdownParent: $('#formModal')
            // });
            // selectstock.select2('open');
            // $(".select2-search__field").focus();
            $(".diskon").val(0);
            $(".quantity").val(1)
        }

        $(window).keydown(function(event) {
            if (event.keyCode == 13) {
                addToCart();
                alert("cart")
            }
        });

        function calcGrandTotal() {
            var gt = 0;
            $(".subtotalOrder").map(function(k, v) {
                gt += parseInt($(v).val())
            })
            $("#grand_total").val(addDecimal(gt));
            $("#total_sales").val(gt);
        }



        function remove() {
            //parent 1 td
            //parent 2 tr
            $(this).parent().parent().remove();
            updateRowOrder();
            calcGrandTotal();
        }

        $('#salesorderhistory').on('show.bs.modal', function(e) {
            // do something...
            var customerid = $(".customer").find('option').filter(':selected').val();
            if (customerid != "") {
                $.ajax({
                        method: "post",
                        url: "{{ url('api/salesorder/history/customer') }}",
                        data: {
                            customer_id: customerid
                        }
                    })
                    .done(function(msg) {
                        var table = $("#riwayat_sales")
                        table.empty()
                        $.each(msg, function(k, v) {
                            var no = `<td class="number">` + (k + 1) + `</td>`
                            var nosales = `<td>` + v.intnomorsales + `</td>`
                            var tanggalorder = `<td>` + new Date(v.order_date).toLocaleDateString() +
                                `</td>`
                            var totalfaktur = `<td>` + number_format(v.total_sales, 0, ",", ".") +
                                `</td>`
                            var totalbayar = `<td>` + number_format(v.total_paid, 0, ",", ".") + `</td>`
                            var retur = `<td>` + number_format(v.retur, 0, ",", ".") + `</td>`
                            var sisabayar = `<td>` + number_format(v.payment_remain, 0, ",", ".") +
                                `</td>`
                            table.append(`<tr>` + no + nosales + tanggalorder + totalfaktur +
                                totalbayar + retur + sisabayar + `</tr>`);
                        })
                    });
            } else {
                $("#salesorderhistory").modal("hide");
                toastr.error("Pilih Konsumen terlebih dahulu");
            }
        })

        $('#requestsaleshistory').on('show.bs.modal', function(e) {
            // do something...
            // var stockid = $(".stock").find('option').filter(':selected').val();
            var customerid = $(".customer").find('option').filter(':selected').val();

            if (customerid != "") {
                $.ajax({
                        method: "post",
                        url: "{{ url('api/salesline/history/customer') }}",
                        data: {
                            item_stock_id: stockid,
                            customer_id: customerid
                        }
                    })
                    .done(function(msg) {
                        var table = $("#riwayat_item")
                        table.empty()
                        $.each(msg, function(k, v) {
                            var tanggal = `<td>` + new Date(v.createdOn).toLocaleDateString() + `</td>`
                            var barang = `<td>` + v.stock.name + `</td>`
                            var qty = `<td>` + v.qty + `</td>`
                            var hargasatuan = `<td>` + number_format(v.price_per_satuan_id, 0, ",",
                                ".") + `</td>`
                            table.append(`<tr>` + tanggal + barang + qty + hargasatuan + `</tr>`);
                        })
                    });
            } else {
                $("#requestsaleshistory").modal("hide");
                toastr.error("Pilih Konsumen terlebih dahulu");
            }
        })

        function submitRequestSales (print) {
            var formheader = $(".form-header").serialize();
            var formbarang = $(".form-barang").serialize();
            
                var param = formheader + "&" + formbarang
                var method = "post"
                $.ajax({
                        method: method,
                        url: "{{ route('requestsales.store') }}",
                        data: param
                    })
                    .done(function(msg) {
                        toastr.success("Success");
                        popupTimer();
                    }).fail(function(msg) {
                        toastr.error("Error");
                    });
        }

        function popupTimer() {
            let timerInterval
            Swal.fire({
                title: 'Auto Reload Page',
                html: 'Pembuatan faktur berhasil, Halaman akan dimuat ulang',
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft()
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    location.reload(true)
                }
            })
        }
    </script>
@endpush
