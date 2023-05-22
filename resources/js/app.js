import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();

import { themeChange } from 'theme-change'
themeChange()

import ApexCharts from 'apexcharts'
