// builder.js

import { createApp } from 'vue';
import PageBuilder from './components/Builder.vue';
alert("builder.js loaded");
// Determine the builder type (e.g., from a data attribute or global variable)
const builderElement = document.getElementById('builder');
const builderType = builderElement.dataset.type; // Assumes a data-type attribute on the #builder div
const hotelId = builderElement.dataset.hotelId; // Assumes a data-hotel-id attribute on the #builder div
const templateId = builderElement.dataset.templateId; // Assumes a data-hotel-id attribute on the #builder div
const templateData = builderElement.dataset.template; // Assumes a data-template attribute on the #builder div
const hotelName = builderElement.dataset.hotelName; // Assumes a data-hotel-name attribute on the #builder div
const hotelLogo = builderElement.dataset.hotelLogo; // Assumes a data-hotel-logo attribute on the #builder div
const hotelFeaturedImage = builderElement.dataset.hotelFeaturedImage; // Assumes a data-hotel-featured-image attribute on the #builder div

// Create and mount the app, passing the type as a prop
createApp(PageBuilder, { type: builderType,
    hotelId: hotelId,
    templateId: templateId,
    templateData: templateData,
    hotelName: hotelName,
    hotelLogo: hotelLogo,
    hotelFeaturedImage: hotelFeaturedImage
}).mount('#builder');
