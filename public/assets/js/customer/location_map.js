

function initAutocomplete() {
  var markers = [];
  var geocoder = new google.maps.Geocoder();
  var lat_current = '';
  var lang_current = '';
  var map = new google.maps.Map(document.getElementById('map'), {
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    center: {lat: 30.6762841, lng: 76.86411778},
          zoom: 15,
          mapTypeId: 'roadmap',
          zoomControl: true,
          mapTypeControl: false,
          gestureHandling: 'cooperative',
          draggable: true
  });
  
  /*if (navigator.geolocation) {
   navigator.geolocation.getCurrentPosition(position_current);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
  function position_current(position){
    lat_current = position.coords.latitude;
    lang_current = position.coords.longitude;
    
  }
  
console.log(lat_current);
console.log(lang_current);*/

  var defaultBounds = new google.maps.LatLngBounds({lat: 30.6762841, lng: 76.86411778});
  map.fitBounds(defaultBounds);    
  var input = /** @type {HTMLInputElement} */(
    document.getElementById('pac-input')
  );
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  var searchBox = new google.maps.places.SearchBox(
    /** @type {HTMLInputElement} */(input));
  google.maps.event.addListener(searchBox, 'places_changed', function() {
    var places = searchBox.getPlaces();
    if (places.length == 0) {
      return;
    }
    for (var i = 0, marker; marker = markers[i]; i++) {
      marker.setMap(null);
    }
    markers = [];
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0, place; place = places[i]; i++) {
      var image = {
        url: place.icon,
       // url:'public/assets/icons/mapicon/mapicon.png',
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };
      var marker = new google.maps.Marker({
        draggable: true,
        map: map,
        icon: image,
        title: place.name,
        position: place.geometry.location
      });
       displayPosition(place.geometry.location);
     
      // drag response
      google.maps.event.addListener(marker, 'dragend', function(e) {
        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
              $('#pac-input').val(results[0].formatted_address);
              console.log(results[0].formatted_address);
            }
          }
        });
        displayPosition(this.getPosition());
      });
      // click response
      google.maps.event.addListener(marker, 'click', function(e) {
        displayPosition(this.getPosition());
      });
      markers.push(marker);
      bounds.extend(place.geometry.location);
    }
    map.fitBounds(bounds);   
  });
  google.maps.event.addListener(map, 'bounds_changed', function() {
    var bounds = map.getBounds();
    searchBox.setBounds(bounds);
    if (this.getZoom()){
            this.setZoom(14);
        }
  });
  // displays a position on two <input> elements
  function displayPosition(pos) {
    //console.log(pos.lat());
    //console.log(pos.lng());
    /*document.getElementById('lat').value = pos.lat();
    document.getElementById('lng').value = pos.lng();*/
    $('#location_coord').val(pos.lat()+','+pos.lng());
  }
  google.maps.event.addDomListener(input, 'keydown', function(event) { 
    console.log(event.keyCode);
    if (event.keyCode === 13) { 
        event.preventDefault(); 
    }
  });
}  
//google.maps.event.addDomListener(window, 'load', initAutocomplete);
