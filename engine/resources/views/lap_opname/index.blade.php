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
                    <h3 class="block-title">Laporan Opname</h3>
                </div>
                <div class="float-end">
                    <a href="javascript:void(0)" id="tambahbtn" class="btn btn-info block-title">Tambah Opname</a>
                </div>
            </div>
            <br>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full-pagination dataTable no-footer class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter js-dataTable-buttons no-footer">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Tanggal Opname</th>
                            <th>Kuantitas</th>
                            <th>Updated By</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{date("d-m-Y",strtotime($item->date))}}</td>
                            <td>{{number_format($item->totalqty)}}</td>
                            <td></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                        id="dropdown-default-primary" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-primary">
                                        <a class="dropdown-item"  href="javascript:void(0)" id="detailbtn" data-attr="{{ route('lap_opname.show', $item->date) }}">Detail</a>
                                        <!-- <a class="dropdown-item"  href="javascript:void(0)" id="" data-attr="{{ route('lap_opname.show', $item->id) }}">Edit</a> -->
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table></div>
                {{ $data->links() }}
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
</div>

<!-- Large Block Modal -->
<div class="modal" id="modal-block-extra-large" tabindex="-1" role="dialog" aria-labelledby="modal-block-extra-large" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                  <div class="block-header block-header-default">
                    <h3 class="block-title">Detail Laporan Opname</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="block-content fs-sm" id="smallBody">
                        <div class="col-md-12 box">
                            <div class="col-md-12" id="bbb">
                                <table id="" class="table table-bordered table-stripped">
                                    <thead>
                                        <th>No.</th>
                                        <th>Tanggal Opname</th>
                                        <th>Barang</th>
                                        <th>QTY Sebelum</th>
                                        <th>QTY Sesudah</th>
                                                    </thead>
                                                    <tbody id="tbl-detail">
                                                    </tbody>
                                                </table></div>

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

          <!-- Large Block Modal -->
        <div class="modal" id="modal-tambah" role="dialog" aria-labelledby="modal-tambah" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                  <div class="block-header block-header-default">
                    <h3 class="block-title">Tambah Opname</h3>
                    <div class="block-options">
                      <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="block-content fs-sm" id="smallBody">
                    <div class="row">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Berdasarkan Kategori</label>
                        <div class="col-sm-8 ms-auto">
                            <select name="" id="category" class="select2 w-100 select-modal">
                                @foreach ($category as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Berdasarkan Merk</label>
                        <div class="col-sm-8 ms-auto">
                            <select name="" id="brand" class="select2 w-100 select-modal">
                                @foreach ($brand as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Cari Berdasarkan Nama</label>
                        <div class="col-sm-8 ms-auto">
                            <input type="text" id="name" class="form-control">
                        </div>
                    </div>
                    <form action="{{route('lap_opname.store')}}" method="post">
                        @csrf
                        <!-- DataTables init on table by adding .js-dataTable-full-pagination dataTable no-footer class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                        <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter js-dataTable-buttons no-footer">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Barang</th>
                                    <th class="d-none d-sm-table-cell" style="width: 30%;">Qty di sistem</th>
                                    <th class="d-none d-sm-table-cell" style="width: 15%;">Qty Hasil Opname</th>
                                </tr>
                            </thead>
                            <tbody id="opname_item">
                            </tbody>
                        </table></div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-info form-control" value="Submit Opname">
                        </div>
                    </form>
                  </div>
                  <div class="block-content block-content-full text-end bg-body">
                    <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END Large Block Modal -->

<!-- END Page Content -->
<script>
    //display modal tambah
    $(document).on('click', '#tambahbtn', function(event) {
        event.preventDefault();
        $('#modal-tambah').modal("show");
    });
    // display modal detail
    $(document).on('click', '#detailbtn', function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                // return the result
                success: function(result) {
                    $('#modal-block-extra-large').modal("show");
                    var obj = JSON.parse(result);
                    var data = obj.data;
                    var html = '';
                    for(let i = 0; i< data.length; i++){
                        console.log(data[i]["name"]);
                        html += '<tr>';
                        html += '<td>'+(i+1)+'</td>';
                        html += '<td>'+data[i]["date"]+'</td>';
                        html += '<td>';
                        if(data[i]["name"] == ''){
                            html += '<b>Barang Dihapus</b>';
                        }else{
                            html += data[i]["name"];
                        }
                        html += '</td>';
                        html += '<td>'+data[i]["qty_before"]+'</td>';
                        html += '<td>'+data[i]["qty"]+'</td>';
                        html += '</tr>';
                    }
                    $("#tbl-detail").html(html);
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                }
            })
        });
