<template>
    <div
        class="flex flex-col items-center justify-center border-2 border-dashed rounded-lg p-6 min-h-[200px] transition-colors duration-200"
        :class="[isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-blue-300']"
        @dragover.prevent="handleDragOver"
        @dragleave.prevent="handleDragLeave"
        @drop.prevent="handleDrop"
    >
        <div v-if="!content" class="text-center text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-4 4 4 4-4v1z" clip-rule="evenodd" />
            </svg>
            <p class="mt-1 text-sm font-medium">Drag and drop an image here</p>
            <p class="mt-1 text-xs text-gray-400">or</p>
            <input type="file" ref="fileInput" @change="handleFileSelect" class="hidden" accept="image/*" />
            <div class="mt-2 flex space-x-2">
                <button @click="openFilePicker" class="text-sm text-blue-500 hover:text-blue-600 font-semibold">
                    Browse Files
                </button>
                <button @click="showLibraryModal = true" class="text-sm text-blue-500 hover:text-blue-600 font-semibold">
                    Media Library
                </button>
            </div>
        </div>
        <div v-else class="relative w-full">
            <img :src="content.large || content.original" :alt="content.filename" class="w-full rounded-lg" />
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-200 cursor-pointer" @click="removeImage">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
        </div>
        <div v-if="uploading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 z-10">
            <p>Uploading...</p>
        </div>
        <p v-if="error" class="text-red-500 text-sm mt-2">{{ error }}</p>
    </div>

    <MediaLibraryModal v-if="showLibraryModal" @close="showLibraryModal = false" @select-image="updateContentFromLibrary" />
</template>

<script setup>
import { defineProps, defineEmits, ref, watch } from 'vue';
import axios from 'axios';
import MediaLibraryModal from '../MediaLibraryModal.vue';

const props = defineProps({
    content: {
        type: [String, Object], // Can be a string (upload) or object (library)
        default: null,
    },
});

const emit = defineEmits(['update:content']);

const isDragging = ref(false);
const uploading = ref(false);
const fileInput = ref(null);
const error = ref(null);
const showLibraryModal = ref(false);

const handleDragOver = () => {
    isDragging.value = true;
    error.value = null;
};

const handleDragLeave = () => {
    isDragging.value = false;
};

const handleDrop = (event) => {
    isDragging.value = false;
    error.value = null;
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        uploadFile(files[0]);
    }
};

const handleFileSelect = (event) => {
    error.value = null;
    const files = event.target.files;
    if (files.length > 0) {
        uploadFile(files[0]);
    }
};

const openFilePicker = () => {
    fileInput.value.click();
};

const uploadFile = async (file) => {
    if (!file.type.startsWith('image/')) {
        error.value = 'Only image files are supported.';
        return;
    }

    uploading.value = true;
    const formData = new FormData();
    formData.append('media_file', file);

    try {
        const response = await axios.post('/media/upload/ajax', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        emit('update:content', response.data.urls);
    } catch (err) {
        console.error('Upload failed:', err);
        error.value = 'Upload failed. Please try again.';
    } finally {
        uploading.value = false;
    }
};

const removeImage = () => {
    emit('update:content', null);
};

const updateContentFromLibrary = (mediaObject) => {
    console.log('Selected from library:', mediaObject);
    // Media Library provides the full object with urls
    emit('update:content', mediaObject.urls);
};

</script>
