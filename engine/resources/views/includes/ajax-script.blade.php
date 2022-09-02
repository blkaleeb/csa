@push('js')
    <script>
        let html = ""
        $(document).ready(function() {

            $(".form-modal-btn").click(function() {
                dataurl = $(this).attr("data-url");
                $.ajax({
                        method: "GET",
                        url: dataurl,
                        data: {
                            mode: "popup"
                        }
                    })
                    .done(function(response) {
                        $("#formModal .modal-body").html(response)
                        $("#formModal").modal("show");

                    });
            });

            $("#formModal").on('shown.bs.modal', event => {
                $(".select2-modal").select2({
                    dropdownParent: $("#formModal .modal-body")
                });
            //     var select2Instance = $(".select2-modal").data('select2');
                // select2Instance.on('results:message', function(params){
                //   this.dropdown._resizeDropdown();
                //   this.dropdown._positionDropdown();
                // });
            })


        })
    </script>
@endpush
