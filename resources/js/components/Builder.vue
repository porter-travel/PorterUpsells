<template>
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-7">
            <div v-if="type === 'email'">
                <EmailConfiguration
                    v-model:meta="emailMeta"
                    @send-test-email="sendTestEmail"
                    @automation-selected="applyAutomationPreset"
                />

            </div>

            <div v-else-if="type === 'webpage'" class="space-y-3">
                <h3 class="text-lg font-semibold text-slate-900">Web page settings</h3>
                <label class="block space-y-1">
                    <span class="text-sm font-medium text-slate-600">Page title</span>
                    <input type="text"
                           class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100"/>
                </label>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-1">
                    <h2 class="text-xl font-semibold text-slate-900">Email preview</h2>
                    <p class="text-sm text-slate-500">Drag, drop and edit blocks to craft the perfect email.</p>
                </div>
                <button
                    type="button"
                    @click="openModal()"
                    class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:-translate-y-0.5 hover:border-indigo-300 hover:bg-indigo-100"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                         class="h-4 w-4">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add block
                </button>
            </div>

            <div class="mt-6 space-y-4">
                <div
                    v-if="!blocks.length"
                    class="flex flex-col items-center justify-center gap-4 rounded-2xl border border-dashed border-slate-300 bg-white/70 p-12 text-center"
                >
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-indigo-50 text-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                             class="h-6 w-6">
                            <path d="M7 10v12"></path>
                            <path d="M17 10v12"></path>
                            <path d="M5 6h14"></path>
                            <path d="M19 10H5"></path>
                            <path d="M9 6V4"></path>
                            <path d="M15 6V4"></path>
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <p class="text-lg font-semibold text-slate-900">Start by adding your first block</p>
                        <p class="text-sm text-slate-500">
                            Combine headers, copy, imagery and CTAs to build your automated message.
                        </p>
                    </div>
                    <button
                        type="button"
                        @click="openModal()"
                        class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:-translate-y-0.5 hover:border-indigo-300 hover:bg-indigo-100"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                             class="h-4 w-4">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add a block
                    </button>
                </div>

                <draggable
                    v-else
                    v-model="blocks"
                    item-key="id"
                    handle=".grab-handle"
                    class="space-y-4"
                    :animation="200"
                >
                    <template #item="{ element: block, index }">
                        <div
                            class="rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-indigo-200">
                            <div class="flex items-start justify-between gap-3 border-b border-slate-100 px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="grab-handle inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-400 transition hover:border-indigo-200 hover:text-indigo-500"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round"
                                             class="h-4 w-4">
                                            <path d="M9 4h0"></path>
                                            <path d="M15 4h0"></path>
                                            <path d="M9 12h0"></path>
                                            <path d="M15 12h0"></path>
                                            <path d="M9 20h0"></path>
                                            <path d="M15 20h0"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">
                                            {{ blockMeta[block.type]?.label ?? 'Custom block' }}</p>
                                        <p class="text-xs text-slate-500">{{
                                                blockMeta[block.type]?.hint ?? 'Drag to reorder or edit the content below.'
                                            }}</p>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    @click="removeBlock(index)"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-600 transition hover:border-rose-300 hover:bg-rose-100"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="h-3.5 w-3.5">
                                        <path d="M3 6h18"></path>
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                        <path d="M10 11v6"></path>
                                        <path d="M14 11v6"></path>
                                    </svg>
                                    Remove
                                </button>
                            </div>
                            <div class="px-4 py-5 sm:px-6">
                                <component
                                    :is="componentsMap[block.type]"
                                    v-model:content="block.content"
                                    v-model:level="block.level"
                                    v-model:alignment="block.alignment"
                                    v-model:url="block.url"
                                    v-model:products="block.products"
                                    v-model:blocks="block.blocks"
                                    :hotel-name="hotelName"
                                    :hotel-logo="hotelLogo"
                                    :hotel-featured-image="hotelFeaturedImage"
                                    :hotel-id="props.hotelId"
                                />
                            </div>
                            <div class="flex justify-center border-t border-slate-100 px-4 py-3">
                                <button
                                    type="button"
                                    @click="openModal(index + 1)"
                                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="h-3.5 w-3.5">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Insert block below
                                </button>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-900/10 bg-slate-900 text-white shadow-xl shadow-indigo-500/20">
            <div class="space-y-3 p-6">
                <h3 class="text-lg font-semibold">Save changes</h3>
                <p class="text-sm text-white/70">Keep your progress safe and publish updates to your automated
                    journeys.</p>
                <button
                    type="button"
                    @click="saveContent"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 via-purple-500 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:shadow-xl hover:shadow-indigo-500/40"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                         class="h-4 w-4">
                        <path d="M7 3v4a1 1 0 0 0 1 1h7"></path>
                        <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"></path>
                        <path
                            d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"></path>
                    </svg>
                    Save content
                </button>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 backdrop-blur">
            <div class="w-full max-w-lg space-y-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
                <div class="space-y-2 text-center">
                    <h3 class="text-xl font-semibold text-slate-900">Choose a block</h3>
                    <p class="text-sm text-slate-500">Mix and match elements to tell your story.</p>
                </div>

                <div class="grid gap-3">
                    <button
                        v-for="option in blockCatalogue"
                        :key="option.type"
                        type="button"
                        @click="addBlock(option.type)"
                        class="flex w-full items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-left transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50"
                    >
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-base font-semibold text-indigo-600">
                            {{ option.icon }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ option.label }}</p>
                            <p class="text-xs text-slate-500">{{ option.description }}</p>
                        </div>
                    </button>
                </div>

                <button
                    type="button"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-white"
                    @click="closeModal"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>

