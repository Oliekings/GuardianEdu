<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Library Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Library <span class="g-gradient-text">Management</span></h2>
            <p class="text-gray-500 text-sm mt-1">Manage book inventory, issuing, and member returns.</p>
        </template>

        <div class="py-12 px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <StatCard title="Total Books" value="12,500" />
                    <StatCard title="Issued Books" value="432" colorClass="text-indigo-400" />
                    <StatCard title="Overdue" value="12" colorClass="text-rose-400" />
                </div>

                <div class="g-card p-12 text-center">
                    <div class="text-5xl mb-6">📚</div>
                    <h3 class="text-xl font-bold text-white mb-2">Library System Initializing</h3>
                    <p class="text-gray-500 max-w-md mx-auto"> Systematic book listing and member management features are coming in the next operational phase.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
