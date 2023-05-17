const initialData = {
	'RG_03': [[-35.3087186, 149.1911898], [-34.5804006,	149.7770018], [-34.4238214,	149.6193475], [-34.0827551, 151.2314215]],
	'RG_02': [[-35.3087091, 149.1911936], [-35.3192328, 149.192379], [-34.8888729, 149.5276869], [-34.5781719, 149.7788262]],
	'RG_10': [[-35.3087091, 149.1911936], [-33.950877, 151.1860735], [-33.950877, 151.1860735], [-33.950877, 151.1860735]],
};
const colorGroup = [
    '#000080', '#FFE5B4', '#404040', '#F7E7CE', '#2F4F4F', '#6A5ACD', '#FFFFF0',
    '#F7E7CE', '#228B22', '#FFFF99', '#556B2F', '#8B0000', '#87CEEB', '#F5F5DC'
];

var map;
var marker_list = {};

async function start_map() {
    const { Map } = await google.maps.importLibrary("maps");
    var mapProp = {
        center: {lat: 0, lng: 0},
        zoom: 8,
        mapId: '0',
        mapTypeId: 'satellite'
    };
    
    map = new Map($('#trackingmap')[0], mapProp);
    refresh_marker();
}

function remove_all_markers()
{
    for(device_name in marker_list) {
        marker_list[device_name].forEach((marker, index) => {
            marker.setMap(null);
        })
    }
    marker_list = {};
}

async function refresh_marker(data) {
    data = data || initialData;
    console.log("refresh_marker", data);

    remove_all_markers();

    const { AdvancedMarkerElement, Marker } = await google.maps.importLibrary("marker");
    const position = { lat: -25.344, lng: 131.031 };

    let current_group = 0;

    for(device_name in data) { 
        positions = data[device_name];
        marker_list[device_name] = [];
        positions.forEach((pos, index) => {
            let position = {lat: pos[0], lng: pos[1]};
            const marker = new AdvancedMarkerElement({
                map: map,
                position: position,
                content: create_marker(current_group, (device_name+"_("+index+")").trim())
            });

            marker_list[device_name].push(marker);
        })
        current_group++;
    }
    refresh_map_bound();
}

function refresh_map_bound() {
    var bounds = new google.maps.LatLngBounds();

    for(device_name in marker_list) {
        marker_list[device_name].forEach((marker, index) => {
            bounds.extend(marker.position);
        })
    }

    map.fitBounds(bounds);
}


/*

device_name:    name of the device;
index:          index of targeting marker in a list of mark positions.
position:       target position

*/

function move_marker(device_name, index, position) {
    marker_list[device_name][index].setPosition(new google.maps.LatLng(positoin[0], position[1]))
}

function getInvertedColor(color) {

    var r = parseInt(color.substr(1, 2), 16);
    var g = parseInt(color.substr(3, 2), 16);
    var b = parseInt(color.substr(5, 2), 16);

    r = 255 - r;
    g = 255 - g;
    b = 255 - b;

    var invertedColor = "#" + r.toString(16) + g.toString(16) + b.toString(16);
    console.log(invertedColor);
    return invertedColor;
}

function create_marker(group_index, title) {
    var markerIcon = document.createElement('div');

    markerIcon.style.borderRadius = '10px';
    markerIcon.style.width = '20px';
    markerIcon.style.height = '20px';
    markerIcon.style.background = colorGroup[group_index];
    markerIcon.style.borderWidth = '2px';
    markerIcon.style.borderStyle = 'solid';
    markerIcon.style.borderColor = 'white';
    markerIcon.style.display = 'flex';
    markerIcon.style.justifyContent = 'center';
    markerIcon.style.textAlign = 'center';

    var textMark = document.createElement('span');
    textMark.textContent = title;
    textMark.style.backgroundColor = colorGroup[group_index];
    textMark.style.color = getInvertedColor(colorGroup[group_index]);
    textMark.style.borderRadius = '2px';
    textMark.style.position = 'relative';
    textMark.style.top = '22px';
    textMark.style.padding = '1px 5px'
    textMark.style.fontSize = '10px';
    textMark.style.height = '15px';

    markerIcon.appendChild(textMark);

    return markerIcon;
}

$(document).ready(() => {
    start_map();
})
