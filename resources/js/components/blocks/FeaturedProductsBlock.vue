<template>
    <div class="p-4 border rounded bg-white shadow-sm">
        <h4 class="font-bold text-lg mb-4">Featured Products</h4>
        <div class="grid grid-cols-2  gap-4">
            <div
                v-for="(product, key) in internalProducts"
                :key="key"
                class="relative border rounded-2xl h-[220px] shadow-sm overflow-hidden"
            >
                <div
                    v-if="!product"
                    class="h-full w-full flex items-center justify-center border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer hover:bg-gray-50"
                    @click="openModal(key)"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>

                <div v-else class="h-full w-full relative">
                    <img :src="product.image" :alt="product.name" class="w-full h-full object-cover rounded-2xl" />
                    <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black via-black/70 to-transparent rounded-b-2xl">
                        <p class="text-white text-sm font-semibold">{{ product.name }}</p>
                        <strong class="text-white text-xs">£{{ product.price }}</strong>
                    </div>
                    <button
                        @click="removeProduct(key)"
                        class="absolute top-2 right-2 bg-white rounded-full p-1 shadow-md hover:bg-gray-100"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <ProductSelectionModal
        v-if="showModal"
        :slot-key="currentSlotKey"
        @product-selected="handleProductSelected"
        @close="showModal = false"
        :hotel-id="props.hotelId"
    />
</template>

<script setup>
import { defineProps, defineEmits, ref, watch } from 'vue';
import ProductSelectionModal from '../ProductSelectionModal.vue'; // Create this file

const props = defineProps({
    products: {
        type: Array,
        default: () => [null, null, null, null], // Default to 4 empty slots
    },
    hotelId: {
        type: Number
    }
});

const emit = defineEmits(['update:products']);

const internalProducts = ref(props.products);
const showModal = ref(false);
const currentSlotKey = ref(null);

const openModal = (key) => {
    currentSlotKey.value = key;
    showModal.value = true;
};

const removeProduct = (key) => {
    internalProducts.value[key] = null;
    emit('update:products', internalProducts.value);
};

const handleProductSelected = ({ slotKey, product }) => {
    internalProducts.value[slotKey] = product;
    emit('update:products', internalProducts.value);
    showModal.value = false;
};

watch(() => props.products, (newVal) => {
    internalProducts.value = newVal;
}, { deep: true });

watch(internalProducts, (newVal) => {
    emit('update:products', newVal);
}, { deep: true });
</script>

