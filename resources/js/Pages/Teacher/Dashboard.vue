<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    schedule: any[];
    recentBehavior: any[];
    upcomingDeadlines: any[];
    stats: {
        total_assignments: number;
        pending_grading: number;
        behavior_score: number;
        student_count: number;
    };
}>();
</script>

<template>
    <Head title="Teacher Portal" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">
                Faculty <span class="g-gradient-text">Dashboard</span>
            </h2>
            <p class="mt-1 text-sm text-gray-500">Managing your academic sessions and student success.</p>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">

                <!-- Stats Row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <StatCard title="Students" :value="stats.student_count" />
                    <StatCard title="Assignments" :value="stats.total_assignments" colorClass="text-indigo-400" />
                    <StatCard title="To Grade" :value="stats.pending_grading" :colorClass="stats.pending_grading > 0 ? 'text-amber-400' : 'text-emerald-400'" />
                    <StatCard title="Avg Behavior" :value="stats.behavior_score" colorClass="text-purple-400" />
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-8">

                        <!-- Today's Schedule -->
                        <div class="g-card p-8">
                            <h3 class="text-lg font-bold text-white mb-6">Today's Academic Schedule</h3>
                            <div v-if="schedule.length" class="space-y-4">
                                <div v-for="item in schedule" :key="item.id" class="p-5 rounded-xl bg-white/[0.02] border border-white/5 flex items-center justify-between transition hover:bg-white/[0.04]">
                                    <div class="flex items-center gap-4">
                                        <div :class="[
                                            'h-2 w-2 rounded-full',
                                            item.status === 'active' ? 'bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.5)] animate-pulse' :
                                            item.status === 'completed' ? 'bg-emerald-500/50' : 'bg-gray-700'
                                        ]"></div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-white">{{ item.name }}</h4>
                                            <p class="text-[11px] text-gray-500 mt-0.5">{{ item.time }} • Room {{ item.room }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <Link :href="route('staff.attendance.index')" class="px-3 py-1.5 bg-white/5 rounded-lg text-[10px] font-bold text-gray-400 hover:bg-white/10 hover:text-white transition">
                                            Attendance
                                        </Link>
                                        <Link :href="route('staff.behavioral.index')" class="px-3 py-1.5 bg-indigo-500/10 rounded-lg text-[10px] font-bold text-indigo-400 hover:bg-indigo-500/20 transition">
                                            Behavior
                                        </Link>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-12">
                                <div class="text-4xl mb-4">📅</div>
                                <p class="text-gray-500">No classes scheduled for today.</p>
                            </div>
                        </div>

                        <!-- Upcoming Deadlines -->
                        <div class="g-card p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-white">Upcoming Deadlines</h3>
                                <Link :href="route('staff.assignments.index')" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition">All Assignments →</Link>
                            </div>
                            <div v-if="upcomingDeadlines.length" class="space-y-4">
                                <Link v-for="d in upcomingDeadlines" :key="d.id"
                                    :href="route('staff.assignments.show', d.id)"
                                    class="p-4 rounded-xl bg-white/[0.02] border border-white/5 flex items-center justify-between hover:bg-white/[0.05] transition block">
                                    <div>
                                        <h4 class="text-sm font-semibold text-white">{{ d.title }}</h4>
                                        <p class="text-[10px] text-gray-500 mt-1">{{ d.subject }} • {{ d.room_id }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="g-badge bg-amber-500/10 text-amber-400 border border-amber-500/20">{{ d.type }}</span>
                                        <p class="text-[10px] text-gray-500 mt-1">{{ d.due_label }}</p>
                                    </div>
                                </Link>
                            </div>
                            <div v-else class="text-center py-8">
                                <p class="text-gray-500 text-sm">No upcoming deadlines.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-8">
                        <!-- Quick Actions -->
                        <div class="g-card p-6 space-y-4">
                            <h3 class="text-md font-bold text-white mb-2">Quick Actions</h3>
                            <Link :href="route('staff.assignments.create')"
                                class="w-full py-4 bg-indigo-600 rounded-2xl text-sm font-bold text-white shadow-xl shadow-indigo-500/20 transition transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                                + Create Assignment
                            </Link>
                            <Link :href="route('staff.gradebook.index')"
                                class="w-full py-4 bg-white/5 border border-white/10 rounded-2xl text-sm font-bold text-white hover:bg-white/10 transition flex items-center justify-center gap-2">
                                📊 Grade Book
                            </Link>
                        </div>

                        <!-- Recent Behavioral Activity -->
                        <div class="g-card p-6">
                            <h3 class="text-md font-bold text-white mb-6">Recent Behavioral Logs</h3>
                            <div v-if="recentBehavior.length" class="space-y-4">
                                <div v-for="b in recentBehavior" :key="b.student + b.date" class="flex items-center gap-3">
                                    <div :class="b.type === 'kudos' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400'"
                                         class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ b.type === 'kudos' ? '+' : '-' }}{{ Math.abs(b.points) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-white truncate">{{ b.student }}</p>
                                        <p class="text-[10px] text-gray-500">{{ b.category }} • {{ b.date }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <p class="text-gray-500 text-sm">No recent logs.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
