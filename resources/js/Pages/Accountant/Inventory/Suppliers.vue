<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    suppliers: any[];
}>();

const form = useForm({
    name: '',
    phone: '',
    email: '',
    address: '',
    contact_person: '',
});

const submit = () => {
    form.post(route('accountant.inventory.suppliers.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Inventory Suppliers" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Vendor <span class="g-gradient-text">Directory</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Maintain a list of suppliers for procurement and stock replenishment.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Management Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="g-card p-10 border-t-4 border-rose-500/50">
                        <h3 class="text-xl font-black text-white mb-8 uppercase tracking-widest text-[10px]">Add Vendor <span class="text-rose-400">Entry</span></h3>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">Supplier / Company Name *</label>
                                <input v-model="form.name" class="g-input" placeholder="e.g. Acme Stationery Ltd." />
                                <p v-if="form.errors.name" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.name }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="g-label">Contact Person</label>
                                    <input v-model="form.contact_person" class="g-input text-xs" placeholder="First Name..." />
                                </div>
                                <div>
                                    <label class="g-label">Phone</label>
                                    <input v-model="form.phone" class="g-input text-xs" placeholder="+1..." />
                                </div>
                            </div>

                            <div>
                                <label class="g-label">Email Address</label>
                                <input v-model="form.email" type="email" class="g-input" placeholder="vendor@example.com" />
                            </div>

                            <div>
                                <label class="g-label">Business Address</label>
                                <textarea v-model="form.address" class="g-input min-h-[80px]" placeholder="Full physical address..."></textarea>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-rose-500 hover:text-white transition shadow-xl active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Registering...' : 'Register Supplier' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- List Panel -->
                <div class="lg:col-span-2">
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest italic">Authorized Vendors</span>
                            <span class="px-3 py-1 bg-rose-500/10 text-rose-400 rounded-full text-[10px] font-bold">{{ suppliers.length }} Approved</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-b border-white/5">
                            <div v-for="supplier in suppliers" :key="supplier.id" class="p-6 border-r border-b border-white/5 hover:bg-white/[0.02] transition group relative overflow-hidden">
                                <div class="absolute top-0 right-0 p-4 opacity-0 group-hover:opacity-100 transition">
                                    <button class="text-gray-600 hover:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                </div>
                                <h4 class="text-lg font-black text-white uppercase tracking-tight">{{ supplier.name }}</h4>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center gap-2 text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                                        <span class="text-indigo-400">👤</span> {{ supplier.contact_person || 'No Contact Person' }}
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] text-gray-500">
                                        <span class="text-indigo-400">📞</span> {{ supplier.phone || 'N/A' }}
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] text-gray-500">
                                        <span class="text-indigo-400">✉️</span> {{ supplier.email || 'N/A' }}
                                    </div>
                                </div>
                                <p class="mt-4 text-[10px] text-gray-600 italic leading-relaxed border-t border-white/5 pt-4">{{ supplier.address || 'Address not registered.' }}</p>
                            </div>
                        </div>
                        <div v-if="!suppliers.length" class="p-20 text-center">
                            <div class="text-gray-600 italic text-sm">No vendors registered. Build your supply chain.</div>
                        </div>
                    </div>
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
