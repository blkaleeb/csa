@extends('layouts.master-sidebar')
@push('css')
<!-- Stylesheets -->
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endpush
@section('content')
<!-- Page Content -->
<div class="content">
    <div class="row">
        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">

            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                <form autocomplete="off" method="get" action="" id="formlaporan" class="col-md-12" target="_blank">
                    <div class="form-group">
                        <input id='reset' type='button' value='reset' />
                        <label for="showparameter">
                            Pilih Laporan:
                        </label>
                        <select autocomplete="off" class="form-control mt-2" id="showparameter" onchange="showparam()">
                            <option value="" selected="">Pilih Laporan</option>
                            <option value="overview">Overview</option>
                            <option value="barang">Daftar Barang</option>
                            <option value="stock">Stock Barang</option>
                            <option value="penjualan">Penjualan</option>
                            <option value="pembelian">Pembelian</option>
                            <option value="komisi">Komisi</option>
                            <option value="piutang">Piutang</option>
                            <option value="pengeluaran">Pengeluaran</option>
                            <option value="hutang">Hutang </option>
                            <option value="rpembelian">Retur Pembelian </option>
                            <option value="rpenjualan">Retur Penjualan </option>
                            <option value="opname">Stock Opname</option>

                            {{-- <option value="labarugi">Laba Rugi</option>
                            <option value="kas">Kas</option>
                            <option value="neraca">Neraca</option> --}}
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tanggal">
                            Dari:
                        </label>
                        <input autocomplete="off" type="date" class="form-control" id="tanggal" name="date_start" min="2021-09-08">
                        <label for="tanggal">
                            Hingga:
                        </label>
                        <input autocomplete="off" type="date" class="form-control" id="tanggal" name="date_end">
                    </div>

                    <div id="opname" class="paramhide" style="display: none">
                        <div class="form-group">
                            Barang:
                            <select class="resetparam stock form-control" name="item_stock" style="width: 100%"></select>
                        </div>
                    </div>

                    <div id="rpembelian" class="paramhide" style="display: none">
                        <div class="form-group">
                            Barang:
                            <select class="resetparam stock form-control" name="item_stock" style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            Supplier:
                            <select class="resetparam supplier form-control" name="supplier" style="width: 100%"></select>
                        </div>
                    </div>

                    <div id="rpenjualan" class="paramhide" style="display: none">
                        <div class="form-group">
                            Barang:
                            <select class="resetparam stock form-control" name="item_stock" style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            Konsumen:
                            <select class="resetparam customer form-control" name="customer" style="width: 100%"></select>
                        </div>
                    </div>
                    <div id="barang" class="paramhide" style="display: none">
                        <div class="form-group">
                            Kategori:
                            <select autocomplete="off" class="resetparam category form-control" name="kategori" style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            Merk:
                            <select autocomplete="off" class="resetparam brands form-control" name="merk" style="width: 100%"></select>
                        </div>
                        <div class="form-group">
                            Tampilkan harga:
                            <input type="checkbox" name="hargajual" autocomplete="off"> Harga Jual
                            <input type="checkbox" name="hargabeli" autocomplete="off"> Harga Beli
                        </div>
                    </div>

                    <div id="stock" class="paramhide" style="display: none">

                        <div class="form-group">
                            Kategori:
                            <select autocomplete="off" class="resetparam category form-control" name="kategori" style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            Merk:
                            <select autocomplete="off" class="resetparam brands form-control" name="merk" style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            Supplier:
                            <select autocomplete="off" class="resetparam supplier form-control" name="supplier" style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            Konsumen:
                            <select autocomplete="off" class="resetparam customer form-control" name="customer" style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            Gudang:
                            <select autocomplete="off" class="resetparam warehouse form-control" name="warehouse_id" style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            Tampilkan harga:
                            <input type="checkbox" name="hargajual" autocomplete="off"> Harga Jual
                            <input type="checkbox" name="hargabeli" autocomplete="off"> Harga Beli
                            <input type="checkbox" name="hargajualtotal" autocomplete="off"> Harga Total Asset Jual
                            <input type="checkbox" name="hargabelitotal" autocomplete="off"> Harga Total Asset Beli
                        </div>
                    </div>

                    <div id="penjualan" class="paramhide" style="display: none">

                        <div class="form-group">
                            Barang:
                            <select class="resetparam stock form-control" name="item_stock" style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            Konsumen:
                            <select class="resetparam customer form-control" name="customer" style="width: 100%"></select>
                            <div class="form-group">
                                Detail Barang:
                                <input type="checkbox" class="" name="detail">
                            </div>
                        </div>

                        <div class="form-group">
                            Sales:
                            <select class="resetparam sales form-control" name="sales" style="width: 100%"></select>
                        </div>


                    </div>
                    <div id="komisi" class="paramhide" style="display: none">
                        <div class="form-group">
                            Konsumen:
                            <select class="resetparam customer form-control" name="customer" style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            Sales:
                            <select class="resetparam sales form-control" name="sales" style="width: 100%"></select>
                        </div>
                        <div class="form-group">
                            Semua Komisi:
                            <input type="checkbox" class="" name="semuakomisi">
                        </div>

                    </div>
                    <div id="pembelian" class="paramhide" style="display: none">

                        <div class="form-group">
                            Barang:
                            <select class="resetparam stock form-control" name="item_stock" style="width: 100%"></select>
                        </div>
                        <div class="form-group">
                            Supplier:
                            <select class="resetparam supplier form-control" name="supplier" style="width: 100%"></select>
                        </div>
                        <div class="form-group">
                            Filter Detail
                            <input type="checkbox" name="detail_supplier" autocomplete="off">
                        </div>


                    </div>
                    <div id="hutang" class="paramhide" style="display: none">

                        <div class="form-group">
                            Supplier:
                            <select class="resetparam supplier form-control" name="supplier" style="width: 100%"></select>
                        </div>
                        <div class="form-group">
                            Filter Lunas
                            <input type="checkbox" name="lunas" autocomplete="off">
                        </div>
                        <div class="form-group">
                            Filter Belum Lunas
                            <input type="checkbox" name="belumlunas" autocomplete="off">
                        </div>
                        <div class="form-group">
                            Filter Detail
                            <input type="checkbox" name="detail" autocomplete="off">
                        </div>


                    </div>
                    <div id="piutang" class="paramhide" style="display: none">

                        <div class="form-group">
                            Customer:
                            <select class="resetparam customer form-control" name="customer" style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            Sales:
                            <select class="resetparam sales form-control" name="sales" style="width: 100%"></select>
                        </div>

                        <div class="form-group">
                            Filter Lunas
                            <input type="checkbox" name="lunas" autocomplete="off">
                        </div>
                        <div class="form-group">
                            Filter Belum Lunas
                            <input type="checkbox" name="belumlunas" autocomplete="off">
                        </div>
                        <div class="form-group">
                            Filter Detail
                            <input type="checkbox" name="detail" autocomplete="off">
                        </div>
                    </div>

                    <div id="pengeluaran" class="paramhide" style="display: none">

                        <div class="form-group">
                            Pegawai:
                            <select class="resetparam userall form-control" name="pegawai" style="width: 100%"></select>
                            Inventaris:
                            <select class="resetparam form-control inventaris" name="inventaris" style="width: 100%"></select>
                            Kategori Pengeluaran:
                            Kategori
                            <select class="form-control katpengadmin resetparam" name="kategori_pengeluaran_id" style="width: 100%"></select>

                        </div>



                    </div>
                    <input type="submit" name="" class="btn btn-info form-control mt-2">
                </form>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
