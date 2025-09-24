<template>
    <div class="relative flex justify-center py-4">
        <a
            href="#"
            :target="isEditing ? '_self' : '_blank'"
            class="inline-block px-6 py-3 rounded-lg font-semibold text-white bg-black transition-colors duration-200"
            :class="isEditing ? 'bg-blue-600' : 'bg-blue-500 hover:bg-blue-600'"
            @click.prevent.stop="handleButtonClick"
        >
            {{ content }}
        </a>

        <div
            v-if="isEditing"
            class="absolute top-0 flex flex-col p-2 bg-white shadow-lg border rounded-lg z-10 space-y-2"
            @click.stop
        >
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">Text:</span>
                <input
                    type="text"
                    v-model="internalContent"
                    @input="updateContent"
                    class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">URL:</span>
                <input
                    type="text"
                    v-model="internalUrl"
                    @input="updateUrl"
                    class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>
            <button
                @click="isEditing = false"
                class="mt-2 w-full bg-gray-200 px-3 py-1 text-sm rounded hover:bg-gray-300"
            >
                Done
            </button>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, ref, watch } from 'vue';

const props = defineProps({
    content: {
        type: String,
        default: 'Click Here',
    },
    url: {
        type: String,
        default: 'https://www.example.com',
    },
});

const emit = defineEmits(['update:content', 'update:url']);

const isEditing = ref(true);
const internalContent = ref(props.content);
const internalUrl = ref(props.url);

watch(() => props.content, (newVal) => {
    if (newVal !== internalContent.value) {
        internalContent.value = newVal;
    }
});

watch(() => props.url, (newVal) => {
    if (newVal !== internalUrl.value) {
        internalUrl.value = newVal;
    }
});

const updateContent = () => {
    emit('update:content', internalContent.value);
};

const updateUrl = () => {
    emit('update:url', internalUrl.value);
};

const handleButtonClick = () => {
        isEditing.value = !isEditing.value;

};
</script>
