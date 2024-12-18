<script setup>
import { Link } from '@inertiajs/vue3';

</script>
<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <Link href="/" class="navbar-brand">ThemeLooks</Link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <Link class="nav-link" :href="route('products.index')">Products</Link>
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link" :href="route('pos.index')">POS</Link>
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link" :href="route('pos.orders')">Orders</Link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-3" v-if="$page.props.flash.error || $page.props.flash.success">
        <div class="alert"
            :class="{ 'alert-success': $page.props.flash.success, 'alert-danger': $page.props.flash.error }">
            {{ $page.props.flash.error || $page.props.flash.success }}
        </div>
    </div>
</template>