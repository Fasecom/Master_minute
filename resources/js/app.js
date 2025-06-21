import './bootstrap';
import { initPhoneFormatter } from './phone-formatter';
import { initMastersSearch } from './masters-search';
import './schedule-edit';

import Alpine from 'alpinejs';
import * as am5 from "@amcharts/amcharts5";
import * as am5xy from "@amcharts/amcharts5/xy";
import am5themes_Animated from "@amcharts/amcharts5/themes/Animated";

window.Alpine = Alpine;
window.am5 = am5;
window.am5xy = am5xy;
window.am5themes_Animated = am5themes_Animated;

Alpine.start();

// Инициализация форматтера телефона
document.addEventListener('DOMContentLoaded', function() {
    initPhoneFormatter();
    initMastersSearch();
});
