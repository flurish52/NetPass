<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

interface Session {
    id: number;
    code: string;
    plan: string;
    deviceMac: string;
    status: string;
    startedAt: string;
    expiresAt: string;
}

const props = defineProps<{
    stats: {
        activeSessions: number;
        vouchersToday: number;
        redeemedToday: number;
        revenueToday: number;
    };
    license: {
        status: string;
        isActive: boolean;
        expiresAt: string | null;
    };
    recentSessions: Session[];
}>();

const formatDate = (iso: string) =>
    new Date(iso).toLocaleString(undefined, {
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    });

const statusStyles: Record<string, string> = {
    active: 'bg-green-100 text-green-700',
    expired: 'bg-gray-100 text-gray-600',
    revoked: 'bg-red-100 text-red-700',
};
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="mx-auto max-w-6xl space-y-6 p-6">
            <div
                v-if="!license.isActive"
                class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800"
            >
                Your subscription is <strong>{{ license.status }}</strong> and access to hotspot devices
                will stop granting once this laptop's agent detects the lapse.
                <Link href="/billing" class="font-medium underline">Manage subscription</Link>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">Active sessions</p>
                    <p class="mt-1 text-2xl font-semibold">{{ stats.activeSessions }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">Vouchers today</p>
                    <p class="mt-1 text-2xl font-semibold">{{ stats.vouchersToday }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">Redeemed today</p>
                    <p class="mt-1 text-2xl font-semibold">{{ stats.redeemedToday }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">Revenue today</p>
                    <p class="mt-1 text-2xl font-semibold">₦{{ stats.revenueToday.toLocaleString() }}</p>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white">
                <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                    <h2 class="font-medium">Recent sessions</h2>
                    <Link href="/sessions" class="text-sm text-gray-500 hover:text-gray-700">View all</Link>
                </div>
                <table class="w-full text-sm">
                    <thead>
                    <tr class="border-b border-gray-100 text-left text-gray-500">
                        <th class="px-4 py-2 font-medium">Code</th>
                        <th class="px-4 py-2 font-medium">Plan</th>
                        <th class="px-4 py-2 font-medium">Device</th>
                        <th class="px-4 py-2 font-medium">Status</th>
                        <th class="px-4 py-2 font-medium">Started</th>
                        <th class="px-4 py-2 font-medium">Expires</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="session in recentSessions"
                        :key="session.id"
                        class="border-b border-gray-50 last:border-0"
                    >
                        <td class="px-4 py-2 font-mono text-xs">{{ session.code }}</td>
                        <td class="px-4 py-2">{{ session.plan }}</td>
                        <td class="px-4 py-2 font-mono text-xs text-gray-500">{{ session.deviceMac }}</td>
                        <td class="px-4 py-2">
                                <span
                                    class="rounded-full px-2 py-0.5 text-xs font-medium"
                                    :class="statusStyles[session.status] ?? 'bg-gray-100 text-gray-600'"
                                >
                                    {{ session.status }}
                                </span>
                        </td>
                        <td class="px-4 py-2 text-gray-500">{{ formatDate(session.startedAt) }}</td>
                        <td class="px-4 py-2 text-gray-500">{{ formatDate(session.expiresAt) }}</td>
                    </tr>
                    <tr v-if="recentSessions.length === 0">
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                            No sessions yet.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
