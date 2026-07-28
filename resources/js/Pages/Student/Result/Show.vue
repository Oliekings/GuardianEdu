<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    results: any[];
}>();

const getGradeColor = (grade: string) => {
    if (['A+', 'A', 'A-'].includes(grade)) return 'text-emerald-400';
    if (['B+', 'B', 'B-'].includes(grade)) return 'text-indigo-400';
    if (['C+', 'C'].includes(grade)) return 'text-amber-400';
    return 'text-rose-400';
};
</script>

<template>
    <Head title="Academic Results" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white italic">Academic <span class="g-gradient-text">Performance</span></h2>
            <p class="text-gray-500 text-sm mt-1 uppercase tracking-widest font-black text-[10px]">Series Evaluation & Formal Transcripts</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl space-y-12">
                <div v-for="exam in results" :key="exam.id" 
                    class="g-card overflow-hidden group border-t-4 border-indigo-500 shadow-2xl shadow-indigo-500/10"
                >
                    <div class="p-8 border-b border-white/5 bg-white/[0.02] flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <h3 class="text-2xl font-black text-white uppercase">{{ exam.name }}</h3>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.2em] mt-1">{{ exam.term }} | Session {{ exam.session }}</p>
                        </div>
                        
                        <div class="flex items-center gap-8">
                            <div class="text-right">
                                <div class="text-xs font-black text-gray-500 uppercase">Grade</div>
                                <div :class="getGradeColor(exam.grade)" class="text-3xl font-black italic">{{ exam.grade }}</div>
                            </div>
                            <div class="h-12 w-[1px] bg-white/10 hidden md:block"></div>
                            <div class="text-right">
                                <div class="text-xs font-black text-gray-500 uppercase">Aggregate</div>
                                <div class="text-3xl font-black text-white">{{ exam.percentage }}%</div>
                            </div>
                            <a 
                                :href="route('student.results.download', exam.id)"
                                class="ml-4 px-6 py-4 bg-white text-black rounded-3xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition shadow-xl active:scale-95"
                            >
                                Download Marksheet
                            </a>
                        </div>
                    </div>

                    <div class="p-8">
                        <table class="w-full text-left font-mono">
                            <thead>
                                <tr class="text-[10px] text-gray-500 uppercase font-black tracking-widest">
                                    <th class="pb-4">Subject</th>
                                    <th class="pb-4 text-center">Max Marks</th>
                                    <th class="pb-4 text-center">Min Pass</th>
                                    <th class="pb-4 text-right">Obtained</th>
                                    <th class="pb-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="sch in exam.schedules" :key="sch.id" class="group/row">
                                    <td class="py-4 font-black text-white uppercase text-xs group-hover/row:translate-x-2 transition duration-300">
                                        {{ sch.subject_name }}
                                    </td>
                                    <td class="py-4 text-center text-xs text-gray-500">{{ sch.max_marks }}</td>
                                    <td class="py-4 text-center text-xs text-gray-500">{{ sch.passing_marks }}</td>
                                    <td class="py-4 text-right font-black text-white">
                                        {{ sch.marks?.[0]?.marks_obtained || '--' }}
                                    </td>
                                    <td class="py-4 text-right">
                                        <span v-if="sch.marks?.[0]" 
                                            :class="sch.marks[0].marks_obtained >= sch.passing_marks ? 'text-emerald-500' : 'text-rose-500'"
                                            class="text-[10px] font-black uppercase tracking-widest"
                                        >
                                            {{ sch.marks[0].marks_obtained >= sch.passing_marks ? 'Passed' : 'Failed' }}
                                        </span>
                                        <span v-else class="text-[10px] font-black text-gray-700 uppercase italic">Pending</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="px-8 py-6 bg-indigo-500/5 border-t border-white/5 flex justify-between items-center">
                        <div class="flex gap-12">
                            <div class="text-[10px] font-black text-gray-500 uppercase">Total Obtained: <span class="text-white">{{ exam.total_obtained }}</span></div>
                            <div class="text-[10px] font-black text-gray-500 uppercase">Grand Total: <span class="text-white">{{ exam.total_max }}</span></div>
                        </div>
                        <div class="text-[9px] font-bold text-gray-600 uppercase italic">System Generated Digital Transcript</div>
                    </div>
                </div>

                <div v-if="!results.length" class="p-40 text-center g-card">
                    <h3 class="text-2xl font-black text-gray-700 uppercase italic tracking-widest">Awaiting Publication</h3>
                    <p class="text-gray-800 mt-2">Evaluation results will manifest here once formalized by the administration.</p>
                </div>
            </div>
        </div>
    </ArtifactLayout>
</template>

<style scoped>
.g-card {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border-radius: 40px;
}
</style>
