@extends('layouts.master-sidebar')
@push("css")
<!-- Stylesheets -->
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="{{asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endpush
@section('content')
<!-- Page Content -->
<div class="content">
    <div class="row">
        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Opname</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <label class="col-sm-4 col-form-label" for="example-hf-email">Berdasarkan Kategori</label>
                    <div class="col-sm-8 ms-auto">
                        <select name="" id="category" class="select2 w-100">
                            @foreach ($category as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-4 col-form-label" for="example-hf-email">Berdasarkan Merk</label>
                    <div class="col-sm-8 ms-auto">
                        <select name="" id="brand" class="select2 w-100">
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
                <form action="{{route('opname.store')}}" method="post">
                    @csrf
                    <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                    <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
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
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
    <div class="row">
        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
        </div>
    </div>
</div>
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
        $(".js-dataTable-buttons").DataTable().destroy();
        var table = $("#opname_item")
        table.empty()
        $.each(data,function(k,v){
            var no = `<td class="number text-center fs-sm">`+(parseInt(k)+1)+`</td>`
            var name= `<td class="number text-center fs-sm">`+v.name+`</td>`
            var qtysystem= `<td class="number text-center fs-sm">`+v.qty+`</td>`
            var input= `<td class="number text-center fs-sm"><input type="number" min="0" class="form-control" name="item[`+v.id+`]" value="`+v.qty+`"></td>`
            table.append(`<tr>`+no+name+qtysystem+input+`</tr>`);
        })
        $(".js-dataTable-buttons").DataTable({pageLength:10,lengthMenu:[[5,10,15,20],[5,10,15,20]],autoWidth:!1,buttons:["copy","csv","excel","pdf","print"],dom:"<'row'<'col-sm-12'<'text-center bg-body-light py-2 mb-2'B>>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"}).draw();

    }
</script>
@endpush
