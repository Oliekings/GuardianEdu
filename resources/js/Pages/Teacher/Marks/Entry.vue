<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps<{
    schedule: any;
    students: any[];
}>();

// Initialize scores from existing marks if available
const initialMarks = {};
props.students.forEach(student => {
    const existingMark = props.schedule.marks?.find(m => m.student_id === student.id);
    initialMarks[student.id] = existingMark ? existingMark.marks_obtained : '';
});

const form = useForm({
    exam_schedule_id: props.schedule.id,
    marks: initialMarks,
});

const submit = () => {
    form.post(route('staff.marks.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Success feedback
        },
    });
};
</script>

<template>
    <Head title="Marks Entry" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('admin.exams.schedule.index', schedule.exam_id)" class="p-2 bg-white/5 rounded-xl text-gray-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </Link>
                <div>
                    <h2 class="text-3xl font-black text-white italic capitalize">Marks <span class="g-gradient-text">Input</span></h2>
                    <p class="text-gray-500 text-sm mt-1 uppercase tracking-widest font-black text-[10px]">{{ schedule.subject_name }} | Max: {{ schedule.max_marks }} | Pass: {{ schedule.passing_marks }}</p>
                </div>
            </div>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl">
                <form @submit.prevent="submit" class="space-y-8">
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                            <span class="text-xs font-black text-white uppercase tracking-widest italic font-mono">Academic Achievement Ledger</span>
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 bg-white/5 text-gray-400 rounded-full text-[10px] font-bold">{{ students.length }} Enrolled Evaluates</span>
                                <button 
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-6 py-2 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-500 transition shadow-lg shadow-emerald-600/20 active:scale-95"
                                >
                                    {{ form.processing ? 'Syncing...' : 'Save All Marks' }}
                                </button>
                            </div>
                        </div>

                        <div class="divide-y divide-white/5">
                            <div v-for="student in students" :key="student.id" 
                                class="p-6 flex items-center justify-between hover:bg-white/[0.01] transition group"
                            >
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-xs font-black text-gray-500 group-hover:text-emerald-500 transition">
                                        #{{ student.admission_id }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-white uppercase tracking-tight">{{ student.user?.name }}</h4>
                                        <p class="text-[10px] text-gray-500 uppercase font-bold">{{ student.section || 'General' }}</p>
                                    </div>
                                </div>

                                <div class="w-48 relative">
                                    <input 
                                        v-model="form.marks[student.id]" 
                                        type="number" 
                                        step="0.1"
                                        :max="schedule.max_marks"
                                        class="g-input text-right font-black text-xl pr-12 focus:border-emerald-500/50"
                                        placeholder="--"
                                    />
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-600 uppercase">/ {{ schedule.max_marks }}</span>
                                </div>
                            </div>
                        </div>

                        <div v-if="!students.length" class="p-20 text-center text-gray-600 italic text-sm">No students found for evaluation in this scoped branch.</div>
                    </div>

                    <div class="flex justify-end">
                        <button 
                            type="submit"
                            :disabled="form.processing"
                            class="px-10 py-4 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition shadow-2xl active:scale-95 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Finalizing Marks Ledger...' : 'Commit Evaluation Batch' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.g-card {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border-radius: 40px;
}
</style>
