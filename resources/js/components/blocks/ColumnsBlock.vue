<template>
    <div class="flex space-x-4">
        <div class="flex-1 border p-2 rounded bg-gray-50 min-h-[150px]">
            <h4 class="font-bold text-sm mb-2 text-gray-700">Column 1</h4>
            <draggable v-model="internalBlocks.column1" item-key="id" handle=".grab-handle">
                <template #item="{ element: block, index }">
                    <div class="relative mb-4 p-4 border rounded bg-white shadow-sm">
                        <div class="flex items-center mb-2">
                            <div class="grab-handle cursor-grab mr-2 text-gray-400 hover:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M10 12a2 2 0 100-4 2 2 0 000 4zM2 10a2 2 0 114 0 2 2 0 01-4 0zm8-8a2 2 0 100 4 2 2 0 000-4zm8 8a2 2 0 11-4 0 2 2 0 014 0zm-8 8a2 2 0 100 4 2 2 0 000-4z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <component
                                    :is="componentsMap[block.type]"
                                    v-model:content="block.content"
                                    v-model:level="block.level"
                                    v-model:alignment="block.alignment"
                                    v-model:url="block.url"
                                />
                            </div>
                            <button @click="removeBlock(1, index)" class="text-red-500 hover:text-red-700 ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>

                        </div>
                        <div class="flex justify-center mt-2">
                            <button @click="openModal(1, index + 1)" class="text-gray-500 hover:text-black text-xs">
                                ➕ Add Block
                            </button>
                        </div>
                    </div>
                </template>
            </draggable>
            <div class="flex justify-center mt-2">
                <button @click="openModal(1, 0)" class="text-gray-500 hover:text-black text-xs">
                    ➕ Add Block
                </button>
            </div>
        </div>

        <div class="flex-1 border p-2 rounded bg-gray-50 min-h-[150px]">
            <h4 class="font-bold text-sm mb-2 text-gray-700">Column 2</h4>
            <draggable v-model="internalBlocks.column2" item-key="id" handle=".grab-handle">
                <template #item="{ element: block, index }">
                    <div class="relative mb-4 p-4 border rounded bg-white shadow-sm">
                        <div class="flex items-center mb-2">
                            <div class="grab-handle cursor-grab mr-2 text-gray-400 hover:text-gray-600">
                                <svg xmlns="http://www.w3.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M10 12a2 2 0 100-4 2 2 0 000 4zM2 10a2 2 0 114 0 2 2 0 01-4 0zm8-8a2 2 0 100 4 2 2 0 000-4zm8 8a2 2 0 11-4 0 2 2 0 014 0zm-8 8a2 2 0 100 4 2 2 0 000-4z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <component
                                    :is="componentsMap[block.type]"
                                    v-model:content="block.content"
                                    v-model:level="block.level"
                                    v-model:alignment="block.alignment"
                                    v-model:url="block.url"
                                />
                            </div>
                            <button @click="removeBlock(2, index)" class="text-red-500 hover:text-red-700 ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        <div class="flex justify-center mt-2">
                            <button @click="openModal(2, index + 1)" class="text-gray-500 hover:text-black text-xs">
                                ➕ Add Block
                            </button>
                        </div>
                    </div>
                </template>
            </draggable>
            <div class="flex justify-center mt-2">
                <button @click="openModal(2, 0)" class="text-gray-500 hover:text-black text-xs">
                    ➕ Add Block
                </button>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded shadow-lg p-6 w-80">
                <h3 class="font-bold mb-4">Choose a block</h3>
                <button class="block w-full text-left px-3 py-2 hover:bg-gray-100" @click="addBlock('TitleBlock')">
                    Title
                </button>
                <button class="block w-full text-left px-3 py-2 hover:bg-gray-100" @click="addBlock('ContentBlock')">
                    Content
                </button>
                <button class="block w-full text-left px-3 py-2 hover:bg-gray-100" @click="addBlock('ImageBlock')">
                    Image
                </button>
                <button class="block w-full text-left px-3 py-2 hover:bg-gray-100" @click="addBlock('ButtonBlock')">
                    Button
                </button>
                <button class="block w-full text-left px-3 py-2 hover:bg-gray-100" @click="addBlock('ContentBreak')">
                    Content Break
                </button>
                <button class="mt-4 w-full bg-gray-200 px-3 py-2 rounded" @click="closeModal">Cancel</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {defineProps, defineEmits, ref, watch} from 'vue';
import draggable from 'vuedraggable';

import TitleBlock from './TitleBlock.vue';
import ContentBlock from './ContentBlock.vue';
import ImageBlock from './ImageBlock.vue';
import ButtonBlock from './ButtonBlock.vue';
import ContentBreak from './ContentBreak.vue';

const props = defineProps({
    blocks: {
        type: Object,
        default: () => ({column1: [], column2: []}),
    },
});

const emit = defineEmits(['update:blocks']);

const componentsMap = {
    TitleBlock,
    ContentBlock,
    ImageBlock,
    ButtonBlock,
    ContentBreak,
};

const showModal = ref(false);
const insertColumn = ref(null);
const insertIndex = ref(null);

const internalBlocks = ref(props.blocks);

// Watch for changes from the parent and update internal state
watch(() => props.blocks, (newVal) => {
    internalBlocks.value = newVal;
}, {deep: true});

// Watch internal state changes and emit to the parent
watch(internalBlocks, (newVal) => {
    emit('update:blocks', newVal);
}, {deep: true});

const openModal = (column, index) => {
    insertColumn.value = column;
    insertIndex.value = index;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    insertColumn.value = null;
    insertIndex.value = null;
};

const removeBlock = (column, index) => {
    if (column === 1)
        internalBlocks.value.column1.splice(index, 1);
    else if (column === 2)
        internalBlocks.value.column2.splice(index, 1);

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
        case 'ContentBreak':
            break;
    }

    if (insertColumn.value === 1) {
        internalBlocks.value.column1.splice(insertIndex.value, 0, newBlock);
    } else if (insertColumn.value === 2) {
        internalBlocks.value.column2.splice(insertIndex.value, 0, newBlock);
    }

    closeModal();
};
</script>
