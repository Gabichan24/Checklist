import './bootstrap';

import Alpine from 'alpinejs'
import persist from '@alpinejs/persist'

window.Alpine = Alpine

Alpine.plugin(persist)

Alpine.start()

import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.css";

window.TomSelect = TomSelect;

    new TomSelect($refs.areas, {
        plugins: ['remove_button'], // Botón para quitar seleccionadas
        placeholder: 'Selecciona Áreas...',
        persist: false,
        create: false,
    });
