
<html>
 <head>
     <meta name="csrf-token" content="{{ csrf_token() }}">
   <style>
       html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
      .controls {
        margin-top: 16px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }
      #pac-input {
       background-color: #fff;
    font-family: Roboto;
    font-size: 15px;
    font-weight: 300;
    margin-left: 12px;
    padding: 0 11px 0 13px;
    text-overflow: ellipsis;
    width: 400px;
    border-radius: 25px;
    height: 50px;
    outline: none;
      }

      #pac-input:focus {
        border-color: transparent;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }

      .gm-style .transit-container{background-color:white;max-width:265px;overflow-x:hidden}.gm-style .transit-container .transit-title span{font-size:14px;font-weight:500}.gm-style .transit-container .transit-title{padding-bottom:6px}.gm-style .transit-container .transit-wheelchair-icon{background:transparent url('https://maps.gstatic.com/mapfiles/api-3/images/mapcnt6.png');background-size:59px 492px;display:inline-block;background-position:-5px -450px;width:13px;height:13px}.gm-style.gm-china .transit-container .transit-wheelchair-icon{background-image:url('http://maps.gstatic.cn/mapfiles/api-3/images/mapcnt6.png')}@media (-webkit-min-device-pixel-ratio:1.2),(min-resolution:1.2dppx),(min-resolution:116dpi){.gm-style .transit-container .transit-wheelchair-icon{background-image:url('https://maps.gstatic.com/mapfiles/api-3/images/mapcnt6_hdpi.png');background-size:59px 492px;display:inline-block;background-position:-5px -449px;width:13px;height:13px}.gm-style.gm-china .transit-container .transit-wheelchair-icon{background-image:url('http://maps.gstatic.cn/mapfiles/api-3/images/mapcnt6_hdpi.png')}}.gm-style .transit-container div{background-color:white;font-size:11px;font-weight:300;line-height:15px}.gm-style .transit-container .transit-line-group{overflow:hidden;margin-right:-6px}.gm-style .transit-container .transit-line-group-separator{border-top:1px solid #e6e6e6;padding-top:5px}.gm-style .transit-container .transit-nlines-more-msg{color:#999;margin-top:-3px;padding-bottom:6px}.gm-style .transit-container .transit-line-group-vehicle-icons{display:inline-block;padding-right:10px;vertical-align:top;margin-top:1px}.gm-style .transit-container .transit-line-group-content{display:inline-block;min-width:100px;max-width:228px;margin-bottom:-3px}.gm-style .transit-container .transit-clear-lines{clear:both}.gm-style .transit-container .transit-div-line-name{float:left;padding:0 6px 6px 0;white-space:nowrap}.gm-style .transit-container .transit-div-line-name .gm-transit-long{width:107px}.gm-style .transit-container .transit-div-line-name .gm-transit-medium{width:50px}.gm-style .transit-container .transit-div-line-name .gm-transit-short{width:37px}.gm-style .transit-div-line-name .renderable-component-icon{float:left;margin-right:2px}.gm-style .transit-div-line-name .renderable-component-color-box{background-image:url(https://maps.gstatic.com/mapfiles/transparent.png);height:10px;width:4px;float:left;margin-top:3px;margin-right:3px;margin-left:1px}.gm-style.gm-china .transit-div-line-name .renderable-component-color-box{background-image:url(http://maps.gstatic.cn/mapfiles/transparent.png)}.gm-style .transit-div-line-name .renderable-component-text{text-align:left;overflow:hidden;text-overflow:ellipsis;display:block}.gm-style .transit-div-line-name .renderable-component-text-box{overflow:hidden;text-overflow:ellipsis;display:block;font-size:8pt;font-weight:400;text-align:center;padding:1px 2px}.gm-style .transit-div-line-name .renderable-component-text-box-white{border:solid 1px #ccc;background-color:white;padding:0 2px}.gm-style .transit-div-line-name .renderable-component-bold{font-weight:400}
.poi-info-window div,.poi-info-window a{color:#333;font-family:Roboto,Arial;font-size:13px;background-color:white;-moz-user-select:text;-webkit-user-select:text;-ms-user-select:text;user-select:text}.poi-info-window{cursor:default}.poi-info-window a:link{text-decoration:none;color:#427fed}.poi-info-window .view-link,.poi-info-window a:visited{color:#427fed}.poi-info-window .view-link:hover,.poi-info-window a:hover{cursor:pointer;text-decoration:underline}.poi-info-window .full-width{width:180px}.poi-info-window .title{overflow:hidden;font-weight:500;font-size:14px}.poi-info-window .address{margin-top:2px;color:#555}
.gm-style .gm-style-iw{font-weight:300;font-size:13px;overflow:hidden}.gm-style .gm-style-iw-a{position:absolute;width:9999px;height:0}.gm-style .gm-style-iw-t{position:absolute;width:100%}.gm-style .gm-style-iw-t::after{background:linear-gradient(45deg,rgba(255,255,255,1) 50%,rgba(255,255,255,0) 51%,rgba(255,255,255,0) 100%);box-shadow:-2px 2px 2px 0 rgba(178,178,178,.4);content:"";height:15px;left:0;position:absolute;top:0;transform:translate(-50%,-50%) rotate(-45deg);width:15px}.gm-style .gm-style-iw-c{position:absolute;box-sizing:border-box;overflow:hidden;top:0;left:0;transform:translate(-50%,-100%);background-color:white;border-radius:8px;padding:12px;box-shadow:0 2px 7px 1px rgba(0,0,0,0.3)}.gm-style .gm-style-iw-d{box-sizing:border-box;overflow:auto}.gm-style .gm-style-iw-d::-webkit-scrollbar{width:18px;height:12px;-webkit-appearance:none}.gm-style .gm-style-iw-d::-webkit-scrollbar-track,.gm-style .gm-style-iw-d::-webkit-scrollbar-track-piece{background:#fff}.gm-style .gm-style-iw-c .gm-style-iw-d::-webkit-scrollbar-thumb{background-color:rgba(0,0,0,0.12);border:6px solid transparent;border-radius:9px;background-clip:content-box}.gm-style .gm-style-iw-c .gm-style-iw-d::-webkit-scrollbar-thumb:horizontal{border:3px solid transparent}.gm-style .gm-style-iw-c .gm-style-iw-d::-webkit-scrollbar-thumb:hover{background-color:rgba(0,0,0,0.3)}.gm-style .gm-style-iw-c .gm-style-iw-d::-webkit-scrollbar-corner{background:transparent}.gm-style .gm-iw{color:#2c2c2c}.gm-style .gm-iw b{font-weight:400}.gm-style .gm-iw a:link,.gm-style .gm-iw a:visited{color:#4272db;text-decoration:none}.gm-style .gm-iw a:hover{color:#4272db;text-decoration:underline}.gm-style .gm-iw .gm-title{font-weight:400;margin-bottom:1px}.gm-style .gm-iw .gm-basicinfo{line-height:18px;padding-bottom:12px}.gm-style .gm-iw .gm-website{padding-top:6px}.gm-style .gm-iw .gm-photos{padding-bottom:8px;-ms-user-select:none;-moz-user-select:none;-webkit-user-select:none}.gm-style .gm-iw .gm-sv,.gm-style .gm-iw .gm-ph{cursor:pointer;height:50px;width:100px;position:relative;overflow:hidden}.gm-style .gm-iw .gm-sv{padding-right:4px}.gm-style .gm-iw .gm-wsv{cursor:pointer;position:relative;overflow:hidden}.gm-style .gm-iw .gm-sv-label,.gm-style .gm-iw .gm-ph-label{cursor:pointer;position:absolute;bottom:6px;color:#fff;font-weight:400;text-shadow:rgba(0,0,0,0.7) 0 1px 4px;font-size:12px}.gm-style .gm-iw .gm-stars-b,.gm-style .gm-iw .gm-stars-f{height:13px;font-size:0}.gm-style .gm-iw .gm-stars-b{position:relative;background-position:0 0;width:65px;top:3px;margin:0 5px}.gm-style .gm-iw .gm-rev{line-height:20px;-ms-user-select:none;-moz-user-select:none;-webkit-user-select:none}.gm-style.gm-china .gm-iw .gm-rev{display:none}.gm-style .gm-iw .gm-numeric-rev{font-size:16px;color:#dd4b39;font-weight:400}.gm-style .gm-iw.gm-transit{margin-left:15px}.gm-style .gm-iw.gm-transit td{vertical-align:top}.gm-style .gm-iw.gm-transit .gm-time{white-space:nowrap;color:#676767;font-weight:bold}.gm-style .gm-iw.gm-transit img{width:15px;height:15px;margin:1px 5px 0 -20px;float:left}
.gm-iw {text-align:left;}.gm-iw .gm-numeric-rev {float:left;}.gm-iw .gm-photos,.gm-iw .gm-rev {direction:ltr;}.gm-iw .gm-stars-f, .gm-iw .gm-stars-b {background:url("https://maps.gstatic.com/mapfiles/api-3/images/review_stars.png") no-repeat;background-size: 65px 26px;float:left;}.gm-iw .gm-stars-f {background-position:left -13px;}.gm-iw .gm-sv-label,.gm-iw .gm-ph-label {left: 4px;}
.gm-style .gm-style-mtc label,.gm-style .gm-style-mtc div{font-weight:400}


   </style>
 </head>
<body>
    <input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="map"></div>
</body>
</html>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD08Emygz5W4HKOZXvogXKb5zYjA8ZRMaQ"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            if (localStorage.getItem("latitude") === null || localStorage.getItem("latitude") === "") {
                initGeolocation();
            }
            function initGeolocation() {
                if (navigator && navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
                } else {
                    console.log('Geolocation is not supported');
                }
            }

            function errorCallback() {
                localStorage.setItem("latitude","");
                localStorage.setItem("longitude","");

            }

            function successCallback(position) {
                localStorage.setItem("latitude",position.coords.latitude);
                localStorage.setItem("longitude",position.coords.longitude);

            }

        });

    </script>
