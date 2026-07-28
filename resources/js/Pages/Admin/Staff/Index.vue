<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps<{
    staff: any[];
    filters: { search?: string };
}>();

const search = ref(props.filters.search || '');

watch(search, debounce((value) => {
    router.get(route('admin.staff.index'), { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

const deleteStaff = (id: number) => {
    if (confirm('Are you sure you want to remove this staff profile?')) {
        router.delete(route('admin.staff.destroy', id));
    }
};
</script>

<template>
    <Head title="Staff Directory" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black text-white">Human <span class="g-gradient-text">Resources</span></h2>
                    <p class="mt-1 text-sm text-gray-500 italic">Managing the backbone of the institution.</p>
                </div>
                <Link :href="route('admin.staff.create')" class="px-8 py-3 bg-white text-black rounded-full font-black text-xs uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition shadow-xl shadow-indigo-500/10">
                    Add Personnel
                </Link>
            </div>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl space-y-8">
                <!-- Search Bar -->
                <div class="g-card p-4 flex items-center gap-4 bg-white/[0.03]">
                    <div class="p-2 bg-white/5 rounded-xl text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input v-model="search" class="flex-1 bg-transparent border-none text-sm font-bold text-white placeholder-gray-600 focus:ring-0" placeholder="Search by name, ID, or designation..." />
                </div>

                <!-- Staff Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="member in staff" :key="member.id" class="g-card p-6 group hover:border-indigo-500/30 transition relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                        
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center text-lg font-black text-indigo-400 border border-white/5">
                                    {{ member.name[0] }}
                                </div>
                                <div>
                                    <h4 class="text-white font-black text-sm uppercase tracking-tight">{{ member.name }}</h4>
                                    <p class="text-[10px] text-gray-500 font-mono">{{ member.staff_id }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 bg-white/10 rounded-lg text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ member.role }}</span>
                        </div>

                        <div class="space-y-3 mb-6">
                            <div class="flex items-center gap-2 text-[11px] text-gray-400 font-bold uppercase tracking-widest">
                                <span class="p-1 px-2 bg-indigo-500/10 text-indigo-400 rounded-md">{{ member.designation }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] text-gray-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                {{ member.phone || 'No Phone' }}
                            </div>
                        </div>

                        <div class="flex gap-2 pt-6 border-t border-white/5">
                            <Link :href="route('admin.staff.edit', member.id)" class="flex-1 py-2 text-center bg-white/5 rounded-xl text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:bg-white/10 hover:text-white transition">Edit Profile</Link>
                            <button @click="deleteStaff(member.id)" class="p-2 bg-rose-500/5 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="!staff.length" class="text-center py-20 animate-in zoom-in duration-300">
                    <div class="text-5xl mb-4">👔</div>
                    <h3 class="text-xl font-bold text-white">No personnel records found</h3>
                    <p class="text-gray-500 mt-2">Scale your team by clicking the add button above.</p>
                </div>
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
