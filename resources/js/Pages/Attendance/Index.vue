<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{
    rooms: any[];
    students: any[];
    filters: { room_id?: string; date?: string };
    stats: { total: number; present: number; absent: number; late: number };
}>();

const roomFilter = ref(props.filters.room_id || '');
const dateFilter = ref(props.filters.date || new Date().toISOString().split('T')[0]);

const form = useForm({
    room_id: roomFilter.value,
    date: dateFilter.value,
    attendance: props.students.map(s => ({
        student_id: s.id,
        status: s.status,
        remarks: s.remarks
    }))
});

// Watch for student changes (e.g. room switch) to update form
watch(() => props.students, (newStudents) => {
    form.attendance = newStudents.map(s => ({
        student_id: s.id,
        status: s.status,
        remarks: s.remarks
    }));
}, { deep: true });

const applyFilter = () => {
    router.get(route('staff.attendance.index'), {
        room_id: roomFilter.value,
        date: dateFilter.value
    }, { preserveState: true });
};

const setStatus = (index: number, status: string) => {
    form.attendance[index].status = status;
};

const markAllPresent = () => {
    form.attendance.forEach(a => a.status = 'Present');
};

const submit = () => {
    form.post(route('staff.attendance.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Optional: Show toast
        }
    });
};
</script>

<template>
    <Head title="Premium Attendance" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-black text-white">Attendance <span class="g-gradient-text">Studio</span></h2>
                    <p class="mt-1 text-sm text-gray-500 italic">Advanced attendance tracking with real-time analytics.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button 
                        @click="markAllPresent"
                        class="px-4 py-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-emerald-500/20 transition"
                    >
                        Mark All Present
                    </button>
                    <button 
                        @click="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-indigo-700 transition disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Attendance' }}
                    </button>
                </div>
            </div>
        </template>

        <div class="pb-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl space-y-8">
                <!-- Advanced Filters -->
                <div class="flex flex-wrap gap-4 items-center bg-white/[0.02] border border-white/5 p-4 rounded-3xl">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Room</span>
                        <select v-model="roomFilter" @change="applyFilter" class="g-input !w-auto !py-1.5 !px-4 !rounded-full text-xs">
                            <option v-for="r in rooms" :key="r" :value="r">{{ r }}</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Date</span>
                        <input type="date" v-model="dateFilter" @change="applyFilter" class="g-input !w-auto !py-1.5 !px-4 !rounded-full text-xs" />
                    </div>
                </div>

                <!-- Live Metrics -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <StatCard title="Total" :value="stats.total" />
                    <StatCard title="Present" :value="stats.present" colorClass="text-emerald-400" />
                    <StatCard title="Absent" :value="stats.absent" colorClass="text-rose-400" />
                    <StatCard title="Late" :value="stats.late" colorClass="text-amber-400" />
                </div>

                <!-- Ultimate Roster -->
                <div v-if="students.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div 
                        v-for="(s, index) in students" 
                        :key="s.id"
                        class="g-card p-5 group transition-all duration-300 hover:border-indigo-500/30"
                        :class="{ 'opacity-50 grayscale-[0.5]': form.attendance[index].status === 'Absent' }"
                    >
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl overflow-hidden bg-white/5 border border-white/10 relative">
                                    <img v-if="s.image" :src="s.image" class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex items-center justify-center text-xl font-bold bg-indigo-500/20 text-indigo-400">
                                        {{ s.name.charAt(0) }}
                                    </div>
                                    <!-- Indicator -->
                                    <div 
                                        class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-slate-900"
                                        :class="{
                                            'bg-emerald-500': form.attendance[index].status === 'Present',
                                            'bg-rose-500': form.attendance[index].status === 'Absent',
                                            'bg-amber-500': form.attendance[index].status === 'Late',
                                            'bg-blue-500': form.attendance[index].status === 'Half Day',
                                        }"
                                    ></div>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white group-hover:text-indigo-400 transition-colors">{{ s.name }}</h4>
                                    <p class="text-[10px] text-gray-500 font-mono uppercase tracking-widest">ID: {{ s.admission_number }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status Toggles -->
                        <div class="grid grid-cols-4 gap-1 p-1 bg-white/5 rounded-xl">
                            <button 
                                v-for="status in ['Present', 'Absent', 'Late', 'Half Day']" 
                                :key="status"
                                @click="setStatus(index, status)"
                                class="py-2 text-[8px] font-black uppercase tracking-tighter rounded-lg transition-all"
                                :class="form.attendance[index].status === status 
                                    ? (status === 'Present' ? 'bg-emerald-500 text-white shadow-lg' : 
                                       status === 'Absent' ? 'bg-rose-500 text-white shadow-lg' :
                                       status === 'Late' ? 'bg-amber-500 text-white shadow-lg' : 'bg-blue-500 text-white shadow-lg')
                                    : 'text-gray-500 hover:text-white'"
                            >
                                {{ status.charAt(0) }}
                            </button>
                        </div>

                        <!-- Remarks -->
                        <div class="mt-4">
                            <input 
                                v-model="form.attendance[index].remarks"
                                placeholder="Add note..."
                                class="w-full bg-transparent border-none p-0 text-[10px] text-gray-400 placeholder:text-gray-700 focus:ring-0"
                            />
                        </div>
                    </div>
                </div>
                
                <EmptyState v-else icon="👨‍🎓" title="No Students Found" description="Select a room to begin marking attendance." />

                <!-- Bottom Save (Mobile) -->
                <div class="md:hidden sticky bottom-6 z-10 flex justify-center">
                    <button 
                        @click="submit"
                        :disabled="form.processing"
                        class="px-10 py-4 bg-indigo-600 text-white rounded-full font-bold shadow-2xl hover:scale-105 transition active:scale-95 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Syncing...' : 'Save Attendance' }}
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.g-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 24px;
}
</style>
