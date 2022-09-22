<div class="modal" id="salesorderhistory" tabindex="-1" aria-labelledby="salesorderhistory" style="display: none;"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-transparent mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Riwayat</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content fs-sm">
                    <div class ="table-responsive"><table class="table table-bordered table-hover box">
                        <thead class="replicate">
                            <th class="col-head">
                                No

                            </th>
                            <th class="col-head">
                                No Sales
                            </th>

                            <th class="col-head">
                                Tanggal Order
                            </th>
                            <th class="col-head">
                                Jatuh Tempo
                            </th>
                            <th class="col-head">
                                Total Faktur <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                                    title="Sudah dikurangi Retur dan Diskon"></i>
                            </th>
                            <th class="col-head">
                                Total Bayar <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                                    title="Total Yang sudah dibayarkan"></i>
                            </th>
                            <th class="col-head">
                                Retur
                            </th>
                            <th class="col-head">
                                Sisa Bayar <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                                    title="Sudah dikurangi Retur dan Diskon"></i>
                            </th>
                        </thead>

                        <tbody id="riwayat_sales">

                        </tbody>
                    </table></div>
                </div>
                <div class="block-content block-content-full text-end bg-body">
                    <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                        data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Perfect</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="saleslinehistory" tabindex="-1" aria-labelledby="saleslinehistory" style="display: none;"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-transparent mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Riwayat</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content fs-sm">
                    <div class ="table-responsive"><table class="table table-bordered table-hover box">
                        <thead>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th>QTY</th>
                            <th>Harga Satuan</th>
                        </thead>
                        <tbody id="riwayat_item">
                        </tbody>
                    </table></div>
                </div>
                <div class="block-content block-content-full text-end bg-body">
                    <button type="button" class="btn btn-sm btn-alt-secondary me-1"
                        data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Perfect</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>
