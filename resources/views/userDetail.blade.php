@extends('layouts.dashboard')
@section('content')

<div class="col-md-12 padding-0">
    <div class="col-md-12 padding-0">

        <div class="col-md-12 padding-0">
            <div class="panel box-shadow-none content-header">
                <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">User Detail</h3>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12 padding-0">
            <div class="panel form-element-padding">
                <div class="col-md-12 panel">
                    @include('layouts.errors')
                    <div class="col-md-12">
                        <form class="cmxform" id="profileForm" method="post" autocomplete="off" action="/user/{{$encrpt}}" enctype="multipart/form-data">
                        @csrf  

                            <div class="form-group">
                                <label class="control-label text-right">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label text-right">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}" placeholder="Your Name" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label text-right">phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{4}-[0-9]{4}-[0-9]{4}" value="{{$user->phone}}" placeholder="0812-1234-5678" maxlength="20">
                            </div>


                            <div class="form-group">
                                <label class="control-label text-right">New Password</label>
                                <input type="password" class="form-control" id="new_password" autocomplete="new-password" name="new_password" placeholder="Your New Password">
                            </div>
             
                            <div class="col-md-12">
                              <input class="submit btn btn-danger" type="submit" value="Submit">
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<script>
    $("form :input").attr("autocomplete", "off");
</script>
@endsection