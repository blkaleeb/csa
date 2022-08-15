<div class="block-content block-content-full">

    <form autocomplete="off" action="{{ route('daftar-piutang.index') }}" class="collapse" id="collapseExample">

        <div class="row">
            <div class="col-lg-6">
                <label class="form-label" for="customer_filter">Customer</label>
                <input type="text" class="form-control" id="customer_filter" name="customer"
                    value="{{ $customer_filter ?? '' }}">
            </div>
            <div class="col-lg-6">
                <label class="form-label" for="invoice_filter">No Faktur</label>
                <input type="text" class="form-control" id="invoice_filter" name="invoice"
                    value="{{ $invoice_filter ?? '' }}">
            </div>

        </div>
        <div class="row">
            <div class="col-lg-6">
                <label class="form-label" for="due_date">Dari</label>
                <input type="text" class="js-flatpickr form-control" id="example-flatpickr-custom"
                    name="date_start" placeholder="hari-bulan-tahun"
                    value="{{ $date_start?->format('d F Y') }}" data-date-format="d F Y">
            </div>
            <div class="col-lg-6">
                <label class="form-label" for="due_date">Sampai</label>
                <input type="text" class="js-flatpickr form-control" id="example-flatpickr-custom"
                    name="date_end" placeholder="hari-bulan-tahun" value="{{ $date_end?->format('d F Y') }}"
                    data-date-format="d F Y">
            </div>

        </div>
        <div class="col-lg-12 mt-3">
            <button type="submit" class="btn btn-info form-control">Filter</button>
        </div>
    </form>
</div>
