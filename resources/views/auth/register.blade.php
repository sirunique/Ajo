
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
    <title>Register - Ajo</title>
  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        <h1>Ajo</h1>
        <!-- <center><h3>Ajo</h3></center> -->
      </div>
      <div class="login-box">
        <form class="login-form" id="registerform" method="post" autocomplete="off" onsubmit="return false">  
          <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
          <h3 class="login-head"><i class="fa fa-sign-in fa-lg fa-fw"></i> Sign Up</h3>
          <div class="form-group">
            <label class="control-label">Email</label>
            <input class="form-control" type="email"  name="email" id="email" placeholder="Email" autofocus>
            <span class="help-block" id="emailError"></span>
          </div>
          <div class="form-group">
            <label class="control-label">Password</label>
            <input class="form-control" type="password" name="password" id="password" placeholder="Password">
            <span class="help-block" id="passwordError"></span>
          </div>
          <div class="form-group">
            <div class="utility">
              <div class="animated-checkbox">
                <label>
                  <input type="checkbox" name="terms" id="terms"><span class="label-text" id="accept">Accept Terms</span>
                </label>
              </div>
              <p class="semibold-text mb-2"><a href="{{ route('login')}}" data-toggle="flip">Already Have Account</a></p>
            </div>
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block submit" name="btnlogin" id="btnlogin"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN UP</button>
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
      $('form#registerform').submit(function(){
        var email = $('#email').val();
        var password = $('#password').val();
        var _token = $('#_token').val();
        var status = "";

        var emailRegex =/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if(email === ""){
          $('#emailError').html('Enter Email Address').addClass('text-danger');
        }else if(!emailRegex.test(email)){
          $('#emailError').html('Enter Valid Email Address').addClass('text-danger');
          status = true;
        }else{
          $('#emailError').html(" ");
        }

        if(password === ""){
          $('#passwordError').html('Enter Password').addClass('text-danger');
          status = true;
        }
        else if(password.length < 5){
          $('#passwordError').html('Password Min 5').addClass('text-danger');
          status = true;
        }
        else{
          $('#passwordError').html(" ");
        }

        if(!$('#terms').is(':checked')){
          $('#accept').addClass('text-danger');
          status = true;
        }

        if(status != true){
          var data = {'email': email, 'password': password, '_token': _token,} 
          $.ajax({
            url: "{{route('register')}}",
            type: 'POST',
            data: data,
            dataType: 'JSON',
            success:function(data){
              console.log(data)
              if(data.res){
                // registrain complete proceed to login
                setTimeout(function()
                {
                  $("#btnlogin").html(data.message);
                  window.location= "{{route('dashboard')}}";
                },2000);
              }
              else if(!data.res){
                // registration failed
                setTimeout(function()
                {
                    $("#btnlogin").html(data.message);
                    window.location= "{{route('register')}}";
                },5000);
              }
              else{
                // try again
                setTimeout(function()
                {
                  $("#btnlogin").html("Try Again");
                  window.location= "{{route('register')}}";
                },5000);
              }
            },
            error:function(xhr,status,error){
              if(xhr.responseJSON.errors.email != null) $('#emailError').html(xhr.responseJSON.errors.email[0]).addClass('text-danger');
            }
          })
        }

      })
    </script>

  </body>
</html>