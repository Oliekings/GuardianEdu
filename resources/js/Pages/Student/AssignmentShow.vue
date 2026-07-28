<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps<{
    assignment: {
        id: number;
        title: string;
        subject: string;
        type: string;
        description: string;
        due_at: string | null;
        total_points: number;
        time_limit: number | null;
        questions: any[] | null;
        is_past_due: boolean;
        teacher: string;
    };
    submission: {
        id: number;
        content: string | null;
        answers: any[] | null;
        file_path: string | null;
        score: number | null;
        max_score: number | null;
        feedback: string | null;
        submitted_at: string | null;
        graded_at: string | null;
        letter_grade: string | null;
    } | null;
}>();

const isSubmitted = computed(() => !!props.submission);
const isGraded = computed(() => !!props.submission?.graded_at);
const isTest = computed(() => ['test', 'quiz'].includes(props.assignment.type));

// Timer
const timeLeft = ref(props.assignment.time_limit ? props.assignment.time_limit * 60 : 0);
const timerRunning = ref(false);
let timerInterval: any = null;

const formattedTime = computed(() => {
    const m = Math.floor(timeLeft.value / 60);
    const s = timeLeft.value % 60;
    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
});

const startTimer = () => {
    if (!props.assignment.time_limit || timerRunning.value) return;
    timerRunning.value = true;
    timerInterval = setInterval(() => {
        timeLeft.value--;
        if (timeLeft.value <= 0) {
            clearInterval(timerInterval);
            submitForm();
        }
    }, 1000);
};

onUnmounted(() => {
    if (timerInterval) clearInterval(timerInterval);
});

// Form
const form = useForm({
    content: '',
    answers: props.assignment.questions ? props.assignment.questions.map(() => null as number | string | null) : [] as (number | string | null)[],
    file: null as File | null,
});

const submitForm = () => {
    if (timerInterval) clearInterval(timerInterval);

    if (isTest.value) {
        form.post(route('student.assignments.submit', props.assignment.id), {
            preserveScroll: true,
        });
    } else {
        form.post(route('student.assignments.submit', props.assignment.id), {
            preserveScroll: true,
            forceFormData: true,
        });
    }
};

const testStarted = ref(false);
const startTest = () => {
    testStarted.value = true;
    startTimer();
};

const percentageWidth = computed(() => {
    if (!props.submission?.score || !props.submission?.max_score) return '0%';
    return Math.min((props.submission.score / props.submission.max_score) * 100, 100) + '%';
});
</script>

