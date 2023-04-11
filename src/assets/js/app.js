import $ from 'jquery';
window.jQuery = window.$ = $

import whatInput from 'what-input';

import 'add-to-calendar-button'
import { atcb_action } from "add-to-calendar-button";
document.atcb_action = atcb_action

import Foundation from 'foundation-sites';
// If you want to pick and choose which modules to include, comment out the above and uncomment
// the line below
//import './lib/foundation-explicit-pieces';

$(document).foundation();
