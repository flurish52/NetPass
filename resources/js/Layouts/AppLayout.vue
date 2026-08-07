<script setup lang="ts">
import { Link, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps<{
    title?: string;
}>();

const page = usePage();

const user = computed(() => page.props.auth?.user as { name: string } | undefined);
const role = computed(() => page.props.auth?.role as string | undefined);
const permissions = computed(() => (page.props.auth?.permissions as string[]) ?? []);

const can = (permission: string) => permissions.value.includes(permission);

const navigation = computed(() => {
    if (role.value === 'admin') {
        return [{ label: 'Dashboard', href: '/dashboard' }];
    }

    const items = [{ label: 'Dashboard', href: '/dashboard' }];

    if (can('manage vouchers')) items.push({ label: 'Vouchers', href: '/vouchers' });
    if (can('view sessions')) items.push({ label: 'Sessions', href: '/sessions' });
    if (can('manage plans')) items.push({ label: 'Plans', href: '/plans' });
    if (can('manage staff')) items.push({ label: 'Add staff', href: '/staff' });

    items.push({ label: 'Billing', href: '/billing' }); // everyone with a business can view billing

    return items;
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Head v-if="title" :title="title" />

        <nav class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-3">
                <div class="flex items-center gap-8">
                    <Link href="/dashboard" class="font-semibold text-gray-900">NetPass</Link>

                    <div class="flex items-center gap-4">
                        <Link
                            v-for="item in navigation"
                            :key="item.href"
                            :href="item.href"
                            class="text-sm text-gray-600 hover:text-gray-900 p-1 hover:bg-gray-200 rounded-md"
                        >
                            {{ item.label }}
                        </Link>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <span v-if="user" class="text-sm text-gray-500">{{ user.name }}</span>
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="text-sm text-gray-600 hover:text-gray-900"
                    >
                        Log out
                    </Link>
                </div>
            </div>
        </nav>

        <main>
            <slot />
        </main>
    </div>
</template>
