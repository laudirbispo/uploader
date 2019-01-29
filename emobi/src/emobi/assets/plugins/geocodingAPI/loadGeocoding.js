'use strict';

var geocoder;
var map;
var marker;

function initialize(position) 
{
    
    if(position === null)
    {
        var lat = -25.77599645469469;
        var lon = -53.53503098095092;
        $('#txtEndereco').val('R. Barão do Rio Branco, 1298 - Centro Cívico, Realeza - PR, 85770-000, Brasil');
        var latlng = new google.maps.LatLng(lat, lon);
    }
    else
    {
        var lat = position.coords.latitude;
        var lon = position.coords.longitude;
        var latlng = new google.maps.LatLng(lat, lon);
    }

	var options = {
		zoom: 16,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var image = '/app/images/icons/marker-custom.png';
	map = new google.maps.Map(document.getElementById("mapa"), options);
	geocoder = new google.maps.Geocoder();
    
    $('#txtLatitude').val(lat);
    $('#txtLongitude').val(lon); 
    
	marker = new google.maps.Marker({
		map: map,
		draggable: true,
        icon: image,
        animation: google.maps.Animation.BOUNCE,
	});
	marker.setPosition(latlng);
    setTimeout(function(){
        marker.setAnimation(google.maps.Animation.DROP);
    }, 5000); 
}

function geocodeLatLng(lat, lon) {

      var latlng = {lat: lat, lng: lon}

      geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
          if (results[1]) {
            map.setZoom(16);
            var location = new google.maps.LatLng(lat, lon);
            marker.setPosition(location);
            map.setCenter(location);
            map.setZoom(16);			
            //infowindow.setContent(results[1].formatted_address);

          } else {
            window.alert('No results found');
          }
        } else {
          window.alert('Geocoder failed due to: ' + status);
        }
      });
    
}

function showError(error)
{
  var x = document.getElementById("mapa");
  switch(error.code) 
    {
    case error.PERMISSION_DENIED:
      x.innerHTML="<div class='text-center'><br><i class='fa fa-frown-o fa-5x'></i><br>Usuário rejeitou a solicitação de Geolocalização.</div>";
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML="<div class=' text-center'><br><i class='fa fa-frown-o fa-5x'></i><br>Localização indisponível.</div>";
      break;
    case error.TIMEOUT:
      x.innerHTML="<div class='text-center'><br><i class='fa fa-frown-o fa-5x'></i><br>O tempo da requisição expirou.</div>";
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML="<div class='text-center'><br><i class='fa fa-frown-o fa-5x'></i><br>Algum erro desconhecido aconteceu.</div>";
      break;
    }
}
