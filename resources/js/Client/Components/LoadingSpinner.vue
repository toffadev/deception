<template>
    <div class="flex flex-col items-center justify-center">
        <div class="relative">
            <!-- Spinner principal -->
            <div class="w-12 h-12 border-4 border-gray-200 border-t-red-500 rounded-full animate-spin"></div>
            
            <!-- Cercle intérieur -->
            <div class="absolute top-1 left-1 w-10 h-10 border-2 border-gray-100 border-b-pink-400 rounded-full animate-spin-reverse"></div>
            
            <!-- Point central -->
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
        </div>
        
        <div v-if="message" class="mt-4 text-center">
            <p class="text-gray-600 font-medium">{{ message }}</p>
            <p v-if="subMessage" class="text-gray-500 text-sm mt-1">{{ subMessage }}</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    message: String,
    subMessage: String
})
</script>

<style scoped>
@keyframes spin-reverse {
    from {
        transform: rotate(360deg);
    }
    to {
        transform: rotate(0deg);
    }
}

.animate-spin-reverse {
    animation: spin-reverse 1.5s linear infinite;
}
</style>