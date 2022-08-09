<!DOCTYPE html>
<html>

<head>
    <title>{{ $filter ?? 'Laporan' }}</title>
    {{-- <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables/datatables.css') }}" /> --}}
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">

</head>

<body class="container-fluid bg-white">
    <style>
        @media print {
            .table {
                overflow: visible !important;
            }
        }

    </style>
