// builder.js

import { createApp } from 'vue';
import PageBuilder from './components/Builder.vue';

// Determine the builder type (e.g., from a data attribute or global variable)
const builderElement = document.getElementById('builder');
const builderType = builderElement.dataset.type; // Assumes a data-type attribute on the #builder div

// Create and mount the app, passing the type as a prop
createApp(PageBuilder, { type: builderType }).mount('#builder');
