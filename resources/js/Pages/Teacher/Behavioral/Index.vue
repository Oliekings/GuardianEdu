<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    students: any[];
    rooms: string[];
    recentLogs: any[];
    filters: { room_id?: string };
}>();

const roomFilter = ref(props.filters.room_id || '');
const applyFilter = () => {
    router.get(route('staff.behavioral.index'), { room_id: roomFilter.value }, { preserveState: true });
};

const form = useForm({
    student_id: null as number | null,
    type: 'kudos',
    category: '',
    points: 0,
    description: '',
});

const submit = () => {
    form.post(route('staff.behavioral.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('category', 'points', 'description');
        }
    });
};

const selectStudent = (id: number) => {
    form.student_id = id;
};

const commonCategories = {
    kudos: [
        { label: 'Participation', pts: 5 },
        { label: 'Leadership', pts: 10 },
        { label: 'Excellence', pts: 15 },
        { label: 'Helping Others', pts: 5 },
        { label: 'Great Improvement', pts: 10 },
    ],
    incident: [
        { label: 'Disruption', pts: -5 },
        { label: 'Late/Tardy', pts: -5 },
        { label: 'Disrespect', pts: -10 },
        { label: 'Missing Work', pts: -5 },
        { label: 'Phones in Class', pts: -10 },
    ]
};

const setCategory = (cat: { label: string, pts: number }) => {
    form.category = cat.label;
    form.points = Math.abs(cat.pts);
};
</script>

<template>
    <Head title="Behavioral Tracking" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">Behavioral <span class="g-gradient-text">Tracking</span></h2>
            <p class="mt-1 text-sm text-gray-500">Record positive (kudos) and negative (incidents) behaviors for your standard rooms.</p>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <!-- Form Area -->
                    <div class="lg:col-span-8 space-y-6">
                        <div class="g-card p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-white">Log Behavior</h3>
                                <div class="flex gap-2">
                                    <select v-model="roomFilter" @change="applyFilter" class="g-input !w-auto !py-1 !px-3 text-xs">
                                        <option v-for="r in rooms" :key="r" :value="r">{{ r }}</option>
                                    </select>
                                </div>
                            </div>

                            <form @submit.prevent="submit" class="space-y-6">
                                <!-- Student Selection -->
                                <div>
                                    <label class="g-label">Select Student *</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                        <button v-for="s in students" :key="s.id" type="button" @click="selectStudent(s.id)"
                                            :class="form.student_id === s.id ? 'bg-indigo-500/20 border-indigo-500/40 text-white' : 'bg-white/[0.02] border-white/5 text-gray-400 hover:bg-white/[0.05]'"
                                            class="p-3 rounded-xl border text-left transition relative">
                                            <div class="text-sm font-semibold truncate">{{ s.name }}</div>
                                            <div class="text-[10px] opacity-70">{{ s.admission_number }}</div>
                                            <div v-if="form.student_id === s.id" class="absolute -top-1 -right-1 h-3 w-3 bg-indigo-500 rounded-full border border-black"></div>
                                        </button>
                                    </div>
                                    <p v-if="form.errors.student_id" class="text-rose-400 text-xs mt-2">{{ form.errors.student_id }}</p>
                                </div>

                                <!-- Type Toggle -->
                                <div>
                                    <label class="g-label">Log Type *</label>
                                    <div class="flex p-1 bg-white/5 rounded-xl border border-white/10 w-full max-w-sm">
                                        <button type="button" @click="form.type = 'kudos'"
                                            :class="form.type === 'kudos' ? 'bg-emerald-500/20 text-emerald-400 font-bold shadow' : 'text-gray-400 hover:text-white'"
                                            class="flex-1 py-2 rounded-lg text-sm transition">
                                            👏 Kudos (+pts)
                                        </button>
                                        <button type="button" @click="form.type = 'incident'"
                                            :class="form.type === 'incident' ? 'bg-rose-500/20 text-rose-400 font-bold shadow' : 'text-gray-400 hover:text-white'"
                                            class="flex-1 py-2 rounded-lg text-sm transition">
                                            ⚠️ Incident (-pts)
                                        </button>
                                    </div>
                                </div>

                                <!-- Category & Points -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="g-label">Behavior Category *</label>
                                        <input v-model="form.category" class="g-input" placeholder="e.g. Participation, Disruption..." />
                                        
                                        <!-- Quick picks -->
                                        <div class="flex flex-wrap gap-2 mt-3">
                                            <button v-for="c in commonCategories[form.type as keyof typeof commonCategories]" :key="c.label" type="button"
                                                @click="setCategory(c)" class="px-2 py-1 rounded border border-white/10 bg-white/5 text-[10px] text-gray-400 hover:bg-white/10 transition">
                                                {{ c.label }} ({{ Math.abs(c.pts) }})
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="g-label">Points Value *</label>
                                        <input v-model.number="form.points" type="number" class="g-input" min="1" max="100" />
                                        <p class="text-[10px] text-gray-500 mt-1">Enter a positive number. Signs are strictly handled.</p>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="g-label">Description / Notes</label>
                                    <textarea v-model="form.description" class="g-input" rows="3" placeholder="Additional details..."></textarea>
                                </div>

                                <div class="border-t border-white/5 pt-6 flex justify-end">
                                    <button type="submit" :disabled="form.processing || !form.student_id" class="g-btn-primary !px-8">
                                        {{ form.processing ? 'Saving...' : 'Save Log' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Recent Logs Sidebar -->
                    <div class="lg:col-span-4 space-y-6">
                        <div class="g-card p-6">
                            <h3 class="text-md font-bold text-white mb-6">Recent Logs</h3>
                            <div v-if="recentLogs.length" class="space-y-4">
                                <div v-for="l in recentLogs" :key="l.id" class="flex gap-3 pb-4 border-b border-white/5 last:border-0 last:pb-0">
                                    <div :class="l.type === 'kudos' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400'"
                                         class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-1">
                                        {{ l.points > 0 ? '+' : '' }}{{ l.points }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-semibold text-white truncate">{{ l.student }}</h4>
                                        <p class="text-xs font-bold mt-0.5" :class="l.type === 'kudos' ? 'text-emerald-400' : 'text-rose-400'">{{ l.category }}</p>
                                        <p v-if="l.description" class="text-xs text-gray-500 line-clamp-2 mt-1">{{ l.description }}</p>
                                        <p class="text-[10px] text-gray-600 mt-2">{{ l.date }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <p class="text-gray-500 text-sm">No recent logs recorded.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.02);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.2);
}
</style>
