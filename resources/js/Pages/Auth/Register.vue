<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register — GuardianEdu" />

        <div class="mb-8">
            <h3 class="text-2xl font-bold text-white mb-2">Request Access</h3>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-widest">Join the Ecosystem</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <label for="name" class="g-label">Full Name</label>
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    class="g-input"
                    placeholder="Enter your registered name..."
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <label for="email" class="g-label">Institutional Email</label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    class="g-input"
                    placeholder="Enter your email address..."
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="g-label">Secure Password</label>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        class="g-input"
                        placeholder="Create password..."
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>
                    <label for="password_confirmation" class="g-label">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        class="g-input"
                        placeholder="Verify password..."
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div class="pt-4">
                <button
                    :class="{ 'opacity-50 cursor-wait': form.processing }"
                    :disabled="form.processing"
                    class="g-btn-primary"
                >
                    {{ form.processing ? 'Creating Profile...' : 'Establish Secure Profile' }}
                </button>
            </div>
            
            <p class="text-center text-xs font-medium" style="color: var(--color-g-text-faint)">
                Already registered? <Link :href="route('login')" class="font-bold" style="color: var(--color-g-primary)">Log in here</Link>
            </p>
        </form>
    </GuestLayout>
</template>
