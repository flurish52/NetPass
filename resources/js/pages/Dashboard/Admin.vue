<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps<{
    businessCount: number;
}>();
</script>

<template>
    <AppLayout title="Admin Dashboard">
        <div class="mx-auto max-w-6xl p-6">
            <h1 class="text-xl font-semibold">Admin Dashboard</h1>
            <p class="mt-2 text-gray-500">{{ businessCount }} business(es) registered.</p>
        </div>
    </AppLayout>
</template>
