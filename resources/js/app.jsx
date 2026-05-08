import '../css/app.css';
import './bootstrap';

import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';

const appName = import.meta.env.VITE_APP_NAME || 'RentHub';

const pages = import.meta.glob('./Pages/**/*.{jsx,tsx}');

createInertiaApp({
    title: (title) => `${title} - ${appName}`,

    resolve: async (name) => {
        const normalizedName = name.replace('Auth/', 'auth/');

        const page =
            pages[`./Pages/${name}.jsx`] ||
            pages[`./Pages/${name}.tsx`] ||
            pages[`./Pages/${normalizedName}.jsx`] ||
            pages[`./Pages/${normalizedName}.tsx`];

        if (!page) {
            console.log('Available pages:', Object.keys(pages));
            throw new Error(`Page not found: ${name}`);
        }

        return page();
    },

    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },

    progress: {
        color: '#4B5563',
    },
});