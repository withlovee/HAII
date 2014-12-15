<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDWdPO7OO5-HIh_yo3hotDHgp0HxN8dbI"></script>
<script type="text/javascript">
	function initialize(){

		function create_markers(image){
			output = [];
			sizes = [
				[5, 10],
				[20, 35]
			];
			for(i in sizes){
				output.push(new google.maps.MarkerImage(
					image,
					null, /* size is determined at runtime */
					null, /* origin is 0,0 */
					null, /* anchor is bottom center of the scaled image */
					new google.maps.Size(sizes[i][0], sizes[i][1])
				));
			}
			return output;
		}
		function render_info(data){
			str = '<div class="map_info">';
			str += '<strong>'+data.full_name+'</strong><br>';
			str += 'ลุ่มน้ำ: '+data.basin+'<br>';
			str += 'จำนวนปัญหา: '+data.num+'<br>';
			str += '</div>';
			return str;
		}
		function get_status(num){
			num = parseInt(num);
			if(num > 50){
				return 'medium';
			}
			else if(num > 200){
				return 'high';
			}
			return 'low';
		}

		var map = new google.maps.Map(document.getElementById("map-canvas"), {
			center: new google.maps.LatLng(12.826045,101.54666),
			zoom: 6
		});

		var icons = {
			low: create_markers("http://maps.google.com/mapfiles/marker_green.png"),
			medium: create_markers("http://maps.google.com/mapfiles/marker_orange.png"),
			high: create_markers("http://maps.google.com/mapfiles/marker.png"),
		};

		var markers = [];

		var openedInfoWindow;

		$.get("{{ URL::to('api/problems/get_map') }}", function(stations){
			for(i in stations){
				//console.log(stations[i]);
				status = get_status(stations[i].num);
				var marker = new google.maps.Marker({
					map: map, 
					position: new google.maps.LatLng(stations[i].lat, stations[i].lng),
					title: stations[i].code,
					status: status,
					html: render_info(stations[i]),
					icon: icons[status][0] 
				});
				markers.push(marker);
				google.maps.event.addListener(marker, 'click', function() {
					if (openedInfoWindow != null) openedInfoWindow.close();
					var infoWindow = new google.maps.InfoWindow({
						position: this.position,
						content: this.html,
					});
					
					// Open infoWindow
					infoWindow.open(map,this);
					// Remember the opened window
					openedInfoWindow = infoWindow;

					// Close it if X box clicked
					google.maps.event.addListener(infoWindow, 'closeclick', function() {
						openedInfoWindow = null; 
					});

				});
			}
		});
		
		var zoomLevel = 1;

		google.maps.event.addListener(map, 'zoom_changed', function() {
		  var i, prevZoomLevel;
		  prevZoomLevel = zoomLevel;
		  map.getZoom() < 10 ? zoomLevel = 0 : zoomLevel = 1;
		  if(prevZoomLevel !== zoomLevel) {
			for(i = 0; i < markers.length; i++) {
				if(zoomLevel == 2) {
					markers[i].setIcon(icons[markers[i].status][zoomLevel]);
				}
				else {
					markers[i].setIcon(icons[markers[i].status][zoomLevel]);
				}
			}
		  }
		});
	}

	google.maps.event.addDomListener(window, 'load', initialize);

</script>
