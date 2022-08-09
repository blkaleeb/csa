<!doctype html>
<html lang="en">
@include("includes.css")

<body>
    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow side-trans-enabled">
        <!-- Main Container -->
        <main id="main-container">
            @yield("content")
        </main>
        <!-- END Main Container -->
    </div>
    <!-- END Page Container -->
    @include("includes.js")
</body>

</html>
