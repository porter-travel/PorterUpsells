<template>
    <div :class="['relative group', isEditing ? 'pt-12' : '']">
        <div
            :class="[
                'min-h-[120px] w-full rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-3 text-sm leading-relaxed text-slate-700 shadow-sm transition focus:outline-none focus:ring-0 focus-visible:outline-none',
                dynamicAlignmentClasses,
                isEditing ? 'border-indigo-200 shadow-md shadow-indigo-100/50' : 'hover:border-indigo-200 hover:shadow'
            ]"
            contenteditable="true"
            ref="editableDiv"
            spellcheck="true"
            @input="updateContent"
            @focus="startEditing"
            @click.stop="startEditing"
            @blur="handleBlur"
        ></div>

        <transition name="fade">
            <div
                v-if="isEditing"
                class="absolute left-1/2 top-3 z-20 flex -translate-x-1/2 items-center gap-1 rounded-full border border-slate-700/70 bg-slate-900/95 px-2 py-1 text-white shadow-2xl"
            >
                <button
                    type="button"
                    @mousedown.prevent="execCommand('bold')"
                    class="toolbar-button"
                    aria-label="Bold"
                    title="Bold"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M7 5h6a3 3 0 0 1 0 6H7V5Z"/>
                        <path d="M13 11h2a3 3 0 0 1 0 6H7v-6"/>
                    </svg>
                </button>
                <button
                    type="button"
                    @mousedown.prevent="execCommand('italic')"
                    class="toolbar-button"
                    aria-label="Italic"
                    title="Italic"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <line x1="19" y1="4" x2="10" y2="4"/>
                        <line x1="14" y1="20" x2="5" y2="20"/>
                        <line x1="15" y1="4" x2="9" y2="20"/>
                    </svg>
                </button>
                <button
                    type="button"
                    @mousedown.prevent="execCommand('underline')"
                    class="toolbar-button"
                    aria-label="Underline"
                    title="Underline"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M6 4v6a6 6 0 0 0 12 0V4"/>
                        <line x1="4" y1="20" x2="20" y2="20"/>
                    </svg>
                </button>
                <span class="mx-1 h-5 w-px bg-white/20"></span>
                <button
                    type="button"
                    @mousedown.prevent="toggleList"
                    class="toolbar-button"
                    aria-label="Bulleted list"
                    title="Bulleted list"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <line x1="8" y1="6" x2="21" y2="6"/>
                        <line x1="8" y1="12" x2="21" y2="12"/>
                        <line x1="8" y1="18" x2="21" y2="18"/>
                        <circle cx="4" cy="6" r="1"/>
                        <circle cx="4" cy="12" r="1"/>
                        <circle cx="4" cy="18" r="1"/>
                    </svg>
                </button>
                <span class="mx-1 h-5 w-px bg-white/20"></span>
                <button
                    type="button"
                    @mousedown.prevent="updateAlignment('left')"
                    :class="['toolbar-button', alignmentButtonClass('left')]"
                    aria-label="Align left"
                    title="Align left"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <line x1="4" y1="6" x2="20" y2="6"/>
                        <line x1="4" y1="12" x2="16" y2="12"/>
                        <line x1="4" y1="18" x2="20" y2="18"/>
                    </svg>
                </button>
                <button
                    type="button"
                    @mousedown.prevent="updateAlignment('center')"
                    :class="['toolbar-button', alignmentButtonClass('center')]"
                    aria-label="Align center"
                    title="Align center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <line x1="6" y1="6" x2="18" y2="6"/>
                        <line x1="4" y1="12" x2="20" y2="12"/>
                        <line x1="6" y1="18" x2="18" y2="18"/>
                    </svg>
                </button>
                <button
                    type="button"
                    @mousedown.prevent="updateAlignment('right')"
                    :class="['toolbar-button', alignmentButtonClass('right')]"
                    aria-label="Align right"
                    title="Align right"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <line x1="4" y1="6" x2="20" y2="6"/>
                        <line x1="8" y1="12" x2="20" y2="12"/>
                        <line x1="4" y1="18" x2="20" y2="18"/>
                    </svg>
                </button>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, watch, ref, onMounted, computed, onBeforeUnmount } from 'vue';

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
    clearBlurTimeout();
    blurTimeout = setTimeout(() => {
        isEditing.value = false;
    }, 120);
};

const clearBlurTimeout = () => {
    if (blurTimeout) {
        clearTimeout(blurTimeout);
        blurTimeout = null;
    }
};

const focusEditable = () => {
    if (editableDiv.value) {
        editableDiv.value.focus({preventScroll: true});
    }
};

const startEditing = () => {
    clearBlurTimeout();
    isEditing.value = true;
};

const execCommand = (command) => {
    document.execCommand(command, false, null);
    updateContent({target: editableDiv.value});
    focusEditable();
};

const toggleList = () => {
    document.execCommand('insertUnorderedList', false, null);
    updateContent({target: editableDiv.value});
    focusEditable();
};

const updateAlignment = (newAlignment) => {
    emit('update:alignment', newAlignment);
    if (editableDiv.value) {
        editableDiv.value.style.textAlign = newAlignment;
    }
    focusEditable();
};

const isAlignmentActive = (alignment) => props.alignment === alignment;

const alignmentButtonClass = (alignment) => (
    isAlignmentActive(alignment)
        ? 'bg-white text-slate-900 shadow-sm'
        : 'text-white/70 hover:bg-white/10'
);

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

onBeforeUnmount(() => {
    clearBlurTimeout();
});
</script>

<style scoped>
.toolbar-button {
    @apply inline-flex h-9 w-9 items-center justify-center rounded-full text-white/80 transition hover:bg-white/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/60 focus-visible:ring-offset-0;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 120ms ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
    opacity: 1;
}
</style>
