"use strict";

require("./styles/app.scss");

require("@popperjs/core");

var _a2lix_sf_collection = _interopRequireDefault(require("@a2lix/symfony-collection/src/a2lix_sf_collection"));

require("./bootstrap");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// any CSS you import will output into a single css file (app.css in this case)
require('bootstrap');

_a2lix_sf_collection["default"].sfCollection.init({
  collectionsSelector: 'form div[data-prototype]',
  manageRemoveEntry: true,
  lang: {
    add: 'Ajouter une image',
    remove: 'Effacer'
  }
}); // start the Stimulus application
//# sourceMappingURL=app.dev.js.map
