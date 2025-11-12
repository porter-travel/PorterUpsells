<template>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-xl max-h-[80vh] flex flex-col" style="width: 900px;">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="font-bold text-xl">Media Library</h3>
                <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-4 flex-1 overflow-y-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div v-for="item in mediaItems" :key="item.id" @click="selectAndInsertImage(item)" class="relative cursor-pointer group rounded-lg overflow-hidden border-2" :class="{ 'border-blue-500': selectedMedia && selectedMedia.id === item.id, 'border-transparent': !selectedMedia || selectedMedia.id !== item.id }">
                    <img :src="item.urls.thumbnail" :alt="item.filename" class="w-full h-40 object-cover" />
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t flex justify-between items-center">
                <button @click="previousPage" :disabled="!pagination.prev_page_url" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 disabled:opacity-50">Previous</button>
                <span>Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
                <button @click="nextPage" :disabled="!pagination.next_page_url" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 disabled:opacity-50">Next</button>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const emit = defineEmits(['close', 'select-image']);

const mediaItems = ref([]);
const pagination = ref({});
const selectedMedia = ref(null);

const fetchMedia = async (url) => {
    try {
        const response = await axios.post(url);
        mediaItems.value = response.data.media.data;
        pagination.value = {
            current_page: response.data.media.current_page,
            last_page: response.data.media.last_page,
            prev_page_url: response.data.media.prev_page_url,
            next_page_url: response.data.media.next_page_url,
        };
    } catch (error) {
        console.error('Failed to fetch media:', error);
    }
};

const selectAndInsertImage = (item) => {
    // Emit the full item object
    emit('select-image', item);
    // Close the modal immediately
    emit('close');
};
const previousPage = () => {
    if (pagination.value.prev_page_url) {
        fetchMedia(pagination.value.prev_page_url);
    }
};

const nextPage = () => {
    if (pagination.value.next_page_url) {
        fetchMedia(pagination.value.next_page_url);
    }
};

onMounted(() => {
    fetchMedia('/media/list/ajax');
});
</script>
