<template>
    <div class="relative">
        <div
            :class="dynamicAlignmentClasses"
            contenteditable="true"
            ref="editableDiv"
            @input="updateContent"
            @click.stop="isEditing = true"
            @blur="handleBlur"
        ></div>

        <div
            v-if="isEditing"
            class="absolute top-0 right-0 z-10 bg-white shadow-lg border rounded-full overflow-hidden flex items-center"
        >
            <button @click.stop="execCommand('bold')" class="p-2 text-sm font-semibold hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M15.65 14.89l-2.07 2.07a1 1 0 01-1.41-1.41l2.07-2.07a1 1 0 011.41 1.41zM9.54 9.17l-2.07 2.07a1 1 0 01-1.41-1.41l2.07-2.07a1 1 0 011.41 1.41zM11 16a5 5 0 100-10 5 5 0 000 10zM12 2a10 10 0 11-8.5 4.5A10.02 10.02 0 0112 2z"/></svg>
            </button>
            <button @click.stop="execCommand('italic')" class="p-2 text-sm font-semibold hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3a9 9 0 010 18H9a1 1 0 110-2h3a7 7 0 000-14H9a1 1 0 110-2h3z"/></svg>
            </button>

            <button @click.stop="changeAlignment('left')" class="p-2 text-sm font-semibold hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4 18h16a1 1 0 010 2H4a1 1 0 010-2zm0-4h16a1 1 0 010 2H4a1 1 0 010-2zm0-4h16a1 1 0 010 2H4a1 1 0 010-2zm0-4h16a1 1 0 010 2H4a1 1 0 010-2z"/></svg>
            </button>
            <button @click.stop="changeAlignment('center')" class="p-2 text-sm font-semibold hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M7 18h10a1 1 0 010 2H7a1 1 0 010-2zm-3-4h16a1 1 0 010 2H4a1 1 0 010-2zm3-4h10a1 1 0 010 2H7a1 1 0 010-2zM4 6h16a1 1 0 010 2H4a1 1 0 010-2z"/></svg>
            </button>
            <button @click.stop="changeAlignment('right')" class="p-2 text-sm font-semibold hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4 18h16a1 1 0 010 2H4a1 1 0 010-2zm0-4h16a1 1 0 010 2H4a1 1 0 010-2zm0-4h16a1 1 0 010 2H4a1 1 0 010-2zm0-4h16a1 1 0 010 2H4a1 1 0 010-2z"/></svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, watch, ref, onMounted, computed } from 'vue';

const props = defineProps({
    content: {
        type: String,
        default: 'Some content goes here...',
    },
    alignment: {
        type: String,
        default: 'left',
    },
});

const emit = defineEmits(['update:content', 'update:alignment']);

const editableDiv = ref(null);
const isEditing = ref(false);
let blurTimeout = null;

const dynamicAlignmentClasses = computed(() => {
    return {
        'text-left': props.alignment === 'left',
        'text-center': props.alignment === 'center',
        'text-right': props.alignment === 'right',
    };
});

// A small delay is needed to allow the buttons to be clicked
const handleBlur = () => {
    blurTimeout = setTimeout(() => {
        isEditing.value = false;
    }, 150);
};

const execCommand = (command) => {
    // This command applies formatting to the current text selection
    document.execCommand(command, false, null);
    // Force a content update since execCommand doesn't trigger the input event
    updateContent({target: editableDiv.value});
};

const changeAlignment = (newAlignment) => {
    emit('update:alignment', newAlignment);
};

// --- Standard contenteditable logic from previous step ---
onMounted(() => {
    if (editableDiv.value) {
        editableDiv.value.innerHTML = props.content;
    }
});

watch(() => props.content, (newContent) => {
    if (editableDiv.value && editableDiv.value.innerHTML !== newContent) {
        editableDiv.value.innerHTML = newContent;
    }
});

const updateContent = (event) => {
    emit('update:content', event.target.innerHTML);
};
</script>
