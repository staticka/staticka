var map = function () {
  var element = document.getElementById('rs-map');

  var place = { lat: 7.074744, lng: 125.619585 };

  var map = new google.maps.Map(element, { zoom: 17, center: place });

  var marker = new google.maps.Marker({ position: place, map: map });
};