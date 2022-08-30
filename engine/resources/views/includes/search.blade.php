<!-- Search Form (visible on larger screens) -->
<form autocomplete="off" class="d-inline-flex" action="" method="">
    @if(Auth::user()->role_id!=3)
    <div class="row gtText" style="display: none">
        <label class="col-sm-2 offset-sm-2 col-form-label" for="example-hf-email" id="toggleModal">Total</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="grand_total" readonly>
        </div>
    </div>
    <div class="row gtPPNText" style="display: none">
        <label class="col-sm-5 offset-sm-1 col-form-label" for="example-hf-email" id="toggleModal">Total dengan PPN</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" id="grand_total_ppn" readonly>
        </div>
    </div>
    <div class="row history ms-4" style="display: none">
        <div class="col-sm-12">
            <button type="button" class="btn btn-alt-primary push" data-bs-toggle="modal" data-bs-target="#salesorderhistory">Riwayat Hutang</button>
        </div>
    </div>
    @endif
    {{-- <div class="row history ms-4" style="display: none">
        <div class="col-sm-12">
            <button type="button" class="btn btn-alt-primary push" data-bs-toggle="modal" data-bs-target="#saleslinehistory">Riwayat Barang</button>
        </div>
    </div> --}}
</form>
@push("js")
<script>
    $("#toggleModal").click(function(){
        $("#modal").toggle();
    })
</script>
@endpush
