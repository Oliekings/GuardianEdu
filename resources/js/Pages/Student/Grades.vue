<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    grades: any[];
    gpa: number;
    subjects: { subject: string; average: number; letter: string; count: number }[];
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
    <Head title="Grade Report" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">
                Grade <span class="g-gradient-text">Report</span>
            </h2>
            <p class="mt-1 text-sm text-gray-500">Your academic performance across all subjects.</p>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">

                <!-- GPA Header -->
                <div class="g-card p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-center md:text-left">
                        <div class="text-6xl font-black text-white">{{ gpa.toFixed(2) }}</div>
                        <div class="text-sm text-gray-400 mt-1">Cumulative GPA</div>
                    </div>
                    <div class="flex-1 max-w-md">
                        <div class="flex items-center gap-4 mb-2">
                            <span class="text-xs text-gray-500">0.0</span>
                            <div class="flex-1 h-3 bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-700"
                                     :style="{ width: Math.min((gpa / 4.0) * 100, 100) + '%' }"></div>
                            </div>
                            <span class="text-xs text-gray-500">4.0</span>
                        </div>
                        <div class="text-center">
                            <span v-if="gpa >= 3.8" class="g-badge bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">🏆 Dean's List</span>
                            <span v-else-if="gpa >= 3.5" class="g-badge bg-blue-500/10 text-blue-400 border border-blue-500/20">⭐ Honor Roll</span>
                            <span v-else-if="gpa >= 3.0" class="g-badge bg-amber-500/10 text-amber-400 border border-amber-500/20">📈 Good Standing</span>
                            <span v-else class="g-badge bg-rose-500/10 text-rose-400 border border-rose-500/20">📚 Needs Improvement</span>
                        </div>
                    </div>
                </div>

                <!-- Subject Overview Cards -->
                <div v-if="subjects.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="s in subjects" :key="s.subject" class="g-card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-base font-bold text-white">{{ s.subject }}</h4>
                            <span :class="gradeColor(s.average)" class="text-2xl font-black">{{ s.letter }}</span>
                        </div>
                        <div class="mb-2">
                            <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                <div :class="'h-full bg-gradient-to-r ' + gradeBg(s.average)" class="transition-all duration-500"
                                     :style="{ width: s.average + '%' }"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">{{ s.count }} grade{{ s.count > 1 ? 's' : '' }}</span>
                            <span :class="gradeColor(s.average)" class="font-bold">{{ s.average }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Detailed Grade Table -->
                <div class="g-card overflow-hidden">
                    <div class="p-6 border-b border-white/5">
                        <h3 class="text-lg font-bold text-white">Detailed Grades</h3>
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
                                    <td class="p-4 text-center">
                                        <span class="text-sm font-bold text-white">{{ g.score }}/{{ g.max_score }}</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span :class="gradeColor(g.percentage)" class="text-lg font-black">{{ g.letter_grade }}</span>
                                    </td>
                                    <td class="p-4 text-sm text-gray-400">{{ g.teacher }}</td>
                                    <td class="p-4 text-xs text-gray-500 max-w-xs truncate">{{ g.remarks || '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="p-16 text-center">
                        <div class="text-4xl mb-4">📊</div>
                        <p class="text-gray-500">No grades recorded yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
