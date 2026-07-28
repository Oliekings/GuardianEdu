<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold" style="color: var(--color-g-text)">Profile Information</h2>
            <p class="mt-1 text-sm" style="color: var(--color-g-text-muted)">Update your account's profile information and email address.</p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div>
                <label for="name" class="g-label">Name</label>
                <input id="name" type="text" class="g-input" v-model="form.name" required autofocus autocomplete="name" />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <label for="email" class="g-label">Email</label>
                <input id="email" type="email" class="g-input" v-model="form.email" required autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm" style="color: var(--color-g-text)">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" as="button" class="text-sm underline transition" style="color: var(--color-g-primary)">
                        Click here to re-send the verification email.
                    </Link>
                </p>
                <div v-show="status === 'verification-link-sent'" class="mt-2 text-sm font-medium" style="color: var(--color-g-success)">
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button :disabled="form.processing" class="g-btn-primary" style="width: auto; padding: 0.75rem 2rem">Save</button>
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm" style="color: var(--color-g-success)">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
