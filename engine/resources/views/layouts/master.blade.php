<!doctype html>
<html lang="en">
@include("includes.css")
<body>
    <div id="page-container"
        class="main-content-narrow">
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
