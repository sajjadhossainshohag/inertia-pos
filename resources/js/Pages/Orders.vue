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
const from_date = ref(page.props.ziggy.query.from_date || '');
const to_date = ref(page.props.ziggy.query.to_date || '');

// Watch individual values using an array
let timeout;
watch([query, from_date, to_date], () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        updateSearchResults();
    }, 500); // 500 ms delay
});

// Method to send filters to the server
const updateSearchResults = () => {
    router.get(route('pos.orders'), {
        search: query.value,
        from_date: from_date.value,
        to_date: to_date.value
    }, { preserveState: true });
};
</script>


<template>

    <Head title="Orders" />
    <Nav />

    <div class="container mt-3">
        <div class="row">
            <div class="col-xxl-4">
                <label class="form-label">Search</label>
                <input class="form-control my-2" type="search" v-model="query" placeholder="Search By Order No">
            </div>
            <div class="col-xxl-4">
                <label class="form-label">From Date</label>
                <input class="form-control my-2" type="date" v-model="from_date" placeholder="From Date">
            </div>
            <div class="col-xxl-4">
                <label class="form-label">To Date</label>
                <input class="form-control my-2" type="date" v-model="to_date" placeholder="To Date">
            </div>
        </div>

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
                                {{ item.name }} - ${{ item.price }} <small>x{{ item.quantity }}</small>
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
