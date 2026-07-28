<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({ password: '' });

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-bold" style="color: var(--color-g-text)">Delete Account</h2>
            <p class="mt-1 text-sm" style="color: var(--color-g-text-muted)">
                Once your account is deleted, all of its resources and data will be permanently deleted.
            </p>
        </header>

        <button @click="confirmUserDeletion" class="g-btn-danger">Delete Account</button>

        <!-- Modal -->
        <div v-if="confirmingUserDeletion" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 backdrop-blur-xl" style="background: rgba(0,0,0,0.85)" @click="closeModal"></div>
            <div class="relative w-full max-w-lg overflow-hidden" style="background: var(--color-g-bg-base); border: 1px solid var(--color-g-border); border-radius: var(--radius-g-2xl)">
                <div class="p-8">
                    <h2 class="text-lg font-bold" style="color: var(--color-g-text)">Are you sure you want to delete your account?</h2>
                    <p class="mt-2 text-sm" style="color: var(--color-g-text-muted)">
                        Please enter your password to confirm you would like to permanently delete your account.
                    </p>

                    <div class="mt-6">
                        <label for="password" class="g-label sr-only">Password</label>
                        <input id="password" ref="passwordInput" v-model="form.password" type="password" class="g-input" placeholder="Password" @keyup.enter="deleteUser" />
                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <button @click="closeModal" class="g-btn-secondary" style="width: auto; padding: 0.75rem 1.5rem">Cancel</button>
                        <button @click="deleteUser" :class="{ 'opacity-50': form.processing }" :disabled="form.processing" class="g-btn-danger">Delete Account</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
