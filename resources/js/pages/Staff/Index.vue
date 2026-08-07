<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, ref } from 'vue';

interface StaffMember {
    id: number;
    name: string;
    email: string;
    created_at: string;
}

defineProps<{ staff: StaffMember[] }>();

const page = usePage();
const credentials = computed(() => page.props.flash?.generatedCredentials as
    | { email: string; password: string }
    | undefined);

const showForm = ref(false);

const form = useForm({
    name: '',
    email: '',
});

const submit = () => {
    form.post(route('staff.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
};

const destroy = (member: StaffMember) => {
    if (!confirm(`Remove ${member.name} from staff?`)) return;
    useForm({}).delete(route('staff.destroy', member.id), { preserveScroll: true });
};
</script>

<template>
    <AppLayout title="Staff">
        <div class="mx-auto max-w-3xl space-y-6 p-6">
            <div
                v-if="credentials"
                class="rounded-lg border border-green-300 bg-green-50 p-4 text-sm text-green-800"
            >
                <p class="font-medium">Staff account created. Send these to them now — the password won't be shown again:</p>
                <p class="mt-2 font-mono">{{ credentials.email }}</p>
                <p class="font-mono">{{ credentials.password }}</p>
            </div>

            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Staff</h1>
                <button
                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white"
                    @click="showForm = !showForm"
                >
                    {{ showForm ? 'Cancel' : 'Add staff' }}
                </button>
            </div>

            <form
                v-if="showForm"
                class="space-y-4 rounded-lg border border-gray-200 bg-white p-4"
                @submit.prevent="submit"
            >
                <div>
                    <label class="block text-xs text-gray-500">Name</label>
                    <input v-model="form.name" type="text" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Email</label>
                    <input v-model="form.email" type="email" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white disabled:opacity-50"
                >
                    Create staff account
                </button>
            </form>

            <div class="rounded-lg border border-gray-200 bg-white">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="border-b border-gray-100 text-left text-gray-500">
                        <th class="px-4 py-2 font-medium">Name</th>
                        <th class="px-4 py-2 font-medium">Email</th>
                        <th class="px-4 py-2 font-medium">Added</th>
                        <th class="px-4 py-2 font-medium"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="s in staff" :key="s.id" class="border-b border-gray-50 last:border-0">
                        <td class="px-4 py-2">{{ s.name }}</td>
                        <td class="px-4 py-2">{{ s.email }}</td>
                        <td class="px-4 py-2 text-gray-500">{{ new Date(s.created_at).toLocaleString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                        }) }}</td>
                        <td class="px-4 py-2 text-right">
                            <button class="text-sm text-red-600 hover:underline" @click="destroy(s)">
                                Remove
                            </button>
                        </td>
                    </tr>
                    <tr v-if="staff.length === 0">
                        <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                            No staff yet.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
