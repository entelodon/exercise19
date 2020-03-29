require('./bootstrap');

import $ from 'jquery';
window.$ = window.jQuery = $;

import 'jquery-ui/ui/widgets/datepicker.js';

import 'selectize/dist/js/selectize';

import 'moment/min/locales.min'

window.moment = require('moment');
moment().format();

window.Chart = require('chart.js');
