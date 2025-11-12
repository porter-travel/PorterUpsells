import { reactive } from 'vue';

export const sharedStore = reactive({
    emailBlocks: [],
    emailConfigData: {}, // Add any other shared data here
});
