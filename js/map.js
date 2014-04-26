var MapStruct = {
    map: null,
    radiusCircle: null,
    defaultRadius: 1, //1km = default radius
    markers: [],
    // selector function
    round: function(num){
        return Number((num).toFixed(2));
    },
    makeInfoWindowEvent: function(infoWindow, contentString, marker) {
        google.maps.event.addListener(marker, 'click', function()
        {
            infoWindow.setContent(contentString);
            infoWindow.open(MapStruct.map, marker);
        });
    },
    mapUpdate: function(location,radius){
        if(MapStruct.map != null){
            MapStruct.map.setCenter(location);
            MapStruct.radiusCircle.setRadius(radius * 1000); //shodan goes in increments of 1km, GMaps likes meters
            MapStruct.radiusCircle.setCenter(MapStruct.map.getCenter());
            MapStruct.map.fitBounds(MapStruct.radiusCircle.getBounds());
            //MapStruct.radiusCircle.setMap(MapStruct.map); //use this to show the radius circle
        }
        else{
            //set up initial map
            MapStruct.map = new google.maps.Map(document.getElementById("map-canvas"),
                {
                    center: new google.maps.LatLng(location.lat,location.lng),
                    //zoom: 10,
                    mapTypeId: google.maps.MapTypeId.HYBRID,
                    scaleControl: true,
                    zoomControl: false,
                    panControl: false
                    //scrollwheel: false,
                    //disableDoubleClickZoom: true
                });
            MapStruct.radiusCircle = new google.maps.Circle({radius: radius*1000, center: MapStruct.map.getCenter()});
            MapStruct.map.fitBounds(MapStruct.radiusCircle.getBounds());
            //Keep zoom the same even if user moves around the map
            google.maps.event.addListener(MapStruct.map, 'dragend', function(){
                MapStruct.radiusCircle.setCenter(MapStruct.map.getCenter());
            });
        }
        $("#decoration").fadeOut();
        $("#frmLocation").fadeOut();
        $(".btn").hide();
        setTimeout(function(){
            $("#frmLocation").removeClass("text-center").addClass("text-left").fadeIn();
            }
        ,500);

        //TODO: Add code to shrink UI when map is in view, then enlarge it again when putting in address
    },
    frmAddressSubmit: function(event){
        event.preventDefault();
        var url="https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyABK5DfRbmldYuU243Mwweg76U4VBTQwhw&sensor=false&address=";
        var address=$("#customAddress")[0].value.replace(/a-zA-Z0-9 +$/g,'');
        $("#customAddress")[0].value = "";
        url += address;
        $.get(url,function(data,status){
            if(status=="success")
            {
                var coords = data.results[0].geometry.location; //TODO: Handle when there is more than 1 result.
                MapStruct.mapUpdate(coords,MapStruct.defaultRadius);
                $.getJSON("/getsignatures.php",function(data,status){ //grabs signatures to search for
                    if(status=="success")
                    {
                        for (var sig in data)
                        {
                            (function(sig){ //make sure we're dealing with correct sig, because of asynchronous behavior
                                var loc = encodeURI(coords.lat+","+coords.lng);
                                var vulnCount= "/vulnsearch.php?op=1&sig=" + sig + "&loc=" + loc;
                                $.getJSON(vulnCount,function(data,status){ //gets hit count for each signature
                                    if(status=="success")
                                    {
                                        var count = data['total'];
                                        console.log("Found "+count+" devices which match " + sig);
                                        if(count!=0)
                                        {
                                            var vulnSearch = "/vulnsearch.php?op=2&sig=" + sig + "&loc=" + loc;
                                            $.getJSON(vulnSearch,function(data,status){
                                                if(status=="success")
                                                {
                                                    var count = data['total'];
                                                    console.log(data);
                                                    for (var matchNum in data['matches'])
                                                    {
                                                        (function(matchNum)
                                                        {
                                                            var match = data['matches'][matchNum];
                                                            /*
                                                            I actually offset the markers randomly to account for many
                                                            of them being stacked on top of each other, which wouldn't
                                                            be useful.
                                                             */
                                                            var plusOrMinus = Math.random();
                                                            var offset = Math.random();
                                                            offset = plusOrMinus > 0.5 ? offset/1000 : 0-offset/1000;
                                                            var fixedLat = match['latitude'] + offset;
                                                            var fixedLng = match['longitude'] + offset;
                                                            var markerLoc = new google.maps.LatLng(fixedLat,fixedLng);
                                                            var markerIP = match['ip'] + ':' + match['port'];

                                                            //TODO: Make infoWindows show vuln details from database
                                                            //TODO: Make only one infoWindow show up at once
                                                            //http://stackoverflow.com/questions/11467070/how-to-set-a-popup-on-markers-with-google-maps-api
                                                            var infoWindow = new google.maps.InfoWindow();

                                                            var marker = new google.maps.Marker({
                                                                position: markerLoc,
                                                                map: MapStruct.map,
                                                                title: markerIP
                                                            });
                                                            MapStruct.makeInfoWindowEvent(infoWindow,markerIP,marker);

                                                        })(matchNum)
                                                    }
                                                }
                                            });
                                            //add marker to map for each vuln device
                                        }
                                    }
                                    else
                                        console.log("FAILED to get hit count of devices in your area");
                                });
                            })(sig);
                        }
                    }
                    else
                        console.log("FAILED to retrieve signatures from database");
                });
            }
            else //error handling if we couldn't get Geolocation
            {
                alert("Failed to fetch your Geolocation codes.  Please try again later.");
            }
        },"json");
    },
    getLocFromGPS: function(){
        if(!navigator.geolocation)
        {
            alert("Geolocation module not present");
        }
        else
        {
            navigator.geolocation.getCurrentPosition(
                function(pos) //Successfully grabbed location from HTML5 Geolocation API
                {   // Figure out why the map isn't working with auto-location on phone
                    var initialLocation = {"lat":pos.coords.latitude,"lng":pos.coords.longitude};
                    MapStruct.mapUpdate(initialLocation,MapStruct.defaultRadius);
                },
                function(error) //Failed to get location from HTML5 Geolocation API.  Parse error
                {
                    alert("Fine!  Don't give me your GPS location!  See if I care!");
                    //Todo: add notification that fades away after telling user about not having GPS
                }
            );
        }
    }
};
$(document).ready(function()
    {
        MapStruct.getLocFromGPS();
        //set up event handler for changing location
        $("#frmLocation").on('submit', MapStruct.frmAddressSubmit);
    });