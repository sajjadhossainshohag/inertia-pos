<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    data: Object
})
</script>

<template>
    <div v-if="data.links.length > 1" class="pagination mt-4">
        <ul class="pagination">
            <li v-for="link in data.links" :key="link.label" :class="['page-item', { active: link.active }]">
                <Link v-if="link.url" :href="link.url" class="page-link" v-html="link.label">
                </Link>
                <span v-else class="page-link" v-html="link.label"></span>
            </li>
        </ul>
    </div>
</template>