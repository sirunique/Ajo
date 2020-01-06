@extends('layouts.app')


@section('content')

      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Group Info</h1>
          <p>Start a beautiful journey here</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Group Info</a></li>
        </ul>
      </div>

      @if ($groupInfo->admin_id == Auth::user()->id)
        <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <button class="btn btn-primary" id="btnAddMemberModal">Add Member</button>
                    <!-- <a class="btn btn-primary" href="staff.php">Refresh</a> -->
                </div>
            </div>
        </div>
        @endif  

        
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title">Group Member</h3>
                    <h3 class="title">{{$groupInfo->status}}</h3>
                    @if($groupInfo->admin_id == Auth::user()->id && $countMember == $groupInfo->max_capacity && $groupInfo->status =='onboarding')
                        <a href="{{ route('startThrift',['id' => $groupInfo->id])}}" class="btn btn-primary">Start</a>
                    @endif
                    <h3 class="title">{{$countMember}} OF {{$groupInfo->max_capacity}}</h3>
                </div>
                <div class="tile-body">
                    <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Payout Date</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        @if($groupInfo->admin_id == Auth::user()->id)
                                        <th>Payout</th>
                                        @endif
                                    </tr>
                                </thead> 
                                <tbody>
                                @foreach ($membersInfo as $info)
                                    <tr>
                                        <td>{{$info->name}}</td>
                                        <td>{{$info->email}}</td>
                                        <td>{{$info->payout_date}}</td>
                                        <td>{{$info->paid_status}}</td>
                                        <td>{{$info->paid_amount}}</td>
                                        <td>{{$info->date}}</td>
                                        @if($groupInfo->admin_id == Auth::user()->id)
                                            <td>
                                                {{-- || date('Y-m-d') == $info->payout_date --}}
                                                {{--  &&  --}}
                                                @if ($info->paid_status == 'Pending' && date('Y-m-d') >= $info->payout_date)
                                                    <button type="submit" class="btn btn-danger" id="btnPayout" data-id="{{$info->id}}" data-amount="{{$groupInfo->amount}}" data-token="{{ csrf_token() }}">Payout</button>
                                                @elseif($info->paid_status == 'Paid')
                                                    <button type="submit" class="btn btn-secondary disabled">Paid</button>
                                                @else
                                                    <button type="submit" class="btn btn-primary disabled">Pending</button>                                  
                                                @endif
                                            </td>
                                        @endif    

                                    </tr>
                                @endforeach
                                </tbody>  
                            </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title">Thrift Breakdown</h3>
                    <h3 class="title">Start: {{$groupInfo->start_date}}</h3>
                    <h3 class="title">End: {{$groupInfo->end_date}}</h3>
                </div>
                <div class="tile-body">
                    @foreach ($paymentWeeksInfo as $key => $val ) 
                    <div class="tile">
                        <h2 class="tile-title">{{$key}} 
                            {{-- @for ($i = 0; $i < count($val); $i++)
                                {{$i}}
                            @endfor --}}
                            {{-- @foreach ($val as $key => $item)
                                {{$item->startDate}} To {{$item->endDate}}
                            @endforeach --}}
                        </h2> 
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Week</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        @if($groupInfo->admin_id == Auth::user()->id)
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach ($val as $value)
                                    <tr>
                                        <td>Week {{$value->week}}</td>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->email}}</td>
                                        <td>{{$value->payment_status}}</td>
                                        <td>{{$value->payment_amount}}</td>
                                        <td>{{$value->date}}</td>
                                        @if($groupInfo->admin_id == Auth::user()->id)
                                            @if ($value->payment_status == 'Pending')
                                                <td><button class="btn btn-danger" id="btnPaid" data-id="{{$value->id}}" data-amount="{{$value->amount}}" data-token="{{ csrf_token() }}">Pay</button></td>
                                            @else
                                                <td><button class="btn btn-primary disabled">Paid</button></td>
                                            @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>  
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
      </div>

      <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="viewclient" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Member</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-md-12"> 
                          <div class="form-group">
                            <label for="staff name">Member Email:</label>
                            <input type="text" class="form-control" name="memberEmail" id="memberEmail" placeholder="Member Email">
                            <span class="help-block" id="emailError"></span>
                            <input type="hidden" name="groupId" id="groupId" value="{{$groupInfo->id}}">
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">   
                <button class="btn btn-primary" type="button" id="btnAddMember" >Add Member</button> 
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

    document.addEventListener('DOMContentLoaded', ()=> {
        $(document).on('click', '#btnAddMemberModal', ()=> {
            $('#addMemberModal').modal('show');
        })

        $(document).on('click', '#btnPaid', ()=>{
            var _token = $('#btnPaid').attr('data-token');
            var id = $('#btnPaid').attr('data-id');
            var amount = $('#btnPaid').attr('data-amount');
            $.ajax({
                url: "{{route('weekPayment')}}",
                type: "POST",
                data: {'_token': _token, 'id': id, 'amount': amount},
                dataType: 'JSON',
                success:function(data){
                    console.log(data)
                    if(!data.res) notify(data.message, data.message)
                    else if(data.res){
                        notify(data.message, data.message)
                        // setTimeout(function()
                        // {   
                        //     window.location = "{{route('group')}}"
                        // },5000);
                    }
                },
                error: function(xhr,status,error){
                    console.log(error)
                }
            })
        });

        $(document).on('click', '#btnAddMember', ()=>{
            var _token = $('#_token').val();
            var groupId = $('#groupId').val();
            var memberEmail = $('#memberEmail').val();
            var emailRegex =/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            var status = "";
            if(memberEmail === ""){
                $('#emailError').html('Enter Member Email').addClass('text-danger');
                status = true;
            }
            else if(!emailRegex.test(memberEmail)){
                $('#emailError').html("Enter Valid Email Address").addClass('text-danger');
                 status = true
            }
            else{
                $('#emailError').html(" ");
                var data = {'email':memberEmail, 'groupId':groupId, '_token':_token}
                $.ajax({
                    url: "{{ route('addMember') }}",
                    type: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success:function(data){
                        console.log(data)
                        if(!data.res)  $('#emailError').html(data.message).addClass('text-danger');
                        else if(data.res){
                            window.location = "{{route('group')}}"
                            // setTimeout(function(){
                            //     $('#emailError').html(data.message).addClass('text-success');
                            // },2000);
                        }   
                    }
                })
            }
        })

        $(document).on('click', '#btnPayout', () =>{
            var _token = $('#btnPayout').attr('data-token');
            var id = $('#btnPayout').attr('data-id');
            var amount = $('#btnPayout').attr('data-amount');
            var data = {'_token': _token, 'id': id, 'amount': amount};
            $.ajax({
                url: "{{route('payout')}}",
                type: "POST",
                data: data,
                dataType: 'JSON',
                success: function(data){
                    console.log(data)
                    if(!data.res)  $('#emailError').html(data.message).addClass('text-danger');
                        else if(data.res){
                        window.location = "{{route('group')}}"
                        // setTimeout(function(){
                        //     $('#emailError').html(data.message).addClass('text-success');
                        // },2000);
                    }
                },
                error: function(xhr,status,error){
                    console.log(error)
                }
            })
        })
    });


</script>
@endsection