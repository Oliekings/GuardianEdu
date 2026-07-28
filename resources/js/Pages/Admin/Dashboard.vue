<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    stats: {
        total_students: number;
        total_staff: number;
        total_parents: number;
        total_users: number;
        total_assignments: number;
        pending_submissions: number;
        attendance_rate: string;
        system_status: string;
    };
    announcements: any[];
    recentActivity: any[];
}>();
</script>

<template>
    <Head title="Admin Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">
                Command <span class="g-gradient-text">Center</span>
            </h2>
            <p class="mt-1 text-sm text-gray-500">Full system overview and school administration.</p>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <StatCard title="Students" :value="stats.total_students" />
                    <StatCard title="Teachers" :value="stats.total_staff" />
                    <StatCard title="Parents" :value="stats.total_parents" />
                    <StatCard title="Attendance" :value="stats.attendance_rate" />
                </div>

                <!-- Secondary Stats -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="g-card-inset p-5 flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 text-xl">📝</div>
                        <div>
                            <div class="text-xl font-bold text-white">{{ stats.total_assignments }}</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Assignments</div>
                        </div>
                    </div>
                    <div class="g-card-inset p-5 flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-400 text-xl">⏳</div>
                        <div>
                            <div class="text-xl font-bold text-white">{{ stats.pending_submissions }}</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Pending Grading</div>
                        </div>
                    </div>
                    <div class="g-card-inset p-5 flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 text-xl">🟢</div>
                        <div>
                            <div class="text-xl font-bold text-emerald-400">{{ stats.system_status }}</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">System</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Quick Navigation -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <Link :href="route('admin.students.index')" class="g-card p-6 text-center hover:border-indigo-500/20 transition group block">
                                <div class="text-3xl mb-3">👨‍🎓</div>
                                <div class="text-xs font-bold text-gray-400 group-hover:text-white transition">Manage Students</div>
                            </Link>
                            <Link :href="route('admin.users.index')" class="g-card p-6 text-center hover:border-purple-500/20 transition group block">
                                <div class="text-3xl mb-3">👥</div>
                                <div class="text-xs font-bold text-gray-400 group-hover:text-white transition">Manage Users</div>
                            </Link>
                            <Link :href="route('security-cam.index')" class="g-card p-6 text-center hover:border-blue-500/20 transition group block">
                                <div class="text-3xl mb-3">📹</div>
                                <div class="text-xs font-bold text-gray-400 group-hover:text-white transition">Security Cams</div>
                            </Link>
                            <Link :href="route('admin.theme.show')" class="g-card p-6 text-center hover:border-amber-500/20 transition group block">
                                <div class="text-3xl mb-3">🎨</div>
                                <div class="text-xs font-bold text-gray-400 group-hover:text-white transition">Theme Editor</div>
                            </Link>
                        </div>

                        <!-- Recent Activity -->
                        <div class="g-card p-8">
                            <h3 class="text-lg font-bold text-white mb-6">Recent Activity</h3>
                            <div v-if="recentActivity.length" class="space-y-4">
                                <div v-for="(a, idx) in recentActivity" :key="idx" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/[0.02] transition">
                                    <div :class="a.type === 'kudos' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border-rose-500/20'"
                                         class="h-9 w-9 rounded-full flex items-center justify-center text-xs font-bold shrink-0 border">
                                        {{ a.type === 'kudos' ? '↑' : '↓' }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-white"><strong>{{ a.student }}</strong> — {{ a.category }}</p>
                                        <p class="text-[10px] text-gray-500">by {{ a.teacher }} • {{ a.date }}</p>
                                    </div>
                                    <span :class="a.points > 0 ? 'text-emerald-400' : 'text-rose-400'" class="text-sm font-bold">
                                        {{ a.points > 0 ? '+' : '' }}{{ a.points }}
                                    </span>
                                </div>
                            </div>
                            <div v-else class="text-center py-8"><p class="text-gray-500">No recent activity.</p></div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-8">
                        <!-- Announcements -->
                        <div class="g-card p-6">
                            <h3 class="text-md font-bold text-white mb-6">Recent Announcements</h3>
                            <div v-if="announcements.length" class="space-y-5">
                                <div v-for="a in announcements" :key="a.id" class="pb-4 border-b border-white/5 last:border-0">
                                    <h4 class="text-sm font-bold text-white">{{ a.title }}</h4>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ a.content }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="text-[10px] text-gray-600">{{ a.author }} • {{ a.created_at }}</span>
                                        <span class="g-badge bg-white/5 text-gray-500 border border-white/5 text-[9px]">{{ a.target_role }}</span>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-8"><p class="text-gray-500 text-sm">No announcements.</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
