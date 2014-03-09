var MapStruct = {
    map: null,
    radiusCircle: null,
    defaultZoom: 1,
    // selector function
    mapUpdate: function(location,radius){
        MapStruct.map.setCenter(location);
        MapStruct.radiusCircle.setRadius(radius * 1000); //shodan goes in increments of 1km, GMaps likes meters
        MapStruct.radiusCircle.setCenter(MapStruct.map.getCenter());
        MapStruct.map.fitBounds(MapStruct.radiusCircle.getBounds());
        MapStruct.map.circleRadius = radius;
        //MapStruct.radiusCircle.setMap(MapStruct.map); //use this to show the radius circle
    },
    newLocation: function(event){
        event.preventDefault();
        var geocode="https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyABK5DfRbmldYuU243Mwweg76U4VBTQwhw&sensor=false&address=";
        var address=$("#customAddress")[0].value.replace(/a-zA-Z0-9 +$/g,'');
        geocode += address;
        $.get(geocode,function(data,status){
            console.log(data);
            if(status=="success")
            {
                var coords = data.results[0].geometry.location;
                MapStruct.mapUpdate(coords,MapStruct.defaultZoom);
            }
            else //error handling if we couldn't get Geolocation
            {
                alert("Failed to fetch your Geolocation codes.  Please try again later.");
            }
        },"json");
    },
    geoLocate: function(){
        if(!navigator.geolocation)
        {
            alert("Geolocation module not present");
        }
        else
        {
            navigator.geolocation.getCurrentPosition(
                function(pos) //Successfully grabbed location from HTML5 Geolocation API
                {
                    var initialLocation = new google.maps.LatLng(pos.coords.latitude,pos.coords.longitude);
                    MapStruct.mapUpdate(initialLocation,MapStruct.defaultZoom);
                },
                function(error) //Failed to get location from HTML5 Geolocation API.  Parse error
                {
                    switch(error)
                    {
                        case 1: alert("User declined to give location."); break;
                        case 2: alert("Position unavailable from HTML5 Geolocation API"); break;
                        case 3: alert("Timed out while trying to grab location from HTML5 Geolocation API"); break;
                        default: alert("An unknown error occurred while using the HTML5 Geolocation API"); break;
                    }
                }
            );
        }
    },
    onReady: function(){
        //set up initial map
        MapStruct.map = new google.maps.Map(document.getElementById("map-canvas"),
            {
                center: new google.maps.LatLng(-34.397, 150.644),
                zoom: 10
            });
        MapStruct.radiusCircle = new google.maps.Circle({radius: MapStruct.defaultZoom, center: MapStruct.map.getCenter()});
        google.maps.event.addListener(MapStruct.map, 'dragend', function(){
            MapStruct.radiusCircle.setCenter(MapStruct.map.getCenter());
        });
        google.maps.event.addDomListener(window, 'load', this.geoLocate);
    }
};
$(document).ready(function()
    {
        MapStruct.onReady();
        //set up event handler for changing location
        $("#frmLocation").on('submit', MapStruct.newLocation);
    });