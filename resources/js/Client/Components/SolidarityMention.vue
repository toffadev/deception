<template>
    <!-- Bandeau flottant solidarité malvoyants -->
    <div v-if="!isHidden" class="fixed bottom-8 left-8 right-8 md:left-auto md:right-8 md:max-w-sm z-50">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-xl shadow-xl border border-indigo-500 overflow-hidden group hover:shadow-2xl transition-all duration-300">
            <!-- Bouton de fermeture -->
            <button 
                v-if="!isMinimized"
                @click="close"
                class="absolute top-2 right-2 text-white/70 hover:text-white transition-colors"
                title="Fermer (réapparaîtra dans 5 minutes)"
            >
                <i class="fas fa-times text-sm"></i>
            </button>
            
            <!-- Version complète -->
            <div v-if="!isMinimized" class="p-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-heart text-white text-lg"></i>
                        </div>
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="text-white font-semibold text-sm mb-1 flex items-center">
                            <span>Soutenez les malvoyants</span>
                            <span class="ml-2 text-xs bg-white/20 text-white px-2 py-0.5 rounded-full">
                                Solidarité
                            </span>
                        </h3>
                        
                        <p class="text-white/90 text-xs leading-relaxed mb-3">
                            Vos dons aident concrètement les personnes en situation de handicap visuel. 
                            <strong>Découvrez nos projets solidaires.</strong>
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-xs text-white/70 space-x-3">
                                <span class="flex items-center">
                                    <i class="fas fa-users mr-1"></i>
                                    Projets actifs
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    Sécurisé
                                </span>
                            </div>
                            
                            <Link 
                                href="/solidarity"
                                class="inline-flex items-center text-xs font-medium bg-white text-indigo-600 px-3 py-1.5 rounded-lg hover:bg-white/90 transition-colors duration-200"
                            >
                                Découvrir
                                <i class="fas fa-arrow-right ml-1 text-xs"></i>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Version minimisée -->
            <div v-else class="p-3">
                <button 
                    @click="expand"
                    class="flex items-center space-x-2 w-full text-white hover:text-white/80 transition-colors"
                >
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-white text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Solidarité malvoyants</span>
                    <i class="fas fa-chevron-up text-xs ml-auto"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

// État du composant
const isMinimized = ref(true) // Par défaut plié
const isHidden = ref(false) // Pour gérer la fermeture complète

// Méthodes
const minimize = () => {
    isMinimized.value = true
}

const expand = () => {
    isMinimized.value = false
}

const close = () => {
    isHidden.value = true
    // Réapparition après 5 minutes
    setTimeout(() => {
        isHidden.value = false
        isMinimized.value = true // Réapparaître en mode plié
    }, 5 * 60 * 1000) // 5 minutes en millisecondes
}
</script>

<style scoped>
/* Effet de survol subtil */
.group:hover .transition-transform {
    transform: translateX(2px);
}
</style>