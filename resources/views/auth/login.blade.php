<!DOCTYPE html>
<html lang="en">


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="theme-color" content="#27415a" />
  <meta name="description"
    content="Discover E POS, your comprehensive POS system designed for supermarkets, retail stores, and more. Streamline transactions, manage inventory, and enhance customer experiences with our intuitive solution." />
  <title>E POS - Login</title>
  <link rel="apple-touch-icon" href="{{ asset('assets/img/epos-logo-192.png') }}" />
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/bundles/bootstrap-social/bootstrap-social.css') }}">
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
  <link rel='shortcut icon' type='image/x-icon' href="{{ asset('assets/img/favicon.png') }}" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <style>
    html {
      background: radial-gradient(circle, rgba(78, 109, 134, 1) 0%, rgba(29, 49, 66, 1) 100%);
      background-size: cover;
      background-position: center;
      background-repeat: repeat;
      height: 100vh !important;
    }

    body {
      background-color: transparent !important
    }

    .login-card {
      border: 3px #4baf3b solid;
      border-radius: 10px !important;
      transition: ease-in-out 0.5s;
      overflow: visible !important;
      margin-top: 15%;
      background: linear-gradient(150deg, #4e6d86 0%, #1d3142 100%);
    }

    @media (max-width: 576px) {

      .login-card::before,
      .login-card::after {
        display: none !important;
      }
    }

    .login-card:hover {
      /* transform: scale(1.02); */
      box-shadow: 0 3px 15px #4baf3b5e !important;
    }

    .login-card:hover .epos-logo-login {
      transform: scale(1.04);
    }

    .login-card:hover .telepos-icon {
      opacity: 0.2;
      filter: blur(5px);
    }

    .custom-input {
      width: 98%;
      height: 42px;
      color: #FFF !important;
      background-color: #1d3142 !important;
      margin-left: 3px;
      border: 1px solid #4baf3b;
      border-bottom-width: 3px;
      border-radius: 8px;
      transition: ease-in-out 0.5s;
    }

    .custom-input::placeholder {
      color: #e0ffd87f !important;
    }

    .custom-input:focus {
      outline: none !important;
      border-color: #4baf3b !important;
      background-color: #132434 !important;
      box-shadow: 0 3px 15px #4baf3b5e !important;
    }

    .custom-input:-webkit-autofill {
      -webkit-box-shadow: 0 0 0 30px #132434 inset !important;
      -webkit-text-fill-color: #DDD !important;
    }

    #login-btn {
      border-radius: 8px !important;
      transition: 0.5s ease-in-out;
      font-weight: 800 !important;
    }

    #login-btn:hover {
      transform: translateY(-0.1rem);
      box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.2);
      letter-spacing: 2px !important;
      text-shadow: 2px 2px 5px #1122409c;
    }

    .center {
      display: block;
      margin: auto;
      margin-right: auto;
      margin-top: auto;
      margin-bottom: auto;
      width: 90%;
      /* text-align: center; */
      /* padding: 50px; */
    }

    .customlabel {
      margin-bottom: 8px;
      font-weight: 800;
      color: #4baf3b;
      font-size: 12px;
      letter-spacing: 0.5px;
      text-shadow: 2px 2px 5px #1140329c;
    }

    .telepos-icon {
      opacity: 0.1;
      transition: ease-in-out 0.5s;
      width: 220px;
      position: absolute;
      top: auto;
      left: 50%;
      transform: translate(-50%, 10%);
    }

    .epos-logo-login {
      /* margin-top: 60px; */
      width: 280px;
      object-fit: contain;
      transition: 0.5s ease-in-out;
      filter: drop-shadow(0px 2px 5px #112240)
    }

    .eye-icon {
      color: #4baf3b;
      position: absolute;
      right: 16px;
      top: 40%;
      transform: translateY(-40%);
      cursor: pointer;
    }

    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    .comapany-name {
      font-family: 'Poppins', sans-serif !important;
      margin-top: -5px;
      transition: ease-in-out 0.5s;
    }

    .comapany-name .by {
      font-size: 12px !important;

    }

    .comapany-name .telesis {
      font-size: 25px !important;
      color: #14216a !important;
      letter-spacing: 12px;
      margin-bottom: 0;
      margin-left: 12px;
      text-shadow: 2px 2px 2px #ffffff;
      transition: ease-in-out 0.8s;
    }

    .comapany-name .soft-sol {
      font-size: 10px !important;
      letter-spacing: 3px;
      color: #4886ea !important;
      text-shadow: 2px 2px 2px #ffffff;
      padding-bottom: 10px;
      transition: ease-in-out 0.8s;
    }

    .comapany-name .soft-sol::after {
      content: '';
      height: 1px;
      background-color: #14216a;
      display: block;
      margin-top: 4px;
      scale: 0;
      transition: ease-in-out 0.8s;
    }

    .login-card:hover .comapany-name .telesis {
      letter-spacing: 15px !important;
      color: #4886ea !important;
      text-shadow: 2px 2px 1px #14216a;
    }

    .login-card:hover .comapany-name .soft-sol {
      letter-spacing: 4px !important;
      color: #14216a !important;
    }

    .login-card:hover .comapany-name .soft-sol::after {
      scale: 0.58;
    }

    .custom-hr-line {
      border-top: #4baf3b 1px solid !important;
      margin-top: 0 !important;
    }

    .login-card::after {
      content: '';
      position: absolute;
      bottom: -90px;
      right: -90px;
      width: 180px;
      height: 180px;
      background-color: #4aaf3b62;
      border-radius: 50%;
      flex-shrink: 0;
      filter: blur(50px);
      z-index: -1;
      transition: all 0.5s ease;
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: -90px;
      left: -90px;
      width: 180px;
      height: 180px;
      background-color: #4aaf3b62;
      border-radius: 50%;
      flex-shrink: 0;
      filter: blur(50px);
      z-index: -1;
      transition: all 0.5s ease;
    }

    .login-card:hover::before {
      width: 220px;
      height: 220px;
      top: -110px;
      left: -110px;
      filter: blur(55px);
    }

    .login-card:hover:after {
      width: 220px;
      height: 220px;
      bottom: -110px;
      right: -110px;
      filter: blur(55px);
    }
  </style>
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card login-card">
              <div class="center text-center">
                {{-- <img class="telepos-icon" src="{{ asset('assets/img/Telepos_icon.png') }}" alt=""> --}}
                <img alt="image" src="{{ asset('assets/img/EPOS-Logo.png') }}" width="280"
                  class="center epos-logo-login" />
                <hr class="custom-hr-line">

                {{-- <div class="text-center comapany-name">
                  <span class="by">By</span>
                  <h3 class="telesis">TELESIS</h3>
                  <h6 class="soft-sol">SOFTWARE SOLUTIONS</h6>
                </div> --}}
                {{--
                <hr> --}}
              </div>
              <div class="card-body" style="padding-bottom: 10px;">

                <form method="POST" action="/login" class="needs-validation" novalidate="">
                  @csrf
                  <div class="form-group" style="margin-top: -10px;">
                    <div class="text-center customlabel" for="email">EMAIL</div>
                    <input class="form-control text-center custom-input" type="email" name="email" tabindex="1" required
                      id="email" autofocus placeholder="Enter your email address">
                    <div class="invalid-feedback text-center">
                      Please enter your email!
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="d-block">
                      <div for="password" class="customlabel text-center">PASSWORD</div>
                      <div class="float-right">
                        {{-- <a href="auth-forgot-password.html" class="text-small">
                          Forgot Password?
                        </a> --}}
                      </div>
                    </div>
                    <div class="position-relative">
                      <input class="form-control text-center custom-input" type="password" name="password"
                        autocomplete="current-password" required id="password" placeholder="Enter your password">
                      <i class="bi bi-eye-slash eye-icon" id="togglePassword" onclick="myFunction();"></i>
                    </div>
                    <div class="invalid-feedback text-center">
                      Please enter your password!
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="text-danger text-center">
                      @if(isset($fail))
                      {{ $fail }}
                      @endif
                    </div>
                  </div>

                  <div class="form-group pt-2">
                    <button type="submit" id="login-btn" class="btn btn-tpos-primary btn-lg btn-block" tabindex="4">
                      LOGIN
                    </button>
                  </div>
                </form>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  </div>
  <script>
    function myFunction() {
      document.getElementById("togglePassword").classList.toggle("bi-eye");

        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
  </script>
  <!-- General JS Scripts -->
  <script src="{{ asset('assets/js/app.min.js')}}"></script>
  <!-- JS Libraies -->
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="{{ asset('assets/js/scripts.js')}}"></script>
  <!-- Custom JS File -->
  <script src="{{ asset('assets/js/custom.js')}}"></script>
</body>

</html>