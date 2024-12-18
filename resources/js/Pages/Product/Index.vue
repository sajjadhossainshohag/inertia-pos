<script setup>
import Nav from '../Components/Nav.vue';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '../Components/Pagination.vue';

defineProps({
    products: Object
});

</script>

<template>
    <Head title="Products" />
    <Nav />

    <div class="container mt-3">
        <Link :href="route('products.create')" class="btn btn-success">Add Product</Link>
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Thumbnail</th>
                    <th>Product Name</th>
                    <th>Selling Price</th>
                    <th>Purchase Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center" v-if="products.data.length === 0">No Product Found!</td>
                </tr>
                <tr v-for="(product, index) in products.data" :key="product.id">
                    <td>{{ (products.current_page - 1) * products.per_page + index + 1 }}</td>
                    <td>
                        <img :src="product.thumbnail_url" class="img-fluid rounded"
                            style="width: 80px; height: 80px; object-fit: cover;" :alt="product.name">
                    </td>
                    <td>{{ product.name }}</td>
                    <td>{{ product.selling_price }}</td>
                    <td>{{ product.purchase_price }}</td>
                    <td>
                        <Link :href="route('products.delete', product.id)" method="delete" as="button"
                            class="btn btn-danger">Delete</Link>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination :data="products"/>
    </div>
</template>