import {defineProps, onMounted, ref} from 'vue';
import draggable from 'vuedraggable';

import TitleBlock from './blocks/TitleBlock.vue';
import ContentBlock from './blocks/ContentBlock.vue';
import ImageBlock from './blocks/ImageBlock.vue';
import ButtonBlock from './blocks/ButtonBlock.vue';
import ContentBreak from './blocks/ContentBreak.vue';
import ColumnsBlock from './blocks/ColumnsBlock.vue';
import FeaturedProductsBlock from './blocks/FeaturedProductsBlock.vue';
import EmailConfiguration from "./EmailConfiguration.vue";
import HeaderBlock from "./blocks/HeaderBlock.vue";

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
    },
    hotelName: {
        type: String,
        required: false,
    },
    hotelLogo: {
        type: String,
        required: false,
    },
    hotelFeaturedImage: {
        type: String,
        required: false,
    },
});

const blocks = ref([]);
const templateId = ref(props.templateId || null);
const emailMeta = ref({
    email_name: '',
    email_subject: '',
    when_to_send: 'before_arrival',
    days: 1,
    time: '12:00'
});

const blockCatalogue = [
    {
        type: 'HeaderBlock',
        label: 'Hero header',
        description: 'Introduce your property with a branded hero.',
        hint: 'Showcase your hotel name and feature image.',
        icon: '✨',
    },
    {
        type: 'TitleBlock',
        label: 'Section title',
        description: 'Add impactful headings to break up your content.',
        hint: 'Use headings to introduce new sections.',
        icon: '🔠',
    },
    {
        type: 'ContentBlock',
        label: 'Rich text',
        description: 'Share stories, itineraries and key information.',
        hint: 'Use rich text to tell your story.',
        icon: '✏️',
    },
    {
        type: 'ImageBlock',
        label: 'Image',
        description: 'Drop in supporting imagery or GIFs.',
        hint: 'Pair visuals with your copy.',
        icon: '🖼️',
    },
    {
        type: 'ButtonBlock',
        label: 'Button',
        description: 'Drive action with a bold call-to-action.',
        hint: 'Link guests to upsells or key pages.',
        icon: '⚡',
    },
    {
        type: 'ContentBreak',
        label: 'Divider',
        description: 'Add breathing room between sections.',
        hint: 'Use dividers to separate ideas.',
        icon: '〰️',
    },
    {
        type: 'ColumnsBlock',
        label: 'Two columns',
        description: 'Place content side-by-side for comparisons.',
        hint: 'Great for highlighting multiple offers.',
        icon: '🧱',
    },
    {
        type: 'FeaturedProductsBlock',
        label: 'Featured products',
        description: 'Showcase add-ons guests can purchase.',
        hint: 'Highlight upsells directly in the email.',
        icon: '🛎️',
    },
];

