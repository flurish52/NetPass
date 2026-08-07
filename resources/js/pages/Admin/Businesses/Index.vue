<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

interface Business {
    id: number;
    name: string;
    subscriptionStatus: string;
    voucherCount: number;
    createdAt: string;
}

defineProps<{ businesses: Business[] }>();

const page = usePage();
const credentials = page.props.flash?.generatedCredentials as
    | { email: string; password: string }
    | undefined;
</script>

<template>
    <AppLayout title="Businesses">
        <div class="mx-auto max-w-4xl space-y-6 p-6">
            <div
                v-if="credentials"
                class="rounded-lg border border-green-300 bg-green-50 p-4 text-sm text-green-800"
            >
                <p class="font-medium">Business created. Send these to the customer now — the password won't be shown again:</p>
                <p class="mt-2 font-mono">{{ credentials.email }}</p>
                <p class="font-mono">{{ credentials.password }}</p>
            </div>

            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Businesses</h1>
                <Link
                    href="/admin/businesses/create"
                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white"
                >
                    New business
                </Link>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="border-b border-gray-100 text-left text-gray-500">
                        <th class="px-4 py-2 font-medium">Name</th>
                        <th class="px-4 py-2 font-medium">Status</th>
                        <th class="px-4 py-2 font-medium">Vouchers</th>
                        <th class="px-4 py-2 font-medium">Created</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="b in businesses" :key="b.id" class="border-b border-gray-50 last:border-0">
                        <td class="px-4 py-2">{{ b.name }}</td>
                        <td class="px-4 py-2">{{ b.subscriptionStatus }}</td>
                        <td class="px-4 py-2">{{ b.voucherCount }}</td>
                        <td class="px-4 py-2 text-gray-500">{{ new Date(b.createdAt).toLocaleString()  }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
