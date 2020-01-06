@extends('layouts.app')


@section('content')

      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Blank Page</h1>
          <p>Start a beautiful journey here</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Blank Page</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">Create a beautiful dashboard</div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <div class="tile-body">
              <h2 class="tile-title">Users --(({{count($users)}}))</h2>
              <div class="table-responsive">
                <table class="table table-bordered responsive table-responsive table-striped table-hover">
                  <thead class="thead-dark">
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Gender</th>
                      <th>Address</th>
                      <th>verify</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($users as $user)
                    <tr>
                      <td>{{$user->name}}</td>
                      <td>{{$user->email}}</td>
                      <td>{{$user->phone}}</td>
                      <td>{{$user->gender}}</td>
                      <td>{{$user->address}}</td>
                      <td>{{$user->verify}}</td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="tile">
            <div class="tile-body">
              <h2 class="tile-title">Thrift   --(({{count($thrifts)}}))</h2>
              <div class="table-responsive">
                <table class="table table-bordered responsive  table-striped table-hover">
                  <thead class="thead-dark">
                    <tr>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Capacity</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th>Start</th>
                      <th>End</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($thrifts as $thrift)
                        <tbody>
                          <tr>
                            <td>{{$thrift->name}}</td>
                            <td>{{$thrift->description}}</td>
                            <td>{{$thrift->max_capacity}}</td>
                            <td>{{$thrift->amount}}</td>
                            <td>{{$thrift->status}}</td>
                            <td>{{$thrift->start_date}}</td>
                            <td>{{$thrift->end_date}}</td>
                          </tr>
                        </tbody>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="tile">
            <div class="tile-body">
              <h2 class="tile-title">Members</h2>
              <div class="table-responsive">
                <table class="table table-bordered responsive table-responsive table-striped table-hover">
                  <thead class="thead-dark">
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Payout Date</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="tile">
            <div class="tile-body">
              <h2 class="tile-title">Payment</h2>
              <div class="table-responsive">
                <table class="table table-bordered responsive table-responsive table-striped table-hover">
                  <thead class="thead-dark">
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Payout Date</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>

@endsection