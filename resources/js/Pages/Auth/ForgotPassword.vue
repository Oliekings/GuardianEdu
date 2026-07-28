<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{ status?: string }>();

const form = useForm({ email: '' });

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password — GuardianEdu" />

        <div class="mb-8">
            <h3 class="text-2xl font-bold text-white mb-2">Recover Access</h3>
            <p class="text-xs leading-relaxed mt-2" style="color: var(--color-g-text-muted)">
                Forgot your secure password? Enter your email and we'll send a password reset link.
            </p>
        </div>

        <div v-if="status" class="mb-6 p-4 text-sm font-bold" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); border-radius: var(--radius-g-lg); color: var(--color-g-success)">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <label for="email" class="g-label">Registered Email</label>
                <input id="email" type="email" class="g-input" v-model="form.email" required autofocus autocomplete="username" placeholder="Enter your email address..." />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="pt-4 flex flex-col gap-4">
                <button :disabled="form.processing" :class="{ 'opacity-50': form.processing }" class="g-btn-primary">
                    {{ form.processing ? 'Transmitting...' : 'Send Recovery Link' }}
                </button>
                <Link :href="route('login')" class="g-btn-secondary text-center block">Return to Authentication</Link>
            </div>
        </form>
    </GuestLayout>
</template>
