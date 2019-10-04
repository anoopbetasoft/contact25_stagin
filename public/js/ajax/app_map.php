<?php 

include('../config.php');


#die("#".$num_rows);

?>
<?php define("YOUR_API_KEY","AIzaSyD08Emygz5W4HKOZXvogXKb5zYjA8ZRMaQ");?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <meta charset="utf-8">
    <title>Directions service</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
    </style>
  </head>
  <body>
    <?php if (1>2){?>
    <div id="floating-panel">
    <b>Shops: </b>
    <img src="include/googlemap/orange.png" width="39" height="48" alt=""/> 
    <b>Homes: </b>
    <img src="include/googlemap/purple.png" width="39" height="48" alt=""/>
    <b>Hotspots: </b>
    <img src="include/googlemap/red.png" width="39" height="48" alt=""/>
    
    
    </div>
    <?php }?>
    <div id="map"></div>
  <script  type="text/javascript">

		var map;
	  
	  var map, infoWindow;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -34.397, lng: 150.644},
          zoom: 6
        });
        infoWindow = new google.maps.InfoWindow;

        // Try HTML5 geolocation.
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent('Location found.');
            infoWindow.open(map);
            map.setCenter(pos);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
      }

      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
      }
	  
      function initMap2() {
		  
		  
		  
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 15,
          center: new google.maps.LatLng(53.85335,  -1.57836),
          mapTypeId: 'roadmap',
          gestureHandling: 'greedy',
			styles: [{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#d6e2e6"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#cfd4d5"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#7492a8"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"lightness":25}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#dde2e3"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"color":"#cfd4d5"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"color":"#dde2e3"}]},{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"color":"#7492a8"}]},{"featureType":"landscape.natural.terrain","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#dde2e3"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#588ca4"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"saturation":-100}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#a9de83"}]},{"featureType":"poi.park","elementType":"geometry.stroke","stylers":[{"color":"#bae6a1"}]},{"featureType":"poi.sports_complex","elementType":"geometry.fill","stylers":[{"color":"#c6e8b3"}]},{"featureType":"poi.sports_complex","elementType":"geometry.stroke","stylers":[{"color":"#bae6a1"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#41626b"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"saturation":-45},{"lightness":10},{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#c1d1d6"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#a6b5bb"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"visibility":"on"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.fill","stylers":[{"color":"#9fb6bd"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"transit","elementType":"labels.icon","stylers":[{"saturation":-70}]},{"featureType":"transit.line","elementType":"geometry.fill","stylers":[{"color":"#b4cbd4"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#588ca4"}]},{"featureType":"transit.station","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#008cb5"},{"visibility":"on"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"saturation":-100},{"lightness":-5}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#a6cbe3"}]}]
        });
		  
		  
		
		  

        var icons = {
          shop: {
            icon:  'include/googlemap/orange.png'
          },
          home: {
            icon: 'include/googlemap/purple.png'
          },
          hotspot: {
            icon: 'include/googlemap/red.png'
          }
        };
		 
		  /*
		  $.ajax({
                    url: "js/ajax/load_hotspot.php",
                    data: {
								
								
							},
                    dataType: "json",
                    type: "POST",
                    error: function () {
						alert("error");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						//alert("success"+data);
						//alert("test");
						
						 var features = [
			
           data
        ];
  //alert(features);
						
						// var features = (JSON.parse(data));
						//alert("##"+JSON.parse(data));
						
                    }
		 });*/
		// alert("test");
		  
		 
		  
		/*
		hotspots - works on old one - to be re-imported on different version
 		$.ajax({
                    url: "../js/ajax/load_hotspot.php",
                    data: {
								
								
							},
                    dataType: "json",
                    type: "POST",
                    error: function () {
						alert("error");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						
						data.forEach(function(pointer) {
						  	add_pointer(pointer[0], pointer[1]);
						  });
        
						
						add_pointer(data[0][0], data[0][1]);
						add_pointer(data[1][0], data[1][1]);
						//alert("##"+data[0][0]);
						//alert("##"+data[0][1]);
						//alert("fin");
							
						
						
                    }

        });*/
		var lat = '53.84338';
		var long =  '-1.57836';
		//  add_pointer(lat,long);
		  var lat = '53.85338';
		var long =  '-1.57836';
		//  add_pointer(lat,long);
		  
		function add_pointer(lat,long){
			 var features = [
			
           
			
			{
            position: new google.maps.LatLng(lat, long),
            type: 'home'
          }
        ];
			 var infowindow = new google.maps.InfoWindow({
          content: contentString
        });  var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<h1 id="firstHeading" class="firstHeading" style="color:#d01c76;"><i class="ti-location-pin" style="color:#d01c76"></i> Collection Hotspot</h1>'+
            '<div id="bodyContent">'+
            '<p style="color:#d01c76;">Collect from 8-9pm Tuesday</p>' +
            ''+
            '<p><a href="https://contact25.com">'+
            '<img style="width:50px;" src="https://contact25.com/uploads/small/7_'+randomIntFromInterval(800000, 802403)+'.jpg"  alt=""/></a> '+
				 '<img style="width:50px;" src="https://contact25.com/uploads/small/7_'+randomIntFromInterval(800000, 802403)+'.jpg"  alt=""/></a> '+
				 '<img style="width:50px;" src="https://contact25.com/uploads/small/7_'+randomIntFromInterval(800000, 802403)+'.jpg"  alt=""/></a> '+
				 '<img style="width:50px;" src="https://contact25.com/uploads/small/7_'+randomIntFromInterval(800000, 802403)+'.jpg"  alt=""/></a> '+
				 '<img style="width:50px;" src="https://contact25.com/uploads/small/7_'+randomIntFromInterval(800000, 802403)+'.jpg"  alt=""/></a> '+
            '.</p>'+
            '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        // Create markers.
        features.forEach(function(feature) {
          var marker = new google.maps.Marker({
            position: feature.position,
            icon: icons[feature.type].icon,
			  title: "Shop",
            map: map
          });
			marker.addListener('click', function() {
          infowindow.open(map, marker);
        	});
			
        });
			//alert(var1);
		}
        
  //alert(features);
		 

		  
		function randomIntFromInterval(min,max)
{
    return Math.floor(Math.random()*(max-min+1)+min);
}  
		  
       
      }
	var locations = [
         ['Goettingen',  51.54128040000001,  9.915803500000038, 'http://www.google.de'],
         ['Kassel', 51.31271139999999,  9.479746100000057,0, 'http://www.stackoverflow.com'],
         ['Witzenhausen', 51.33996819999999,  9.855564299999969,0, 'www.http://developer.mozilla.org.de']

 ];	
		var infowindow = new google.maps.InfoWindow();

