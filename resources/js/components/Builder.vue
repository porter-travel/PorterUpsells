<template>
    <div class="flex h-screen">
        <div class="flex-1 p-4">
            <h2 class="font-bold text-xl mb-4">Email Preview</h2>
            <div class="border p-4 min-h-[400px] bg-gray-50 rounded">
                <div class="flex justify-center mb-2">
                    <button @click="openModal(0)" class="text-gray-500 hover:text-black">
                        ➕ Add Block
                    </button>
                </div>

                <draggable v-model="blocks" item-key="id" handle=".grab-handle">
                    <template #item="{ element: block, index }">
                        <div class="relative mb-4 p-4 border rounded bg-white shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="grab-handle cursor-grab mr-2 text-gray-400 hover:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 12a2 2 0 100-4 2 2 0 000 4zM2 10a2 2 0 114 0 2 2 0 01-4 0zm8-8a2 2 0 100 4 2 2 0 000-4zm8 8a2 2 0 11-4 0 2 2 0 014 0zm-8 8a2 2 0 100 4 2 2 0 000-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <component
                                        :is="componentsMap[block.type]"
                                        v-model:content="block.content"
                                        v-model:level="block.level"
                                        v-model:alignment="block.alignment"
                                        v-model:url="block.url"
                                        v-model:blocks="block.blocks" />
                                </div>
                                <button @click="removeBlock(index)" class="text-red-500 hover:text-red-700 ml-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex justify-center mt-2">
                                <button @click="openModal(index + 1)" class="text-gray-500 hover:text-black">
                                    ➕ Add Block
                                </button>
                            </div>
                        </div>
                    </template>
                </draggable>

                <div class="flex justify-center mt-4">
                    <button @click="saveContent" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        💾 Save Content
                    </button>
                </div>
            </div>
        </div>

        <div class="w-1/4 border-l p-4">
            <h2 class="font-bold text-xl mb-4">Configuration</h2>

            <div v-if="type === 'email'">
                <h3 class="text-lg font-semibold mb-2">Email Settings</h3>
                <EmailConfiguration v-model:meta="emailMeta" />
            </div>

            <div v-else-if="type === 'webpage'">
                <h3 class="text-lg font-semibold mb-2">Web Page Settings</h3>
                <label class="block mb-2">
                    <span class="text-gray-700">Page Title:</span>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                </label>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded shadow-lg p-6 w-80">
                <h3 class="font-bold mb-4">Choose a block</h3>
                <button class="block w-full text-left px-3 py-2 hover:bg-gray-100" @click="addBlock('TitleBlock')">Title</button>
                <button class="block w-full text-left px-3 py-2 hover:bg-gray-100" @click="addBlock('ContentBlock')">Content</button>
                <button class="block w-full text-left px-3 py-2 hover:bg-gray-100" @click="addBlock('ImageBlock')">Image</button>
                <button class="block w-full text-left px-3 py-2 hover:bg-gray-100" @click="addBlock('ButtonBlock')">Button</button>
                <button class="block w-full text-left px-3 py-2 hover:bg-gray-100" @click="addBlock('ContentBreak')">Content Break</button>
                <button class="block w-full text-left px-3 py-2 hover:bg-gray-100" @click="addBlock('ColumnsBlock')">Columns</button>
                <button class="block w-full text-left px-3 py-2 hover:bg-gray-100" @click="addBlock('FeaturedProductsBlock')">Featured Products</button>

                <button class="mt-4 w-full bg-gray-200 px-3 py-2 rounded" @click="closeModal">Cancel</button>
            </div>
        </div>
    </div>
</template>

<script setup>

import {defineProps, onMounted} from 'vue';


import { ref } from 'vue';
import draggable from 'vuedraggable';

import TitleBlock from './blocks/TitleBlock.vue';
import ContentBlock from './blocks/ContentBlock.vue';
import ImageBlock from './blocks/ImageBlock.vue';
import ButtonBlock from './blocks/ButtonBlock.vue';
import ContentBreak from './blocks/ContentBreak.vue';
import ColumnsBlock from './blocks/ColumnsBlock.vue';
import FeaturedProductsBlock from './blocks/FeaturedProductsBlock.vue';
import EmailConfiguration from "./EmailConfiguration.vue";

