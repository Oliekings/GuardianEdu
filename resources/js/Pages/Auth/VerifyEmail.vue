<script setup lang="ts">
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{ status?: string }>();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification — GuardianEdu" />

        <div class="mb-8">
            <h3 class="text-2xl font-bold text-white mb-2">Verify Email</h3>
            <p class="text-xs leading-relaxed" style="color: var(--color-g-text-muted)">
                Thanks for joining the ecosystem! Before getting started, please verify your email address by clicking the link we just sent. If you didn't receive the email, we'll gladly send another.
            </p>
        </div>

        <div v-if="verificationLinkSent" class="mb-6 p-4 text-sm font-bold" style="background: var(--color-g-success); background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); border-radius: var(--radius-g-lg); color: var(--color-g-success)">
            A new verification link has been sent to your email address.
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <button :disabled="form.processing" :class="{ 'opacity-50': form.processing }" class="g-btn-primary">
                {{ form.processing ? 'Sending...' : 'Resend Verification Email' }}
            </button>

            <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="g-btn-secondary block text-center"
            >
                Log Out
            </Link>
        </form>
    </GuestLayout>
</template>
