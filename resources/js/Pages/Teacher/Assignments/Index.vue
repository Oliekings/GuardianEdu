<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    assignments: any[];
    filters: { type?: string };
}>();

const filterType = ref(props.filters.type || '');
const applyFilter = () => {
    router.get(route('staff.assignments.index'), { type: filterType.value || undefined }, { preserveState: true });
};

const statusLabel = (a: any) => {
    if (!a.is_published) return { text: 'Draft', class: 'bg-gray-500/10 text-gray-400 border-gray-500/20' };
    if (a.graded_count < a.submissions_count) return { text: `${a.submissions_count - a.graded_count} to grade`, class: 'bg-amber-500/10 text-amber-400 border-amber-500/20' };
    if (a.submissions_count > 0) return { text: 'All graded', class: 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' };
    return { text: 'Published', class: 'bg-blue-500/10 text-blue-400 border-blue-500/20' };
};
</script>

<template>
    <Head title="My Assignments" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-white">Assignment <span class="g-gradient-text">Manager</span></h2>
                    <p class="mt-1 text-sm text-gray-500">Create, manage, and grade assignments, tests, and quizzes.</p>
                </div>
                <Link :href="route('staff.assignments.create')" class="px-6 py-3 bg-indigo-600 rounded-2xl text-sm font-bold text-white shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 transition">
                    + New Assignment
                </Link>
            </div>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <div class="flex gap-4">
                    <select v-model="filterType" @change="applyFilter" class="g-input !w-auto !py-2 !px-4 !rounded-full text-sm">
                        <option value="">All Types</option>
                        <option value="assignment">Assignments</option>
                        <option value="quiz">Quizzes</option>
                        <option value="test">Tests</option>
                    </select>
                </div>

                <div v-if="assignments.length" class="space-y-4">
                    <Link v-for="a in assignments" :key="a.id" :href="route('staff.assignments.show', a.id)"
                        class="g-card p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-indigo-500/20 transition block">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <h4 class="text-base font-bold text-white truncate">{{ a.title }}</h4>
                                <span :class="'g-badge border ' + statusLabel(a).class">{{ statusLabel(a).text }}</span>
                            </div>
                            <p class="text-xs text-gray-500">{{ a.subject }} • {{ a.room_id }} • {{ a.type }} • {{ a.total_points }} pts</p>
                        </div>
                        <div class="flex items-center gap-6 text-sm shrink-0">
                            <div class="text-center">
                                <div class="text-lg font-bold text-white">{{ a.submissions_count }}</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-widest">Submitted</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold" :class="a.is_past_due ? 'text-rose-400' : 'text-gray-400'">{{ a.due_label }}</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-widest">Due</div>
                            </div>
                        </div>
                    </Link>
                </div>

                <div v-else class="g-card p-16 text-center">
                    <div class="text-5xl mb-6">📝</div>
                    <h3 class="text-xl font-bold text-white mb-2">No Assignments Yet</h3>
                    <p class="text-gray-500 mb-6">Create your first assignment to get started.</p>
                    <Link :href="route('staff.assignments.create')" class="g-btn-primary !w-auto !inline-block !px-8">Create Assignment</Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
