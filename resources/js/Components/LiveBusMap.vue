<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import { Loader } from '@googlemaps/js-api-loader';

const props = defineProps<{
    apiKey: string;
    center?: { lat: number; lng: number };
    zoom?: number;
}>();

const mapContainer = ref<HTMLElement | null>(null);
let map: google.maps.Map | any = null;
const markers = ref<Record<number, google.maps.Marker | any>>({});

const loader = new Loader({
    apiKey: props.apiKey,
    version: "weekly",
});

const darkStyle = [
    { elementType: "geometry", stylers: [{ color: "#212121" }] },
    { elementType: "labels.icon", stylers: [{ visibility: "off" }] },
    { elementType: "labels.text.fill", stylers: [{ color: "#757575" }] },
    { elementType: "labels.text.stroke", stylers: [{ color: "#212121" }] },
    {
        featureType: "administrative",
        elementType: "geometry",
        stylers: [{ color: "#757575" }],
    },
    {
        featureType: "administrative.country",
        elementType: "labels.text.fill",
        stylers: [{ color: "#9e9e9e" }],
    },
    {
        featureType: "administrative.land_parcel",
        stylers: [{ visibility: "off" }],
    },
    {
        featureType: "administrative.locality",
        elementType: "labels.text.fill",
        stylers: [{ color: "#bdbdbd" }],
    },
    {
        featureType: "poi",
        elementType: "labels.text.fill",
        stylers: [{ color: "#757575" }],
    },
    {
        featureType: "poi.park",
        elementType: "geometry",
        stylers: [{ color: "#181818" }],
    },
    {
        featureType: "poi.park",
        elementType: "labels.text.fill",
        stylers: [{ color: "#616161" }],
    },
    {
        featureType: "poi.park",
        elementType: "labels.text.stroke",
        stylers: [{ color: "#1b1b1b" }],
    },
    {
        featureType: "road",
        elementType: "geometry.fill",
        stylers: [{ color: "#2c2c2c" }],
    },
    {
        featureType: "road",
        elementType: "labels.text.fill",
        stylers: [{ color: "#8a8a8a" }],
    },
    {
        featureType: "road.arterial",
        elementType: "geometry",
        stylers: [{ color: "#373737" }],
    },
    {
        featureType: "road.highway",
        elementType: "geometry",
        stylers: [{ color: "#3c3c3c" }],
    },
    {
        featureType: "road.highway.controlled_access",
        elementType: "geometry",
        stylers: [{ color: "#4e4e4e" }],
    },
    {
        featureType: "road.local",
        elementType: "labels.text.fill",
        stylers: [{ color: "#616161" }],
    },
    {
        featureType: "transit",
        elementType: "labels.text.fill",
        stylers: [{ color: "#757575" }],
    },
    {
        featureType: "water",
        elementType: "geometry",
        stylers: [{ color: "#000000" }],
    },
    {
        featureType: "water",
        elementType: "labels.text.fill",
        stylers: [{ color: "#3d3d3d" }],
    },
];

onMounted(async () => {
    if (!mapContainer.value) return;

    await (loader as any).load();

    map = new google.maps.Map(mapContainer.value, {
        center: props.center || { lat: 6.5244, lng: 3.3792 },
        zoom: props.zoom || 14,
        styles: darkStyle,
        disableDefaultUI: true,
        backgroundColor: '#0a0a0a',
    });

    // Listen for Fleet Updates via Reverb
    if (window.Echo) {
        window.Echo.channel('fleet-telemetry')
            .listen('.BusLocationUpdated', (e: any) => {
                updateMarker(e);
            });
    }
});

function updateMarker(data: any) {
    const position = { lat: data.current_lat, lng: data.current_lng };
    
    if (markers.value[data.id]) {
        // Smoothly animate existing marker
        markers.value[data.id].setPosition(position);
    } else {
        // Create new marker
        markers.value[data.id] = new google.maps.Marker({
            position,
            map,
            title: data.vehicle_number,
            icon: {
                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                scale: 5,
                fillColor: "#6366f1", // Indigo-500
                fillOpacity: 1,
                strokeWeight: 2,
                strokeColor: "#ffffff",
                rotation: data.heading || 0,
            },
        });
    }
}

onUnmounted(() => {
    if (window.Echo) {
        window.Echo.leave('fleet-telemetry');
    }
});
</script>

<template>
    <div ref="mapContainer" class="w-full h-full rounded-2xl overflow-hidden shadow-inner bg-black/50">
        <!-- Optional: API Key Warning Overlay -->
        <div v-if="apiKey === 'YOUR_KEY_HERE'" class="absolute inset-0 flex items-center justify-center bg-black/80 z-20 p-8 text-center ring-1 ring-white/10 rounded-2xl">
            <div>
                <div class="h-12 w-12 rounded-full bg-amber-500/10 flex items-center justify-center mx-auto mb-4 border border-amber-500/20">
                    <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h5 class="text-white font-bold mb-2">Maps API Key Required</h5>
                <p class="text-xs text-gray-500">Please provide a valid Google Maps API Key in your .env to enable real-time tracking.</p>
            </div>
        </div>
    </div>
</template>
