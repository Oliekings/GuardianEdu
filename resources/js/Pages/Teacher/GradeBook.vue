<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    students: any[];
    subjects: string[];
    selectedSubject: string;
    selectedTerm: string;
    terms: string[];
}>();

const subject = ref(props.selectedSubject);
const term = ref(props.selectedTerm);

const applyFilter = () => {
    router.get(route('staff.gradebook.index'), { subject: subject.value, term: term.value }, { preserveState: true });
};

const editingId = ref<number | null>(null);
const gradeForm = useForm({
    student_id: 0,
    subject: '',
    term: '',
    score: 0,
    max_score: 100,
    remarks: '',
});

const startEdit = (student: any) => {
    editingId.value = student.id;
    gradeForm.student_id = student.id;
    gradeForm.subject = subject.value;
    gradeForm.term = term.value;
    gradeForm.score = student.grade?.score || 0;
    gradeForm.max_score = student.grade?.max_score || 100;
    gradeForm.remarks = student.grade?.remarks || '';
};

const saveGrade = () => {
    gradeForm.post(route('staff.grades.store'), {
        preserveScroll: true,
        onSuccess: () => { editingId.value = null; },
    });
};

const gradeColor = (pct: number) => {
    if (pct >= 90) return 'text-emerald-400';
    if (pct >= 80) return 'text-blue-400';
    if (pct >= 70) return 'text-amber-400';
    return 'text-rose-400';
};
</script>

<template>
    <Head title="Grade Book" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">Grade <span class="g-gradient-text">Book</span></h2>
            <p class="mt-1 text-sm text-gray-500">Record and manage student grades per subject and term.</p>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <!-- Filters -->
                <div class="flex flex-wrap gap-4">
                    <select v-model="subject" @change="applyFilter" class="g-input !w-auto !py-2 !px-4 !rounded-full text-sm">
                        <option v-for="s in subjects" :key="s" :value="s">{{ s }}</option>
                    </select>
                    <select v-model="term" @change="applyFilter" class="g-input !w-auto !py-2 !px-4 !rounded-full text-sm">
                        <option v-for="t in terms" :key="t" :value="t">{{ t }}</option>
                    </select>
                </div>

                <!-- Grade Table -->
                <div class="g-card overflow-hidden">
                    <div v-if="students.length" class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/5">
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Student</th>
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Room</th>
                                    <th class="text-center p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Score</th>
                                    <th class="text-center p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Grade</th>
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Remarks</th>
                                    <th class="text-center p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="s in students" :key="s.id" class="border-b border-white/5 hover:bg-white/[0.02] transition">
                                    <td class="p-4">
                                        <div class="text-sm font-semibold text-white">{{ s.name }}</div>
                                        <div class="text-[10px] text-gray-500">{{ s.admission_number }}</div>
                                    </td>
                                    <td class="p-4 text-sm text-gray-400">{{ s.room_id }}</td>
                                    <td class="p-4 text-center">
                                        <template v-if="editingId === s.id">
                                            <div class="flex items-center gap-1 justify-center">
                                                <input v-model.number="gradeForm.score" type="number" class="g-input !w-20 !py-1 !px-2 text-center text-sm" min="0" />
                                                <span class="text-gray-500 text-sm">/</span>
                                                <input v-model.number="gradeForm.max_score" type="number" class="g-input !w-20 !py-1 !px-2 text-center text-sm" min="1" />
                                            </div>
                                        </template>
                                        <template v-else>
                                            <span v-if="s.grade" class="text-sm font-bold text-white">{{ s.grade.score }}/{{ s.grade.max_score }}</span>
                                            <span v-else class="text-sm text-gray-600">—</span>
                                        </template>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span v-if="s.grade" :class="gradeColor(s.grade.percentage)" class="text-lg font-black">{{ s.grade.letter_grade }}</span>
                                        <span v-else class="text-gray-600">—</span>
                                    </td>
                                    <td class="p-4">
                                        <template v-if="editingId === s.id">
                                            <input v-model="gradeForm.remarks" class="g-input !py-1 !px-2 text-sm" placeholder="Remarks..." />
                                        </template>
                                        <template v-else>
                                            <span class="text-xs text-gray-500">{{ s.grade?.remarks || '—' }}</span>
                                        </template>
                                    </td>
                                    <td class="p-4 text-center">
                                        <template v-if="editingId === s.id">
                                            <div class="flex items-center gap-2 justify-center">
                                                <button @click="saveGrade" :disabled="gradeForm.processing" class="px-3 py-1.5 bg-emerald-500/10 text-emerald-400 rounded-lg text-xs font-bold hover:bg-emerald-500/20 transition">Save</button>
                                                <button @click="editingId = null" class="px-3 py-1.5 bg-white/5 text-gray-400 rounded-lg text-xs font-bold hover:bg-white/10 transition">Cancel</button>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <button @click="startEdit(s)" class="px-3 py-1.5 bg-indigo-500/10 text-indigo-400 rounded-lg text-xs font-bold hover:bg-indigo-500/20 transition">
                                                {{ s.grade ? 'Edit' : 'Set Grade' }}
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="p-16 text-center">
                        <div class="text-4xl mb-4">📊</div>
                        <p class="text-gray-500">No students found for this subject/term combination.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
