<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'staff',
});

const submit = () => {
    form.post(route('admin.users.store'));
};
</script>

<template>
    <Head title="Add User" />
    <AuthenticatedLayout>
        <template #header>
            <Link :href="route('admin.users.index')" class="text-gray-500 hover:text-white transition text-sm">← Back to Users</Link>
            <h2 class="text-3xl font-extrabold tracking-tight text-white mt-2">Add <span class="g-gradient-text">User</span></h2>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-8">
                    <div class="g-card p-8 space-y-6">
                        <h3 class="text-lg font-bold text-white">User Information</h3>
                        <div>
                            <label class="g-label">Full Name *</label>
                            <input v-model="form.name" class="g-input" placeholder="e.g. Dr. James Carter" />
                            <p v-if="form.errors.name" class="text-rose-400 text-xs mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="g-label">Email Address *</label>
                            <input v-model="form.email" type="email" class="g-input" placeholder="e.g. user@school.edu" />
                            <p v-if="form.errors.email" class="text-rose-400 text-xs mt-1">{{ form.errors.email }}</p>
                        </div>
                        <div>
                            <label class="g-label">Password *</label>
                            <input v-model="form.password" type="password" class="g-input" placeholder="Minimum 8 characters" />
                            <p v-if="form.errors.password" class="text-rose-400 text-xs mt-1">{{ form.errors.password }}</p>
                        </div>
                        <div>
                            <label class="g-label">Role *</label>
                            <select v-model="form.role" class="g-input">
                                <option value="admin">Administrator</option>
                                <option value="staff">Teacher / Staff</option>
                                <option value="parent">Parent / Guardian</option>
                                <option value="student">Student</option>
                            </select>
                            <p class="text-[10px] text-gray-500 mt-2">
                                <template v-if="form.role === 'student'">Student accounts need to be linked to a Student Record in the Student Directory.</template>
                                <template v-else-if="form.role === 'parent'">Parent accounts can be linked to students in the Student Directory.</template>
                                <template v-else-if="form.role === 'staff'">Teachers can create assignments, manage grades, and log behavioral data.</template>
                                <template v-else>Administrators have full access to all system features.</template>
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <Link :href="route('admin.users.index')" class="g-btn-secondary text-center">Cancel</Link>
                        <button type="submit" :disabled="form.processing" class="g-btn-primary">
                            {{ form.processing ? 'Creating...' : 'Create User' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
