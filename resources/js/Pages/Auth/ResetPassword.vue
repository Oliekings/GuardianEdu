<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password — GuardianEdu" />

        <div class="mb-8">
            <h3 class="text-2xl font-bold text-white mb-2">Set New Password</h3>
            <p class="text-xs font-medium uppercase tracking-widest" style="color: var(--color-g-text-muted)">Establish new secure credentials</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <label for="email" class="g-label">Email</label>
                <input id="email" type="email" class="g-input" v-model="form.email" required autofocus autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <label for="password" class="g-label">New Password</label>
                <input id="password" type="password" class="g-input" v-model="form.password" required autocomplete="new-password" placeholder="Create new password..." />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <label for="password_confirmation" class="g-label">Confirm Password</label>
                <input id="password_confirmation" type="password" class="g-input" v-model="form.password_confirmation" required autocomplete="new-password" placeholder="Verify password..." />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="pt-4">
                <button :disabled="form.processing" :class="{ 'opacity-50': form.processing }" class="g-btn-primary">
                    {{ form.processing ? 'Resetting...' : 'Reset Password' }}
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