<script type="text/javascript">

    var map;
    var infoWindow;
    var markersData = [];
    var mainUrl = '';
    var lat = $('#lat').val();
    var lng = $('#lng').val();
    var icons = {
        shop: {
            icon:  'assets/googlemap/orange.png'
        },
        home: {
            icon: 'assets/googlemap/purple.png'
        },
        hotspot: {
            icon: 'assets/googlemap/red.png'
        }
    };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    console.log($('meta[name="csrf-token"]').attr('content'));
    $.ajax({
        type: 'POST',
        url: mainUrl+'/fetchproducts',
        dataType : 'json',
        data:{'country':'<?php echo $location['country']; ?>'},
        success: function(data) {
            console.log(data);
            markersData = data;
            initialize(lat,lng);

        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
            console.log();
        }
    });
    function initGeolocation() {
        if (navigator && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            console.log('Geolocation is not supported');
        }
    }
    function errorCallback() {
        localStorage.setItem("latitude","");
        localStorage.setItem("longitude","");

    }

    function successCallback(position) {
        localStorage.setItem("latitude",position.coords.latitude);
        localStorage.setItem("longitude",position.coords.longitude);

    }

    function initialize(latitude,longitude)
    {
        var mapOptions = {
            center: new google.maps.LatLng(latitude,longitude),
            zoom: 10,
            mapTypeId: 'roadmap',
            gestureHandling: 'greedy',
            styles: [{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#d6e2e6"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#cfd4d5"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#7492a8"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"lightness":25}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#dde2e3"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"color":"#cfd4d5"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"color":"#dde2e3"}]},{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"color":"#7492a8"}]},{"featureType":"landscape.natural.terrain","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#dde2e3"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#588ca4"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"saturation":-100}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#a9de83"}]},{"featureType":"poi.park","elementType":"geometry.stroke","stylers":[{"color":"#bae6a1"}]},{"featureType":"poi.sports_complex","elementType":"geometry.fill","stylers":[{"color":"#c6e8b3"}]},{"featureType":"poi.sports_complex","elementType":"geometry.stroke","stylers":[{"color":"#bae6a1"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#41626b"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"saturation":-45},{"lightness":10},{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#c1d1d6"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#a6b5bb"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"visibility":"on"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.fill","stylers":[{"color":"#9fb6bd"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"transit","elementType":"labels.icon","stylers":[{"saturation":-70}]},{"featureType":"transit.line","elementType":"geometry.fill","stylers":[{"color":"#b4cbd4"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#588ca4"}]},{"featureType":"transit.station","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#008cb5"},{"visibility":"on"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"saturation":-100},{"lightness":-5}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#a6cbe3"}]}]
        };

        map = new google.maps.Map(document.getElementById('map'), mapOptions);

        // a new Info Window is created
        infoWindow = new google.maps.InfoWindow();

        // Event that closes the Info Window with a click on the map
        google.maps.event.addListener(map, 'click', function() {
            infoWindow.close();
        });

        // Finally displayMarkers() function is called to begin the markers creation
        displayMarkers(latitude,longitude);
        setTimeout(function(){
            map.setCenter(new google.maps.LatLng(latitude,longitude));
            map.setZoom(10);
        }, 300);
    }
    function displayMarkers(latitude,longitude)
    {
        // this variable sets the map bounds according to markers position
        var bounds = new google.maps.LatLngBounds();

        // for loop traverses markersData array calling createMarker function for each marker
        for (var i = 0; i < markersData.length; i++){

            var latlng = new google.maps.LatLng(markersData[i].lat, markersData[i].lng);
            var name = markersData[i].title;
            //var address1 = markersData[i].address1;
            var image = '<img src="'+markersData[i].image+'" alt="" style="width:50px;">';
            var link = markersData[i].link;
            // var postalCode = markersData[i].postalCode;
            // var cat = markersData[i].cat;
            // var price = markersData[i].price;

            createMarker(latlng, name, image,link);

            // marker position is added to bounds variable
            bounds.extend(latlng);
        }

        // Finally the bounds variable is used to set the map bounds
        // with fitBounds() function
        map.fitBounds(bounds);
    }
    function createMarker(latlng, name, image, link) {
        var marker = new google.maps.Marker({
            map: map,
            position: latlng,
            title: name,
            icon: icons['home'].icon
        });
        // This event expects a click on a marker
        // When this event is fired the Info Window content is created
        // and the Info Window is opened.
        google.maps.event.addListener(marker, 'click', function () {

            // Creating the content to be inserted in the infowindow
            var iwContent =  '<div id="content">'+
                '<div id="siteNotice">'+
                '</div>'+
                '<h1 id="firstHeading" class="firstHeading" style="color:#d01c76;"><i class="ti-location-pin" style="color:#d01c76"></i>'+ name +'</h1>'+
                '<div id="bodyContent">'+
                '<p style="color:#d01c76;"></p>' +
                ''+
                '<p><a href=' + link +'>'+
                image +'</a> '+
                '.</p>'+
                '</div>'+
                '</div>';

            // including content to the Info Window.
            infoWindow.setContent(iwContent);

            // opening the Info Window in the current map and at the current marker location.
            infoWindow.open(map, marker);
        });

    }
</script>
</body> 
</html>