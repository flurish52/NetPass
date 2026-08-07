<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';

interface Plan {
    id: number;
    name: string;
}

interface Voucher {
    id: number;
    code: string;
    status: string;
    batch_id: string | null;
    plan: { id: number; name: string };
    created_at: string;
}

const props = defineProps<{
    vouchers: { data: Voucher[]; links: any[] };
    plans: Plan[];
}>();

const page = usePage();
const lastBatchId = computed(() => page.props.flash?.batch_id as string | undefined);

const form = useForm({
    plan_id: '',
    quantity: 50,
});

const submit = () => {
    form.post(route('vouchers.generate'), { preserveScroll: true });
};

const statusColor = (status: string) => ({
    unused: 'bg-gray-100 text-gray-700',
    active: 'bg-green-100 text-green-700',
    expired: 'bg-red-100 text-red-700',
}[status] ?? 'bg-gray-100 text-gray-700');
</script>

<template>
    <AppLayout title="Vouchers">
        <div class="mx-auto max-w-5xl space-y-6 p-6">
            <div
                v-if="lastBatchId"
                class="flex items-center justify-between rounded-lg border border-green-300 bg-green-50 p-4 text-sm text-green-800"
            >
                <span>Batch generated successfully.</span>

             <a   :href="route('vouchers.print', lastBatchId)"
                target="_blank"
                class="font-medium underline"
                >
                Print batch
                </a>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <h2 class="text-sm font-medium text-gray-700">Generate vouchers</h2>
                <form class="mt-3 flex items-end gap-3" @submit.prevent="submit">
                    <div>
                        <label class="block text-xs text-gray-500">Plan</label>
                        <select
                            v-model="form.plan_id"
                            class="mt-1 rounded-md border-gray-300 text-sm"
                        >
                            <option value="" disabled>Select plan</option>
                            <option v-for="p in plans" :key="p.id" :value="p.id">
                                {{ p.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Quantity</label>
                        <input
                            v-model.number="form.quantity"
                            type="number"
                            min="1"
                            max="500"
                            class="mt-1 w-24 rounded-md border-gray-300 text-sm"
                        />
                    </div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white disabled:opacity-50"
                    >
                        Generate
                    </button>
                </form>
                <p v-if="form.errors.plan_id" class="mt-2 text-sm text-red-600">{{ form.errors.plan_id }}</p>
                <p v-if="form.errors.quantity" class="mt-2 text-sm text-red-600">{{ form.errors.quantity }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="border-b border-gray-100 text-left text-gray-500">
                        <th class="px-4 py-2 font-medium">Code</th>
                        <th class="px-4 py-2 font-medium">Plan</th>
                        <th class="px-4 py-2 font-medium">Status</th>
                        <th class="px-4 py-2 font-medium">Batch</th>
                        <th class="px-4 py-2 font-medium">Created</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="v in vouchers.data" :key="v.id" class="border-b border-gray-50 last:border-0">
                        <td class="px-4 py-2 font-mono">{{ v.code }}</td>
                        <td class="px-4 py-2">{{ v.plan.name }}</td>
                        <td class="px-4 py-2">
                                <span class="rounded-full px-2 py-0.5 text-xs" :class="statusColor(v.status)">
                                    {{ v.status }}
                                </span>
                        </td>
                        <td class="px-4 py-2 text-gray-500">

                           <a v-if="v.batch_id"
                            :href="route('vouchers.print', v.batch_id)"
                            target="_blank"
                            class="underline"
                            >
                            {{ v.batch_id }}
                            </a>
                        </td>
                        <td class="px-4 py-2 text-gray-500">
                            {{ new Date(v.created_at).toLocaleString() }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex gap-2 text-sm">
                <Link
                    v-for="link in vouchers.links"
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
