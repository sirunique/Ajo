@extends('layouts.app')


@section('content')

      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Create Thrift</h1>
          <p>Start a beautiful journey here</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Create Thrift</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-6">
            <div class="tile">
                <h3 class="tile-title">Create Thrift</h3>
                <div class="tile-body">
                    <form class="login-form" id="createThriftForm" method="post" autocomplete="off" onsubmit="return false"> 
                        <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="control-label">Thrift Name</label>
                                        <input type="text" name="groupName" id="groupName" class="form-control" placeholder="Thrift Name" >
                                        <span class="help-block" id="nameError"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="control-label">Amount</label>
                                        <input type="number"  name="amount" id="amount" class="form-control" placeholder="Thrift Amount" >
                                        <span class="help-block" id="amountError"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="control-label">Searchable</label>
                                        <select name="searchable" id="searchable" class="form-control" >
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="control-label">Capacity</label>
                                        <input type="number" name="capacity" id="capacity" class="form-control" placeholder="Thrift Capacity" >
                                        <span class="help-block" id="capacityError"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="control-label">Thrift Description</label>
                                        <textarea name="groupDescription" id="groupDescription" placeholder="Thrift Description" class="form-control" cols="30" rows="5" ></textarea>
                                        <span class="help-block" id="descriptionError"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" id="btnCreate" data-token="{{ csrf_token() }}">Create Thrift</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
      </div>

@endsection
@section('script')
<script type="text/javascript" src="{{ asset('vali') }}/js/plugins/bootstrap-notify.min.js"></script>
<script>
    function notify(title, msg){
        $.notify({
      		title: title+": ",
      		message: msg,
      		icon: 'fa fa-check' 
      	},{
      		type: "info"
      	});
    }
    $('form#createThriftForm').submit(function(){
        var groupName = $('#groupName').val();
        var amount = $('#amount').val();
        var searchable = $('#searchable').val();
        var capacity = $('#capacity').val();
        var groupDescription = $('#groupDescription').val();
        var _token = $('#btnCreate').attr('data-token')
        var status = '';
        
        if(groupName.length === 0){
            $('#nameError').html('Enter Thrift Name').addClass('text-danger');
            status = true;
        }
        else{
            $('#nameError').html(" ");
        }

        if(amount.length === 0){
            $('#amountError').html('Enter Thrift Amount').addClass('text-danger');
            status = true;
        }
        else if(amount == 0){
            $('#amountError').html('Thrift Cant be Zero').addClass('text-danger');
            status = true;
        }
        else{
            $('#amountError').html(" ");
        }

        if(capacity.length === 0){
            $('#capacityError').html('Enter Thrift Capacity').addClass('text-danger');
            status = true;
        }
        else if(capacity < 2){
            $('#capacityError').html('Thrift Capacity Min 2').addClass('text-danger');
            status = true;
        }
        else{
            $('#capacityError').html(" ");
        }
        
        if(groupDescription.length === 0){
            $('#descriptionError').html('Enter Thrift Description').addClass('text-danger');
            status = true;
        }
        else if(groupDescription === 0){
            $('#descriptionError').html('Thrift Description').addClass('text-danger');
            status = true;
        }
        else{
            $('#descriptionError').html(" ");
        }

        if(status != true){
            var data = {
                'groupName':groupName, 'amount':amount,
                'searchable':searchable, 'capacity':capacity,
                'groupDescription': groupDescription, '_token':_token
            }
            $.ajax({
                url: "{{route('createThrift')}}",
                type:"POST",
                data:data,
                dataType: 'JSON',
                success: function(data){
                    console.log(data)
                    if(!data.res) notify(data.message, data.message)
                    else if(data.res){
                        notify(data.message, data.message)
                        setTimeout(function()
                        {   
                            window.location = "{{route('group')}}"
                        },5000);
                    }
                },
                error: function(xhr,status,error){
                    console.log(error)
                    notify(error, 'Try Again')
                }
            })
        }


    });

</script>
@endsection