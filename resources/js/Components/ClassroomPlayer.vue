<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';

const props = defineProps<{
    streamUrl: string;
    roomName: string;
    isActive: boolean;
}>();

const videoElement = ref<HTMLVideoElement | null>(null);
let player: any = null;
const isPlayerReady = ref(false);
const error = ref<string | null>(null);

async function loadIVSPlayerScript(): Promise<void> {
    return new Promise((resolve, reject) => {
        if ((window as any).IVSPlayer) {
            resolve();
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://player.live-video.net/1.31.0/amazon-ivs-player.min.js';
        script.async = true;
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Failed to load Amazon IVS player script'));
        document.head.appendChild(script);
    });
}

onMounted(async () => {
    try {
        await loadIVSPlayerScript();
    } catch (e) {
        error.value = (e as Error).message;
        return;
    }

    const IVS = (window as any).IVSPlayer;
    if (!IVS.isPlayerSupported) {
        error.value = 'Your browser does not support AWS IVS Video Player.';
        return;
    }

    player = IVS.create({
        wasmWorker: 'https://web-weaver.amazon-ivs.com/player/1.24.0/amazon-ivs-wasmworker.min.js',
        wasmBinary: 'https://web-weaver.amazon-ivs.com/player/1.24.0/amazon-ivs-wasmworker.min.wasm'
    });

    if (videoElement.value) {
        player.attachHTMLVideoElement(videoElement.value);
        player.load(props.streamUrl);
        player.play();
        isPlayerReady.value = true;
    }
});

onUnmounted(() => {
    if (player) {
        player.pause();
        player.delete();
    }
});
</script>

<template>
    <div class="relative w-full aspect-video bg-black rounded-3xl overflow-hidden shadow-2xl ring-1 ring-white/10 group">
        <video 
            ref="videoElement" 
            playsinline 
            class="w-full h-full object-cover"
        ></video>

        <!-- UI Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/40 flex flex-col justify-between p-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-white uppercase tracking-widest">Live: {{ roomName }}</span>
                </div>
                <div class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-full border border-white/10 text-[10px] text-white font-bold uppercase tracking-wider">
                    Secure Feed
                </div>
            </div>

            <div v-if="error" class="absolute inset-0 flex items-center justify-center bg-black/90 p-8 text-center text-white">
                <p class="text-sm font-medium">{{ error }}</p>
            </div>

            <div class="flex items-center justify-between mt-auto">
                <div class="flex items-center gap-4">
                    <button class="h-10 w-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white hover:bg-white/20 transition">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM10 14.5v-5l4.5 2.5-4.5 2.5z"/></svg>
                    </button>
                    <div class="text-xs text-white/50 font-medium">
                        Synced with School Local Time
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Loading Spinner -->
        <div v-if="!isPlayerReady && !error" class="absolute inset-0 flex items-center justify-center bg-zinc-900">
            <div class="h-8 w-8 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>
</template>
