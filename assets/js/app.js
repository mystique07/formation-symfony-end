/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
//import $ from 'jquery';

import $  from 'jquery';
//global.$ = $;
//global.$ =global.jQuery=$;
import('@fortawesome/fontawesome-free/js/fontawesome');
import('@fortawesome/fontawesome-free/js/solid');
import('@fortawesome/fontawesome-free/js/regular');
import('@fortawesome/fontawesome-free/js/brands');
import('bootstrap');
import('./ad.js');




console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
