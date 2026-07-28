<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    student: { id: number; name: string; room_id: string; admission_number: string; gpa: number };
    grades: any[];
    subjects: { subject: string; average: number; letter: string; count: number }[];
    submissions: any[];
}>();

const gradeColor = (pct: number) => {
    if (pct >= 90) return 'text-emerald-400';
    if (pct >= 80) return 'text-blue-400';
    if (pct >= 70) return 'text-amber-400';
    return 'text-rose-400';
};

const gradeBg = (pct: number) => {
    if (pct >= 90) return 'from-emerald-500 to-emerald-400';
    if (pct >= 80) return 'from-blue-500 to-blue-400';
    if (pct >= 70) return 'from-amber-500 to-amber-400';
    return 'from-rose-500 to-rose-400';
};
</script>

<template>
    <Head :title="student.name + ' — Grades'" />
    <AuthenticatedLayout>
        <template #header>
            <Link :href="route('parent.dashboard')" class="text-gray-500 hover:text-white transition text-sm">← Back to Dashboard</Link>
            <div class="flex items-center gap-4 mt-2">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-black">
                    {{ student.name.charAt(0) }}
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-white">{{ student.name }}'s <span class="g-gradient-text">Grades</span></h2>
                    <p class="text-sm text-gray-500">{{ student.admission_number }} • {{ student.room_id }} • GPA: {{ student.gpa.toFixed(2) }}</p>
                </div>
            </div>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                <!-- Subject Cards -->
                <div v-if="subjects.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="s in subjects" :key="s.subject" class="g-card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-base font-bold text-white">{{ s.subject }}</h4>
                            <span :class="gradeColor(s.average)" class="text-2xl font-black">{{ s.letter }}</span>
                        </div>
                        <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden mb-2">
                            <div :class="'h-full bg-gradient-to-r ' + gradeBg(s.average)" class="transition-all duration-500" :style="{ width: s.average + '%' }"></div>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">{{ s.count }} grade{{ s.count > 1 ? 's' : '' }}</span>
                            <span :class="gradeColor(s.average)" class="font-bold">{{ s.average }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Grade Table -->
                <div class="g-card overflow-hidden">
                    <div class="p-6 border-b border-white/5">
                        <h3 class="text-lg font-bold text-white">All Grades</h3>
                    </div>
                    <div v-if="grades.length" class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/5">
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Subject</th>
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Term</th>
                                    <th class="text-center p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Score</th>
                                    <th class="text-center p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Grade</th>
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Teacher</th>
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="g in grades" :key="g.id" class="border-b border-white/5 hover:bg-white/[0.02] transition">
                                    <td class="p-4 text-sm font-semibold text-white">{{ g.subject }}</td>
                                    <td class="p-4 text-sm text-gray-400">{{ g.term }}</td>
                                    <td class="p-4 text-center text-sm font-bold text-white">{{ g.score }}/{{ g.max_score }}</td>
                                    <td class="p-4 text-center"><span :class="gradeColor(g.percentage)" class="text-lg font-black">{{ g.letter_grade }}</span></td>
                                    <td class="p-4 text-sm text-gray-400">{{ g.teacher }}</td>
                                    <td class="p-4 text-xs text-gray-500 max-w-xs truncate">{{ g.remarks || '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="p-16 text-center"><p class="text-gray-500">No grades recorded yet.</p></div>
                </div>

                <!-- Recent Assignment Scores -->
                <div v-if="submissions.length" class="g-card overflow-hidden">
                    <div class="p-6 border-b border-white/5">
                        <h3 class="text-lg font-bold text-white">Recent Assignment Scores</h3>
                    </div>
                    <div class="divide-y divide-white/5">
                        <div v-for="s in submissions" :key="s.title" class="p-5 flex items-center justify-between hover:bg-white/[0.02] transition">
                            <div>
                                <h4 class="text-sm font-semibold text-white">{{ s.title }}</h4>
                                <p class="text-[10px] text-gray-500 mt-0.5">{{ s.subject }} • {{ s.type }}</p>
                            </div>
                            <div class="text-right">
                                <span :class="gradeColor(s.percentage || 0)" class="text-lg font-black">{{ s.letter_grade }}</span>
                                <p class="text-[10px] text-gray-500">{{ s.score }}/{{ s.max_score }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
