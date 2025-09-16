<template>
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div 
            v-for="particle in particles" 
            :key="particle.id"
            class="absolute animate-float-particle"
            :style="{
                left: particle.x + '%',
                top: particle.y + '%',
                animationDelay: particle.delay + 's',
                animationDuration: particle.duration + 's'
            }"
        >
            <div 
                class="rounded-full opacity-20"
                :class="[
                    particle.color,
                    particle.size
                ]"
            ></div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
    count: {
        type: Number,
        default: 15
    },
    colors: {
        type: Array,
        default: () => ['bg-red-400', 'bg-pink-400', 'bg-red-300', 'bg-pink-300']
    },
    sizes: {
        type: Array,
        default: () => ['w-1 h-1', 'w-2 h-2', 'w-3 h-3']
    }
})

const particles = ref([])

const generateParticles = () => {
    particles.value = []
    for (let i = 0; i < props.count; i++) {
        particles.value.push({
            id: i,
            x: Math.random() * 100,
            y: Math.random() * 100,
            color: props.colors[Math.floor(Math.random() * props.colors.length)],
            size: props.sizes[Math.floor(Math.random() * props.sizes.length)],
            delay: Math.random() * 4,
            duration: 4 + Math.random() * 4
        })
    }
}

onMounted(() => {
    generateParticles()
})
</script>

<style scoped>
@keyframes float-particle {
    0%, 100% {
        transform: translateY(0px) translateX(0px) rotate(0deg);
    }
    25% {
        transform: translateY(-20px) translateX(10px) rotate(90deg);
    }
    50% {
        transform: translateY(-10px) translateX(-10px) rotate(180deg);
    }
    75% {
        transform: translateY(-30px) translateX(5px) rotate(270deg);
    }
}

.animate-float-particle {
    animation: float-particle linear infinite;
}
</style>