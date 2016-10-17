/**
 * Created by jaime on 16/10/16.
 */

function MapUses(){};

MapUses.prototype.addMarker = function(map, latLng, icon, title, draggable) {
    var marker = new google.maps.Marker({
        position: latLng,
        title: title,
        map: map,
        draggable: draggable,
    });

    if (icon != null) {
        marker.setIcon(icon);
    }

    return marker;
};

MapUses.prototype.addPolyline = function(map, latLngs, color) {
    var polyline = new google.maps.Polyline({
        path: latLngs,
        geodesic: true,
        strokeColor: color,
        strokeOpacity: 1.0,
        strokeWeight: 5
    });

    polyline.setMap(map);

    return polyline;
};
