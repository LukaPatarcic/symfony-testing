/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../../node_modules/mdbootstrap/css/bootstrap.min.css');
require('../../node_modules/mdbootstrap/css/mdb.min.css');
require('../../node_modules/mdbootstrap/js/popper.min.js');
require('../../node_modules/bootstrap/dist/js/bootstrap.min.js');
require('../../node_modules/mdbootstrap/js/mdb.min.js');
require('../css/app.css');
require('../../node_modules/wow.js/dist/wow.js');
require('../../node_modules/wow.js/css/libs/animate.css');
require('../img/google.svg');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
//const $ = require('jquery');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
var pathname = window.location.pathname;

var match = /\/user\/dashboard\/((\w)*)/.exec(pathname);
console.log(match[1]);
if(match[1] == '') {
    $('#home').addClass('active');
} else {
    $('#'+match[1]).addClass('active');

}

// Animations initialization

