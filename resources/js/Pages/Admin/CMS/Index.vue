<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    sectors: any[];
}>();

const selectedSection = ref('hero');

// For simplicity in this UI, we manage keys like 'hero', 'about', 'contact'
const form = useForm({
    key: 'hero',
    content: {
        headline: '',
        subheadline: '',
        cta_text: '',
    },
    is_visible: true,
});

const loadSection = (key: string) => {
    selectedSection.value = key;
    const existing = props.sectors.find(s => s.key === key);
    if (existing) {
        form.key = existing.key;
        form.content = existing.content;
        form.is_visible = existing.is_visible;
    } else {
        form.key = key;
        form.content = { headline: '', subheadline: '', cta_text: '' };
        form.is_visible = true;
    }
};

const submit = () => {
    form.post(route('admin.cms.sector.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Public Identity Management" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white italic">CMS <span class="g-gradient-text">Orchestrator</span></h2>
            <p class="text-gray-500 text-sm mt-1 uppercase tracking-widest font-black text-[10px]">Real-time public landing page configuration.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-4 gap-12">
                <!-- Navigation -->
                <div class="lg:col-span-1 space-y-4">
                    <button 
                        @click="loadSection('hero')" 
                        :class="selectedSection === 'hero' ? 'border-indigo-500 bg-indigo-500/10 text-white' : 'border-white/5 text-gray-500'"
                        class="w-full p-6 g-card border text-left font-black uppercase tracking-widest text-[10px] transition group hover:border-indigo-500/50"
                    >
                        Hero Section
                    </button>
                    <button 
                        @click="loadSection('about')" 
                        :class="selectedSection === 'about' ? 'border-primary-500 bg-primary-500/10 text-white' : 'border-white/5 text-gray-500'"
                        class="w-full p-6 g-card border text-left font-black uppercase tracking-widest text-[10px] transition group hover:border-indigo-500/50"
                    >
                        About Narrative
                    </button>
                    <button 
                        @click="loadSection('footer')" 
                        :class="selectedSection === 'footer' ? 'border-primary-500 bg-primary-500/10 text-white' : 'border-white/5 text-gray-500'"
                        class="w-full p-6 g-card border text-left font-black uppercase tracking-widest text-[10px] transition group hover:border-indigo-500/50"
                    >
                        Institutional Footer
                    </button>
                </div>

                <!-- Editor -->
                <div class="lg:col-span-3">
                    <div class="g-card p-12 border-t-4 border-indigo-500">
                        <div class="flex justify-between items-center mb-12">
                            <h3 class="text-xl font-black text-white uppercase tracking-tighter italic">Editing: {{ selectedSection }}</h3>
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-black text-gray-500 uppercase">Visible on Public Site</span>
                                <button 
                                    @click="form.is_visible = !form.is_visible"
                                    :class="form.is_visible ? 'bg-emerald-500 text-black' : 'bg-rose-500 text-white'"
                                    class="px-4 py-1 rounded-full text-[9px] font-black uppercase transition"
                                >
                                    {{ form.is_visible ? 'Active' : 'Hidden' }}
                                </button>
                            </div>
                        </div>

                        <form @submit.prevent="submit" class="space-y-8">
                            <div class="space-y-6">
                                <div>
                                    <label class="g-label">Primary Headline</label>
                                    <input v-model="form.content.headline" class="g-input font-black text-xl italic" placeholder="The Future of Education..." />
                                </div>

                                <div>
                                    <label class="g-label">Subheadline/Description</label>
                                    <textarea v-model="form.content.subheadline" class="g-input h-32 leading-relaxed" placeholder="Detailed institutional narrative..."></textarea>
                                </div>

                                <div v-if="selectedSection === 'hero'">
                                    <label class="g-label">CTA Button Label</label>
                                    <input v-model="form.content.cta_text" class="g-input" placeholder="Get Started" />
                                </div>
                            </div>

                            <div class="pt-8 border-t border-white/5 flex justify-end">
                                <button 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="px-10 py-4 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition shadow-2xl active:scale-95 disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Syncing Ecosystem...' : 'Save Configuration' }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-8 p-10 g-card border-dashed border-2 border-white/5 flex items-center justify-between text-gray-600 group hover:border-indigo-500/20 transition">
                        <div class="flex items-center gap-4">
                            <span class="text-3xl grayscale group-hover:grayscale-0 transition">🖼️</span>
                            <div>
                                <h4 class="text-xs font-black uppercase tracking-widest text-gray-400">Media Library Integration</h4>
                                <p class="text-[10px] italic">Automated asset pipeline & CDN optimization enabled.</p>
                            </div>
                        </div>
                        <button class="px-6 py-2 bg-white/5 border border-white/10 rounded-xl text-[9px] font-black uppercase hover:bg-white/10 transition">Upload Asset</button>
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
