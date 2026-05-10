import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';

const pages = import.meta.glob('./Pages/**/*.{jsx,tsx}');

createInertiaApp({
    resolve: async (name) => {
        const fixedName = name
            .replace('Auth/', 'auth/')
            .replace('Settings/', 'settings/')
            .replace('Teams/', 'teams/');

        const page =
            pages[`./Pages/${name}.jsx`] ||
            pages[`./Pages/${name}.tsx`] ||
            pages[`./Pages/${fixedName}.jsx`] ||
            pages[`./Pages/${fixedName}.tsx`];

        if (!page) {
            throw new Error(`Page not found: ${name}`);
        }

        return page();
    },

    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});