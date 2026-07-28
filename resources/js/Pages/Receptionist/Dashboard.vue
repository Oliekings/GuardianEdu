<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Reception Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Front <span class="g-gradient-text">Office</span></h2>
            <p class="text-gray-500 text-sm mt-1">Admission enquiries, visitor tracking, and community logs.</p>
        </template>

        <div class="py-12 px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <StatCard title="New Enquiries" value="28" colorClass="text-emerald-400" />
                    <StatCard title="Today's Visitors" value="14" colorClass="text-indigo-400" />
                    <StatCard title="Pending Complaints" value="3" colorClass="text-amber-400" />
                </div>

                <div class="g-card p-12 text-center">
                    <div class="text-5xl mb-6">🏢</div>
                    <h3 class="text-xl font-bold text-white mb-2">Front Desk Ecosystem</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Admission enquiry logs, visitor books, and postal tracking are being integrated into the operations hub.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
