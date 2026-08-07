<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps<{
    business: {
        subscriptionStatus: string;
        isActive: boolean;
        trialEndsAt: string | null;
        subscriptionEndsAt: string | null;
    };
}>();
</script>

<template>
    <AppLayout title="Billing">
        <div class="mx-auto max-w-lg space-y-6 p-6">
            <h1 class="text-xl font-semibold">Billing</h1>

            <div class="rounded-lg border border-gray-200 bg-white p-4 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Status</span>
                    <span
                        class="rounded-full px-2 py-0.5 text-xs"
                        :class="business.isActive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                    >
                        {{ business.subscriptionStatus }}
                    </span>
                </div>
                <div v-if="business.subscriptionStatus === 'trial'" class="mt-3 flex justify-between">
                    <span class="text-gray-500">Trial ends</span>
                    <span>
                        {{ new Date(business.trialEndsAt).toLocaleString() }}
                    </span>
                </div>
                <div v-else class="mt-3 flex justify-between">
                    <span class="text-gray-500">Renews / ends</span>
                    <span>{{ business.subscriptionEndsAt ?? '—' }}</span>
                </div>
            </div>

            <p class="text-sm text-gray-500">
                Paid plans and payment methods aren't available yet — this page will let you upgrade once billing is
                connected.
            </p>
        </div>
    </AppLayout>
</template>
