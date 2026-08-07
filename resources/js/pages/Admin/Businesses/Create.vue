<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = useForm({
    business_name: '',
    name: '',
    email: '',
});

const submit = () => {
    form.post(route('admin.businesses.store'));
};
</script>

<template>
    <AppLayout title="New Business">
        <div class="mx-auto max-w-lg p-6">
            <h1 class="text-xl font-semibold">New business</h1>
            <p class="mt-1 text-sm text-gray-500">
                Creates the business and an owner login. A password is generated automatically —
                you'll get it once on the next screen to send to the customer.
            </p>

            <form class="mt-6 space-y-4" @submit.prevent="submit">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Business name</label>
                    <input
                        v-model="form.business_name"
                        type="text"
                        class="mt-1 w-full rounded-md border-gray-300 text-sm"
                    />
                    <p v-if="form.errors.business_name" class="mt-1 text-sm text-red-600">
                        {{ form.errors.business_name }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Owner name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="mt-1 w-full rounded-md border-gray-300 text-sm"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                        {{ form.errors.name }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Owner email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="mt-1 w-full rounded-md border-gray-300 text-sm"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                        {{ form.errors.email }}
                    </p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white disabled:opacity-50"
                    >
                        Create business
                    </button>
                    <Link href="/admin/businesses" class="text-sm text-gray-500">Cancel</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
