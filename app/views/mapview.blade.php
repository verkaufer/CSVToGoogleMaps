@extends('layout.master')

@section('content')
  <div id="map-canvas"></div>
@stop

@section('js-includes')
    <!-- Google Maps API library -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBlHCth60LNlyArX5Q_QI41bWTaAsHLt40"></script>
    <!-- Google Maps builder -->
    <script type="text/javascript">

        // global map variable
        var map;

        // creates our map view
        function initialize() {
          var mapOptions = {
            zoom: 8,
            center: new google.maps.LatLng(40.592676, -74.07419199999998)
          };
          map = new google.maps.Map(document.getElementById('map-canvas'),
              mapOptions);

          buildMarkers(map);

        }

        // dynamically builds markers and creates colored "clusters" of markers based on common category data
        function buildMarkers(map)
        {
          //Category Loop
          @foreach($mapDataPts as $groupedDataPt)

          // Randomly generate pin color for each category
          var pinColor = Math.floor(Math.random()*16777215).toString(16);

            //Location loop
            @foreach($groupedDataPt as $dataPt)

              var geoCodedLoc = new google.maps.LatLng({{ $dataPt['lat'] }}, {{ $dataPt['lng'] }});

              // create the marker
              var marker = new google.maps.Marker({
                  position: geoCodedLoc,
                  title: '{{ $dataPt["category"] }}',
                  map: map,
                  icon: {
                    path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
                    scale: 8.5,
                    fillColor: '#'+pinColor,
                    fillOpacity: 1.0,
                    strokeWeight: 0.4
                  }
              });

              @endforeach

          @endforeach
        }

        // register initialize function to run on page load
        google.maps.event.addDomListener(window, 'load', initialize);
        

    </script>
    <!-- End Google Maps builder -->
@stop