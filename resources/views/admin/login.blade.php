
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vali') }}/css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vali') }}/css/font-awesome.min.css">
    <title>Admin Login - Ajo</title>
  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        <h1>Ajo</h1>
      </div>
      <div class="login-box">
        <form class="login-form" id="loginform" method="post" autocomplete="off" onsubmit="return false">  
          <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
          <h3 class="login-head"><i class="fa fa-sign-in fa-lg fa-fw"></i>Admin Sign In</h3>
          <div class="form-group">
            <label class="control-label">Email</label>
            <input class="form-control" type="email"  name="email" id="email" placeholder="Email" autofocus >
            <span class="help-block" id="emailError"></span>
          </div>
          <div class="form-group">
            <label class="control-label">Password</label>
            <input class="form-control" type="password" name="password" id="password" placeholder="Password" >
            <span class="help-block" id="passwordError"></span>
          </div>
          <div class="form-group">
            <div class="utility">
                <p class="semibold-text mb-2"><a href="#" >Remember me</a></p>
              <p class="semibold-text mb-2"><a href="#" data-toggle="flip">Forgot Password ?</a></p>
            </div>
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block submit" name="btnlogin" id="btnlogin"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
          </div>
        </form>
        <form class="forget-form" action="index.html">
          <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Forgot Password ?</h3>
          <div class="form-group">
            <label class="control-label">EMAIL</label>
            <input class="form-control" type="text" placeholder="Email">
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>RESET</button>
          </div>
          <div class="form-group mt-3">
            <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
          </div>
        </form>
      </div>
    </section>
    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('vali') }}/js/jquery-3.2.1.min.js"></script>
    <script src="{{ asset('vali') }}/js/popper.min.js"></script>
    <script src="{{ asset('vali') }}/js/bootstrap.min.js"></script>
    <script src="{{ asset('vali') }}/js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="{{ asset('vali') }}/js/plugins/pace.min.js"></script>
    <script>
     $('form#loginform').submit(function(){
        var email = $('#email').val();
        var password = $('#password').val();
        var _token = $('#_token').val();
        var status = "";
        
        var emailRegex =/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if(email === ""){
          $('#emailError').html('Enter Email Address').addClass('text-danger');
          status = true;
        }else if(!emailRegex.test(email)){
          $('#emailError').html('Enter Valid Email Address').addClass('text-danger');
          status = true;
        }else{
          $('#emailError').html(" ");
        }

        if(password === ""){
          $('#passwordError').html('Enter Password').addClass('text-danger');
          status = true;
        }else{
          $('#passwordError').html(" ");
        }

        if(status != true){
          var data = {'email': email, 'password': password, '_token': _token}
          $.ajax({
            url: "{{route('admin.login')}}",
            type: 'POST',
            data: data,
            dataType: 'JSON',
            success:function(data){
              console.log(data)
              $("#btnlogin").attr("disabled", true);
              $("#btnlogin").html("Authenticating");
              if(data.res){
                // login
                setTimeout(function()
                {
                  $("#btnlogin").html(data.message);
                  window.location= "{{route('admin.admin')}}";
                },2000);
              }
              else if(!data.res){
                // invalid login
                setTimeout(function()
                {
                  $("#btnlogin").html(data.message);
                  window.location= "{{route('admin.login')}}";
                },5000);
              }
              else{
                // try again
                setTimeout(function()
                {
                  $("#btnlogin").html("Try Again");
                  window.location= "{{route('admin.login')}}";
                },5000);
              }
            },
            error: function(xhr,status,error){
              console.log(error)
              // try again
              setTimeout(function()
                {
                  $("#btnlogin").html("Try Again");
                  window.location= "{{route('admin.login')}}";
                },5000);
            }
          })
        }

     });
    </script>

  </body>
</html>