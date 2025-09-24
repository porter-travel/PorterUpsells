<template>
    <div class="grid grid-cols-1 gap-4 mb-8">
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
        <div class="grid grid-cols-1 gap-6">
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
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

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

const emit = defineEmits(['update:meta']);

const internalMeta = ref({});

// Initialize internal state from props
onMounted(() => {
    internalMeta.value = { ...props.meta };
});

// Sync internal state with props and emit changes
watch(internalMeta, (newVal) => {
    emit('update:meta', newVal);
}, { deep: true });


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
