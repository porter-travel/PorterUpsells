import React from 'react';
import { createRoot } from 'react-dom/client';
import CalendarPage from './components/CalendarPage';

const rootElement = document.getElementById('calendar-page-root');

if (rootElement) {
    const dataElement = document.getElementById('calendar-page-data');
    let initialData = { events: [], products: [], defaultDate: new Date().toISOString() };

    if (dataElement && dataElement.textContent) {
        try {
            initialData = JSON.parse(dataElement.textContent);
        } catch (error) {
            console.error('Failed to parse calendar data payload', error);
        }
    }

    const root = createRoot(rootElement);
    root.render(<CalendarPage {...initialData} />);
}
