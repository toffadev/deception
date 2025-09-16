<template>
    <div class="relative py-16 md:py-20 overflow-hidden">
        <!-- Background avec parallaxe -->
        <div 
            class="absolute inset-0 bg-gradient-to-r"
            :class="gradientClass"
            ref="backgroundRef"
        >
            <!-- Particules flottantes -->
            <FloatingParticles />
            
            <!-- Pattern overlay -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.4) 1px, transparent 0); background-size: 20px 20px;"></div>
            </div>
            
            <!-- Gradient overlay -->
            <div class="absolute inset-0 bg-black opacity-20"></div>
        </div>
        
        <!-- Contenu -->
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Badge animé -->
                <div v-if="badge" class="inline-flex items-center bg-white bg-opacity-90 backdrop-blur-sm rounded-full px-6 py-2 mb-6 animate-fade-in-up shadow-lg">
                    <i v-if="badgeIcon" :class="badgeIcon + ' mr-2 text-red-500'"></i>
                    <span class="text-gray-700 font-medium text-sm">{{ badge }}</span>
                </div>
                
                <!-- Titre principal -->
                <h1 class="title-font text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 animate-fade-in-up" style="animation-delay: 0.2s">
                    {{ title }}
                </h1>
                
                <!-- Sous-titre -->
                <p v-if="subtitle" class="text-xl md:text-2xl text-white mb-8 leading-relaxed animate-fade-in-up" style="animation-delay: 0.4s">
                    {{ subtitle }}
                </p>
                
                <!-- Boutons d'action -->
                <div v-if="$slots.actions" class="flex flex-col sm:flex-row justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.6s">
                    <slot name="actions"></slot>
                </div>
            </div>
        </div>
        
        <!-- Flèche de défilement -->
        <div v-if="showScrollHint" class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import FloatingParticles from './FloatingParticles.vue'

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    subtitle: String,
    badge: String,
    badgeIcon: String,
    gradientClass: {
        type: String,
        default: 'from-red-500 to-pink-500'
    },
    showScrollHint: {
        type: Boolean,
        default: false
    },
    parallax: {
        type: Boolean,
        default: true
    }
})

const backgroundRef = ref(null)

const handleScroll = () => {
    if (!props.parallax || !backgroundRef.value) return
    
    const scrolled = window.pageYOffset
    const parallaxSpeed = 0.5
    
    backgroundRef.value.style.transform = `translateY(${scrolled * parallaxSpeed}px)`
}

onMounted(() => {
    if (props.parallax) {
        window.addEventListener('scroll', handleScroll)
    }
})

onUnmounted(() => {
    if (props.parallax) {
        window.removeEventListener('scroll', handleScroll)
    }
})
</script>

<style scoped>
.title-font {
    font-family: 'Playfair Display', serif;
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out forwards;
    opacity: 0;
}
</style>