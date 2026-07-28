<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps<{
    schools: any[];
    activeSchoolId: number | null;
}>();

const switchBranch = (schoolId: number) => {
    router.post(route('super_admin.switch-school', schoolId));
};
</script>

<template>
    <Head title="Branch Management" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Multi-Branch <span class="g-gradient-text">Management</span></h2>
            <p class="text-gray-500 text-sm mt-1">Super Admin Overview of all registered school institutions.</p>
        </template>

        <div class="py-12 px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <StatCard title="Total Branches" :value="schools.length" />
                    <StatCard title="Active Branch" :value="schools.find(s => s.id === activeSchoolId)?.name || 'System Default'" colorClass="text-indigo-400" />
                </div>

                <div class="g-card overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/[0.02]">
                                <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">School Name</th>
                                <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Students</th>
                                <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Users</th>
                                <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="school in schools" :key="school.id" class="border-b border-white/5 hover:bg-white/[0.02] transition">
                                <td class="p-4">
                                    <div class="font-bold text-white">{{ school.name }}</div>
                                    <div class="text-xs text-gray-500">{{ school.domain || 'no-domain.edu' }}</div>
                                </td>
                                <td class="p-4 font-mono text-indigo-400">{{ school.students_count }}</td>
                                <td class="p-4 font-mono text-purple-400">{{ school.users_count }}</td>
                                <td class="p-4 text-right">
                                    <button 
                                        @click="switchBranch(school.id)"
                                        :disabled="school.id === activeSchoolId"
                                        class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest transition"
                                        :class="school.id === activeSchoolId ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-white/10 text-white hover:bg-white/20 border border-white/10'"
                                    >
                                        {{ school.id === activeSchoolId ? 'Active' : 'Switch Into' }}
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