const props = defineProps({
    type: {
        type: String,
        required: true,
        validator: (value) => ['email', 'webpage'].includes(value),
    },
    hotelId: {
        type: Number,
        required: false,
    },
    templateId: {
        type: Number,
        required: false,
    }
});

const blocks = ref([]);
const templateId = ref(props.templateId || null);

onMounted(() => {
    console.log(props);
    if (templateId.value) {
        // Fetch existing template data
        axios.get(`/admin/hotel/${props.hotelId}/email_builder/${templateId.value}/template_data`)
            .then(response => {
                if (response.data && response.data.template.body) {
                    try {
                        blocks.value = JSON.parse(response.data.template.body);
                        console.log('blocks', blocks);
                    } catch (e) {
                        console.error('Failed to parse template body:', e);
                    }
                }
                if (response.data) {
                    console.log(response.data);
                    emailMeta.value.email_name = response.data.template.name || '';
                    emailMeta.value.email_subject = response.data.template.subject || '';
                    emailMeta.value.when_to_send = response.data.template.when_to_send || 'before_arrival';
                    emailMeta.value.days = response.data.template.days || 1;
                    emailMeta.value.time = response.data.template.time || '12:00';
                }
            })
            .catch(error => {
                console.error('Error fetching template data:', error);
            });
    }
})
const componentsMap = {
    TitleBlock,
    ContentBlock,
    ImageBlock,
    ButtonBlock,
    ContentBreak,
    ColumnsBlock,
    FeaturedProductsBlock, // 👈 Add this line
};

// Define the reactive state for email settings
const emailMeta = ref({
    email_name: '',
    email_subject: '',
    when_to_send: 'before_arrival',
    days: 1,
    time: '12:00'
});

const showModal = ref(false);
const insertIndex = ref(null);

const openModal = (index) => {
    insertIndex.value = index;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    insertIndex.value = null;
};

const removeBlock = (index) => {
    blocks.value.splice(index, 1);
};

const addBlock = (type) => {
    const newBlock = {
        id: Date.now(),
        type,
        content: null,
        level: null,
        alignment: null,
        url: null,
    };

    switch (type) {
        case 'TitleBlock':
            newBlock.content = 'Untitled';
            newBlock.level = 'h1';
            break;
        case 'ContentBlock':
            newBlock.content = 'Some content goes here...';
            newBlock.alignment = 'left';
            break;
        case 'ImageBlock':
            break;
        case 'ButtonBlock':
            newBlock.content = 'Click Here';
            newBlock.url = 'https://www.example.com';
            break;
        case 'ColumnsBlock': // 👈 Add this case
            newBlock.blocks = { column1: [], column2: [] }; // The initial nested blocks
            break;
        case 'FeaturedProductsBlock':
            newBlock.products = [null, null, null, null]; // Initialise with 4 empty slots
            break;
    }

    blocks.value.splice(insertIndex.value, 0, newBlock);
    closeModal();
};

const saveContent = () => {

    axios.post(`/admin/hotel/${props.hotelId}/email_builder/store`, {
        name: emailMeta.value.email_name,
        subject: emailMeta.value.email_subject,
        when_to_send: emailMeta.value.when_to_send,
        days: emailMeta.value.days,
        time: emailMeta.value.time,
        body: JSON.stringify(blocks.value, null, 2),
        type: 'email',
        is_active: true,
        template_id: templateId.value,
    }).then(response => {
        if (response.data.success) {
            alert(response.data.success);
            if (!templateId.value && response.data.template_id) {
                templateId.value = response.data.template_id;
            }
        } else {
            alert('Failed to save content.');
        }
    }).catch(error => {
        console.error('Error saving content:', error);
        alert('An error occurred while saving content.');
    })
};
</script>
