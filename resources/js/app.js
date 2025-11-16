import './bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.css";

window.Alpine = Alpine;
Alpine.plugin(persist);
window.TomSelect = TomSelect;

// ✅ Modo oscuro persistente
document.addEventListener('alpine:init', () => {
    Alpine.data('themeSwitcher', () => ({
        dark: Alpine.$persist(localStorage.theme === 'dark'),

        init() {
            this.updateTheme();
        },

        toggleTheme() {
            this.dark = !this.dark;
            localStorage.theme = this.dark ? 'dark' : 'light';
            this.updateTheme();
        },

        updateTheme() {
            if (this.dark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }));
});

Alpine.start();
