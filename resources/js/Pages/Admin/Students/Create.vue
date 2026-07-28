<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    parentUsers: { id: number; name: string; email: string }[];
    studentUsers: { id: number; name: string; email: string }[];
    feeGroups: { id: number; name: string }[];
}>();

const activeTab = ref('academic');

const form = useForm({
    // Academic
    admission_number: '',
    room_id: '',
    fee_group_id: '',
    rfid_token: '',
    admission_date: new Date().toISOString().split('T')[0],
// ... (rest of form bio/parents/etc remains same)

    // Bio
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    dob: '',
    gender: 'male',
    category: 'General',
    religion: '',
    blood_group: '',
    
    // Parents
    father_name: '',
    father_phone: '',
    father_occupation: '',
    mother_name: '',
    mother_phone: '',
    mother_occupation: '',

    // Guardian
    guardian_is: 'father',
    guardian_name: '',
    guardian_phone: '',
    guardian_email: '',
    guardian_relation: '',
    guardian_address: '',

    // System
    user_id: null as number | null,
    parent_ids: [] as number[],
});

const submit = () => {
    form.post(route('admin.students.store'), {
        onSuccess: () => {
            // Success handling
        }
    });
};

const toggleParent = (id: number) => {
    const idx = form.parent_ids.indexOf(id);
    if (idx > -1) form.parent_ids.splice(idx, 1);
    else form.parent_ids.push(id);
};

const tabs = [
    { id: 'academic', name: 'Academic Details', icon: '🎓' },
    { id: 'bio', name: 'Biography', icon: '👤' },
    { id: 'parents', name: 'Parental Info', icon: '👨‍👩‍👧' },
    { id: 'guardian', name: 'Guardian Details', icon: '🛡️' },
    { id: 'accounts', name: 'Portal Access', icon: '💻' },
];
</script>

