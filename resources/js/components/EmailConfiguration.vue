<template>

    <h2 class="font-bold text-xl mb-4">Configuration</h2>

    <details name="config" class="border-y" open>
        <summary class="flex items-center justify-between mb-2 py-2 cursor-pointer">
            <h3 class="text-lg font-semibold">Email Settings</h3>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 :class="['h-5 w-5 transition-transform duration-200 text-slate-500', emailSettingsOpen ? 'rotate-180' : '']">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </summary>
        <div class="grid grid-cols-1 gap-4 mb-8 p-2 bg-grey/20">
            <div class="w-full">
                <label for="email_name" class="block mb-1 font-medium text-sm text-gray-700">Email
                    Name<sup>*</sup></label>
                <input
                    required
                    type="text"
                    id="email_name"
                    class="w-full border-gray-300 rounded-md shadow-sm"
                    v-model="meta.email_name"
                >
            </div>
            <div>
                <label for="email_subject" class="block mb-1 font-medium text-sm text-gray-700">Email
                    Subject<sup>*</sup></label>
                <input
                    required
                    type="text"
                    id="email_subject"
                    class="w-full border-gray-300 rounded-md shadow-sm"
                    v-model="meta.email_subject"
                >
            </div>
            <div>
                <label for="when_to_send" class="block mb-1 font-medium text-sm text-gray-700">When To Send</label>
                <select
                    id="when_to_send"
                    class="w-full rounded-md shadow-sm border-gray-300"
                    v-model="meta.when_to_send"
                >
                    <option value="before_arrival">Before Arrival</option>
                    <option value="after_arrival">After Arrival</option>
                    <option value="before_checkout">Before Check-out</option>
                    <option value="after_checkout">After Check-out</option>
                </select>
            </div>
            <div>
                <label for="days_count" class="block mb-1 font-medium text-sm text-gray-700">{{ daysLabel }}</label>
                <input
                    type="number"
                    id="days_count"
                    class="w-full border-gray-300 rounded-md shadow-sm"
                    v-model="meta.days"
                >
            </div>
            <div>
                <label for="time" class="block mb-1 font-medium text-sm text-gray-700">Time</label>
                <input
                    type="time"
                    id="time"
                    class="w-full border-gray-300 rounded-md shadow-sm"
                    v-model="meta.time"
                >
            </div>
        </div>
    </details>

    <details name="config" class="border-b ">
        <summary class="flex items-center justify-between mb-2 py-2 cursor-pointer">
            <h3 class="text-lg font-semibold">Test Email</h3>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-chevron-up-icon lucide-chevron-up">
                <path d="m18 15-6-6-6 6"/>
            </svg>
        </summary>

        <div class="grid grid-cols-1 gap-4 mb-8 p-2 bg-grey/20">
            <div class="w-full">
                <label for="email_address" class="block mb-1 font-medium text-sm text-gray-700">Email Address</label>
                <input
                    type="email"
                    id="email_address"
                    class="w-full border-gray-300 rounded-md shadow-sm"
                    v-model="email_address"
                >

                <label for="use_test_booking" class="block mt-4 mb-1 font-medium text-sm text-gray-700">
                    <input type="checkbox" id="use_test_booking" class="mr-2 align-middle"
                           v-model="use_test_booking">Use Test Booking</label>


                <button
                    class="mt-2 px-4 py-2 border border-gray-200 rounded-md shadow-sm w-full flex items-center justify-center bg-white gap-2"
                    @click="$emit('send-test-email', email_address, use_test_booking)"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-mail-question-mark-icon lucide-mail-question-mark">
                        <path d="M22 10.5V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h12.5"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        <path d="M18 15.28c.2-.4.5-.8.9-1a2.1 2.1 0 0 1 2.6.4c.3.4.5.8.5 1.3 0 1.3-2 2-2 2"/>
                        <path d="M20 22v.01"/>
                    </svg>
                    Send Test Email
                </button>
            </div>

        </div>
    </details>

    <details name="config">
        <summary class="flex items-center justify-between mb-2 py-2 cursor-pointer">
            <h3 class="text-lg font-semibold">Merge Tags</h3>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-chevron-up-icon lucide-chevron-up">
                <path d="m18 15-6-6-6 6"/>
            </svg>
        </summary>
        <div class="grid grid-cols-1 gap-2 mb-8 p-2 bg-gray-50 rounded">
            <div class="w-full">
            <span v-for="(tag, index) in mergeTags" :key="index"
                  @click="copyTag(tag)"
                  class="inline-block px-2 py-1 m-1 text-sm font-mono bg-indigo-100 text-indigo-700 rounded cursor-pointer hover:bg-indigo-200 transition"
                  :title="'Click to copy ' + tag">
                {{ tag }}
            </span>
            </div>
        </div>

        <div v-if="showCopySuccess" style="z-index: 999"
             class="fixed bottom-4 right-4 bg-green-500 text-white p-3 rounded shadow-lg transition-opacity duration-300 z-50 w-[250px]">
            <strong>Merge Tag copied to clipboard</strong>, paste it where you would like to use it in the template
        </div>
    </details>
