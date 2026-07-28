<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    assignment: any;
    submissions: any[];
    missing_students: any[];
}>();

const gradingId = ref<number | null>(null);
const gradeForm = useForm({ score: 0, feedback: '' });

const openGrading = (sub: any) => {
    gradingId.value = sub.id;
    gradeForm.score = sub.score || 0;
    gradeForm.feedback = sub.feedback || '';
};

const submitGrade = () => {
    if (!gradingId.value) return;
    gradeForm.post(route('staff.submissions.grade', gradingId.value), {
        preserveScroll: true,
        onSuccess: () => { gradingId.value = null; },
    });
};

const togglePublish = () => {
    router.post(route('staff.assignments.toggle-publish', props.assignment.id), {}, { preserveScroll: true });
};
</script>

<template>
    <Head :title="assignment.title" />
    <AuthenticatedLayout>
        <template #header>
            <Link :href="route('staff.assignments.index')" class="text-gray-500 hover:text-white transition text-sm">← Assignments</Link>
            <div class="flex items-center justify-between mt-2">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-white">{{ assignment.title }}</h2>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="g-badge bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">{{ assignment.type }}</span>
                        <span class="text-sm text-gray-500">{{ assignment.subject }} • {{ assignment.room_id }} • {{ assignment.total_points }} pts</span>
                    </div>
                </div>
                <button @click="togglePublish"
                    :class="assignment.is_published ? 'bg-amber-500/10 text-amber-400 border-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold border transition hover:opacity-80">
                    {{ assignment.is_published ? 'Unpublish' : 'Publish' }}
                </button>
            </div>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">

                <!-- Submissions -->
                <div class="g-card overflow-hidden">
                    <div class="p-6 border-b border-white/5 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Submissions ({{ submissions.length }})</h3>
                    </div>

                    <div v-if="submissions.length">
                        <div v-for="sub in submissions" :key="sub.id" class="p-6 border-b border-white/5 hover:bg-white/[0.02] transition">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="text-sm font-bold text-white">{{ sub.student_name }}</h4>
                                    <p class="text-[10px] text-gray-500">{{ sub.admission_number }} • Submitted {{ new Date(sub.submitted_at).toLocaleString() }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span v-if="sub.is_graded" class="g-badge bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        {{ sub.score }}/{{ sub.max_score }} ({{ sub.letter_grade }})
                                    </span>
                                    <span v-else class="g-badge bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>
                                    <button @click="openGrading(sub)" class="px-4 py-2 bg-indigo-500/10 text-indigo-400 rounded-xl text-xs font-bold hover:bg-indigo-500/20 transition">
                                        {{ sub.is_graded ? 'Edit Grade' : 'Grade' }}
                                    </button>
                                </div>
                            </div>

                            <!-- Content Preview -->
                            <div v-if="sub.content" class="p-4 rounded-xl bg-white/[0.02] border border-white/5 text-sm text-gray-300 leading-relaxed max-h-32 overflow-y-auto">
                                {{ sub.content }}
                            </div>

                            <!-- Quiz Answers -->
                            <div v-if="sub.answers && assignment.questions" class="space-y-2 mt-2">
                                <div v-for="(q, qi) in assignment.questions" :key="qi" class="p-3 rounded-lg bg-white/[0.02] border border-white/5 text-sm">
                                    <span class="text-gray-500 text-xs">Q{{ qi + 1 }}:</span>
                                    <span class="text-white ml-2">
                                        <template v-if="q.type === 'mcq' && q.options">
                                            {{ q.options[sub.answers[qi]] || 'No answer' }}
                                            <span v-if="sub.answers[qi] === q.correct" class="text-emerald-400 ml-1">✓</span>
                                            <span v-else class="text-rose-400 ml-1">✗</span>
                                        </template>
                                        <template v-else>{{ sub.answers[qi] || 'No answer' }}</template>
                                    </span>
                                </div>
                            </div>

                            <a v-if="sub.file_path" :href="sub.file_path" target="_blank" class="inline-flex items-center gap-2 mt-3 text-xs text-indigo-400 hover:text-indigo-300">
                                📎 View Attached File
                            </a>
                        </div>
                    </div>
                    <div v-else class="p-12 text-center">
                        <p class="text-gray-500">No submissions yet.</p>
                    </div>
                </div>

                <!-- Missing Students -->
                <div v-if="missing_students.length" class="g-card p-6">
                    <h3 class="text-sm font-bold text-gray-400 mb-4">Missing Submissions ({{ missing_students.length }})</h3>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="s in missing_students" :key="s.id" class="px-3 py-1.5 rounded-lg bg-rose-500/5 border border-rose-500/10 text-xs text-rose-400">
                            {{ s.name }}
                        </span>
                    </div>
                </div>

                <!-- Grading Modal -->
                <div v-if="gradingId" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/90 backdrop-blur-xl" @click="gradingId = null"></div>
                    <div class="relative w-full max-w-lg bg-zinc-900 border border-white/10 rounded-[32px] overflow-hidden shadow-2xl p-8">
                        <h3 class="text-xl font-bold text-white mb-6">Grade Submission</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="g-label">Score (out of {{ assignment.total_points }})</label>
                                <input v-model.number="gradeForm.score" type="number" class="g-input" :max="assignment.total_points" min="0" />
                            </div>
                            <div>
                                <label class="g-label">Feedback</label>
                                <textarea v-model="gradeForm.feedback" rows="4" class="g-input" placeholder="Write feedback for the student..."></textarea>
                            </div>
                            <div class="flex gap-4">
                                <button @click="gradingId = null" class="g-btn-secondary">Cancel</button>
                                <button @click="submitGrade" :disabled="gradeForm.processing" class="g-btn-primary">
                                    {{ gradeForm.processing ? 'Saving...' : 'Save Grade' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
