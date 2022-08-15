<!--
        OneUI JS

        Core libraries and functionality
        webpack is putting everything together at assets/_js/main/app.js
    -->
@if (!isset($mode))
    <script src="{{ asset('assets/js/oneui.app.min.js') }}"></script>
    {{-- <script src="{{asset('assets/js/lib/jquery.min.js')}}"></script> --}}
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
        crossorigin="anonymous"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('assets/js/plugins/chart.js/chart.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <!-- Page JS Code -->
    <script src="{{ asset('assets/js/pages/op_auth_signin.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>

    <!-- Page JS Code -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        One.helpersOnLoad([
            'js-flatpickr',
        ]);

        document.addEventListener("wheel", function(event) {
            if (document.activeElement.type === "number") {
                document.activeElement.blur();
            }
        });

        $(".select2").select2({
            placeholder: 'Silahkan pilih'
        });
        $(".delete").click(function() {
            let form = $(this).siblings("form");
            console.log(form);
            //swal

            Swal.fire({
                title: 'Data akan dihapus',
                showDenyButton: true,
                showCancelButton: true,
                input: 'textarea',
                inputLabel: 'Alasan',
                confirmButtonText: 'Hapus',
                denyButtonText: `Batal`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    if (result.value) {
                        console.log("Result: " + result.value);
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'reason',
                            value: result.value
                        }).appendTo(form);
                        Swal.fire('Data Dihapus!', '', 'success')
                        form.submit();
                    }

                } else if (result.isDenied) {
                    Swal.fire('Batal', '', 'info')
                }
            })
        })

        $(".logout").click(function() {
            let form = $(this).siblings("form");
            console.log(form);
            //swal
            Swal.fire({
                title: 'Logout',
                showCancelButton: true,
                confirmButtonText: 'Ya',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    form.submit();
                } else if (result.isDenied) {}
            })
        })
    </script>
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
    </script>

    @if (Session::has('message'))
        <script>
            toastr.success("{{ session('message') }}");
        </script>
    @endif
    @if (Session::has('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif

    @if (Session::has('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error("{{ $error }}");
            </script>
        @endforeach
    @endif
    @if (Session::has('info'))
        <script>
            toastr.info("{{ session('info') }}");
        </script>
    @endif

    @if (Session::has('warning'))
        <script>
            toastr.warning("{{ session('warning') }}");
        </script>
    @endif

    <script>
        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            return s.join(dec);
        }

        function addDecimal(val) {
            var value = "Rp " + number_format(val, 2, ",", ".");
            return value;
        }

        function updateRowOrder() {
            $('.number').each(function(i) {
                $(this).text(i + 1);
            });
            // $('tr.id').each(function(i) {
            //     $(this).attr('id', i + 1);
            // });
        }
    </script>
@endif

@stack('js')
