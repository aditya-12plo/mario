@extends('layouts.dashboard')
@section('content')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKsEC_DDuCMdWKPgN4dNQVKxPjte6M_Rc"></script>
<style>
#mapNya {
  height: 900px;
  width: 100%;
  margin: 0px;
  padding: 0px
}
</style>
<div class="col-md-12 padding-0">
    <div class="col-md-12 padding-0">
        
    
        <div class="col-md-12 padding-0">
            <div class="panel box-shadow-none content-header">
                <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">Dashboard</h3>
                    </div>
                </div>
            </div>
        </div>
                
        <div class="col-md-12 padding-0">
            
            <div class="panel form-element-padding">
            <div class="col-md-12 panel">
                    <div class="col-md-12 panel-heading">
                      <h4>Form Data AIzaSyDKsEC_DDuCMdWKPgN4dNQVKxPjte6M_Rc  units=metric mode=driving</h4>
                    </div>
                    <div class="col-md-12 panel-body" style="padding-bottom:30px;">
                    @include('layouts.errors')
                      <div class="col-md-12">
                        <form class="cmxform" id="signupForm" method="post" action="/home" enctype="multipart/form-data">
                        @csrf  
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label text-right">Avoid Type</label>
                                <select class="form-control" name="avoid" required>
                                    <option value="tolls">tolls</option>
                                    <option value="highways">highways</option>
                                </select>
                            </div>
                            
                          </div>    
                          <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label text-right">File</label>
                                <input type="file" class="form-control" name= "file" id="file" required>
                            </div>
                            
                          </div>                   
                          <div class="col-md-12">
                              <input class="submit btn btn-danger" type="submit" value="Submit">
                        </div>
                      </form>

                    </div>
                  </div>
                </div>

    @if(count($datas) > 0)
  <div class="col-md-12 panel-heading">
    <h4> Result destinations </h4>
  </div>
<table class="table">
  <thead>
    <tr>
      <th scope="col">From Desc</th>
      <th scope="col">From coordinates</th>
      <th scope="col">To Desc</th>
      <th scope="col">To Name</th>
      <th scope="col">To coordinates</th>
      <th scope="col">Distance</th>
      <th scope="col">Duration</th>
      <th scope="col">Direction</th>
    </tr>
  </thead>
  <tbody>
    <?php
        if($avoid == "tolls"){
          $void = "t";
        }else{
          $void = "h";
        }
    ?>
@foreach ($datas as $data)
    <tr>
      <th>{{ $data["from"]["desc"] }}</th>
      <td>{{ $data["from"]["coordinates"] }}</td>
      <td>{{ $data["to"]["desc"] }}</td>
      <th>{{ $data["origin_addresses"] }}</th>
      <td>{{ $data["to"]["coordinates"] }}</td>
      <td>{{ $data["distance_value"] }} M / {{ $data["distance_text"] }}</td>
      <td>{{ $data["duration_value"] }} Second / {{ $data["duration_text"] }}</td>
      <td><a href="http://maps.google.com/maps?saddr={{ $data["from"]["coordinates"] }}&daddr={{ $data["to"]["coordinates"] }}&dirflg=d,<?php echo $void;?>" target="_blank"> Detail </a></td>
    </tr>
@endforeach
  </tbody>
</table>



<div class="col-md-12 panel-heading">
    <h4> Result Maps Directions </h4>
  </div>

  <div class="col-md-12">
    <div id="mapNya"></div>
  </div>



    <script>
 var geocoder;
var map;
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var locations = <?php echo json_encode($maps);?>;

function initialize() {
  directionsDisplay = new google.maps.DirectionsRenderer();


  var map = new google.maps.Map(document.getElementById('mapNya'), {
    zoom: 10,
    center: new google.maps.LatLng(-6.1753924,106.8249641),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  directionsDisplay.setMap(map);
  var infowindow = new google.maps.InfoWindow();

  var marker, i;
  var request = {
    travelMode: google.maps.TravelMode.DRIVING
  };
  for (i = 0; i < locations.length; i++) {
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(locations[i][1], locations[i][2]),
    });

    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
        infowindow.setContent(locations[i][0]);
        infowindow.open(map, marker);
      }
    })(marker, i));

    if (i == 0) request.origin = marker.getPosition();
    else if (i == locations.length - 1) request.destination = marker.getPosition();
    else {
      if (!request.waypoints) request.waypoints = [];
      request.waypoints.push({
        location: marker.getPosition(),
        stopover: true
      });
    }

  }
  directionsService.route(request, function(result, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(result);
    }
  });
}
google.maps.event.addDomListener(window, "load", initialize);

</script>


    @endif
 


<!--
< ?php
echo "<pre>";
$json_pretty = json_encode($datas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo $json_pretty;
echo "</pre>";
? >
-->
            </div>
        </div>
               
    </div>
</div>


@endsection
