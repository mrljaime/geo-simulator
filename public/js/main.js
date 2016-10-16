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
