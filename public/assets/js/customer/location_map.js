

function initAutocomplete() {
  var marker = [];
  var geocoder = new google.maps.Geocoder();
  if($('#lat').val())
  {
    var lat_current = parseFloat($('#lat').val());
    var lng_current = parseFloat($('#lng').val());
  }
  else
  {
    var lat_current = '';
    var lng_current = '';
  }
 /* var lat_current = '30';
  var lang_current = '40';*/
//console.log(lat_current);
  if(lat_current!='')
  {
    var map = new google.maps.Map(document.getElementById('map'), {
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      center: {lat: lat_current, lng: lng_current},
      zoom: 15,
      mapTypeId: 'roadmap',
      zoomControl: true,
      mapTypeControl: false,
      gestureHandling: 'cooperative',
      draggable: true
    });
  }
  else {
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
  }
  
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
  if(lat_current!='') {
    //console.log(parseFloat(lat_current));
    var defaultBounds = new google.maps.LatLngBounds({lat: lat_current, lng: lng_current});
  }
  else
  {
    var defaultBounds = new google.maps.LatLngBounds({lat: 30.6762841, lng: 76.86411778});
  }
  map.fitBounds(defaultBounds);    
  var input = /** @type {HTMLInputElement} */(
    document.getElementById('pac-input')
  );
  var markers = [];
  var marker = [];
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

    /*if(lat_current!='')
    {
      markers = new google.maps.Marker({
        position: new google.maps.LatLng(lat_current, lng_current),
        map: map,
        title: 'map'
      });
    }*/


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
      //console.log(place.geometry.location);
      //console.log(marker);
      //marker.setMap('');
      var marker = new google.maps.Marker({
        draggable: true,
        map: map,
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
              //console.log(results[0].formatted_address);
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
  if(lat_current!='')         // If we are editing the product/service
  {
    marker = new google.maps.Marker({
      draggable: true,
      map: map,
      position: new google.maps.LatLng(lat_current, lng_current)
    });
    markers.push(marker);
    geocoder.geocode({'latLng': new google.maps.LatLng(lat_current, lng_current)}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
          $('#pac-input').val(results[0].formatted_address);
          //console.log(results[0].formatted_address);
        }
      }
    });
    //console.log(marker);
    $('#location_coord').val(lat_current+','+lng_current);
    google.maps.event.addListener(marker, 'dragend', function(e) {
      geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {
            $('#pac-input').val(results[0].formatted_address);
            //console.log(results[0].formatted_address);
          }
        }
      });
      displayPosition(this.getPosition());
    });
  }
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
    //console.log(event.keyCode);
    if (event.keyCode === 13) { 
        event.preventDefault(); 
    }
  });
}  
//google.maps.event.addDomListener(window, 'load', initAutocomplete);
