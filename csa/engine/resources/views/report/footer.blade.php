</body>
<script src="{{ asset('assets/js/oneui.app.min.js') }}"></script>
{{-- <script src="{{asset('assets/js/lib/jquery.min.js')}}"></script> --}}
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/pages/be_tables_datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
{{-- <script src="{{asset('assets/vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/datatables/datatables.min.js')}}"></script> --}}

<script type="text/javascript">
    var table = $('#tabels').DataTable({
        dom: 'lBfrtip',
        paging: false,
        buttons: [
            'copy', 'excel', 'pdf'
        ],
        "search": {
            "smart": true
        },

    });

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
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
    var classUang = document.getElementsByClassName("printUang");
    if (classUang.length > 0) {
        for (var i = 0; i < classUang.length; i++) {
            var val = classUang[i].innerHTML;
            if (val != '' && val != null && !isNaN(val)) {
                val = parseFloat(classUang[i].innerHTML);
                var res = "Rp. " + number_format(val, 2, ",", ".");
                classUang[i].innerHTML = res;
            }
        }
    }

    var classAngka = document.getElementsByClassName("printAngka");
    if (classAngka.length > 0) {
        for (var i = 0; i < classAngka.length; i++) {
            var val = classAngka[i].innerHTML;
            if (val != '' && val != null && !isNaN(val)) {
                val = parseFloat(classAngka[i].innerHTML);
                var res = number_format(val, 0, ",", ".");
                classAngka[i].innerHTML = res;
            }
        }
    }
</script>

</html>
