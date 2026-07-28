<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    stats?: {
        total_fees_collected: string;
        pending_arrears: string;
        today_collection: string;
    }
}>();
</script>

<template>
    <Head title="Accountant Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Financial <span class="g-gradient-text">Overview</span></h2>
            <p class="text-gray-500 text-sm mt-1">Fee collections, expenses, and financial reporting hub.</p>
        </template>

        <div class="py-12 px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <StatCard title="Total Fees (M-o-M)" value="$42,500" colorClass="text-emerald-400" />
                    <StatCard title="Arrears" value="$12,100" colorClass="text-rose-400" />
                    <StatCard title="Today's Collection" value="$1,250" colorClass="text-indigo-400" />
                </div>

                <div class="g-card p-12 text-center">
                    <div class="text-5xl mb-6">💰</div>
                    <h3 class="text-xl font-bold text-white mb-2">Finance Module Incoming</h3>
                    <p class="text-gray-500 max-w-md mx-auto">The detailed fee masters, discount structures, and receipt generation systems are currently being initialized.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
