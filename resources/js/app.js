import './bootstrap';
import { initPhoneFormatter } from './phone-formatter';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Инициализация форматтера телефона
document.addEventListener('DOMContentLoaded', () => {
    initPhoneFormatter();
});
