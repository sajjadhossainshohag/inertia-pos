<script setup>
import Nav from './Components/Nav.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import Pagination from './Components/Pagination.vue';
import { ref, watch } from 'vue';

defineProps({
    orders: Object
});


const page = usePage();

const query = ref(page.props.ziggy.query.search || '');

// Watch the search query and debounce the request
let timeout;
watch(query, (newValue) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        // Call the method to update the results based on search query
        updateSearchResults(newValue);
    }, 500);  // 500 ms delay
});

// Method to send the search query to the server
const updateSearchResults = (query) => {
    router.get(route('pos.orders'), { search: query }, { preserveState: true });
};

</script>

<template>

    <Head title="Orders" />
    <Nav />

    <div class="container mt-3">
        <input class="form-control my-2" type="search" v-model="query" placeholder="Search By Order No"
            aria-label="Search By Order No">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Date</th>
                    <th>Order No</th>
                    <th>Items</th>
                    <th>Total Tax</th>
                    <th>Subtotal</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" class="text-center" v-if="orders.data.length === 0">No Orders Found!</td>
                </tr>
                <tr v-for="(order, index) in orders.data" :key="order.id">
                    <td>{{ (orders.current_page - 1) * orders.per_page + index + 1 }}</td>
                    <td>{{ new Date(order.created_at).toLocaleDateString('en-GB', {
                        year: 'numeric', month: 'short',
                        day: 'numeric'
                    }) }}</td>
                    <td>{{ order.order_id }}</td>
                    <td>
                        <ul>
                            <li v-for="(item, index) in order.items" :key="index">
                                {{ item.name }} - ${{ item.price }}
                            </li>
                        </ul>
                    </td>
                    <td>{{ order.tax }}</td>
                    <td>{{ order.sub_total }}</td>
                    <td>{{ order.total }}</td>
                </tr>
            </tbody>
        </table>
        <Pagination :data="orders" />
    </div>
</template>
