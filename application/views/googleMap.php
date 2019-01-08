<!doctype html>
<html>
  <head>
    <title>Google Maps Tutorial</title>
    <script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.19.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
  </head>
  <body>
  <div class="container">
  <h1>PubNub Google Maps Tutorial - Live Map Marker</h1>
  <div id="map-canvas" style="width:600px;height:400px"></div>
</div>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>

window.lat = -7.9409493;
window.lng = 112.6169839;
function circlePoint(time) {
  var radius = 0.01;
  var x = Math.cos(time) * radius;
  var y = Math.sin(time) * radius;
  return {lat:window.lat + y, lng:window.lng + x};
};
var map;
var mark;
var initialize = function() {
  map  = new google.maps.Map(document.getElementById('map-canvas'), {center:{lat:lat,lng:lng},zoom:15});
  mark = new google.maps.Marker({position:{lat:lat, lng:lng}, map:map});
};
window.initialize = initialize;
var redraw = function(payload) {
  lat = payload.message.lat;
  lng = payload.message.lng;
  map.setCenter({lat:lat, lng:lng, alt:0});
  mark.setPosition({lat:lat, lng:lng, alt:0});
};
var pnChannel = "map-channel";
var pubnub = new PubNub({
  publishKey:   'pub-c-bb22a4b5-b9a3-413d-9254-f4642a4e077a',
  subscribeKey: 'sub-c-f55f5f86-18c9-11e6-b700-0619f8945a4f'
});
pubnub.subscribe({channels: [pnChannel]});
pubnub.addListener({message:redraw});

setInterval(function() {
  pubnub.publish({channel:pnChannel, message:circlePoint(new Date().getTime()/1000)});
}, 10000);
</script>
 <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCOeGW19cELfiUyb8m7-kAf_esjwdc5nVE&callback=initialize"></script>
  </body>
</html>