<script setup>
import Nav from '../Components/Nav.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Pagination from '../Components/Pagination.vue';

defineProps({
    products: Object
});

// Selected product for variation view
const selectedProduct = ref(null);
// Cart to store selected products
const cart = ref([]);

// Add product or variation to the cart
const addToCart = (product, variation = null) => {
    const price = variation
        ? variation.sell_price
        : product.selling_price_discount || product.selling_price;
    const tax = variation ? variation.tax_amount : product.tax_amount;

    const item = variation
        ? {
            product_id: null,
            variation_id: variation.id,
            name: `${product.name} (${variation.attributes.map(a => `${a.key}: ${a.value}`).join(', ')})`,
            price,
            tax: tax
        }
        : { product_id: product.id, variation_id: null, name: product.name, price, tax: tax };

    // Check if item already exists in the cart
    const existingItem = cart.value.find(
        c => c.product_id === item.product_id && c.variation_id === item.variation_id
    );

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.value.push({ ...item, quantity: 1 });
    }
};

// Remove item from the cart
const removeFromCart = (productId, variationId = null) => {
    cart.value = cart.value.filter(
        item => item.product_id !== productId || item.variation_id !== variationId
    );
};

// Calculate subtotal (before tax and discounts)
const cartSubtotal = computed(() =>
    cart.value.reduce((total, item) => total + item.price * item.quantity, 0)
);

// Calculate tax
const cartTax = computed(() =>
    cart.value.reduce((total, item) => total + item.tax, 0)
);

// Calculate grand total (subtotal + tax)
const cartGrandTotal = computed(() => cartSubtotal.value + cartTax.value);

// Place order
const placeOrder = () => {
    if (cart.value.length === 0) {
        alert('Cart is empty. Please add items before placing an order.');
        return;
    }

    // Prepare order data
    const orderData = useForm({
        items: cart.value.map(item => ({
            product_id: item.product_id,
            variation_id: item.variation_id,
            name: item.name,
            price: item.price,
            quantity: item.quantity,
            tax: item.tax,
        })),
        subtotal: cartSubtotal.value,
        tax: cartTax.value,
        total: cartGrandTotal.value,
    });

    // Send to request
    router.post(route('pos.place.order'), orderData, {
        onSuccess: () => cart.value = []
    })
};
</script>

<template>

    <Head title="POS" />
    <Nav />

    <div class="container mt-3">
        <div class="bg-primary p-2 text-center text-white mb-4">Point Of Sale</div>
        <div class="row">
            <div class="col-xxl-8">
                <div class="row">
                    <div v-for="product in products.data" :key="product.id" class="col-xxl-3 text-center mb-2">
                        <div class="card">
                            <div class="card-body" style="cursor:pointer"
                                @click="product.variations.length > 0 ? (selectedProduct = product) : addToCart(product)">
                                <img :src="product.thumbnail_url" :alt="product.name" class="img-thumbnail rounded"
                                    style="height: 150px">
                                <h6 class="text-truncate mt-3">{{ product.name }}</h6>
                                <h6>
                                    ${{ product.selling_price_discount || product.selling_price }}
                                    <small v-if="product.selling_price_discount != product.selling_price">
                                        <del>${{ product.selling_price }}</del>
                                    </small>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Cart Section -->
            <div class="col-xxl-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">Cart</div>
                    <div class="card-body">
                        <ul class="list-group mb-3">
                            <li v-for="(item, index) in cart" :key="index"
                                class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ item.name }}</strong>
                                </div>
                                <div class="d-flex align-items-center">
                                    <!-- Quantity Input -->
                                    <input type="number" class="form-control form-control-sm me-2"
                                        v-model.number="item.quantity" @input="updateCartQuantity(index, item.quantity)"
                                        min="1" style="width: 60px;" />
                                    <!-- Item Price -->
                                    <span>${{ (item.price * item.quantity).toFixed(2) }}</span>
                                    <!-- Remove Button -->
                                    <button class="btn btn-sm btn-danger ms-2"
                                        @click="removeFromCart(item.product_id, item.variation_id)">
                                        X
                                    </button>
                                </div>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-between">
                            <strong>Subtotal:</strong>
                            <strong>${{ cartSubtotal.toFixed(2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <strong>Tax:</strong>
                            <strong>${{ cartTax.toFixed(2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <strong>Grand Total:</strong>
                            <strong>${{ cartGrandTotal.toFixed(2) }}</strong>
                        </div>
                    </div>

                    <button class="btn btn-primary m-2" @click="placeOrder">Place Order</button>
                </div>
            </div>
        </div>
        <!-- Modal for Variations -->
        <div v-if="selectedProduct" class="modal fade show" style="display: block; background: rgba(0, 0, 0, 0.5);"
            @click.self="selectedProduct = null">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ selectedProduct.name }}</h5>
                        <button type="button" class="btn-close" @click="selectedProduct = null"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Variations -->
                        <ul class="list-group">
                            <li v-for="variation in selectedProduct.variations" :key="variation.id"
                                class="list-group-item d-flex justify-content-between align-items-center"
                                @click="addToCart(selectedProduct, variation); selectedProduct = null;"
                                style="cursor:pointer">
                                <!-- Display attributes -->
                                <div>
                                    <span v-for="(value, key) in variation.attributes" :key="key" class="d-block">
                                        <strong>{{ value.key }}:</strong> {{ value.value }}
                                    </span>
                                </div>
                                <!-- Display price -->
                                <span>${{ variation.sell_price }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <Pagination :data="products" />
    </div>
</template>
