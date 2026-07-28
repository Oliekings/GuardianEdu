<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    users: any[];
    filters: { search?: string; role?: string };
}>();

const search = ref(props.filters.search || '');
const roleFilter = ref(props.filters.role || '');

const applyFilter = () => {
    router.get(route('admin.users.index'), {
        search: search.value || undefined,
        role: roleFilter.value || undefined,
    }, { preserveState: true });
};

const toggleSuspend = (id: number) => {
    router.post(route('admin.users.toggle-suspend', id), {}, { preserveScroll: true });
};

const roleColor = (role: string) => {
    switch (role) {
        case 'admin': return 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20';
        case 'staff': return 'bg-purple-500/10 text-purple-400 border-purple-500/20';
        case 'parent': return 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
        case 'student': return 'bg-blue-500/10 text-blue-400 border-blue-500/20';
        default: return 'bg-gray-500/10 text-gray-400 border-gray-500/20';
    }
};
</script>

<template>
    <Head title="User Management" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-white">User <span class="g-gradient-text">Management</span></h2>
                    <p class="mt-1 text-sm text-gray-500">Manage all system accounts — administrators, teachers, parents, and students.</p>
                </div>
                <Link :href="route('admin.users.create')" class="px-6 py-3 bg-indigo-600 rounded-2xl text-sm font-bold text-white shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 transition">
                    + Add User
                </Link>
            </div>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <div class="flex flex-wrap gap-4 items-center">
                    <div class="relative flex-1 max-w-md">
                        <input v-model="search" @keyup.enter="applyFilter" class="g-input !py-2.5 !pl-10 !rounded-full text-sm" placeholder="Search by name or email..." />
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <select v-model="roleFilter" @change="applyFilter" class="g-input !w-auto !py-2 !px-4 !rounded-full text-sm">
                        <option value="">All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Teacher</option>
                        <option value="parent">Parent</option>
                        <option value="student">Student</option>
                    </select>
                    <button @click="applyFilter" class="px-5 py-2.5 bg-white/5 border border-white/10 rounded-full text-xs font-bold text-gray-400 hover:bg-white/10 transition">Search</button>
                </div>

                <div class="g-card overflow-hidden">
                    <div v-if="users.length" class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/5">
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Name</th>
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Email</th>
                                    <th class="text-center p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Role</th>
                                    <th class="text-center p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Status</th>
                                    <th class="text-center p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="u in users" :key="u.id" class="border-b border-white/5 hover:bg-white/[0.02] transition">
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                                                {{ u.name.charAt(0) }}
                                            </div>
                                            <span class="text-sm font-semibold text-white">{{ u.name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm text-gray-400">{{ u.email }}</td>
                                    <td class="p-4 text-center">
                                        <span :class="'g-badge border ' + roleColor(u.role)">{{ u.role === 'staff' ? 'teacher' : u.role }}</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span v-if="u.is_suspended" class="g-badge bg-rose-500/10 text-rose-400 border border-rose-500/20">Suspended</span>
                                        <span v-else class="g-badge bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Active</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <button @click="toggleSuspend(u.id)"
                                            :class="u.is_suspended ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400'"
                                            class="px-3 py-1.5 rounded-lg text-xs font-bold hover:opacity-80 transition">
                                            {{ u.is_suspended ? 'Reactivate' : 'Suspend' }}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="p-16 text-center">
                        <div class="text-5xl mb-6">👥</div>
                        <h3 class="text-xl font-bold text-white mb-2">No Users Found</h3>
                        <p class="text-gray-500">Adjust your search or add a new user.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