</div>
<!-- END Page Content -->
@endsection
@push('js')
<!-- Page JS Plugins -->
<script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>

<!-- Page JS Code -->
<script src="{{ asset('assets/js/pages/be_tables_datatables.min.js') }}"></script>
<script type="text/javascript">
    function showparam() {
        $(".resetparam").val(null).trigger('change');;

        var id = $("#showparameter").val();
        $(".paramhide").hide();
        $("#" + id).show();
        $('#formlaporan').attr('action', "{{ url('/') }}/report/" + id);
    }
    $("#reset").on('click', function(e) {
        $(".resetparam").val(null).trigger('change');;
    });
</script>
<script type="text/javascript">
    $("#reset").on('click', function(e) {
        $(".resetparam").val(null).trigger('change');;
    });
    $('.brands').select2({
        allowClear: true,
        selectOnClose: true,
        placeholder: 'Pilih merk',
        ajax: {
            url: "{!! url('/') !!}" + '/api/brand',
            dataType: 'json',
            delay: 1000,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });

    $('.category').select2({
        allowClear: true,
        selectOnClose: true,
        placeholder: 'Pilih Kategori',
        ajax: {
            url: "{!! url('/') !!}" + '/api/category',
            dataType: 'json',
            delay: 1000,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
    $('.supplier').select2({
        allowClear: true,
        selectOnClose: true,
        placeholder: 'Pilih Supplier',
        ajax: {
            url: "{!! url('/') !!}" + '/api/supplier',
            dataType: 'json',
            delay: 1000,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.supplier_name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });


    $('.inventory').select2({
        allowClear: true,
        selectOnClose: true,
        placeholder: 'Pilih Barang',
        ajax: {
            url: "{!! url('/') !!}" + '/api/inventoryproperty',
            dataType: 'json',
            delay: 1000,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.item.item_name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
    $('.customer').select2({
        allowClear: true,
        selectOnClose: true,
        placeholder: 'Pilih Konsumen',
        ajax: {
            url: "{!! url('/') !!}" + '/api/customer',
            dataType: 'json',
            delay: 1000,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
    $('.stock').select2({
        allowClear: true,
        selectOnClose: true,
        placeholder: 'Pilih stok',
        ajax: {
            url: "{!! url('/') !!}" + '/api/itemstock',
            dataType: 'json',
            delay: 1000,
            processResults: function(data) {
                return {
                    results: $.map(data, function(items) {
                        return {
                            text: items.name,
                            id: items.id
                        }
                    })
                };
            },
            cache: true
        }
    });
    // $('.brands').select2({
    // 		selectOnClose: true,
    // 		placeholder: 'Pilih merk',
    // 		ajax: {
    // 			url: "{!! url('/') !!}" + '/api/brand',
    // 			dataType: 'json',
    // 			delay: 1000,
    // 			processResults: function (data) {
    // 				return {
    // 					results: $.map(data, function (item) {
    // 						return {
    // 							text: item.name,
    // 							id: item.id
    // 						}
    // 					})
    // 				};
    // 			},
    // 			cache: true
    // 		}
    // 	});

    // 	$('.category').select2({
    // 		selectOnClose: true,
    // 		placeholder: 'Pilih Kategori',
    // 		ajax: {
    // 			url: "{!! url('/') !!}" + '/api/category',
    // 			dataType: 'json',
    // 			delay: 1000,
    // 			processResults: function (data) {
    // 				return {
    // 					results: $.map(data, function (item) {
    // 						return {
    // 							text: item.name,
    // 							id: item.id
    // 						}
    // 					})
    // 				};
    // 			},
    // 			cache: true
    // 		}
    // 	});
    // 	$('.inventory').select2({
    //     selectOnClose: true,
    //     placeholder: 'Pilih stok',
    //     ajax: {
    //         url: "{!! url('/') !!}" + '/api/inventoryproperty',
    //         dataType: 'json',
    //         delay: 1000,
    //         processResults: function (data) {
    //             return {
    //                 results: $.map(data, function (item) {
    //                     return {
    //                         text: item.item.item_name,
    //                         id: item.id
    //                     }
    //                 })
    //             };
    //         },
    //         cache: true
    //     }
    // });
    $('.warehouse').select2({
        allowClear: true,
        selectOnClose: true,
        placeholder: 'Pilih Gudang',
        ajax: {
            url: "{!! url('/') !!}" + '/api/gudang',
            dataType: 'json',
            delay: 1000,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.warehouse_name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
    $('.sales').select2({
        allowClear: true,
        selectOnClose: true,
        placeholder: 'Pilih Sales',
        ajax: {
            url: "{!! url('/') !!}" + '/api/user',
            dataType: 'json',
            delay: 1000,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.displayName,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });


    $('.userall').select2({
        selectOnClose: true,
        placeholder: 'Pilih Karyawan',
        ajax: {
            url: "{!! url('/') !!}" + '/api/user',
            dataType: 'json',
            delay: 1000,
            processResults: function(data) {

                return {
                    results: $.map(data, function(item) {

                        return {
                            text: item.displayName,
                            id: item.id

                        }

                    })

                };
            },
            cache: true

        }

    });


    $('.inventaris').select2({
        selectOnClose: true,
        placeholder: 'Pilih Inventaris',
        ajax: {
            url: "{!! url('/') !!}" + '/api/inventaris',
            dataType: 'json',
            delay: 1000,
            processResults: function(data) {

                return {
                    results: $.map(data, function(item) {

                        return {
                            text: item.name,
                            id: item.id

                        }

                    })

                };
            },
            cache: true

        }

    });


    $('.katpeng').select2({
        selectOnClose: true,
        placeholder: 'Pilih Kategori Pengeluaran',
        ajax: {
            url: "{!! url('/') !!}" + '/api/kategori-pengeluaran',
            dataType: 'json',
            delay: 1000,
            processResults: function(data) {

                return {
                    results: $.map(data, function(item) {

                        return {
                            text: item.name,
                            id: item.id

                        }

                    })

                };
            },
            cache: true

        }

    });

    $('.katpengadmin').select2({
        selectOnClose: true,
        placeholder: 'Pilih Kategori Pengeluaran',
        ajax: {
            url: "{!! url('/') !!}" + '/api/kategori-pengeluaran',
            dataType: 'json',
            delay: 1000,
            processResults: function(data) {

                return {
                    results: $.map(data, function(item) {
                        if (item.name != "Gaji" && item.name != "Komisi" && item.name != "Thr") {
                            return {
                                text: item.name,
                                id: item.id

                            }
                        }
                    })

                };
            },
            cache: true

        }

    });
</script>
@endpush