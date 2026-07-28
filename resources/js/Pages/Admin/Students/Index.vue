<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    students: any[];
    rooms: string[];
    filters: { search?: string; room_id?: string };
}>();

const search = ref(props.filters.search || '');
const roomFilter = ref(props.filters.room_id || '');

const applyFilter = () => {
    router.get(route('admin.students.index'), {
        search: search.value || undefined,
        room_id: roomFilter.value || undefined,
    }, { preserveState: true });
};

const deleteStudent = (id: number) => {
    if (confirm('Are you sure you want to remove this student?')) {
        router.delete(route('admin.students.destroy', id));
    }
};
</script>

<template>
    <Head title="Student Directory" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-white">Student <span class="g-gradient-text">Directory</span></h2>
                    <p class="mt-1 text-sm text-gray-500">Manage enrolled students, link parents, and assign classrooms.</p>
                </div>
                <Link :href="route('admin.students.create')" class="px-6 py-3 bg-indigo-600 rounded-2xl text-sm font-bold text-white shadow-lg shadow-indigo-500/20 hover:bg-indigo-500 transition">
                    + Add Student
                </Link>
            </div>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <!-- Filters -->
                <div class="flex flex-wrap gap-4 items-center">
                    <div class="relative flex-1 max-w-md">
                        <input v-model="search" @keyup.enter="applyFilter" class="g-input !py-2.5 !pl-10 !rounded-full text-sm" placeholder="Search by name or admission #..." />
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <select v-model="roomFilter" @change="applyFilter" class="g-input !w-auto !py-2 !px-4 !rounded-full text-sm">
                        <option value="">All Rooms</option>
                        <option v-for="r in rooms" :key="r" :value="r">{{ r }}</option>
                    </select>
                    <button @click="applyFilter" class="px-5 py-2.5 bg-white/5 border border-white/10 rounded-full text-xs font-bold text-gray-400 hover:bg-white/10 transition">Search</button>
                </div>

                <!-- Table -->
                <div class="g-card overflow-hidden">
                    <div v-if="students.length" class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/5">
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Adm #</th>
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Name</th>
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Room</th>
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Account</th>
                                    <th class="text-left p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Parents</th>
                                    <th class="text-center p-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="s in students" :key="s.id" class="border-b border-white/5 hover:bg-white/[0.02] transition">
                                    <td class="p-4 text-sm font-mono text-gray-400">{{ s.admission_number }}</td>
                                    <td class="p-4 text-sm font-semibold text-white">{{ s.full_name }}</td>
                                    <td class="p-4"><span v-if="s.room_id" class="g-badge bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">{{ s.room_id }}</span><span v-else class="text-gray-600">—</span></td>
                                    <td class="p-4 text-xs text-gray-500">{{ s.user_email || 'No account' }}</td>
                                    <td class="p-4">
                                        <div v-if="s.parents.length" class="flex flex-wrap gap-1">
                                            <span v-for="p in s.parents" :key="p.id" class="px-2 py-1 rounded-md bg-emerald-500/10 text-emerald-400 text-[10px] font-bold">{{ p.name }}</span>
                                        </div>
                                        <span v-else class="text-gray-600 text-xs">None linked</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center gap-2 justify-center">
                                            <Link :href="route('admin.students.edit', s.id)" class="px-3 py-1.5 bg-indigo-500/10 text-indigo-400 rounded-lg text-xs font-bold hover:bg-indigo-500/20 transition">Edit</Link>
                                            <button @click="deleteStudent(s.id)" class="px-3 py-1.5 bg-rose-500/10 text-rose-400 rounded-lg text-xs font-bold hover:bg-rose-500/20 transition">Remove</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="p-16 text-center">
                        <div class="text-5xl mb-6">👨‍🎓</div>
                        <h3 class="text-xl font-bold text-white mb-2">No Students Found</h3>
                        <p class="text-gray-500">Adjust your search or add a new student.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
