import './styles/zoom.scss';
var ImageZoom = require('image-zoom');
 
var img = document.querySelector('img');
var zoom = new Imagezoom(img).overlay().padding(350);
 
img.onclick = function(e){
  // stop propagation if we want to retain our HTML api
  // in other parts of the site.
  e.stopPropagation();
  zoom.show();
};
 
// unbind our delegate listener if we aren't
// using the HTML api.
zoom.stopListening();
 