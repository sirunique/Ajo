
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
    <title>Verify Data - Ajo</title>
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
        <form class="login-form" id="loginform" method="post" autocomplete="off" onsubmit="return false">  
          <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="investorId" id="investorId" value="{{$investor->id}}">
          <input type="hidden" name="token" id="token" value="{{$token}}">
          <h3 class="login-head"><i class="fa fa-sign-in fa-lg fa-fw"></i> Sign In</h3>
          <div class="form-group">
            <label class="control-label">Email</label>
            <input class="form-control" type="email"  name="email" id="email" value="{{$investor->email}}" disabled  >
            <span class="help-block" id="emailError"></span>
          </div>

          <div class="form-group">
            <label class="control-label">Name</label>
            <input class="form-control" type="name"  name="name" id="name" placeholder="Name" >
            <span class="help-block" id="nameError"></span>
          </div>

          <div class="form-group">
            <label class="control-label">Phone</label>
            <input class="form-control" type="phone"  name="phone" id="phone" placeholder="Phone" >
            <span class="help-block" id="phoneError"></span>
          </div>

          <div class="form-group">
            <label class="control-label">Gemder</label>
            <select name="gender" id="gender" class="form-control">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <span class="help-block" id="genderError"></span>
          </div>

          <div class="form-group">
            <label class="control-label">Address</label>
            <input class="form-control" type="address"  name="address" id="address" placeholder="Address" >
            <span class="help-block" id="addressError"></span>
          </div>

          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block submit" name="btnlogin" id="btnlogin"><i class="fa fa-sign-in fa-lg fa-fw"></i>Verify</button>
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
        var name = $('#name').val();
        var phone = $('#phone').val();
        var gender = $('#gender').val();
        var address = $('#address').val();
        var investorId = $('#investorId').val();
        var token = $('#token').val();
        var _token = $('#_token').val();
        var status = "";
        
        if(name === ""){
          $('#nameError').html('Enter Name').addClass('text-danger');
          status = true;
        }else{
          $('#nameError').html(" ");
        }

        if(phone === ""){
          $('#phoneError').html('Enter phone').addClass('text-danger');
          status = true;
        }else{
          $('#phoneError').html(" ");
        }
        if(address === ""){
          $('#addressError').html('Enter address').addClass('text-danger');
          status = true;
        }else{
          $('#addressError').html(" ");
        }

        if(status != true){
          var data = {'name': name, 'phone': phone, 'gender': gender, 'address': address, 'investorId': investorId, 'token': token, '_token': _token}
          $.ajax({
            url: "{{route('verifydata')}}",
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
                  window.location= "{{route('dashboard')}}";
                },2000);
              }
              else if(!data.res){
                // invalid login
                setTimeout(function()
                {
                  $("#btnlogin").html(data.message);
                  window.location= "{{route('login')}}";
                },5000);
              }
              else{
                // try again
                setTimeout(function()
                {
                  $("#btnlogin").html("Try Again");
                  window.location= "{{route('login')}}";
                },5000);
              }
            },
            error: function(xhr,status,error){
              console.log(error)
              // try again
              setTimeout(function()
                {
                  $("#btnlogin").html("Try Again");
                  window.location= "{{route('login')}}";
                },5000);
            }
          })
        }

     });
    </script>

  </body>
</html>