<template>
    <Head :title="assignment.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3 mb-2">
                <Link :href="route('student.assignments.index')" class="text-gray-500 hover:text-white transition text-sm">← Back</Link>
            </div>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">{{ assignment.title }}</h2>
            <div class="flex items-center gap-4 mt-2">
                <span class="g-badge bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">{{ assignment.type }}</span>
                <span class="text-sm text-gray-500">{{ assignment.subject }} • {{ assignment.teacher }}</span>
                <span v-if="assignment.time_limit" class="text-sm text-gray-500">⏱ {{ assignment.time_limit }} min</span>
                <span class="text-sm text-gray-500">{{ assignment.total_points }} pts</span>
            </div>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 space-y-8">

                <!-- Already Submitted / Graded Result -->
                <div v-if="isSubmitted" class="space-y-6">
                    <div :class="isGraded ? 'border-emerald-500/20 bg-emerald-500/5' : 'border-blue-500/20 bg-blue-500/5'" class="g-card !border p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <span :class="isGraded ? 'bg-emerald-500' : 'bg-blue-500'" class="h-3 w-3 rounded-full animate-pulse"></span>
                            <h3 class="text-lg font-bold text-white">{{ isGraded ? 'Graded' : 'Submitted — Awaiting Grade' }}</h3>
                        </div>

                        <div v-if="isGraded" class="space-y-6">
                            <div class="flex items-center gap-8">
                                <div>
                                    <div class="text-5xl font-black text-emerald-400">{{ submission!.letter_grade }}</div>
                                    <div class="text-sm text-gray-500 mt-1">{{ submission!.score }} / {{ submission!.max_score }} points</div>
                                </div>
                                <div class="flex-1">
                                    <div class="h-3 w-full bg-white/5 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-emerald-500 to-blue-500 transition-all duration-700" :style="{ width: percentageWidth }"></div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="submission!.feedback" class="p-4 rounded-xl bg-white/[0.02] border border-white/5">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Teacher Feedback</h4>
                                <p class="text-sm text-gray-300 leading-relaxed">{{ submission!.feedback }}</p>
                            </div>
                        </div>

                        <div v-else class="text-sm text-gray-400">
                            Submitted {{ new Date(submission!.submitted_at!).toLocaleString() }}. Your teacher will grade this soon.
                        </div>
                    </div>
                </div>

                <!-- Assignment Description -->
                <div class="g-card p-8">
                    <h3 class="text-lg font-bold text-white mb-4">Instructions</h3>
                    <p class="text-gray-300 leading-relaxed whitespace-pre-wrap">{{ assignment.description }}</p>
                </div>

                <!-- Submission Form (only if not submitted) -->
                <template v-if="!isSubmitted && !assignment.is_past_due">

                    <!-- Test/Quiz Interface -->
                    <div v-if="isTest && assignment.questions">
                        <!-- Pre-start screen -->
                        <div v-if="!testStarted" class="g-card p-12 text-center">
                            <div class="text-5xl mb-6">⚡</div>
                            <h3 class="text-2xl font-bold text-white mb-4">Ready to Begin?</h3>
                            <p class="text-gray-400 mb-2">{{ assignment.questions.length }} questions • {{ assignment.total_points }} points</p>
                            <p v-if="assignment.time_limit" class="text-amber-400 text-sm font-bold mb-8">⏱ Time Limit: {{ assignment.time_limit }} minutes</p>
                            <p class="text-gray-500 text-sm mb-8">Once started, the timer cannot be paused. Your answers will auto-submit when time expires.</p>
                            <button @click="startTest" class="g-btn-primary !w-auto !px-12">Start {{ assignment.type === 'quiz' ? 'Quiz' : 'Test' }}</button>
                        </div>

                        <!-- Active test -->
                        <template v-if="testStarted">
                            <!-- Timer Bar -->
                            <div v-if="assignment.time_limit" class="sticky top-16 z-40 bg-black/80 backdrop-blur-xl border-b border-white/10 p-4 rounded-2xl mb-6">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-white">Time Remaining</span>
                                    <span :class="timeLeft < 60 ? 'text-rose-400 animate-pulse' : timeLeft < 180 ? 'text-amber-400' : 'text-emerald-400'" class="text-2xl font-mono font-black">
                                        {{ formattedTime }}
                                    </span>
                                </div>
                                <div class="mt-2 h-1 w-full bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-rose-500 transition-all duration-1000"
                                         :style="{ width: ((timeLeft / (assignment.time_limit! * 60)) * 100) + '%' }"></div>
                                </div>
                            </div>

                            <!-- Questions -->
                            <div class="space-y-6">
                                <div v-for="(q, idx) in assignment.questions" :key="idx" class="g-card p-8">
                                    <div class="flex items-center gap-3 mb-4">
                                        <span class="h-8 w-8 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-400 text-sm font-bold">{{ idx + 1 }}</span>
                                        <span class="text-[10px] font-bold uppercase tracking-widest" :class="q.type === 'mcq' ? 'text-blue-400' : 'text-purple-400'">
                                            {{ q.type === 'mcq' ? 'Multiple Choice' : 'Short Answer' }}
                                        </span>
                                    </div>
                                    <p class="text-white font-medium mb-6">{{ q.question }}</p>

                                    <!-- MCQ Options -->
                                    <div v-if="q.type === 'mcq'" class="space-y-3">
                                        <button v-for="(opt, oi) in q.options" :key="oi"
                                            @click="form.answers[idx] = oi"
                                            :class="form.answers[idx] === oi
                                                ? 'bg-indigo-600/20 border-indigo-500/40 text-white'
                                                : 'bg-white/[0.02] border-white/5 text-gray-400 hover:bg-white/[0.05] hover:text-white'"
                                            class="w-full text-left p-4 rounded-xl border transition-all flex items-center gap-3">
                                            <span :class="form.answers[idx] === oi ? 'bg-indigo-500 text-white' : 'bg-white/5 text-gray-600'"
                                                  class="h-7 w-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0 transition">
                                                {{ String.fromCharCode(65 + oi) }}
                                            </span>
                                            <span class="text-sm">{{ opt }}</span>
                                        </button>
                                    </div>

                                    <!-- Short Answer -->
                                    <div v-else>
                                        <textarea v-model="form.answers[idx]" rows="4" class="g-input" :placeholder="'Your answer (max ' + (q.max_words || 200) + ' words)...'"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8">
                                <button @click="submitForm" :disabled="form.processing" class="g-btn-primary">
                                    {{ form.processing ? 'Submitting...' : 'Submit ' + (assignment.type === 'quiz' ? 'Quiz' : 'Test') }}
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Regular Assignment -->
                    <div v-else class="g-card p-8">
                        <h3 class="text-lg font-bold text-white mb-6">Your Submission</h3>

                        <div class="space-y-6">
                            <div>
                                <label class="g-label">Response</label>
                                <textarea v-model="form.content" rows="8" class="g-input" placeholder="Write your response here..."></textarea>
                            </div>

                            <div>
                                <label class="g-label">Attach File (optional, max 10MB)</label>
                                <input type="file" @change="(e: any) => form.file = e.target.files[0]"
                                    class="block w-full text-sm text-gray-400 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 file:cursor-pointer file:transition" />
                            </div>

                            <button @click="submitForm" :disabled="form.processing || (!form.content && !form.file)" class="g-btn-primary">
                                {{ form.processing ? 'Submitting...' : 'Submit Assignment' }}
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Past Due Notice -->
                <div v-if="!isSubmitted && assignment.is_past_due" class="g-card !border-rose-500/20 !bg-rose-500/5 p-8 text-center">
                    <div class="text-4xl mb-4">⏰</div>
                    <h3 class="text-xl font-bold text-rose-400 mb-2">Submission Closed</h3>
                    <p class="text-gray-400">This assignment is past its due date and can no longer accept submissions.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
