<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-2xl h-[500px] w-[500px] flex flex-col">
            <div class="flex items-center justify-between border-b pb-2 mb-4">
                <h2 class="text-xl font-bold">Select a Product</h2>
                <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div v-if="loading" class="flex-1 flex items-center justify-center">
                <p>Loading products...</p>
            </div>

            <ul v-else class="flex-1 overflow-y-auto">
                <li
                    v-for="product in products"
                    :key="product.id"
                    class="flex items-center justify-between border-b border-gray-300 p-2 last:border-b-0"
                >
                    <img :src="product.image" :alt="product.name" class="w-12 h-12 object-cover rounded" />
                    <div class="flex flex-col flex-grow px-2">
                        <span class="font-medium text-left">{{ product.name }}</span>
                        <strong class="text-gray-600">£{{ product.price }}</strong>
                    </div>
                    <button
                        @click="selectProduct(product)"
                        class="bg-black text-white px-4 py-1 rounded-full text-sm hover:bg-gray-800"
                    >
                        Add
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    slotKey: {
        type: Number,
        required: true,
    },
    hotelId: {
        type: Number
    }
});

const emit = defineEmits(['product-selected', 'close']);

const products = ref([]);
const loading = ref(true);

const fetchProducts = async () => {
    console.log(props.hotelId)
    try {
        const response = await axios.post('/admin/hotel/' + props.hotelId + '/list-products-as-json'); // Replace '1' with your hotel ID
        products.value = response.data;
    } catch (error) {
        console.error('Failed to fetch products:', error);
    } finally {
        loading.value = false;
    }
};

const selectProduct = (product) => {
    emit('product-selected', {
        slotKey: props.slotKey,
        product,
    });
};

onMounted(() => {
    fetchProducts();
});
</script>
