@extends('layouts.app')


@section('content')

      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Group Page</h1>
          <p>Start a beautiful journey here</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Group Page</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">My Group</h3>
                <div class="tile-body">
                    @if (empty($members))
                        <h2>No Data</h2>
                    @else
                        @foreach ($members as $details)
                        <a href="{{ route('groupInfo',['id' => $details->id])}}">
                            <div class="tile">
                                <div class="row">
                                    <div class="col-md-4"> <h2>{{$details->name}}</h2> </div>
                                    <div class="col-md-4"> <h2>N {{$details->amount}}</h2> </div>
                                    <div class="col-md-4">  
                                        <div class="row">
                                            <div class="col-md-12"><h6>{{$details->status}}</h6></div>
                                            <div class="col-md-12"><h6>{{$details->role}}</h6></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
      </div>

@endsection