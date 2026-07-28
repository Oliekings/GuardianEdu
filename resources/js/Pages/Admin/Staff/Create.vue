<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    availableUsers: { id: number; name: string; email: string; role: string }[];
}>();

const activeTab = ref('hr');

const form = useForm({
    user_id: '',
    staff_id: '',
    designation: '',
    department: '',
    joining_date: new Date().toISOString().split('T')[0],
    
    // Bio
    gender: 'male',
    dob: '',
    phone: '',
    emergency_contact: '',
    marital_status: 'Single',
    current_address: '',
    permanent_address: '',
    
    // Work
    qualification: '',
    work_experience: '',
    contract_type: 'Permanent',
    work_shift: 'Day Shift',
    
    // Payroll
    basic_salary: 0,
    epf_no: '',
    bank_account_title: '',
    bank_account_no: '',
    bank_name: '',
    ifsc_code: '',
});

const submit = () => {
    form.post(route('admin.staff.store'));
};

const tabs = [
    { id: 'hr', name: 'HR Identity', icon: '👔' },
    { id: 'bio', name: 'Biography', icon: '👤' },
    { id: 'work', name: 'Work Profile', icon: '💼' },
    { id: 'payroll', name: 'Payroll & Bank', icon: '🏦' },
];
</script>

<template>
    <Head title="Staff Onboarding" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black text-white">Personnel <span class="g-gradient-text">Onboarding</span></h2>
                    <p class="mt-1 text-sm text-gray-500 italic">Expanding the institution's professional core.</p>
                </div>
                <Link :href="route('admin.staff.index')" class="p-2 bg-white/5 border border-white/10 rounded-full hover:bg-white/10 transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </Link>
            </div>
        </template>

        <div class="pb-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl">
                <!-- Tab Navigation -->
                <div class="flex gap-1 bg-white/5 p-1.5 rounded-2xl mb-8 overflow-x-auto">
                    <button 
                        v-for="tab in tabs" 
                        :key="tab.id"
                        @click="activeTab = tab.id"
                        class="flex items-center gap-2 px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all whitespace-nowrap"
                        :class="activeTab === tab.id ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-gray-500 hover:text-white hover:bg-white/5'"
                    >
                        <span>{{ tab.icon }}</span>
                        {{ tab.name }}
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-8">
                    <!-- HR IDENTITY -->
                    <div v-show="activeTab === 'hr'" class="g-card p-10 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <section class="space-y-6">
                            <h3 class="text-xl font-black text-white decoration-indigo-500 underline decoration-4 underline-offset-8 uppercase tracking-widest">System <span class="text-indigo-400">Assignment</span></h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="g-label">Link User Account *</label>
                                    <select v-model="form.user_id" class="g-input">
                                        <option value="">— Choose Registered User —</option>
                                        <option v-for="u in availableUsers" :key="u.id" :value="u.id">{{ u.name }} ({{ u.role }})</option>
                                    </select>
                                    <p v-if="form.errors.user_id" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.user_id }}</p>
                                </div>
                                <div>
                                    <label class="g-label">Employee ID / Staff ID *</label>
                                    <input v-model="form.staff_id" class="g-input" placeholder="e.g. EMP-101" />
                                    <p v-if="form.errors.staff_id" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.staff_id }}</p>
                                </div>
                            </div>
                        </section>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div>
                                <label class="g-label">Designation</label>
                                <input v-model="form.designation" class="g-input" placeholder="e.g. Senior Lecturer" />
                            </div>
                            <div>
                                <label class="g-label">Department</label>
                                <input v-model="form.department" class="g-input" placeholder="e.g. Academic" />
                            </div>
                            <div>
                                <label class="g-label">Joining Date</label>
                                <input type="date" v-model="form.joining_date" class="g-input" />
                            </div>
                        </div>
                    </div>

                    <!-- BIOGRAPHY -->
                    <div v-show="activeTab === 'bio'" class="g-card p-10 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <h3 class="text-xl font-black text-white decoration-emerald-500 underline decoration-4 underline-offset-8 uppercase tracking-widest">Personal <span class="text-emerald-400">Bio</span></h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div><label class="g-label">Gender</label><select v-model="form.gender" class="g-input"><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
                            <div><label class="g-label">Date of Birth</label><input type="date" v-model="form.dob" class="g-input" /></div>
                            <div><label class="g-label">Contact Number</label><input v-model="form.phone" class="g-input" /></div>
                            <div><label class="g-label">Emergency Contact</label><input v-model="form.emergency_contact" class="g-input" /></div>
                            <div class="md:col-span-2"><label class="g-label">Current Address</label><textarea v-model="form.current_address" class="g-input min-h-[80px]"></textarea></div>
                        </div>
                    </div>

                    <!-- WORK PROFILE -->
                    <div v-show="activeTab === 'work'" class="g-card p-10 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <h3 class="text-xl font-black text-white decoration-amber-500 underline decoration-4 underline-offset-8 uppercase tracking-widest">Professional <span class="text-amber-400">Track</span></h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div><label class="g-label">Qualification</label><textarea v-model="form.qualification" class="g-input min-h-[80px]"></textarea></div>
                            <div><label class="g-label">Work Experience</label><textarea v-model="form.work_experience" class="g-input min-h-[80px]"></textarea></div>
                            <div><label class="g-label">Contract Type</label><select v-model="form.contract_type" class="g-input"><option value="Permanent">Permanent</option><option value="Probation">Probation</option><option value="Contract">Contract</option></select></div>
                            <div><label class="g-label">Work Shift</label><input v-model="form.work_shift" class="g-input" placeholder="e.g. Day Shift (8AM-4PM)" /></div>
                        </div>
                    </div>

                    <!-- PAYROLL & BANK -->
                    <div v-show="activeTab === 'payroll'" class="g-card p-10 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <h3 class="text-xl font-black text-white decoration-rose-500 underline decoration-4 underline-offset-8 uppercase tracking-widest">Fiscal <span class="text-rose-400">Settings</span></h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div><label class="g-label">Basic Salary ($)</label><input v-model="form.basic_salary" type="number" class="g-input" /></div>
                            <div><label class="g-label">EPF / Insurance No</label><input v-model="form.epf_no" class="g-input" /></div>
                        </div>
                        <div class="p-6 bg-white/[0.02] border border-white/5 rounded-3xl space-y-6">
                            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Bank Remittance Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div><label class="g-label text-[10px]">Account Title</label><input v-model="form.bank_account_title" class="g-input" /></div>
                                <div><label class="g-label text-[10px]">Account Number</label><input v-model="form.bank_account_no" class="g-input" /></div>
                                <div><label class="g-label text-[10px]">Bank Name</label><input v-model="form.bank_name" class="g-input" /></div>
                                <div><label class="g-label text-[10px]">IFSC / Swift Code</label><input v-model="form.ifsc_code" class="g-input" /></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-between pt-8 border-t border-white/5">
                        <div class="text-[10px] text-gray-600 uppercase font-bold tracking-widest italic font-mono">
                            GuardianEdu HR Registry | v2.2.0-STABLE
                        </div>
                        <div class="flex gap-4">
                            <Link :href="route('admin.staff.index')" class="px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-white transition">Cancel</Link>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="px-12 py-4 bg-white text-black rounded-full font-black text-xs uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition shadow-xl hover:shadow-indigo-500/20 active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing...' : 'Onboard Staff' }}
                            </button>
                        </div>
                    </div>
                </form>
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