</template>

<script setup>
import {ref, computed} from 'vue';

// 1. Use defineModel to declare the two-way binding for 'meta'
const meta = defineModel('meta', {
    type: Object,
    default: () => ({
        email_name: '',
        email_subject: '',
        when_to_send: 'before_arrival',
        days: 1,
        time: '12:00'
    })
});

// Since defineModel takes care of the two-way binding, we only need to declare emits
// for custom events (not update:meta).
const emit = defineEmits(['send-test-email', 'template-selected', 'selectTemplate']);

// Other local state
const email_address = ref('');
const use_test_booking = ref(false);
const isDropdownOpen = ref(false); // Not used in template, but kept for completeness
const showCopySuccess = ref(false);

const mergeTags = ref([
    '[[first_name]]',
    '[[last_name]]',
    '[[business_name]]',
    '[[arrival_date]]',
    '[[departure_date]]',
    '[[days_until_arrival]]',
]);

// The daysLabel computed property now uses the reactive 'meta' ref directly
const daysLabel = computed(() => {
    switch (meta.value.when_to_send) {
        case 'before_arrival':
            return 'Days Before Arrival';
        case 'after_arrival':
            return 'Days After Arrival';
        case 'before_checkout':
            return 'Days Before Check-out';
        case 'after_checkout':
            return 'Days After Check-out';
        default:
            return 'Days';
    }
});

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const copyTag = async (tag) => {
    await console.log('Copying tag:', tag);
    try {
        // This is the correct use of the modern Clipboard API
        await navigator.clipboard.writeText(tag);

        // Only run on success
        showCopySuccess.value = true;
        setTimeout(() => {
            showCopySuccess.value = false;
        }, 3000);

    } catch (err) {
        // If the click doesn't work, this error will be logged to the console.
        console.error('Failed to copy to clipboard. Details:', err);

        // FALLBACK: Use the deprecated document.execCommand('copy') API
        // This is necessary for environments where navigator.clipboard is blocked (e.g., some iframes)
        // Note: This is less reliable and should only be used as a fallback.

        const tempTextArea = document.createElement('textarea');
        tempTextArea.value = tag;
        document.body.appendChild(tempTextArea);
        tempTextArea.select();

        try {
            document.execCommand('copy');
            // Show success message even on fallback success
            showCopySuccess.value = true;
            setTimeout(() => {
                showCopySuccess.value = false;
            }, 3000);
        } catch (execErr) {
            console.error('Fallback clipboard copy failed:', execErr);
            alert(`Copy failed. Please copy the tag manually: ${tag}`);
        }

        document.body.removeChild(tempTextArea);
    }
};

// Function to handle the selection of a template type
const selectTemplate = (type) => {
    isDropdownOpen.value = false;
    emit('template-selected', emailTemplates[type]);
    // You would typically navigate or trigger a component change here
    console.log(`Template type selected: ${type}`);
};




</script>

<style scoped>
/* Ensure chevron icon rotates correctly for the details/summary elements */
details[open] summary svg {
    transform: rotate(180deg);
}

/* Add transition for the dropdown button's chevron rotation */
.transition.duration-200 {
    transition-property: transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}
</style>
