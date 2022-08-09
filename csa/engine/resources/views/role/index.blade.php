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
                <h3 class="block-title">Dynamic Table <small>Export Buttons</small></h3>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                <div class ="table-responsive"><table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 80px;">ID</th>
                            <th>Name</th>
                            <th class="d-none d-sm-table-cell" style="width: 30%;">Email</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">Access</th>
                            <th style="width: 15%;">Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center fs-sm">1</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Jesse Fisher</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client1<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Disabled</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">5 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">2</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Jose Mills</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client2<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">3 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">3</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">David Fuller</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client3<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Disabled</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">8 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">4</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Wayne Garcia</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client4<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">7 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">5</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Judy Ford</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client5<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Disabled</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">4 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">6</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Justin Hunt</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client6<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">VIP</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">10 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">7</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Sara Fields</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client7<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">8 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">8</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Danielle Jones</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client8<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">VIP</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">4 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">9</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Jack Greene</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client9<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">9 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">10</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Betty Kelley</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client10<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Disabled</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">3 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">11</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Carl Wells</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client11<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Disabled</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">3 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">12</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Megan Fuller</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client12<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Disabled</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">3 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">13</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Carl Wells</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client13<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Disabled</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">5 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">14</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Jose Mills</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client14<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">VIP</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">8 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">15</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Judy Ford</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client15<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">8 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">16</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Jack Greene</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client16<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning">Trial</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">9 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">17</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Andrea Gardner</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client17<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Disabled</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">9 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">18</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Judy Ford</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client18<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning">Trial</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">10 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">19</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Barbara Scott</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client19<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning">Trial</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">2 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">20</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Barbara Scott</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client20<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning">Trial</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">9 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">21</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Barbara Scott</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client21<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Disabled</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">9 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">22</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Jack Greene</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client22<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">10 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">23</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Barbara Scott</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client23<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">6 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">24</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Helen Jacobs</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client24<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">VIP</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">7 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">25</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Wayne Garcia</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client25<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning">Trial</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">2 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">26</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Albert Ray</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client26<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Disabled</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">2 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">27</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Carl Wells</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client27<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">6 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">28</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">David Fuller</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client28<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">7 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">29</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Jesse Fisher</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client29<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">6 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">30</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Lisa Jenkins</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client30<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">6 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">31</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Susan Day</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client31<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">9 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">32</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Brian Stevens</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client32<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">VIP</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">5 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">33</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Carol White</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client33<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning">Trial</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">5 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">34</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Betty Kelley</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client34<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning">Trial</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">5 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">35</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Megan Fuller</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client35<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Disabled</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">6 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">36</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Jack Greene</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client36<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">VIP</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">8 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">37</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Laura Carr</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client37<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning">Trial</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">6 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">38</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Sara Fields</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client38<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">VIP</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">5 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">39</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Adam McCoy</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client39<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning">Trial</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">6 days ago</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center fs-sm">40</td>
                            <td class="fw-semibold fs-sm">
                                <a href="be_pages_generic_profile.html">Carl Wells</a>
                            </td>
                            <td class="d-none d-sm-table-cell fs-sm">
                                client40<span class="text-muted">@example.com</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">Business</span>
                            </td>
                            <td>
                                <span class="text-muted fs-sm">3 days ago</span>
                            </td>
                        </tr>
                    </tbody>
                </table></div>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
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
@endpush
