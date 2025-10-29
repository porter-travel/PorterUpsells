<template>
    <div class="relative z-10 mb-8 flex justify-end w-full">
        <div class="relative">
            <button
                type="button"
                @click="toggleDropdown"
                class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                     class="h-4 w-4">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Use Automation
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                     :class="['h-4 w-4 transition-transform duration-200', isDropdownOpen ? 'rotate-180' : '']">
                    <path d="m6 9 6 6 6-6"></path>
                </svg>
            </button>

            <div
                v-if="isDropdownOpen"
                @click.stop
                class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg"
            >
                <template v-for="(templateKey, index) in Object.keys(automationPresets)" :key="index">
                    <a href="#" @click.prevent="selectAutomation(templateKey)"
                       class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 transition hover:bg-slate-50">
<!--                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"-->
<!--                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"-->
<!--                             class="h-4 w-4 text-indigo-500">-->
<!--                            <path d="M15 17L12 21L9 17L5 14L9 11L12 7L15 11L19 14Z"></path>-->
<!--                        </svg>-->
                        <div>
                            <div class="font-medium text-slate-900">{{ automationPresets[templateKey].emailName }}</div>
                            <div class="text-xs text-slate-500">{{ automationPresets[templateKey].emailSubject }}</div>
                        </div>
                    </a>
                </template>

            </div>
        </div>
    </div>

    <h2 class="font-bold text-xl mb-4">Configuration</h2>

    <details name="config" class="border-y" :open="emailSettingsOpen" @toggle="handleEmailSettingsToggle">
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
                    v-model="internalMeta.email_name"
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
                    v-model="internalMeta.email_subject"
                >
            </div>
            <div>
                <label for="when_to_send" class="block mb-1 font-medium text-sm text-gray-700">When To Send</label>
                <select
                    id="when_to_send"
                    class="w-full rounded-md shadow-sm border-gray-300"
                    v-model="internalMeta.when_to_send"
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
                    v-model="internalMeta.days"
                >
            </div>
            <div>
                <label for="time" class="block mb-1 font-medium text-sm text-gray-700">Time</label>
                <input
                    type="time"
                    id="time"
                    class="w-full border-gray-300 rounded-md shadow-sm"
                    v-model="internalMeta.time"
                >
            </div>
        </div>
    </details>

    <details name="config" class="border-b" :open="testEmailOpen" @toggle="handleTestEmailToggle">
        <summary class="flex items-center justify-between mb-2 py-2 cursor-pointer">
            <h3 class="text-lg font-semibold">Test Email</h3>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 :class="['h-5 w-5 transition-transform duration-200 text-slate-500', testEmailOpen ? 'rotate-180' : '']">
                <path d="m6 9 6 6 6-6"/>
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

                <button
                    class="mt-2 px-4 py-2 border border-gray-200 rounded-md shadow-sm w-full flex items-center justify-center bg-white gap-2"
                    @click="$emit('send-test-email', email_address)"
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
</template>

<script setup>
import {ref, computed, watch, onMounted} from 'vue';

const props = defineProps({
    meta: {
        type: Object,
        default: () => ({
            email_name: '',
            email_subject: '',
            when_to_send: 'before_arrival',
            days: 1,
            time: '12:00'
        })
    },
    // New prop for conditional rendering, matching the Blade context
    propertyType: {
        type: String,
        default: 'default' // 'hotel' or 'default'
    }
});

const emit = defineEmits(['update:meta', 'send-test-email', 'automation-selected']);

const internalMeta = ref({});
const email_address = ref('');
// State for the new dropdown
const isDropdownOpen = ref(false);
const emailSettingsOpen = ref(true);
const testEmailOpen = ref(false);


// Initialize internal state from props
onMounted(() => {
    internalMeta.value = {...props.meta};
});

// Sync internal state with props and emit changes
watch(internalMeta, (newVal) => {
    emit('update:meta', newVal);
}, {deep: true});


