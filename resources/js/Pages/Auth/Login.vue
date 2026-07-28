<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in — GuardianEdu" />

        <div v-if="status" class="mb-6 p-4 text-sm font-bold" style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); border-radius: var(--radius-g-lg); color: var(--color-g-success)">
            {{ status }}
        </div>

        <div class="mb-8">
            <h3 class="text-2xl font-bold text-white mb-2">Welcome Back</h3>
            <p class="text-xs font-medium uppercase tracking-widest" style="color: var(--color-g-text-faint)">Secure Portal Authentication</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <label for="email" class="g-label">Institutional Email</label>
                <input id="email" type="email" class="g-input" v-model="form.email" required autofocus autocomplete="username" placeholder="Enter your email address..." />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="g-label" style="margin-bottom: 0">Password</label>
                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-[10px] font-bold uppercase tracking-widest transition" style="color: var(--color-g-primary)">Forgot?</Link>
                </div>
                <input id="password" type="password" class="g-input" v-model="form.password" required autocomplete="current-password" placeholder="Enter your password..." />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="pt-4">
                <button :disabled="form.processing" :class="{ 'opacity-50 cursor-wait': form.processing }" class="g-btn-primary">
                    {{ form.processing ? 'Authenticating...' : 'Secure Authorization' }}
                </button>
            </div>
            
            <p class="text-center text-xs font-medium" style="color: var(--color-g-text-faint)">
                New to the institution? <Link :href="route('register')" class="font-bold" style="color: var(--color-g-primary)">Request Access</Link>
            </p>
        </form>
    </GuestLayout>
</template>
