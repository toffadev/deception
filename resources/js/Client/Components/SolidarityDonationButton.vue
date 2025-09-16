<template>
    <!-- Bouton flottant solidarité malvoyants -->
    <div class="fixed bottom-8 right-8 z-40">
        <div class="relative group">
            <!-- Bouton principal -->
            <button 
                @click="openModal"
                class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 flex items-center"
            >
                <i class="fas fa-heart text-lg mr-2"></i>
                <span class="text-sm font-medium whitespace-nowrap">
                    Solidarité malvoyants
                </span>
            </button>
        </div>
    </div>

    <!-- Modal de don -->
    <div 
        v-if="showModal" 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click="closeModal"
    >
        <div 
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300"
            @click.stop
        >
            <!-- Header du modal -->
            <div class="p-6 border-b border-gray-100">
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
            <div class="p-6">
                <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                    Votre don contribue directement aux projets d'aide aux personnes malvoyantes au Bénin. 
                    Chaque contribution fait la différence dans leur vie quotidienne.
                </p>

                <!-- Options de montant -->
                <div class="grid grid-cols-4 gap-3 mb-6">
                    <button 
                        v-for="option in donationOptions" 
                        :key="option.value"
                        @click="selectAmount(option)"
                        :class="[
                            'p-3 rounded-xl text-center transition-all duration-200',
                            selectedAmount?.value === option.value 
                                ? 'bg-indigo-50 border-2 border-indigo-300 text-indigo-700' 
                                : 'bg-gray-50 border-2 border-gray-200 text-gray-600 hover:border-indigo-200 hover:bg-indigo-50'
                        ]"
                    >
                        <div class="text-lg mb-1">{{ option.emoji }}</div>
                        <div class="text-xs font-medium">{{ option.label }}</div>
                    </button>
                </div>

                <!-- Montant personnalisé -->
                <div v-if="selectedAmount?.value === 'custom'" class="mb-4">
                    <input 
                        v-model.number="customAmount"
                        type="number" 
                        min="1"
                        max="500"
                        step="0.01"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                        placeholder="Montant en €"
                    >
                </div>

                <!-- Message optionnel -->
                <div class="mb-4">
                    <textarea 
                        v-model="message"
                        rows="3" 
                        maxlength="200"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none text-sm" 
                        placeholder="Message de soutien (optionnel)"
                    ></textarea>
                    <div class="text-right text-xs text-gray-400 mt-1">
                        {{ message.length }}/200
                    </div>
                </div>

                <!-- Option anonymat -->
                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input v-model="isAnonymous" type="checkbox" class="form-checkbox text-indigo-500 rounded">
                        <span class="ml-2 text-sm text-gray-600">Don anonyme</span>
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
                        :disabled="!canSubmit || processing"
                        class="flex-1 bg-indigo-500 hover:bg-indigo-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white py-3 rounded-lg font-medium transition-colors flex items-center justify-center"
                    >
                        <span v-if="processing" class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
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

                <!-- Sécurité -->
                <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="flex items-center text-xs text-blue-700">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span>Paiement sécurisé via Stripe</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de succès -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-green-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Merci pour votre générosité !</h3>
                <p class="text-sm text-gray-600 mb-6">
                    Votre don de {{ successDonationAmount }}€ contribue aux projets solidaires pour les malvoyants.
                    Votre soutien fait la différence !
                </p>
                <button 
                    @click="closeSuccessModal"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition-colors"
                >
                    Continuer
                </button>
            </div>
        </div>
    </div>

    <!-- Modal d'erreur -->
    <div v-if="showErrorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Erreur</h3>
                <p class="text-sm text-gray-600 mb-6">{{ errorMessage }}</p>
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
import { ref, reactive, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    isAuthenticated: Boolean,
    selectedProject: Object // Projet de solidarité sélectionné
})

const emit = defineEmits(['donation-completed'])

// État local
const showModal = ref(false)
const showSuccessModal = ref(false)
const showErrorModal = ref(false)
const successDonationAmount = ref(0)
const selectedAmount = ref(null)
const customAmount = ref(0)
const message = ref('')
const isAnonymous = ref(false)
const processing = ref(false)
const errorMessage = ref('')

// Options de don pour solidarité
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
const openModal = () => {
    if (!props.isAuthenticated) {
        router.visit('/auth/login')
        return
    }
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    resetForm()
}

const closeSuccessModal = () => {
    showSuccessModal.value = false
    successDonationAmount.value = 0
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

// Vérifier le statut du paiement après retour de Stripe
const checkPaymentStatus = async (donationId) => {
    try {
        const response = await fetch(`/solidarity/donation/${donationId}/status`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        
        const data = await response.json()
        
        if (data.success && data.status === 'completed') {
            successDonationAmount.value = data.donation.amount
            showSuccessModal.value = true
            emit('donation-completed', data.donation)
        } else {
            showError('Le paiement n\'a pas pu être confirmé. Veuillez vérifier votre historique de transactions.')
        }
    } catch (error) {
        console.error('Erreur lors de la vérification du paiement:', error)
        showError('Erreur lors de la vérification du paiement.')
    }
}

// Détecter le retour de Stripe au montage du composant
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search)
    
    if (urlParams.get('payment_success') === 'true') {
        const donationId = urlParams.get('donation_id')
        // Vérifier que le donationId existe vraiment avant de vérifier le statut
        if (donationId && donationId !== 'null' && donationId !== '') {
            checkPaymentStatus(donationId)
        }
        
        // Nettoyer l'URL
        const newUrl = window.location.pathname
        window.history.replaceState({}, document.title, newUrl)
    }
    
    if (urlParams.get('payment_cancelled') === 'true') {
        showError('Le paiement a été annulé.')
        
        // Nettoyer l'URL
        const newUrl = window.location.pathname
        window.history.replaceState({}, document.title, newUrl)
    }
})
</script>

<style scoped>
.form-checkbox {
    border-radius: 0.25rem;
    border-color: #d1d5db;
    color: #6366f1;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.form-checkbox:focus {
    border-color: #a5b4fc;
    box-shadow: 0 0 0 3px rgba(165, 180, 252, 0.1);
}
</style>