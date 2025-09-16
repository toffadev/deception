<template>
    <!-- Modal de don solidaire -->
    <div 
        v-if="show" 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto"
        @click="closeModal"
    >
        <div 
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 my-8 transform transition-all duration-300 max-h-[90vh] overflow-y-auto"
            @click.stop
        >
            <!-- Header du modal -->
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-heart text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Soutenir les malvoyants</h3>
                            <p class="text-sm text-gray-500">Projet solidaire</p>
                        </div>
                    </div>
                    <button 
                        @click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Contenu du modal -->
            <div class="p-4">
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                    Votre don contribue directement aux projets d'aide aux personnes malvoyantes ou aveugles au Bénin. 
                    Chaque contribution fait la différence dans leur vie quotidienne.
                </p>

                <!-- Options de montant -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <button 
                        v-for="option in donationOptions" 
                        :key="option.value"
                        @click="selectAmount(option)"
                        :class="[
                            'p-4 rounded-xl border-2 transition-all duration-200 text-center hover:shadow-md',
                            selectedAmount?.value === option.value
                                ? 'border-indigo-500 bg-indigo-50 text-indigo-700'
                                : 'border-gray-200 hover:border-indigo-300'
                        ]"
                    >
                        <div class="text-2xl mb-1">{{ option.emoji }}</div>
                        <div class="font-semibold text-sm">{{ option.label }}</div>
                    </button>
                </div>

                <!-- Montant personnalisé -->
                <div v-if="selectedAmount?.value === 'custom'" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Montant personnalisé</label>
                    <div class="relative">
                        <input
                            v-model.number="customAmount"
                            type="number"
                            min="1"
                            max="500"
                            step="1"
                            class="w-full pl-8 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="0"
                        >
                        <span class="absolute left-3 top-3 text-gray-500">€</span>
                        <span class="absolute right-3 top-3 text-xs text-gray-400">Max 500€</span>
                    </div>
                </div>

                <!-- Message optionnel -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message personnel (optionnel)</label>
                    <textarea
                        v-model="message"
                        rows="3"
                        maxlength="500"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                        placeholder="Votre message de soutien..."
                    ></textarea>
                    <div class="text-xs text-gray-400 text-right mt-1">{{ message.length }}/500</div>
                </div>

                <!-- Option anonyme -->
                <div class="mb-4">
                    <label class="flex items-center">
                        <input
                            v-model="isAnonymous"
                            type="checkbox"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">Don anonyme</span>
                    </label>
                </div>

                <!-- Boutons d'action -->
                <div class="flex space-x-3">
                    <button 
                        @click="closeModal"
                        class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-lg font-medium transition-colors hover:bg-gray-200"
                    >
                        Annuler
                    </button>
                    <button 
                        @click="processDonation"
                        :disabled="!canSubmit"
                        :class="[
                            'flex-1 py-3 rounded-lg font-medium transition-all duration-200',
                            canSubmit
                                ? 'bg-indigo-500 hover:bg-indigo-600 text-white shadow-md hover:shadow-lg'
                                : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                        ]"
                    >
                        <span v-if="processing" class="flex items-center justify-center">
                            <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Traitement...
                        </span>
                        <span v-else>
                            Donner {{ finalAmount }}€
                        </span>
                    </button>
                </div>

                <!-- Connexion requise -->
                <div v-if="!isAuthenticated" class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-800 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Vous devez être connecté pour faire un don.
                        <a href="/auth/login" class="font-medium underline ml-1">Se connecter</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'erreur -->
    <div v-if="showErrorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4">
                            <div class="p-4 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Erreur</h3>
                <p class="text-sm text-gray-600 mb-4">{{ errorMessage }}</p>
                <div class="flex space-x-3">
                    <button 
                        @click="closeErrorModal"
                        class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-lg font-medium transition-colors hover:bg-gray-200"
                    >
                        Annuler
                    </button>
                    <button 
                        @click="retry"
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-medium transition-colors"
                    >
                        Réessayer
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    show: Boolean,
    isAuthenticated: Boolean,
    selectedProject: Object // Projet de solidarité sélectionné
})

const emit = defineEmits(['close', 'donation-completed'])

// État local
const showErrorModal = ref(false)
const selectedAmount = ref(null)
const customAmount = ref(0)
const message = ref('')
const isAnonymous = ref(false)
const processing = ref(false)
const errorMessage = ref('')

const donationOptions = [
    { value: 10, emoji: '🤝', label: '10€' },
    { value: 25, emoji: '💛', label: '25€' },
    { value: 50, emoji: '❤️', label: '50€' },
    { value: 'custom', emoji: '💝', label: 'Autre' }
]

// Computed
const finalAmount = computed(() => {
    if (!selectedAmount.value) return 0
    if (selectedAmount.value.value === 'custom') {
        return customAmount.value || 0
    }
    return selectedAmount.value.value
})

const canSubmit = computed(() => {
    return selectedAmount.value && 
           finalAmount.value > 0 && 
           finalAmount.value <= 500 && 
           !processing.value &&
           props.isAuthenticated
})

// Méthodes
const closeModal = () => {
    emit('close')
    resetForm()
}

const closeErrorModal = () => {
    showErrorModal.value = false
    errorMessage.value = ''
}

const selectAmount = (option) => {
    selectedAmount.value = option
    if (option.value !== 'custom') {
        customAmount.value = 0
    }
}

const resetForm = () => {
    selectedAmount.value = null
    customAmount.value = 0
    message.value = ''
    isAnonymous.value = false
}

const showError = (message) => {
    errorMessage.value = message
    showErrorModal.value = true
}

const retry = () => {
    closeErrorModal()
    processDonation()
}

const processDonation = async () => {
    if (!canSubmit.value) return
    
    processing.value = true
    
    try {
        // Étape 1: Créer la session de don solidaire
        const response = await fetch('/solidarity/donate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                amount: finalAmount.value,
                message: message.value.trim() || null,
                is_anonymous: isAnonymous.value,
                project_id: props.selectedProject?.id || null // Optionnel si un projet spécifique
            })
        })
        
        const data = await response.json()
        
        if (data.success) {
            // Rediriger directement vers Stripe Checkout
            window.location.href = data.checkout_url
        } else {
            showError(data.message || 'Une erreur est survenue lors du traitement du don.')
        }
    } catch (error) {
        console.error('Erreur lors du don solidaire:', error)
        showError('Une erreur réseau est survenue. Veuillez réessayer.')
    } finally {
        processing.value = false
    }
}
</script>