<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

interface Plan {
    id: number;
    name: string;
    price: number;
    duration_minutes: number;
    speed_limit_mbps: number | null;
    data_limit_mb: number | null;
    is_active: boolean;
}

const props = defineProps<{ plans: Plan[] }>();

const showForm = ref(false);

const form = useForm({
    name: '',
    price: 0,
    duration_minutes: 60,
    speed_limit_mbps: null as number | null,
    data_limit_mb: null as number | null,
    is_active: true,
});

const submit = () => {
    form.post(route('plans.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
};

const destroy = (plan: Plan) => {
    if (!confirm(`Delete plan "${plan.name}"?`)) return;
    useForm({}).delete(route('plans.destroy', plan.id), { preserveScroll: true });
};

const nairaPrice = (kobo: number) => (kobo / 100).toLocaleString('en-NG', {
    style: 'currency',
    currency: 'NGN',
});
</script>

<template>
    <AppLayout title="Plans">
        <div class="mx-auto max-w-4xl space-y-6 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Plans</h1>
                <button
                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white"
                    @click="showForm = !showForm"
                >
                    {{ showForm ? 'Cancel' : 'New plan' }}
                </button>
            </div>

            <form
                v-if="showForm"
                class="space-y-4 rounded-lg border border-gray-200 bg-white p-4"
                @submit.prevent="submit"
            >
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500">Name</label>
                        <input v-model="form.name" type="text" placeholder="e.g. Daily 200" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Price (₦)</label>
                        <input
                            :value="form.price / 100"
                            @input="form.price = Math.round(Number(($event.target as HTMLInputElement).value) * 100)"
                            type="number"
                            min="0"
                            step="1"
                            class="mt-1 w-full rounded-md border-gray-300 text-sm"
                        />
                        <p v-if="form.errors.price" class="mt-1 text-sm text-red-600">{{ form.errors.price }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Duration (minutes)</label>
                        <input v-model.number="form.duration_minutes" type="number" min="1" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                        <p v-if="form.errors.duration_minutes" class="mt-1 text-sm text-red-600">{{ form.errors.duration_minutes }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Speed limit (Mbps, optional)</label>
                        <input v-model.number="form.speed_limit_mbps" type="number" min="1" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Data limit (MB, optional)</label>
                        <input v-model.number="form.data_limit_mb" type="number" min="1" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                    </div>
                </div>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white disabled:opacity-50"
                >
                    Create plan
                </button>
            </form>

            <div class="rounded-lg border border-gray-200 bg-white">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="border-b border-gray-100 text-left text-gray-500">
                        <th class="px-4 py-2 font-medium">Name</th>
                        <th class="px-4 py-2 font-medium">Price</th>
                        <th class="px-4 py-2 font-medium">Duration</th>
                        <th class="px-4 py-2 font-medium">Status</th>
                        <th class="px-4 py-2 font-medium"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="p in plans" :key="p.id" class="border-b border-gray-50 last:border-0">
                        <td class="px-4 py-2">{{ p.name }}</td>
                        <td class="px-4 py-2">{{ nairaPrice(p.price) }}</td>
                        <td class="px-4 py-2">{{ p.duration_minutes }} min</td>
                        <td class="px-4 py-2">
                                <span
                                    class="rounded-full px-2 py-0.5 text-xs"
                                    :class="p.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                >
                                    {{ p.is_active ? 'active' : 'inactive' }}
                                </span>
                        </td>
                        <td class="px-4 py-2 text-right">
                            <button class="text-sm text-red-600 hover:underline" @click="destroy(p)">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <tr v-if="plans.length === 0">
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                            No plans yet — create one above to start generating vouchers.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
