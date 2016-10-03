<!DOCTYPE html>
<html>
  <head>
    <title>Audio Dashboard</title>
    <meta name="_token" content="{!! csrf_token() !!}">
    
    @if(isset(Auth::user()->id))
    <meta id="user-id" data-user-id="{!! Auth::user()->id !!}">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="{!! secure_asset('bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet">
    <!-- styles -->
    <link href="{!! secure_asset('css/styles.css') !!}" rel="stylesheet">
    <script>
document.addEventListener('DOMContentLoaded', function(){ 
   if (window.location.protocol != "https:") {
   window.location.href = "https://pasoncall.com/home";
}
}, false);

    </script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="header">
         <div class="container">
            <div class="row">
               <div class="col-md-5">
                  <!-- Logo -->
                  <div class="logo">
                     <h1><a href="/home">Audio App</a></h1>
                  </div>
               </div>
               <div class="col-md-5">
                  <div class="row">
                    <div class="col-lg-12">
                    </div>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="navbar navbar-inverse" role="banner">
                      <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                        <ul class="nav navbar-nav">
                          <li class="dropdown">
                          @if (isset(Auth::user()->id))
                            <a href="/logout" >Log out</a>
                            @endif
<!--                             <ul class="dropdown-menu animated fadeInUp">
                              <li><a href="profile.html">Profile</a></li>
                              <li><a href="login.html">Logout</a></li>
                            </ul> -->
                          </li>
                        </ul>
                      </nav>
                  </div>
               </div>
            </div>
         </div>
    </div>

    @yield('content')

    
    <footer>
         <div class="container">
         
            
         </div>
      </footer>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{!! secure_asset('bootstrap/js/bootstrap.min.js') !!}"></script>
    <!-- <script src="js/mp3Worker.js"></script> -->
    <script src="{!! secure_asset('js/custom.js') !!}"></script>
    <script src="{!! secure_asset('js/recorder.js') !!}"></script>
    <script src="{!! secure_asset('js/Fr.voice.js') !!}"></script>
    <script src="{!! secure_asset('js/app.js') !!}"></script>
    <script src="https://cdn.rawgit.com/zenorocha/clipboard.js/v1.5.12/dist/clipboard.min.js"></script>
    <script src="{!! secure_asset('js/index.js') !!}"></script>
  </body>
</html>