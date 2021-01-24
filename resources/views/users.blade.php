@extends('layouts.dashboard')
@section('content')

<div class="col-md-12 padding-0">
    <div class="col-md-12 padding-0">

        <div class="col-md-12 padding-0">
            <div class="panel box-shadow-none content-header">
                <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">Users</h3>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12 padding-0">
            <div class="panel form-element-padding">
                <div class="col-md-12 panel">
                    @include('layouts.errors')
                    <div class="col-md-12">

<table class="table">
  <thead>
  <form class="cmxform" id="userForm" method="get" autocomplete="off" action="/users">
    <tr>
      <th colspan="2"><input type="text" class="form-control" id="filter" name="filter" value="{{request()->input('filter')}}" placeholder="Search By Name or Email" required></th>
      <th colspan="2"><input class="submit btn btn-danger" type="submit" value="Search"> <a href="/users"><input class="submit btn btn-primary" type="button" value="Reset"></a></th>
    </tr>
  </form>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Phone</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>

@foreach ($users as $data)
    <tr>
      <td>{{ $data->name }}</td>
      <td>{{ $data->email }}</td>
      <td>{{ $data->phone }}</td>
      <td><a href="/user/{{Crypt::encryptString($data->user_id)}}"> Edit </a> </td>
    </tr>
@endforeach

  </tbody>

    <tr>
      <td colspan="4">{{$users->appends(Request::all())->links()}}</td>
    </tr>
</table>
 

                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection