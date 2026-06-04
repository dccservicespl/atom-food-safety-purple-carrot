<!DOCTYPE html>
<html data-bs-theme="light" lang="en-US" dir="ltr">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================--><!--    Document Title--><!-- ===============================================-->
    <title>{{ env('APP_NAME') }}</title>
    <!-- ===============================================--><!--    Favicons--><!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicons/favicon.ico">
    <link rel="manifest" href="/assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="/assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <script src="/assets/js/config.js"></script>
    <script src="/assets/vendors/simplebar/simplebar.min.js"></script>

    <!-- ===============================================--><!--    Stylesheets--><!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap"
        rel="stylesheet">
    <link href="/assets/vendors/simplebar/simplebar.min.css">
    <link href="/assets/css/theme-rtl.min.css" rel="stylesheet">
    <link href="/assets/css/theme.min.css" rel="stylesheet">
    <link href="/assets/css/user-rtl.min.css" rel="stylesheet">
    <link href="/assets/css/user.min.css" rel="stylesheet">
    <link href="/assets/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.css">
</head>
<style>
    .error {
        color: red;
    }

    label.error {
        width: 100% !important
    }

    .icon {
        position: relative;
        display: inline-block;
        margin-right: 10px;
        top: 5px;
    }
</style>

<body>
    <!-- ===============================================--><!--    Main Content--><!-- ===============================================-->
    <main class="main" id="top">
        <div class="container-fluid">
            <script>
                var isFluid = JSON.parse(localStorage.getItem('isFluid'));
                if (isFluid) {
                    var container = document.querySelector('[data-layout]');
                    container.classList.remove('container');
                    container.classList.add('container-fluid');
                }
            </script>
            <div class="row min-vh-100  bg-white">
                <div class="col-sm-10 col-md-10 col-lg-6 col-xl-6 px-sm-0 align-self-center mx-auto py-5">
                    <div class="row justify-content-center g-0">
                        <div class="col-sm-10 col-lg-10 col-xl-10 col-md-10 col-xxl-6">
                            <div class="">
                                <div class="card-header bg-circle-shape text-center p-2">
                                    <a class="font-sans-serif fw-bolder fs-5 z-1 position-relative link-light text-decoration-none"
                                        href="" data-bs-theme="light">
                                        <img src="/assets/img/logo.png" style="height:300px" alt="">
                                    </a>
                                    <div class="mt-3 mb-3">
                                        <h2 class="login_title">F<span class="text-primary">OO</span>D SAFETY</h2>
                                    </div>
                                    <p class="text-center text-900 font-weight-bolder mt-3 h4">Login into your account
                                    </p>
                                </div>
                                <div class="card-body p-4">
                                    <div class="mt-3 mb-3">
                                        @if (Session('error'))
                                        <div class="alert alert-danger mt-4 mb-4" role="alert">
                                            {{ session('error') }}
                                        </div>
                                        @endif

                                        @if (Session('success'))
                                        <div class="alert alert-success mt-4 mb-4" role="alert">
                                            {{ session('success') }}
                                        </div>
                                        @endif
                                    </div>
                                    <form class="needs-validation" action="{{ route('admin_login_action') }}"
                                        method="POST" id="login_form">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="split-login-email">Email Address</label>
                                            <div class="input-group">
                                                <input class="form-control login_input p-3" id="split-login-email"
                                                    type="text" value="{{ old('email') }}" name="email" required
                                                    placeholder="alex@email.com" />
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <label class="form-label" for="split-login-password">Password</label>
                                            </div>
                                            <div class="input-group">
                                                <input class="form-control login_input p-3" id="split-login-password"
                                                    placeholder="Enter your password" type="password" name="password"
                                                    required />
                                            </div>
                                        </div>

                                        <div class="mb-3 mt-5">
                                            <button class="btn btn-primary d-block w-100 mt-3 p-3 login_btn"
                                                type="submit">Login Now</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- ===============================================--><!--    End of Main Content--><!-- ===============================================-->

    <!-- ===============================================--><!--    JavaScripts--><!-- ===============================================-->
    <script src="/assets/vendors/popper/popper.min.js"></script>
    <script src="/assets/vendors/bootstrap/bootstrap.min.js"></script>
    <script src="/assets/vendors/anchorjs/anchor.min.js"></script>
    <script src="/assets/vendors/is/is.min.js"></script>
    <script src="/assets/vendors/fontawesome/all.min.js"></script>
    <script src="/assets/vendors/lodash/lodash.min.js"></script>
    <script src="/assets/vendors/list.js/list.min.js"></script>
    <script src="/assets/js/theme.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>


    <script>
        $.validator.addMethod("customEmail", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|in|co\.in|net)$/i
                .test(value);
        }, "Please enter a valid email address.");
        $(document).ready(function() {
            $("#login_form").validate({
                rules: {
                    email: {
                        required: true,
                        //email: true,
                        //customEmail: true
                    }
                },
                messages: {
                    email: {
                        required: "Please enter an email address.",
                        //email: "Please enter a valid email address.",
                        //customEmail: "Please enter a valid email address."
                    }
                },
            });
        });
    </script>
</body>

</html>