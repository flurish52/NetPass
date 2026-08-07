<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

interface SessionRow {
    id: number;
    device_mac: string;
    status: string;
    started_at: string;
    expires_at: string;
    voucher: { code: string; plan: { name: string } };
}

defineProps<{ sessions: { data: SessionRow[]; links: any[] } }>();
</script>

<template>
    <AppLayout title="Sessions">
        <div class="mx-auto max-w-5xl space-y-6 p-6">
            <h1 class="text-xl font-semibold">Sessions</h1>

            <div class="rounded-lg border border-gray-200 bg-white">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="border-b border-gray-100 text-left text-gray-500">
                        <th class="px-4 py-2 font-medium">Device MAC</th>
                        <th class="px-4 py-2 font-medium">Voucher</th>
                        <th class="px-4 py-2 font-medium">Plan</th>
                        <th class="px-4 py-2 font-medium">Status</th>
                        <th class="px-4 py-2 font-medium">Started</th>
                        <th class="px-4 py-2 font-medium">Expires</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="s in sessions.data" :key="s.id" class="border-b border-gray-50 last:border-0">
                        <td class="px-4 py-2 font-mono">{{ s.device_mac }}</td>
                        <td class="px-4 py-2 font-mono">{{ s.voucher.code }}</td>
                        <td class="px-4 py-2">{{ s.voucher.plan.name }}</td>
                        <td class="px-4 py-2">
                                <span
                                    class="rounded-full px-2 py-0.5 text-xs"
                                    :class="s.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                >
                                    {{ s.status }}
                                </span>
                        </td>
                        <td class="px-4 py-2 text-gray-500">{{ new Date(s.started_at).toLocaleString() }}</td>
                        <td class="px-4 py-2 text-gray-500">{{ new Date(s.expires_at).toLocaleString() }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex gap-2 text-sm">
                <Link
                    v-for="link in sessions.links"
                    :key="link.label"
                    :href="link.url ?? ''"
                    v-html="link.label"
                    class="rounded px-2 py-1"
                    :class="link.active ? 'bg-gray-900 text-white' : 'text-gray-500'"
                />
            </div>
        </div>
    </AppLayout>
</template>
