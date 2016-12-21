jQuery(document).ready(function($) {

$(document).foundation();

var h = document.getElementById("masthead");
//var readout = document.getElementById("readout");
var stuck = false;
var stickPoint = getDistance();

function getDistance() {
  var topDist = h.offsetTop + 180;
  return topDist;
}

window.onscroll = function(e) {
  var distance = getDistance() - window.pageYOffset;
  var offset = window.pageYOffset;
  if ( (distance <= 0) && !stuck) {
    h.classList.toggle('sticky');
    stuck = true;
  } else if (stuck && (offset <= stickPoint + 190)){
    h.classList.toggle('sticky');
    stuck = false;
  }
}

});