<template>
    <Head title="Premium Student Admission" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black text-white">Student <span class="g-gradient-text">Admission</span></h2>
                    <p class="mt-1 text-sm text-gray-500 italic text-sm">Registering new academic excellence into the ecosystem.</p>
                </div>
                <Link :href="route('admin.students.index')" class="p-2 bg-white/5 border border-white/10 rounded-full hover:bg-white/10 transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="6 18L18 6M6 6l12 12" /></svg>
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
                    <!-- ACADEMIC TAB -->
                    <div v-show="activeTab === 'academic'" class="g-card p-10 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <h3 class="text-xl font-black text-white decoration-indigo-500 underline decoration-4 underline-offset-8">Academic <span class="text-indigo-400">Identity</span></h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="g-label">Admission Number <span class="text-rose-500">*</span></label>
                                <input v-model="form.admission_number" class="g-input" placeholder="ADM-2026-XXXX" />
                                <p v-if="form.errors.admission_number" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.admission_number }}</p>
                            </div>
                            <div>
                                <label class="g-label">Admission Date</label>
                                <input type="date" v-model="form.admission_date" class="g-input" />
                            </div>
                            <div>
                                <label class="g-label">Classroom / Section</label>
                                <input v-model="form.room_id" class="g-input" placeholder="e.g. Grade 10-A" />
                            </div>
                            <div>
                                <label class="g-label">Fee Group Selection</label>
                                <select v-model="form.fee_group_id" class="g-input">
                                    <option value="">— Generic Billing —</option>
                                    <option v-for="g in feeGroups" :key="g.id" :value="g.id">{{ g.name }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="g-label">RFID Token Unique ID</label>
                                <input v-model="form.rfid_token" class="g-input" placeholder="SCAN RFID" />
                            </div>
                        </div>
                    </div>

                    <!-- BIOGRAPHY TAB -->
                    <div v-show="activeTab === 'bio'" class="g-card p-10 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <h3 class="text-xl font-black text-white decoration-emerald-500 underline decoration-4 underline-offset-8">Profile <span class="text-emerald-400">Biography</span></h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="g-label">First Name *</label>
                                <input v-model="form.first_name" class="g-input" />
                            </div>
                            <div>
                                <label class="g-label">Last Name *</label>
                                <input v-model="form.last_name" class="g-input" />
                            </div>
                            <div>
                                <label class="g-label">Email Address</label>
                                <input type="email" v-model="form.email" class="g-input" placeholder="student@school.edu" />
                            </div>
                            <div>
                                <label class="g-label">Phone Number</label>
                                <input v-model="form.phone" class="g-input" placeholder="+1 (XXX) XXX-XXXX" />
                            </div>
                            <div>
                                <label class="g-label">Date of Birth</label>
                                <input type="date" v-model="form.dob" class="g-input" />
                            </div>
                            <div>
                                <label class="g-label">Gender</label>
                                <select v-model="form.gender" class="g-input">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-white/5">
                            <div>
                                <label class="g-label text-[10px]">Category</label>
                                <select v-model="form.category" class="g-input">
                                    <option value="General">General</option>
                                    <option value="Scholarship">Scholarship</option>
                                    <option value="International">International</option>
                                </select>
                            </div>
                            <div>
                                <label class="g-label text-[10px]">Religion</label>
                                <input v-model="form.religion" class="g-input" />
                            </div>
                            <div>
                                <label class="g-label text-[10px]">Blood Group</label>
                                <input v-model="form.blood_group" class="g-input" placeholder="A+, O-, etc." />
                            </div>
                        </div>
                    </div>

                    <!-- PARENTS TAB -->
                    <div v-show="activeTab === 'parents'" class="g-card p-10 space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <!-- Father Info -->
                        <section class="space-y-6">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                <span class="p-1 px-2 bg-indigo-500/10 text-indigo-400 rounded">Father's Information</span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div><label class="g-label text-[10px]">Full Name</label><input v-model="form.father_name" class="g-input" /></div>
                                <div><label class="g-label text-[10px]">Contact No</label><input v-model="form.father_phone" class="g-input" /></div>
                                <div><label class="g-label text-[10px]">Occupation</label><input v-model="form.father_occupation" class="g-input" /></div>
                            </div>
                        </section>
                        <!-- Mother Info -->
                        <section class="space-y-6">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                <span class="p-1 px-2 bg-rose-500/10 text-rose-400 rounded">Mother's Information</span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div><label class="g-label text-[10px]">Full Name</label><input v-model="form.mother_name" class="g-input" /></div>
                                <div><label class="g-label text-[10px]">Contact No</label><input v-model="form.mother_phone" class="g-input" /></div>
                                <div><label class="g-label text-[10px]">Occupation</label><input v-model="form.mother_occupation" class="g-input" /></div>
                            </div>
                        </section>
                    </div>

                    <!-- GUARDIAN TAB -->
                    <div v-show="activeTab === 'guardian'" class="g-card p-10 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <h3 class="text-xl font-black text-white decoration-amber-500 underline decoration-4 underline-offset-8">Guardian <span class="text-amber-400">Responsibility</span></h3>
                        
                        <div class="bg-white/5 p-4 rounded-2xl flex gap-4">
                            <span class="text-xs font-bold text-gray-500 uppercase self-center px-4">Guardian Is:</span>
                            <button v-for="role in ['father', 'mother', 'other']" :key="role" type="button"
                                @click="form.guardian_is = role"
                                class="px-6 py-2 rounded-xl text-[10px] font-bold uppercase transition"
                                :class="form.guardian_is === role ? 'bg-indigo-600 text-white' : 'bg-white/5 text-gray-500 hover:text-white'">
                                {{ role }}
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div><label class="g-label">Guardian Name *</label><input v-model="form.guardian_name" class="g-input" /></div>
                            <div><label class="g-label">Guardian Phone *</label><input v-model="form.guardian_phone" class="g-input" /></div>
                            <div><label class="g-label">Guardian Email</label><input type="email" v-model="form.guardian_email" class="g-input" /></div>
                            <div><label class="g-label">Relationship to Student</label><input v-model="form.guardian_relation" class="g-input" /></div>
                            <div class="md:col-span-2"><label class="g-label">Residential Address</label><textarea v-model="form.guardian_address" class="g-input min-h-[100px]"></textarea></div>
                        </div>
                    </div>

                    <!-- ACCOUNTS TAB -->
                    <div v-show="activeTab === 'accounts'" class="g-card p-10 space-y-12 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <section class="space-y-6">
                            <h4 class="text-xs font-black text-white uppercase tracking-widest">Linked Student Account</h4>
                            <select v-model="form.user_id" class="g-input">
                                <option :value="null">— No Account —</option>
                                <option v-for="u in studentUsers" :key="u.id" :value="u.id">{{ u.name }} ({{ u.email }})</option>
                            </select>
                        </section>

                        <section class="space-y-6">
                            <h4 class="text-xs font-black text-white uppercase tracking-widest">Linked Parent/Guardian Portal Accounts</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <button v-for="p in parentUsers" :key="p.id" type="button" @click="toggleParent(p.id)"
                                    :class="form.parent_ids.includes(p.id) ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' : 'bg-white/[0.02] border-white/5 text-gray-400'"
                                    class="p-4 rounded-2xl border text-left transition hover:bg-white/[0.05]">
                                    <div class="text-sm font-bold">{{ p.name }}</div>
                                    <div class="text-[10px] text-gray-500 mt-0.5">{{ p.email }}</div>
                                </button>
                            </div>
                        </section>
                    </div>

                    <!-- Submit Footer -->
                    <div class="flex items-center justify-between pt-8 border-t border-white/5">
                        <div class="text-[10px] text-gray-600 uppercase font-bold tracking-widest italic">
                            GuardianEdu Enterprise | Admission Registry v2.0
                        </div>
                        <div class="flex gap-4">
                            <Link :href="route('admin.students.index')" class="px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-white transition">Cancel</Link>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="px-12 py-4 bg-white text-black rounded-full font-black text-xs uppercase tracking-widest hover:bg-indigo-400 hover:text-white transition shadow-xl hover:shadow-indigo-500/20 active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing...' : 'Admit Student' }}
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
