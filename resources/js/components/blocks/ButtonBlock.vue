<template>
    <div class="space-y-4 py-4 text-center">
        <div class="flex justify-center">
            <a
                href="#"
                :target="isEditing ? '_self' : '_blank'"
                class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-indigo-500 via-purple-500 to-sky-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-transform duration-200"
                :class="isEditing ? 'ring-2 ring-indigo-200/70 ring-offset-2' : 'hover:-translate-y-0.5 hover:shadow-xl'
                "
                @click.prevent.stop="toggleEditing"
            >
                {{ content }}
            </a>
        </div>

        <transition name="fade">
            <div
                v-if="isEditing"
                class="mx-auto w-full max-w-sm rounded-2xl border border-slate-200 bg-white/95 p-5 text-left shadow-xl shadow-indigo-500/15"
                @click.stop
            >
                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Button text
                        </label>
                        <input
                            type="text"
                            v-model="internalContent"
                            @input="updateContent"
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                        />
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Link URL
                        </label>
                        <input
                            type="text"
                            v-model="internalUrl"
                            @input="updateUrl"
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                        />
                        <p class="text-xs text-slate-500">
                            Guests will be taken to this link when they tap your call-to-action.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="isEditing = false"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        Done
                    </button>
                </div>
            </div>
        </transition>
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

const toggleEditing = () => {
    isEditing.value = !isEditing.value;
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 150ms ease;
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
