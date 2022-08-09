<!doctype html>
<html lang="en">

<head>
    @include("includes.head-meta")
</head>

<body>

    <div id="page-container">

        <!-- Main Container -->
        <main id="main-container">

            <!-- Page Content -->
            <div class="hero-static d-flex align-items-center">
                <div class="content">
                    <div class="row justify-content-center push">
                        <div class="col-md-12 col-lg-6 col-xl-6">
                            <!-- Sign In Block -->
                            <div class="block block-rounded mb-0">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">Sign In</h3>
                                    <div class="block-options">

                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5">
                                        <div class="row d-block">

                                            <div class="col-lg-12 text-center">
                                                <img src="{{asset('assets/media/logo.png')}}" alt=""
                                                    class="img-fluid " style="height: 100px">
                                                <p class="fw-medium text-muted">
                                                    Welcome, please login.
                                                </p>
                                            </div>

                                        </div>


                                        <!-- Sign In Form -->
                                        <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _js/pages/op_auth_signin.js) -->
                                        <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                        <form class="js-validation-signin" action="{{route('login')}}" method="POST">
                                            @csrf
                                            <div class="py-3">
                                                <div class="mb-4">
                                                    <input type="text"
                                                        class="form-control form-control-alt form-control-lg"
                                                        id="username" name="username" placeholder="Username">
                                                </div>
                                                <div class="mb-4">
                                                    <input type="password"
                                                        class="form-control form-control-alt form-control-lg"
                                                        id="password" name="password" placeholder="Password">
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-md-6 col-xl-5">
                                                    <button type="submit" class="btn w-100 btn-alt-primary">
                                                        <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Sign In
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- END Sign In Form -->
                                    </div>
                                </div>
                            </div>
                            <!-- END Sign In Block -->
                        </div>
                    </div>
                    <div class="fs-sm text-muted text-center">
                        <strong>OneUI 5.1</strong> &copy; <span data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
            <!-- END Page Content -->
        </main>
        <!-- END Main Container -->
    </div>
    <!-- END Page Container -->

    <!--
        OneUI JS

        Core libraries and functionality
        webpack is putting everything together at {{asset('assets/_js/main/app.js')}}
    -->
    @include("includes.js")
</body>

</html>
