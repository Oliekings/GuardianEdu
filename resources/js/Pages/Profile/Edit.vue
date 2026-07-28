<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Profile — GuardianEdu" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">
                Account <span class="g-gradient-text">Settings</span>
            </h2>
            <p class="mt-1 text-sm" style="color: var(--color-g-text-muted)">Manage your profile, credentials, and account security.</p>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl space-y-8 sm:px-6 lg:px-8">
                <div class="g-card p-8">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="g-card p-8">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="g-card p-8">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
