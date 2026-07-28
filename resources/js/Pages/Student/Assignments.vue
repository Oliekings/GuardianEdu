<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    assignments: any[];
    subjects: string[];
    filters: { type?: string; subject?: string };
}>();

const filterType = ref(props.filters.type || '');
const filterSubject = ref(props.filters.subject || '');

const applyFilter = () => {
    router.get(route('student.assignments.index'), {
        type: filterType.value || undefined,
        subject: filterSubject.value || undefined,
    }, { preserveState: true });
};

const statusColor = (status: string) => {
    switch (status) {
        case 'graded': return 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
        case 'submitted': return 'bg-blue-500/10 text-blue-400 border-blue-500/20';
        case 'overdue': return 'bg-rose-500/10 text-rose-400 border-rose-500/20';
        default: return 'bg-amber-500/10 text-amber-400 border-amber-500/20';
    }
};

const typeLabel = (type: string) => {
    switch (type) {
        case 'quiz': return '⚡ Quiz';
        case 'test': return '📝 Test';
        default: return '📄 Assignment';
    }
};
</script>

<template>
    <Head title="My Assignments" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">
                My <span class="g-gradient-text">Assignments</span>
            </h2>
            <p class="mt-1 text-sm text-gray-500">All assignments, quizzes, and tests for your classes.</p>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <!-- Filters -->
                <div class="flex flex-wrap gap-4 items-center">
                    <select v-model="filterType" @change="applyFilter" class="g-input !w-auto !py-2 !px-4 !rounded-full text-sm">
                        <option value="">All Types</option>
                        <option value="assignment">Assignments</option>
                        <option value="quiz">Quizzes</option>
                        <option value="test">Tests</option>
                    </select>
                    <select v-model="filterSubject" @change="applyFilter" class="g-input !w-auto !py-2 !px-4 !rounded-full text-sm">
                        <option value="">All Subjects</option>
                        <option v-for="s in subjects" :key="s" :value="s">{{ s }}</option>
                    </select>
                </div>

                <!-- Assignment Grid -->
                <div v-if="assignments.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link v-for="a in assignments" :key="a.id"
                        :href="route('student.assignments.show', a.id)"
                        class="g-card p-6 hover:border-indigo-500/30 transition-all group block">

                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-400">{{ typeLabel(a.type) }}</span>
                            <span :class="'g-badge border ' + statusColor(a.status)">{{ a.status }}</span>
                        </div>

                        <h4 class="text-base font-bold text-white mb-2 group-hover:text-indigo-400 transition">{{ a.title }}</h4>
                        <p class="text-xs text-gray-500 mb-4">{{ a.subject }} • {{ a.teacher }}</p>

                        <div class="flex items-center justify-between pt-4 border-t border-white/5">
                            <span class="text-xs text-gray-400">{{ a.total_points }} pts</span>
                            <span :class="a.is_past_due && a.status === 'pending' ? 'text-rose-500' : 'text-gray-500'" class="text-xs font-medium">
                                {{ a.due_label }}
                            </span>
                        </div>

                        <!-- Score if graded -->
                        <div v-if="a.status === 'graded'" class="mt-4 p-3 rounded-xl bg-emerald-500/5 border border-emerald-500/10">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-emerald-400 font-bold">Score</span>
                                <span class="text-lg font-black text-emerald-400">{{ a.score }}/{{ a.max_score }}</span>
                            </div>
                        </div>
                    </Link>
                </div>

                <div v-else class="g-card p-16 text-center">
                    <div class="text-5xl mb-6">📚</div>
                    <h3 class="text-xl font-bold text-white mb-2">No Assignments Found</h3>
                    <p class="text-gray-500">Check back later or adjust your filters.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
