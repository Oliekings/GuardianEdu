<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{ rooms: string[] }>();

const form = useForm({
    title: '',
    subject: '',
    type: 'assignment',
    room_id: props.rooms[0] || '',
    description: '',
    due_at: '',
    total_points: 100,
    time_limit_minutes: null as number | null,
    is_published: false,
    questions: [] as any[],
});

const addQuestion = (type: string) => {
    if (type === 'mcq') {
        form.questions.push({ type: 'mcq', question: '', options: ['', '', '', ''], correct: 0 });
    } else {
        form.questions.push({ type: 'short_answer', question: '', max_words: 200 });
    }
};

const removeQuestion = (idx: number) => {
    form.questions.splice(idx, 1);
};

const submit = () => {
    form.post(route('staff.assignments.store'));
};

const isTestType = ref(false);
watch(() => form.type, (val) => {
    isTestType.value = ['test', 'quiz'].includes(val);
    if (!isTestType.value) {
        form.questions = [];
        form.time_limit_minutes = null;
    }
});
</script>

<template>
    <Head title="Create Assignment" />
    <AuthenticatedLayout>
        <template #header>
            <Link :href="route('staff.assignments.index')" class="text-gray-500 hover:text-white transition text-sm">← Back to Assignments</Link>
            <h2 class="text-3xl font-extrabold tracking-tight text-white mt-2">Create <span class="g-gradient-text">Assignment</span></h2>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Basic Info -->
                    <div class="g-card p-8 space-y-6">
                        <h3 class="text-lg font-bold text-white">Basic Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="g-label">Title *</label>
                                <input v-model="form.title" class="g-input" placeholder="e.g. Quadratic Equations Problem Set" />
                                <p v-if="form.errors.title" class="text-rose-400 text-xs mt-1">{{ form.errors.title }}</p>
                            </div>
                            <div>
                                <label class="g-label">Subject *</label>
                                <input v-model="form.subject" class="g-input" placeholder="e.g. Mathematics" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="g-label">Type *</label>
                                <select v-model="form.type" class="g-input">
                                    <option value="assignment">Assignment</option>
                                    <option value="quiz">Quiz</option>
                                    <option value="test">Test</option>
                                </select>
                            </div>
                            <div>
                                <label class="g-label">Room *</label>
                                <select v-model="form.room_id" class="g-input">
                                    <option v-for="r in rooms" :key="r" :value="r">{{ r }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="g-label">Total Points *</label>
                                <input v-model.number="form.total_points" type="number" class="g-input" min="1" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="g-label">Due Date</label>
                                <input v-model="form.due_at" type="datetime-local" class="g-input" />
                            </div>
                            <div v-if="isTestType">
                                <label class="g-label">Time Limit (minutes)</label>
                                <input v-model.number="form.time_limit_minutes" type="number" class="g-input" placeholder="e.g. 30" min="1" />
                            </div>
                        </div>

                        <div>
                            <label class="g-label">Description / Instructions</label>
                            <textarea v-model="form.description" rows="4" class="g-input" placeholder="Describe the assignment requirements..."></textarea>
                        </div>
                    </div>

                    <!-- Questions (for tests/quizzes) -->
                    <div v-if="isTestType" class="g-card p-8 space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-white">Questions</h3>
                            <div class="flex gap-2">
                                <button type="button" @click="addQuestion('mcq')" class="px-4 py-2 bg-blue-500/10 text-blue-400 rounded-xl text-xs font-bold hover:bg-blue-500/20 transition">+ MCQ</button>
                                <button type="button" @click="addQuestion('short_answer')" class="px-4 py-2 bg-purple-500/10 text-purple-400 rounded-xl text-xs font-bold hover:bg-purple-500/20 transition">+ Short Answer</button>
                            </div>
                        </div>

                        <div v-for="(q, idx) in form.questions" :key="idx" class="p-6 rounded-2xl bg-white/[0.02] border border-white/5 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="g-badge" :class="q.type === 'mcq' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'bg-purple-500/10 text-purple-400 border border-purple-500/20'">
                                    Q{{ idx + 1 }} — {{ q.type === 'mcq' ? 'Multiple Choice' : 'Short Answer' }}
                                </span>
                                <button type="button" @click="removeQuestion(idx)" class="text-rose-400 text-xs font-bold hover:text-rose-300">Remove</button>
                            </div>

                            <div>
                                <label class="g-label">Question</label>
                                <input v-model="q.question" class="g-input" placeholder="Enter the question..." />
                            </div>

                            <template v-if="q.type === 'mcq'">
                                <div v-for="(opt, oi) in q.options" :key="oi" class="flex items-center gap-3">
                                    <input type="radio" :name="'correct-' + idx" :value="oi" v-model="q.correct"
                                        class="h-4 w-4 text-indigo-500 bg-black/40 border-white/10" />
                                    <input v-model="q.options[oi]" class="g-input !py-2" :placeholder="'Option ' + String.fromCharCode(65 + oi)" />
                                </div>
                                <p class="text-[10px] text-gray-500">Select the radio button next to the correct answer.</p>
                            </template>
                        </div>

                        <div v-if="!form.questions.length" class="text-center py-8">
                            <p class="text-gray-500 text-sm">No questions added yet. Use the buttons above to add questions.</p>
                        </div>
                    </div>

                    <!-- Publish & Submit -->
                    <div class="g-card p-8">
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" v-model="form.is_published" class="h-5 w-5 rounded bg-black/40 border-white/10 text-indigo-500" />
                                <div>
                                    <span class="text-sm font-bold text-white">Publish immediately</span>
                                    <p class="text-[10px] text-gray-500">Students will see this right away. Leave unchecked to save as draft.</p>
                                </div>
                            </label>
                        </div>

                        <div class="mt-8 flex gap-4">
                            <Link :href="route('staff.assignments.index')" class="g-btn-secondary text-center">Cancel</Link>
                            <button type="submit" :disabled="form.processing" class="g-btn-primary">
                                {{ form.processing ? 'Creating...' : 'Create Assignment' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
