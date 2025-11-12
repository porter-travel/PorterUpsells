<template>
    <div
        class="relative"
        @click.stop="isEditing = true"
    >
        <!-- The editable title element -->
        <div
            :class="dynamicClasses"
            contenteditable="true"
            ref="editableDiv"
            @input="updateContent"
            @blur="handleBlur"
        ></div>

        <!-- Floating controls menu -->
        <div
            v-if="isEditing"
            class="absolute top-0 right-0 z-10 bg-white shadow-lg border rounded-full overflow-hidden"
        >
            <button
                v-for="h in ['h1', 'h2', 'h3', 'h4']"
                :key="h"
                @click.stop="changeLevel(h)"
                class="px-3 py-2 text-sm font-semibold hover:bg-gray-100"
            >
                {{ h.toUpperCase() }}
            </button>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, watch, ref, onMounted, computed } from 'vue';

const props = defineProps({
    content: {
        type: String,
        default: 'Untitled',
    },
    level: {
        type: String,
        default: 'h1',
    },
});

const emit = defineEmits(['update:content', 'update:level']);

const editableDiv = ref(null);
const isEditing = ref(false);
let blurTimeout = null;

// Dynamically apply Tailwind CSS classes based on the 'level' prop
const dynamicClasses = computed(() => {
    let base = 'cursor-pointer p-2 hover:bg-gray-100 rounded mb-4 font-bold';
    switch (props.level) {
        case 'h1':
            return base + ' text-2xl';
        case 'h2':
            return base + ' text-xl';
        case 'h3':
            return base + ' text-lg';
        case 'h4':
            return base + ' text-base';
        default:
            return base + ' text-2xl';
    }
});

// Emits the new level to the parent
const changeLevel = (newLevel) => {
    emit('update:level', newLevel);
};

// Handles the blur event with a timeout to allow clicking the controls
const handleBlur = () => {
    blurTimeout = setTimeout(() => {
        isEditing.value = false;
    }, 150);
};

// Clear the timeout if we click within the component
const handleClick = () => {
    clearTimeout(blurTimeout);
};

// --- Standard contenteditable logic from previous step ---
onMounted(() => {
    editableDiv.value.innerText = props.content;
});

watch(() => props.content, (newContent) => {
    if (editableDiv.value && editableDiv.value.innerText !== newContent) {
        editableDiv.value.innerText = newContent;
    }
});

const updateContent = (event) => {
    emit('update:content', event.target.innerText);
};
</script>
