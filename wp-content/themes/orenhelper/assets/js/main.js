// 'use strict';
// (function ($, window, document) {
//     var myLatlng = new google.maps.LatLng(-25.363882, 131.044922);
//     var mapOptions = {
//         zoom: 4,
//         center: myLatlng
//     }
//     var map = new google.maps.Map(document.getElementById("map"), mapOptions);
//
//     var marker = new google.maps.Marker({
//         position: myLatlng,
//         title: "Hello World!"
//     });
//
//     // To add the marker to the map, call setMap();
//     marker.setMap(map);
//
//     $('#gmap').click(function (e) {
//         e.preventDefault();
//
//         var is_chrome = /chrom(e|ium)/.test(navigator.userAgent.toLowerCase());
//         var is_ssl = 'https:' == document.location.protocol;
//         if (is_chrome && !is_ssl) {
//             return false;
//         }
//
//         navigator.geolocation.getCurrentPosition(
//             function (position) { // все в порядке
//
//                 var lat = position.coords.latitude;
//                 var lng = position.coords.longitude;
//                 var google_map_pos = new google.maps.LatLng(lat, lng);
//
//
//                 var google_maps_geocoder = new google.maps.Geocoder();
//                 google_maps_geocoder.geocode(
//                     {'latLng': google_map_pos},
//                     function (results, status) {
//                         if (status == google.maps.GeocoderStatus.OK && results[0]) {
//                             // console.log(results[0].formatted_address);
//                         }
//                     }
//                 );
//             },
//             function () { // ошибка
//             }
//         );
//     });
// })(jQuery, window, document);

// (function ($) {
//
//     $(document).ready(function () {
//         let map = new google.maps.Map(document.getElementById("map"), {
//             center: { lat: -34.397, lng: 150.644 },
//             zoom: 8,
//         });
//     });
//
//
// })(jQuery);