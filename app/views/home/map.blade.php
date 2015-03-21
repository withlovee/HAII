<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvFSmXsECeHYMyLagUAlvUjIbV-ViTlPs"></script>
<script>
$(function(){

  var thailandLatLng = new google.maps.LatLng(13.03887,101.490104);
  var mapCanvasId = "map-canvas";
  var map = null;
  var mapOptions = {
      center: thailandLatLng,
      zoom: 6
  };
  var markers = [];
  var infowindow = null;
  var currentProblemType = "OR";
  var currentDataType = "WATER";

  function initializeMap() {

    map = new google.maps.Map(document.getElementById(mapCanvasId),
        mapOptions);

    $.get("{{ URL::to('api/problems/get_map') }}", function(stations){
      addMarkers(stations);
      showMarkerForType(currentDataType, currentProblemType);
    });
    
  }

  function showInfoWindow(info, marker) {
    if (infowindow) {
      infowindow.close();
    }

    var str;
    str = '<div class="map_info">';
    str += '<strong>' + info.full_name + '</strong><br>';
    str += 'รหัส: ' + info.code + '<br>';
    str += 'ลุ่มน้ำ: ' + info.basin + '<br>';
    str += 'จำนวนปัญหา: ' + info.num + '<br>';
    str += '</div>';

    infowindow = new google.maps.InfoWindow({content: str});
    infowindow.open(map, marker);
    console.log("show window info");
    console.log(info);
  }

  function addMarker(info) {
    var marker = new google.maps.Marker({
      position: new google.maps.LatLng(info.lat, info.lng),
      animation: google.maps.Animation.DROP,
      clickable: true,
      info: info
    });

    markers.push(marker);

    google.maps.event.addListener(marker, 'click', function() {
      showInfoWindow(marker.info, marker);
    });
  }

  function addMarkers(infos) {
    for (var i = 0; i < infos.length; i++) {
      addMarker(infos[i]);
    }
  }

  function showMarkerForType(data_type, problem_type) {
    if (infowindow) {
      infowindow.close();
    }

    for (var i = 0; i < markers.length; i++) {
      console.log(markers[i].info);
      if (markers[i].info.problem_type == problem_type &&
          markers[i].info.data_type == data_type) {
        markers[i].setMap(map);
        markers[i].setAnimation(google.maps.Animation.DROP);
      } else {
        markers[i].setMap(null);
      }
    }
  }  
  
  initializeMap();


  $("input[name=map-selector]").change(function(){
    var val = $(this).val();
    currentProblemType = val;
    showMarkerForType(currentDataType, currentProblemType);
  });

  $("input[name=data-type-selector]").change(function(){
    var val = $(this).val();
    currentDataType = val;
    showMarkerForType(currentDataType, currentProblemType);
  });
  
});
</script>