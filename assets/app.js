/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import '@popperjs/core';

require('bootstrap');
require('fslightbox')
require('jquery');


import a2lix_lib from '@a2lix/symfony-collection/src/a2lix_sf_collection';


a2lix_lib.sfCollection.init({
    collectionsSelector: 'form div#trick_pictures[data-prototype]',
    manageRemoveEntry: true,
    lang: {
        add: 'Ajouter une image',
        remove: 'Effacer'
    }
});

a2lix_lib.sfCollection.init({
    collectionsSelector: 'form div#trick_videos[data-prototype]',
    manageRemoveEntry: true,
    lang: {
        add: 'Ajouter une video',
        remove: 'Effacer'
    }
});


// start the Stimulus application
import './bootstrap';

let element = document.getElementById('test');
let [btn] = document.getElementsByTagName('button');
btn.addEventListener('click', () => {
    element.scrollIntoView({ behavior: "smooth" });
})

import $ from "jquery";




