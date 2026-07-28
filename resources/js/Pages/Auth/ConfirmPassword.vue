<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({ password: '' });

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password — GuardianEdu" />

        <div class="mb-8">
            <h3 class="text-2xl font-bold text-white mb-2">Security Checkpoint</h3>
            <p class="text-xs font-medium uppercase tracking-widest" style="color: var(--color-g-text-muted)">
                This is a secure area. Please confirm your password before continuing.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <label for="password" class="g-label">Password</label>
                <input id="password" type="password" class="g-input" v-model="form.password" required autocomplete="current-password" autofocus placeholder="Confirm your password..." />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="pt-4">
                <button :disabled="form.processing" :class="{ 'opacity-50': form.processing }" class="g-btn-primary">
                    {{ form.processing ? 'Verifying...' : 'Confirm Identity' }}
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
