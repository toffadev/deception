<template>
    <!-- Bouton de don discret dans le coin -->
    <div class="mt-6 flex justify-end">
        <button 
            v-if="isAuthenticated"
            @click="openModal"
            class="inline-flex items-center px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-800 text-sm font-medium rounded-lg border border-amber-300 transition-all duration-200 hover:shadow-sm"
        >
            <i class="fas fa-coffee text-amber-600 mr-2"></i>
            Offrir un café à l'auteur
        </button>
        <button 
            v-else
            @click="openModal"
            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-blue-100 text-gray-600 hover:text-blue-600 text-sm font-medium rounded-lg border border-gray-300 hover:border-blue-300 transition-all duration-200 hover:shadow-sm"
            title="Connectez-vous pour soutenir l'auteur"
        >
            <i class="fas fa-sign-in-alt text-gray-500 hover:text-blue-500 mr-2 transition-colors"></i>
            Se connecter pour offrir un café
        </button>
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
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-coffee text-amber-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Offrir un café</h3>
                            <p class="text-sm text-gray-500">à {{ publication.author_name }}</p>
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
                    Si cette publication vous a touché, soutenez son auteur avec un petit geste symbolique.
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
                                ? 'bg-amber-50 border-2 border-amber-300 text-amber-700' 
                                : 'bg-gray-50 border-2 border-gray-200 text-gray-600 hover:border-amber-200 hover:bg-amber-50'
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
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" 
                        placeholder="Montant en €"
                    >
                </div>

                <!-- Message optionnel -->
                <div class="mb-4">
                    <textarea 
                        v-model="message"
                        rows="3" 
                        maxlength="200"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none text-sm" 
                        placeholder="Message d'encouragement (optionnel)"
                    ></textarea>
                    <div class="text-right text-xs text-gray-400 mt-1">
                        {{ message.length }}/200
                    </div>
                </div>

                <!-- Option anonymat -->
                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input v-model="isAnonymous" type="checkbox" class="form-checkbox text-amber-500 rounded">
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
                        class="flex-1 bg-amber-500 hover:bg-amber-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white py-3 rounded-lg font-medium transition-colors flex items-center justify-center"
                    >
                        <span v-if="processing" class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Traitement...
                        </span>
                        <span v-else>
                            Offrir {{ finalAmount }}€
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
                    <i class="fas fa-check text-green-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Merci !</h3>
                <p class="text-sm text-gray-600 mb-6">
                    Votre don de {{ finalAmount }}€ a été envoyé à {{ publication.author_name }}.
                </p>
                <button 
                    @click="closeSuccessModal"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition-colors"
                >
                    Parfait !
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
import { ref, reactive, computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    publication: Object,
    isAuthenticated: Boolean
})

const emit = defineEmits(['donation-completed'])

// État local
const showModal = ref(false)
const showSuccessModal = ref(false)
const showErrorModal = ref(false)
const selectedAmount = ref(null)
const customAmount = ref(0)
const message = ref('')
const isAnonymous = ref(false)
const processing = ref(false)
const errorMessage = ref('')

// Options de don
const donationOptions = [
    { value: 1, emoji: '☕', label: '1€' },
    { value: 3, emoji: '☕☕', label: '3€' },
    { value: 5, emoji: '🍰', label: '5€' },
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
        // Étape 1: Créer le Payment Intent
        const response = await fetch(`/publication/${props.publication.id}/donate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                amount: finalAmount.value,
                message: message.value.trim() || null,
                is_anonymous: isAnonymous.value
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
        console.error('Erreur lors du don:', error)
        showError('Une erreur réseau est survenue. Veuillez réessayer.')
    } finally {
        processing.value = false
    }
}
</script>

<style scoped>
.form-checkbox {
    border-radius: 0.25rem;
    border-color: #d1d5db;
    color: #f59e0b;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.form-checkbox:focus {
    border-color: #fcd34d;
    box-shadow: 0 0 0 3px rgba(252, 211, 77, 0.1);
}
</style>