var marker, i;

for (i = 0; i < locations.length; i++) {
  marker = new google.maps.Marker({
   position: new google.maps.LatLng(locations[i][1], locations[i][2]),
   map: map,
   url: locations[i][4]
  });

 google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
   return function() {
     infowindow.setContent(locations[i][0]);
     infowindow.open(map, marker);
   }
 })(marker, i));

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
   return function() {
     infowindow.setContent(locations[i][0]);
     infowindow.open(map, marker);
     window.location.href = this.url;
   }
 })(marker, i));

    }
		  	 
		
		
		
		/*
      function initMap() {
		  
		  
		  
		  
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: {lat: 41.85, lng: -87.65}
        });
        directionsDisplay.setMap(map);
		  
		  
		  
		  
		  
		  
		  

        var onChangeHandler = function() {
          calculateAndDisplayRoute(directionsService, directionsDisplay);
        };
        document.getElementById('start').addEventListener('change', onChangeHandler);
        document.getElementById('end').addEventListener('change', onChangeHandler);
		var infoWindow = new google.maps.InfoWindow({map: map});

        // Try HTML5 geolocation.
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent('You are here');
            map.setCenter(pos);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
      }
		
		
		
		 var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
        var icons = {
          parking: {
            icon: iconBase + 'parking_lot_maps.png'
          },
          library: {
            icon: iconBase + 'library_maps.png'
          },
          info: {
            icon: iconBase + 'info-i_maps.png'
          }
        };

        var features = [
          {
            position: new google.maps.LatLng(53.85335,-1.57836),
            type: 'info'
          }, {
            position: new google.maps.LatLng(-33.91539, 151.22820),
            type: 'info'
          }, {
            position: new google.maps.LatLng(-33.91747, 151.22912),
            type: 'info'
          }, {
            position: new google.maps.LatLng(-33.91910, 151.22907),
            type: 'info'
          }, {
            position: new google.maps.LatLng(-33.91725, 151.23011),
            type: 'info'
          }, {
            position: new google.maps.LatLng(-33.91872, 151.23089),
            type: 'info'
          }, {
            position: new google.maps.LatLng(-33.91784, 151.23094),
            type: 'info'
          }, {
            position: new google.maps.LatLng(-33.91682, 151.23149),
            type: 'info'
          }, {
            position: new google.maps.LatLng(-33.91790, 151.23463),
            type: 'info'
          }, {
            position: new google.maps.LatLng(-33.91666, 151.23468),
            type: 'info'
          }, {
            position: new google.maps.LatLng(-33.916988, 151.233640),
            type: 'info'
          }, {
            position: new google.maps.LatLng(-33.91662347903106, 151.22879464019775),
            type: 'parking'
          }, {
            position: new google.maps.LatLng(-33.916365282092855, 151.22937399734496),
            type: 'parking'
          }, {
            position: new google.maps.LatLng(-33.91665018901448, 151.2282474695587),
            type: 'parking'
          }, {
            position: new google.maps.LatLng(-33.919543720969806, 151.23112279762267),
            type: 'parking'
          }, {
            position: new google.maps.LatLng(-33.91608037421864, 151.23288232673644),
            type: 'parking'
          }, {
            position: new google.maps.LatLng(-33.91851096391805, 151.2344058214569),
            type: 'parking'
          }, {
            position: new google.maps.LatLng(-33.91818154739766, 151.2346203981781),
            type: 'parking'
          }, {
            position: new google.maps.LatLng(-33.91727341958453, 151.23348314155578),
            type: 'library'
          }
        ];

        // Create markers.
        features.forEach(function(feature) {
          var marker = new google.maps.Marker({
            position: feature.position,
            icon: icons[feature.type].icon,
            map: map
          });
        });
		
		
		
		
		
		

      function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        directionsService.route({
          origin: document.getElementById('start').value,
          destination: document.getElementById('end').value,
		  waypoints: [{location: 'leeds, uk'}, {location: 'cardiff, uk'}],
		  optimizeWaypoints: false,
  		  provideRouteAlternatives: true,
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
            directionsDisplay.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
		  
		  
      }*/
		
    </script>
    
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo YOUR_API_KEY?>&callback=initMap">
    </script>
  <script>
      // In the following example, markers appear when the user clicks on the map.
      // Each marker is labeled with a single alphabetical character.
      var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      var labelIndex = 0;

		    
		
      function initialize() {
        var bangalore = { lat: 53.85755, lng: -1.58836 };
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: bangalore
        });

        // This event listener calls addMarker() when the map is clicked.
        google.maps.event.addListener(map, 'click', function(event) {
          addMarker(event.latLng, map);
        });

        // Add a marker at the center of the map.
        addMarker(bangalore, map);
      }

      // Adds a marker to the map.
      function addMarker(location, map) {
        // Add the marker at the clicked location, and add the next-available label
        // from the array of alphabetical characters.
        var marker = new google.maps.Marker({
          position: location,
          label: labels[labelIndex++ % labels.length],
          map: map
        });
      }

      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </body>
</html>

<?php die();?>
<!DOCTYPE html>
<html>
  <head>
    <title>Geolocation</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>
      // Note: This example requires that you consent to location sharing when
      // prompted by your browser. If you see the error "The Geolocation service
      // failed.", it means you probably did not give permission for the browser to
      // locate you.

      function initMap() {
		 var im = 'http://www.robotwoods.com/dev/misc/bluecircle.png';
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 6
        });
       // var infoWindow = new google.maps.InfoWindow({map: map});

        // Try HTML5 geolocation.
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            //infoWindow.setPosition(pos);
            //infoWindow.setContent('Location found.');
            map.setCenter(pos);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
      }

      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo YOUR_API_KEY?>&callback=initMap">
    </script>
  </body>
</html>