const blockMeta = blockCatalogue.reduce((accumulator, option) => {
    accumulator[option.type] = option;
    return accumulator;
}, {});

onMounted(() => {
    if (templateId.value) {
        // Fetch existing automation data
        axios.get(`/admin/hotel/${props.hotelId}/email_builder/${templateId.value}/template_data`)
            .then(response => {
                if (response.data && response.data.template.body) {
                    try {
                        blocks.value = JSON.parse(response.data.template.body);
                    } catch (e) {
                        console.error('Failed to parse automation body:', e);
                    }
                }
                if (response.data) {
                    emailMeta.value.email_name = response.data.template.name || '';
                    emailMeta.value.email_subject = response.data.template.subject || '';
                    emailMeta.value.when_to_send = response.data.template.when_to_send || 'before_arrival';
                    emailMeta.value.days = response.data.template.days || 1;
                    emailMeta.value.time = response.data.template.time || '12:00';
                }
            })
            .catch(error => {
                console.error('Error fetching automation data:', error);
            });
    } else {
        if (example_template_data) {
            console.log(example_template_data.emailName);
            emailMeta.value.email_name = example_template_data.emailName;
            emailMeta.value.email_subject = example_template_data.emailSubject || '';
            emailMeta.value.when_to_send = example_template_data.whenToSend || 'before_arrival';
            emailMeta.value.days = example_template_data.days || 1;
            emailMeta.value.time = example_template_data.time || '12:00';
            blocks.value = JSON.parse(example_template_data.emailBody);
            console.log(emailMeta.value)
        }
    }
})
const componentsMap = {
    TitleBlock,
    ContentBlock,
    ImageBlock,
    ButtonBlock,
    ContentBreak,
    ColumnsBlock,
    FeaturedProductsBlock,
    HeaderBlock
};

// Define the reactive state for email settings


const showModal = ref(false);
const insertIndex = ref(null);

const openModal = (index = blocks.value.length) => {
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
            newBlock.content = 'Add your message here…';
            newBlock.alignment = 'left';
            break;
        case 'ImageBlock':
            break;
        case 'ButtonBlock':
            newBlock.content = 'Explore more';
            newBlock.url = 'https://www.example.com';
            break;
        case 'ColumnsBlock': // 👈 Add this case
            newBlock.blocks = {column1: [], column2: []}; // The initial nested blocks
            break;
        case 'FeaturedProductsBlock':
            newBlock.products = [null, null, null, null]; // Initialise with 4 empty slots
            break;
    }

    const targetIndex = insertIndex.value ?? blocks.value.length;
    blocks.value.splice(targetIndex, 0, newBlock);
    closeModal();
};

const sendTestEmail = (email, use_test_booking) => {

    if (!email) {
        alert('Please enter a valid email address.');
        return;
    }

    axios.post(`/admin/hotel/${props.hotelId}/email_builder/send-test-email`, {
        email: email,
        use_test_booking: use_test_booking,
        content: blocks.value,
        meta: emailMeta.value,
    }).then(response => {
        if (response.data.success) {
            alert(response.data.success);
        } else {
            alert('Failed to send test email.');
        }
    }).catch(error => {
        console.error('Error sending test email:', error);
        alert('An error occurred while sending test email.');
    })
}

    const applyAutomationPreset = (data) => {
        emailMeta.value.email_name = data.emailName || '';
        emailMeta.value.email_subject = data.emailSubject || '';
        emailMeta.value.when_to_send = data.whenToSend || 'before_arrival';
        emailMeta.value.days = data.days || 1;
        emailMeta.value.time = data.time || '12:00';
        try {
            blocks.value = JSON.parse(data.emailBody || '[]');
        } catch (error) {
            console.error('Failed to load automation body from preset:', error);
            blocks.value = [];
        }
    };


    const saveContent = () => {

        if (!emailMeta.value.email_name) {
            alert('Please provide an email name in the configuration section.');
            return;
        }

        if (!emailMeta.value.email_subject) {
            alert('Please provide an email subject in the configuration section.');
            return;
        }

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
                alert('Failed to save automation content.');
            }
        }).catch(error => {
            console.error('Error saving content:', error);
            alert('An error occurred while saving content.');
        })

};
</script>
