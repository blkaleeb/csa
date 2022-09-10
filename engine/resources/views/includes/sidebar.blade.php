<!-- Sidebar -->
<!--
          Sidebar Mini Mode - Display Helper classes

          Adding 'smini-hide' class to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
          Adding 'smini-show' class to an element will make it visible (opacity: 1) when the sidebar is in mini mode
              If you would like to disable the transition animation, make sure to also add the 'no-transition' class to your element

          Adding 'smini-hidden' to an element will hide it when the sidebar is in mini mode
          Adding 'smini-visible' to an element will show it (display: inline-block) only when the sidebar is in mini mode
          Adding 'smini-visible-block' to an element will show it (display: block) only when the sidebar is in mini mode
      -->
<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="content-header">
        <!-- Logo -->
        <a class="fw-semibold text-dual" href="index.html">
            <span class="smini-visible">
                <i class="fa fa-circle-notch text-primary"></i>
            </span>
            <span class="smini-hide fs-5 tracking-wider">{{ getenv('APP_NAME') }}</span>
        </a>
        <!-- END Logo -->

        <!-- Extra -->
        <div>
            <!-- Dark Mode -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="layout"
                data-action="dark_mode_toggle">
                <i class="far fa-moon"></i>
            </button>
            <!-- END Dark Mode -->

            <!-- Options -->
            <div class="dropdown d-inline-block ms-1">
                <button type="button" class="btn btn-sm btn-alt-secondary" id="sidebar-themes-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-circle"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end fs-sm smini-hide border-0"
                    aria-labelledby="sidebar-themes-dropdown">
                    <!-- Color Themes -->
                    <!-- Layout API, functionality initialized in Template._uiHandleTheme() -->
                    <a class="dropdown-item d-flex align-items-center justify-content-between fw-medium"
                        data-toggle="theme" data-theme="default" href="#">
                        <span>Default</span>
                        <i class="fa fa-circle text-default"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between fw-medium"
                        data-toggle="theme" data-theme="assets/css/themes/amethyst.min.css" href="#">
                        <span>Amethyst</span>
                        <i class="fa fa-circle text-amethyst"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between fw-medium"
                        data-toggle="theme" data-theme="assets/css/themes/city.min.css" href="#">
                        <span>City</span>
                        <i class="fa fa-circle text-city"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between fw-medium"
                        data-toggle="theme" data-theme="assets/css/themes/flat.min.css" href="#">
                        <span>Flat</span>
                        <i class="fa fa-circle text-flat"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between fw-medium"
                        data-toggle="theme" data-theme="assets/css/themes/modern.min.css" href="#">
                        <span>Modern</span>
                        <i class="fa fa-circle text-modern"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between fw-medium"
                        data-toggle="theme" data-theme="assets/css/themes/smooth.min.css" href="#">
                        <span>Smooth</span>
                        <i class="fa fa-circle text-smooth"></i>
                    </a>
                    <!-- END Color Themes -->

                    <div class="dropdown-divider"></div>

                    <!-- Sidebar Styles -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <a class="dropdown-item fw-medium" data-toggle="layout" data-action="sidebar_style_light"
                        href="javascript:void(0)">
                        <span>Sidebar Light</span>
                    </a>
                    <a class="dropdown-item fw-medium" data-toggle="layout" data-action="sidebar_style_dark"
                        href="javascript:void(0)">
                        <span>Sidebar Dark</span>
                    </a>
                    <!-- END Sidebar Styles -->

                    <div class="dropdown-divider"></div>

                    <!-- Header Styles -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <a class="dropdown-item fw-medium" data-toggle="layout" data-action="header_style_light"
                        href="javascript:void(0)">
                        <span>Header Light</span>
                    </a>
                    <a class="dropdown-item fw-medium" data-toggle="layout" data-action="header_style_dark"
                        href="javascript:void(0)">
                        <span>Header Dark</span>
                    </a>
                    <!-- END Header Styles -->
                </div>
            </div>
            <!-- END Options -->

            <!-- Close Sidebar, Visible only on mobile screens -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout" data-action="sidebar_close"
                href="javascript:void(0)">
                <i class="fa fa-fw fa-times"></i>
            </a>
            <!-- END Close Sidebar -->
        </div>
        <!-- END Extra -->
    </div>
    <!-- END Side Header -->

    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
            <ul class="nav-main">
                @if(Auth::user()->role_id==3)
                <li class="nav-main-item ">
                    <a class="nav-main-link {{ (request()->is('requestsales')) ? 'active open' : '' }}"
                        href="{{ route('requestsales.index') }}">
                        <i class="nav-main-link-icon si si-badge"></i>
                        <span class="nav-main-link-name">Request For Sales</span>
                    </a>
                </li>
                <li class="nav-main-item ">
                    <a class="nav-main-link {{ (request()->is('listrequestsales')) ? 'active open' : '' }}"
                        href="{{ route('listrequestsales.index') }}">
                        <i class="nav-main-link-icon si si-energy"></i>
                        <span class="nav-main-link-name">Daftar Request</span>
                    </a>
                </li>
                @else
                <li class="nav-main-item ">
                    <a class="nav-main-link {{ (request()->is('/')) ? 'active open' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="nav-main-link-icon si si-speedometer"></i>
                        <span class="nav-main-link-name">Dashboard</span>
                    </a>
                </li>
                <li class="nav-main-heading">Transaksi</li>
                <li class="nav-main-item {{ (request()->is('penjualan*')) ? 'active open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                        aria-expanded="false" href="#">
                        <i class="nav-main-link-icon si si-energy"></i>
                        <span class="nav-main-link-name">Penjualan</span>
                    </a>
                    <ul class="nav-main-submenu">

                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('penjualan/create-invoice*')) ? 'active' : '' }}"
                                href="{{ route('penjualan-new.create-invoice.create') }}">
                                <span class="nav-main-link-name">Buat Faktur</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('penjualan/daftar-piutang*')) ? 'active' : '' }}"
                                href="{{ route('penjualan-new.daftar-piutang.index') }}">
                                <span class="nav-main-link-name">Daftar Piutang</span>
                            </a>
                        </li>

                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('penjualan/daftar-void*')) ? 'active' : '' }}"
                                href="{{ route('penjualan-new.daftar-void.index') }}">
                                <span class="nav-main-link-name">Daftar Void</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('penjualan/retur*')) ? 'active' : '' }}"
                                href="{{ route('penjualan-new.retur.index') }}">
                                <span class="nav-main-link-name">Retur Penjualan</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('penjualan/laporan*')) ? 'active' : '' }}"
                                href="{{ route('penjualan-new.laporan.index') }}">
                                <span class="nav-main-link-name">Laporan Penjualan</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-main-item {{ (request()->is('po*')) || (request()->is('penerimaan*')) || (request()->is('hutang*')) || (request()->is('retur_pembelian*')) ? 'active open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                        aria-expanded="false" href="#">
                        <i class="nav-main-link-icon si si-badge"></i>
                        <span class="nav-main-link-name">Pembelian</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('po*')) ? 'active' : '' }}"
                                href="{{ route('po.index') }}">
                                <span class="nav-main-link-name">PO</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('penerimaan*')) ? 'active' : '' }}"
                                href="{{ route('penerimaan.index') }}">
                                <span class="nav-main-link-name">Penerimaan</span>
                            </a>
                        </li>

                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('hutang*')) ? 'active' : '' }}"
                                href="{{ route('hutang.index') }}">
                                <span class="nav-main-link-name">Pembayaran Hutang</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('retur_pembelian*')) ? 'active' : '' }}"
                                href="{{ route('retur_pembelian.index') }}">
                                <span class="nav-main-link-name">Retur Pembelian</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li
                    class="nav-main-item {{ (request()->is('pengeluaran*')) || (request()->is('kategori_pengeluaran*')) || (request()->is('inventoris*')) ? 'active open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                        aria-expanded="false" href="#">
                        <i class="nav-main-link-icon si si-note"></i>
                        <span class="nav-main-link-name">Pengeluaran</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('pengeluaran*')) ? 'active' : '' }}"
                                href="{{ route('pengeluaran.index') }}">
                                <span class="nav-main-link-name">Data</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('kategori_pengeluaran*')) ? 'active' : '' }}"
                                href="{{ route('kategori_pengeluaran.index') }}">
                                <span class="nav-main-link-name">Kategori</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('inventoris*')) ? 'active' : '' }}"
                                href="{{ route('inventoris.index') }}">
                                <span class="nav-main-link-name">Inventoris</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-main-item {{ (request()->is('penerimaan_barang*')) || (request()->is('stok_barang*')) || (request()->is('lap_opname*')) || (request()->is('permintaan_retur*'))  ? 'active open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                        aria-expanded="false" href="#">
                        <i class="nav-main-link-icon si si-drawer"></i>
                        <span class="nav-main-link-name">Inventory</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('penerimaan_barang*')) ? 'active' : '' }}"
                                href="{{ route('penerimaan_barang.index') }}">
                                <span class="nav-main-link-name">Daftar Penerimaan Barang</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('stok_barang*')) ? 'active' : '' }}"
                                href="{{ route('stok_barang.index') }}">
                                <span class="nav-main-link-name">Daftar Stok Barang</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('lap_opname*')) ? 'active' : '' }}"
                                href="{{ route('lap_opname.index') }}">
                                <span class="nav-main-link-name">Laporan Opname</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ (request()->is('permintaan_retur*')) ? 'active' : '' }}"
                                href="{{ route('permintaan_retur.index') }}">
                                <span class="nav-main-link-name">Daftar Permintaan Retur</span>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-main-heading">Master</li>
                <li
                    class="nav-main-item {{ (request()->is('karyawan*')) || (request()->is('role*')) ? 'active open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                        aria-expanded="false" href="#">
                        <i class="nav-main-link-icon si si-layers"></i>
                        <span class="nav-main-link-name">Account</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ (request()->is('karyawan*')) ? 'active' : '' }}"
                                href="{{ route('karyawan.index') }}">
                                <span class="nav-main-link-name">Karyawan</span>
                            </a>
                        </li>
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ (request()->is('role*')) ? 'active' : '' }}"
                                href="{{ route('role.index') }}">
                                <span class="nav-main-link-name">Role</span>
                            </a>
                        </li>

                    </ul>
                </li>
                <li
                    class="nav-main-item {{ (request()->is('kategori*')) || (request()->is('merk*')) || (request()->is('stok*')) || (request()->is('opname*')) ? 'active open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                        aria-expanded="false" href="#">
                        <i class="nav-main-link-icon si si-wrench"></i>
                        <span class="nav-main-link-name">Barang</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ (request()->is('kategori*')) ? 'active' : '' }}"
                                href="{{ route('kategori.index') }}">
                                <span class="nav-main-link-name">Kategori</span>
                            </a>
                        </li>
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ (request()->is('merk*')) ? 'active' : '' }}"
                                href="{{ route('merk.index') }}">
                                <span class="nav-main-link-name">Merk</span>
                            </a>
                        </li>
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ (request()->is('stok*')) ? 'active' : '' }}"
                                href="{{ route('stok.index') }}">
                                <span class="nav-main-link-name">Stok</span>
                            </a>
                        </li>
                        <li class="nav-main-item ">
                            <a class="nav-main-link {{ (request()->is('opname*')) ? 'active' : '' }}"
                                href="{{ route('opname.index') }}">
                                <span class="nav-main-link-name">Opname</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-main-item ">
                    <a class="nav-main-link {{ (request()->is('konsumen*')) ? 'active' : '' }}" aria-haspopup="true"
                        aria-expanded="true" href="{{ route('konsumen.index') }}">
                        <i class="nav-main-link-icon si si-magic-wand"></i>
                        <span class="nav-main-link-name">Konsumen</span>
                    </a>
                </li>
                <li class="nav-main-item ">
                    <a class="nav-main-link {{ (request()->is('supplier*')) ? 'active' : '' }}" aria-haspopup="true"
                        aria-expanded="true" href="{{ route('supplier.index') }}">
                        <i class="nav-main-link-icon si si-magic-wand"></i>
                        <span class="nav-main-link-name">Supplier</span>
                    </a>
                </li>
                <li class="nav-main-item ">
                    <a class="nav-main-link {{ (request()->is('gudang*')) ? 'active' : '' }}" aria-haspopup="true"
                        aria-expanded="false" href="{{ route('gudang.index') }}">
                        <i class="nav-main-link-icon si si-lock"></i>
                        <span class="nav-main-link-name">Gudang</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ (request()->is('report*')) ? 'active' : '' }}" aria-haspopup="true"
                        aria-expanded="false" href="{{ route('report.index') }}">
                        <i class="nav-main-link-icon si si-lock"></i>
                        <span class="nav-main-link-name">Report</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ (request()->is('log*')) ? 'active' : '' }}" aria-haspopup="true"
                        aria-expanded="false" href="{{ route('log.index') }}">
                        <i class="nav-main-link-icon si si-book-open"></i>
                        <span class="nav-main-link-name">Log Aktivitas</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
        <!-- END Side Navigation -->
    </div>
    <!-- END Sidebar Scrolling -->
</nav>
<!-- END Sidebar -->
