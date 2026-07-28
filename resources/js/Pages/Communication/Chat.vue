<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const props = defineProps<{
    contacts: any[];
}>();

const selectedContact = ref(null);
const messages = ref([]);
const newMessage = ref('');
const loadingMessages = ref(false);

const selectContact = async (contact) => {
    selectedContact.value = contact;
    loadingMessages.value = true;
    try {
        const response = await axios.get(route('api.chat.messages', contact.id));
        messages.value = response.data;
    } catch (e) {
        console.error("Failed to load messages", e);
    } finally {
        loadingMessages.value = false;
        scrollToBottom();
    }
};

const sendMessage = async () => {
    if (!newMessage.value.trim() || !selectedContact.value) return;

    const body = newMessage.value;
    newMessage.value = '';

    try {
        await axios.post(route('api.chat.send'), {
            receiver_id: selectedContact.value.id,
            body: body
        });
        // Push locally for instant feedback
        messages.value.push({
            id: Date.now(),
            sender_id: 'me', // temporary marker
            body: body,
            created_at: new Date().toISOString()
        });
        scrollToBottom();
    } catch (e) {
        console.error("Message send failed", e);
    }
};

const scrollToBottom = () => {
    setTimeout(() => {
        const container = document.getElementById('chat-container');
        if (container) container.scrollTop = container.scrollHeight;
    }, 50);
};

onMounted(() => {
    if (props.contacts.length > 0) {
        selectContact(props.contacts[0]);
    }
});
</script>

<template>
    <Head title="Internal Messaging" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Institutional <span class="g-gradient-text">Messenger</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Secure, real-time communication within the school ecosystem.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl h-[700px] flex gap-8">
                <!-- Contacts List -->
                <div class="w-80 g-card flex flex-col overflow-hidden">
                    <div class="p-6 border-b border-white/5 bg-white/[0.02]">
                        <input class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs font-bold text-white placeholder-gray-600 focus:ring-1 focus:ring-indigo-500" placeholder="Search contacts..." />
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-2">
                        <button 
                            v-for="contact in contacts" 
                            :key="contact.id"
                            @click="selectContact(contact)"
                            class="w-full p-4 rounded-3xl text-left transition-all group relative overflow-hidden"
                            :class="selectedContact?.id === contact.id ? 'bg-indigo-600 shadow-xl shadow-indigo-500/20' : 'hover:bg-white/5'"
                        >
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white/10 rounded-2xl flex items-center justify-center font-black text-xs" :class="selectedContact?.id === contact.id ? 'text-white' : 'text-indigo-400'">
                                    {{ contact.name[0] }}
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <h4 class="text-sm font-black truncate" :class="selectedContact?.id === contact.id ? 'text-white' : 'text-gray-300 group-hover:text-white'">{{ contact.name }}</h4>
                                    <p class="text-[10px] uppercase font-bold tracking-widest truncate" :class="selectedContact?.id === contact.id ? 'text-indigo-200' : 'text-gray-600'">{{ contact.role }}</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Chat Window -->
                <div class="flex-1 g-card flex flex-col relative overflow-hidden">
                    <div v-if="selectedContact" class="h-full flex flex-col">
                        <!-- Header -->
                        <div class="p-6 border-b border-white/5 flex items-center justify-between bg-white/[0.02]">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-400 font-black">
                                    {{ selectedContact.name[0] }}
                                </div>
                                <div>
                                    <h4 class="text-white font-black uppercase tracking-tight">{{ selectedContact.name }}</h4>
                                    <p class="text-[10px] text-emerald-500 font-bold uppercase tracking-widest flex items-center gap-1.5 leading-none">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                        Active Now
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="p-2 text-gray-600 hover:text-white transition hover:bg-white/5 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg></button>
                                <button class="p-2 text-gray-600 hover:text-white transition hover:bg-white/5 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg></button>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div id="chat-container" class="flex-1 overflow-y-auto p-8 space-y-6">
                            <div v-for="msg in messages" :key="msg.id" class="flex" :class="msg.sender_id === 'me' ? 'justify-end' : 'justify-start'">
                                <div class="max-w-[70%] space-y-2">
                                    <div 
                                        class="p-4 px-6 rounded-3xl text-sm font-medium"
                                        :class="msg.sender_id === 'me' ? 'bg-indigo-600 text-white rounded-br-none shadow-lg shadow-indigo-500/10' : 'bg-white/5 text-gray-200 rounded-bl-none border border-white/5'"
                                    >
                                        {{ msg.body }}
                                    </div>
                                    <p class="text-[9px] text-gray-600 uppercase font-black" :class="msg.sender_id === 'me' ? 'text-right' : 'text-left'">
                                        {{ new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                                    </p>
                                </div>
                            </div>
                            <div v-if="loadingMessages" class="flex items-center justify-center h-full opacity-20 italic text-sm">Synchronizing encrypted packets...</div>
                        </div>

                        <!-- Input -->
                        <div class="p-6 border-t border-white/5 bg-white/[0.01]">
                            <form @submit.prevent="sendMessage" class="relative group">
                                <input 
                                    v-model="newMessage"
                                    class="w-full bg-white/5 border border-white/10 rounded-full py-5 px-8 pr-20 text-sm font-bold text-white placeholder-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    placeholder="Type your message securely..."
                                />
                                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center hover:bg-indigo-500 transition shadow-lg shadow-indigo-500/20 active:scale-95">
                                    <svg class="w-5 h-5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div v-else class="h-full flex flex-col items-center justify-center opacity-40 italic">
                        <div class="text-6xl mb-6">🛰️</div>
                        <p class="font-black uppercase tracking-[0.3em] text-[10px]">Initialize Secure Uplink</p>
                        <p class="text-xs mt-2">Select a contact to begin institutional communication.</p>
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

::-webkit-scrollbar {
    width: 6px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.1);
}
</style>
