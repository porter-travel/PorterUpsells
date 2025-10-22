<template>

    <details name="config" class="border-y"  open>
        <summary class="flex items-center justify-between mb-2 py-2 cursor-pointer">
            <h3 class="text-lg font-semibold">Email Settings</h3>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-up-icon lucide-chevron-up"><path d="m18 15-6-6-6 6"/></svg>
        </summary>
        <div class="grid grid-cols-1 gap-4 mb-8 p-2 bg-grey/20">
            <div class="w-full">
                <label for="email_name" class="block mb-1 font-medium text-sm text-gray-700">Email Name</label>
                <input
                    type="text"
                    id="email_name"
                    class="w-full border-gray-300 rounded-md shadow-sm"
                    v-model="internalMeta.email_name"
                >
            </div>
            <div>
                <label for="email_subject" class="block mb-1 font-medium text-sm text-gray-700">Email Subject</label>
                <input
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

    <details name="config" class="border-b ">
        <summary class="flex items-center justify-between mb-2 py-2 cursor-pointer">
            <h3 class="text-lg font-semibold">Test Email</h3>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-up-icon lucide-chevron-up"><path d="m18 15-6-6-6 6"/></svg>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-question-mark-icon lucide-mail-question-mark"><path d="M22 10.5V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h12.5"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/><path d="M18 15.28c.2-.4.5-.8.9-1a2.1 2.1 0 0 1 2.6.4c.3.4.5.8.5 1.3 0 1.3-2 2-2 2"/><path d="M20 22v.01"/></svg>
                    Send Test Email</button>
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
    }
});

const emit = defineEmits(['update:meta', 'send-test-email']);

const internalMeta = ref({});

const email_address = ref('');

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
</script>

<style scoped>

details[open] summary svg{
    transform: rotate(180deg);
}
</style>