</script>
@endsection
@push("js")
<script>
    $(".select-modal").select2({ dropdownParent: "#modal-tambah",width: '100%' });
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
    first_time = 1;
    category_id = 0;
    brand_id = 0;
    old_name = "";
    $("#category").change(function(){
        var id = $(this).find("option").filter(":selected").val();
        if(first_time == 0){
            Swal.fire({
            title: 'Data yang sudah diinput tidak akan tersimpan, Lanjutkan?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Iya',
            denyButtonText: `Tidak`,
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                category_id = id;
                $.ajax({
                    method: "post",
                    url: "{{url('api/itemstock/category')}}",
                    data: { id: id}
                }).done(function( data ) {
                        refreshTable(data)
                });
            } else if (result.isDenied) {
                // Swal.fire('Changes are not saved', '', 'info')
            }
            })
        }else{
            var id = $(this).find("option").filter(":selected").val();
                console.log(id);
                $.ajax({
                    method: "post",
                    url: "{{url('api/itemstock/category')}}",
                    data: { id: id}
                }).done(function( data ) {
                        refreshTable(data)
                });
                first_time = 0;
        }
    })

    $("#brand").change(function(){
        var id = $(this).find("option").filter(":selected").val();
        if(first_time == 0){
            Swal.fire({
            title: 'Data yang sudah diinput tidak akan tersimpan, Lanjutkan?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Iya',
            denyButtonText: `Tidak`,
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                brand_id = id;
                $.ajax({
                    method: "post",
                    url: "{{url('api/itemstock/brand')}}",
                    data: { id: id}
                }).done(function( data ) {
                        refreshTable(data)
                });
            } else if (result.isDenied) {
                // Swal.fire('Changes are not saved', '', 'info')
            }
            })
        }else{
            var id = $(this).find("option").filter(":selected").val();
                console.log(id);
                $.ajax({
                    method: "post",
                    url: "{{url('api/itemstock/brand')}}",
                    data: { id: id}
                }).done(function( data ) {
                        refreshTable(data)
                });
                first_time = 0;
        }
    })

    $("#name").change(function(){
        var name = $(this).val();

        if(first_time == 0){
            Swal.fire({
            title: 'Data yang sudah diinput tidak akan tersimpan, Lanjutkan?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Iya',
            denyButtonText: `Tidak`,
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                old_name = name;
                $.ajax({
            method: "post",
            url: "{{url('api/itemstock/name')}}",
            data: { name: name}
        }).done(function( data ) {
                refreshTable(data)
        });
            } else if (result.isDenied) {
                // Swal.fire('Changes are not saved', '', 'info')
            }
            })
        }else{
            $.ajax({
            method: "post",
            url: "{{url('api/itemstock/name')}}",
            data: { name: name}
        }).done(function( data ) {
                refreshTable(data)
        });
                first_time = 0;
        }

    })

    function refreshTable(data){
        $(".js-dataTable-full-pagination dataTable no-footer").DataTable().destroy();
        var table = $("#opname_item")
        table.empty()
        $.each(data,function(k,v){
            var no = `<td class="number text-center fs-sm">`+(parseInt(k)+1)+`</td>`
            var name= `<td class="number text-center fs-sm">`+v.name+`</td>`
            var qtysystem= `<td class="number text-center fs-sm">`+v.qty+`</td>`
            var input= `<td class="number text-center fs-sm"><input type="number" min="0" class="form-control" name="item[`+v.id+`]" value="`+v.qty+`"></td>`
            table.append(`<tr>`+no+name+qtysystem+input+`</tr>`);
        })
        $(".js-dataTable-full-pagination dataTable no-footer").DataTable({pageLength:10,lengthMenu:[[5,10,15,20],[5,10,15,20]],autoWidth:!1,buttons:["copy","csv","excel","pdf","print"],dom:"<'row'<'col-sm-12'<'text-center bg-body-light py-2 mb-2'B>>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"}).draw();

    }
</script>
@endpush
