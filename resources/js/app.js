import './bootstrap';
import { initPhoneFormatter } from './phone-formatter';
import { initMastersSearch } from './masters-search';
import './schedule-edit';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Инициализация форматтера телефона
document.addEventListener('DOMContentLoaded', function() {
    initPhoneFormatter();
    initMastersSearch();
});