const daysLabel = computed(() => {
    switch (internalMeta.value.when_to_send) {
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

const handleEmailSettingsToggle = (event) => {
    emailSettingsOpen.value = event.target.open;
};

const handleTestEmailToggle = (event) => {
    testEmailOpen.value = event.target.open;
};

// Function to handle the selection of a preset automation type
const selectAutomation = (type) => {
    isDropdownOpen.value = false;
    emit('automation-selected', automationPresets[type]);
    console.log(`Automation type selected: ${type}`);
};

const automationPresets = {
    preArrivalUpselEmail: {
        emailName: 'Pre-Arrival Upsell Email',
        emailSubject: 'Enhance your upcoming experience',
        emailBody: '[{"id": 1761293680674, "url": null, "type": "HeaderBlock", "level": null, "content": null, "alignment": null}, {"id": 1761293684352, "url": null, "type": "ContentBlock", "level": null, "content": "<div>Hi {{first_name}},</div><div><br></div><div>We’re looking forward to welcoming you soon! Before you arrive, why not explore a few ways to personalise your experience.</div>", "alignment": "left"}, {"id": 1761293698239, "url": null, "type": "FeaturedProductsBlock", "level": null, "content": null, "products": [null, null, null, null], "alignment": null}, {"id": 1761293700987, "url": "https://www.example.com", "type": "ButtonBlock", "level": null, "content": "Personalise Your Visit", "alignment": null}, {"id": 1761293709883, "url": null, "type": "ContentBlock", "level": null, "content": "<div>See you soon,</div><div>The {{business_name}} Team</div>", "alignment": "left"}]',
        whenToSend: 'before_arrival',
        days: 10,
        time: '12:00'
    },
    preArrivalInformationEmail: {
        emailName: 'Pre-Arrival Information Email',
        emailSubject: 'Your visit is coming up — here’s what to know',
        emailBody: '[{"id": 1761293793153, "url": null, "type": "HeaderBlock", "level": null, "content": null, "alignment": null}, {"id": 1761293796128, "url": null, "type": "ContentBlock", "level": null, "content": "<p data-start=\\"1246\\" data-end=\\"1278\\">Hi {{first_name}},<br><br></p>\\n<p data-start=\\"1280\\" data-end=\\"1401\\">Your upcoming visit to <strong data-start=\\"1303\\" data-end=\\"1324\\">{{business_name}}</strong> is almost here!<br><br data-start=\\"1340\\" data-end=\\"1343\\">\\nTo make everything smooth, here’s what you need to know:<br><br></p>\\n<p data-start=\\"1403\\" data-end=\\"1465\\">📍 <strong data-start=\\"1406\\" data-end=\\"1436\\">Location &amp; Arrival Details</strong><br data-start=\\"1436\\" data-end=\\"1439\\">\\n{{arrival_instructions}}<br><br></p>\\n<p data-start=\\"1467\\" data-end=\\"1519\\">🕒 <strong data-start=\\"1470\\" data-end=\\"1499\\">Opening or Check-In Times</strong><br data-start=\\"1499\\" data-end=\\"1502\\">\\n{{timing_info}}<br><br></p>\\n<p data-start=\\"1521\\" data-end=\\"1542\\">📱 <strong data-start=\\"1524\\" data-end=\\"1540\\">Useful Links</strong></p>\\n<ul data-start=\\"1543\\" data-end=\\"1618\\">\\n<li data-start=\\"1543\\" data-end=\\"1580\\">\\n<p data-start=\\"1545\\" data-end=\\"1580\\">{{link_1_label}} → {{link_1_url}}</p>\\n</li>\\n<li data-start=\\"1581\\" data-end=\\"1618\\">\\n<p data-start=\\"1583\\" data-end=\\"1618\\">{{link_2_label}} → {{link_2_url}}<br><br></p>\\n</li>\\n</ul>\\n<p data-start=\\"1620\\" data-end=\\"1716\\">If you’d like to add extras or special touches before you arrive, you can still do that below.</p>", "alignment": "left"}, {"id": 1761293829625, "url": "https://www.example.com", "type": "ButtonBlock", "level": null, "content": "View available add-ons", "alignment": null}, {"id": 1761293842021, "url": null, "type": "ContentBlock", "level": null, "content": "See you soon,<br data-start=\\"1762\\" data-end=\\"1765\\">\\n<strong data-start=\\"1765\\" data-end=\\"1795\\">The {{business_name}} Team</strong>", "alignment": "left"}]',
        whenToSend: 'before_arrival',
        days: 5,
        time: '12:00'
    },
    inStayUpsellEmail: {
        emailName: 'In-Stay Upsell Email',
        emailSubject: 'Make the most of your time with us',
        emailBody: '[{"id": 1761293907774, "url": null, "type": "HeaderBlock", "level": null, "content": null, "alignment": null}, {"id": 1761293909880, "url": null, "type": "ContentBlock", "level": null, "content": "<p data-start=\\"1997\\" data-end=\\"2029\\">Hi {{first_name}},<br><br></p>\\n<p data-start=\\"2031\\" data-end=\\"2188\\">We hope you’re enjoying your time with <strong data-start=\\"2070\\" data-end=\\"2091\\">{{business_name}}</strong>!<br><br data-start=\\"2092\\" data-end=\\"2095\\">\\nIf you’d like to enhance your experience, we’ve got some great options available right now:<br><br></p>\\n<p data-start=\\"2190\\" data-end=\\"2281\\">✨ Add-ons, experiences, and limited-time offers — all available directly from your phone.<br><br></p>\\n<p data-start=\\"2283\\" data-end=\\"2350\\">Tap below to see what’s on offer and we’ll take care of the rest.</p>", "alignment": "left"}, {"id": 1761293923997, "url": "https://www.example.com", "type": "ButtonBlock", "level": null, "content": "Explore extras", "alignment": null}, {"id": 1761293936230, "url": null, "type": "ContentBlock", "level": null, "content": "Thanks for being with us,<br data-start=\\"2400\\" data-end=\\"2403\\">\\n<strong data-start=\\"2403\\" data-end=\\"2433\\">The {{business_name}} Team</strong>", "alignment": "left"}]',
        whenToSend: 'after_arrival',
        days: 1,
        time: '12:00'
    },
    postStayEmail: {
        emailName: 'Post-Stay Review + Discount Email',
        emailSubject: 'Thanks for visiting — here’s a little thank-you',
        emailBody: '[{"id": 1761293981745, "url": null, "type": "HeaderBlock", "level": null, "content": null, "alignment": null}, {"id": 1761293984299, "url": null, "type": "ContentBlock", "level": null, "content": "<p data-start=\\"2651\\" data-end=\\"2683\\">Hi {{first_name}},<br><br></p>\\n<p data-start=\\"2685\\" data-end=\\"2871\\">Thanks for spending time with <strong data-start=\\"2715\\" data-end=\\"2736\\">{{business_name}}</strong> — we hope you had a great experience!<br><br data-start=\\"2774\\" data-end=\\"2777\\">\\nWe’d love to hear your thoughts. Your feedback helps us improve and means a lot to our team.<br><br></p>\\n<p data-start=\\"2873\\" data-end=\\"2922\\">💬 Leave a quick review → [Leave a review link]<br><br></p>\\n<p data-start=\\"2924\\" data-end=\\"3040\\">As a small thank-you, here’s a <strong data-start=\\"2955\\" data-end=\\"2979\\">{{discount_amount}}%</strong> discount code for your next visit:&nbsp;<strong data-start=\\"3017\\" data-end=\\"3038\\">{{discount_code}}<br><br></strong></p>\\n<p data-start=\\"3042\\" data-end=\\"3105\\">We hope to see you again soon,<br data-start=\\"3072\\" data-end=\\"3075\\">\\n<strong data-start=\\"3075\\" data-end=\\"3105\\">The {{business_name}} Team</strong></p>", "alignment": "left"}]',
        whenToSend: 'after_checkout',
        days: 2,
        time: '12:00'
    }
}


</script>
