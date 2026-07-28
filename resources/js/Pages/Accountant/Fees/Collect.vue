<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps<{
    students: any[];
    filters: { search?: string };
}>();

const search = ref(props.filters.search || '');

watch(search, debounce((value) => {
    router.get(route('accountant.fees.collect.index'), { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));
</script>

<template>
    <Head title="Fee Collection" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Fee <span class="g-gradient-text">Collection</span></h2>
            <p class="text-gray-500 text-sm mt-1">Search student records to collect payments and view balance reports.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl space-y-8">
                <!-- Search Bar -->
                <div class="g-card p-6 flex items-center gap-4">
                    <div class="p-3 bg-white/5 rounded-2xl text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input 
                        v-model="search"
                        class="flex-1 bg-transparent border-none text-xl font-bold text-white placeholder-gray-700 focus:ring-0" 
                        placeholder="Search Admission No or Student Name..." 
                    />
                </div>

                <!-- Results -->
                <div v-if="students.length" class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div v-for="student in students" :key="student.id" class="g-card p-6 flex items-center gap-6 group hover:border-indigo-500/50 transition relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                        
                        <div class="w-20 h-20 bg-white/5 rounded-3xl flex items-center justify-center text-2xl font-black text-indigo-400 border border-white/5">
                            {{ student.first_name[0] }}{{ student.last_name[0] }}
                        </div>

                        <div class="flex-1">
                            <h4 class="text-lg font-black text-white uppercase tracking-tight">{{ student.first_name }} {{ student.last_name }}</h4>
                            <p class="text-xs font-mono text-gray-500 mt-1">{{ student.admission_number }}</p>
                            <div class="mt-3 flex gap-2">
                                <span class="px-2 py-0.5 bg-white/10 rounded text-[9px] font-bold text-gray-400 uppercase">{{ student.room_id || 'No Room' }}</span>
                                <span class="px-2 py-0.5 bg-emerald-500/10 rounded text-[9px] font-bold text-emerald-400 uppercase">Active</span>
                            </div>
                        </div>

                        <Link 
                            :href="route('accountant.fees.collect.show', student.id)"
                            class="px-6 py-3 bg-white text-black rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition relative z-10 shadow-xl"
                        >
                            Collect
                        </Link>
                    </div>
                </div>

                <div v-else-if="search" class="text-center py-20 animate-in zoom-in duration-300">
                    <div class="text-5xl mb-4">🕵️‍♂️</div>
                    <h3 class="text-xl font-bold text-white">No matches found</h3>
                    <p class="text-gray-500 mt-2">Check the admission number and try again.</p>
                </div>
                
                <div v-else class="text-center py-20 opacity-50">
                    <div class="text-5xl mb-4 text-white/5 font-black uppercase">GuardianEdu Fiscal Registry</div>
                    <p class="text-gray-600 font-medium">Ready for transaction entry. Type above to begin.</p>
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
