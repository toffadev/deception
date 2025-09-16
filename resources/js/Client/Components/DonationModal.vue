<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 transform transition-all duration-200 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-coffee text-amber-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Offrir un café</h3>
                            <p class="text-sm text-gray-500">Soutenez {{ publication.author_name }}</p>
                        </div>
                    </div>
                    <button @click="close" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Publication info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-gray-200 rounded-full mr-3 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-800 truncate">{{ publication.title }}</h4>
                            <p class="text-sm text-gray-500 mb-2">Par {{ publication.author_name }}</p>
                            <p class="text-xs text-gray-600 leading-relaxed line-clamp-2">{{ publication.excerpt }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick amounts -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Montants suggérés</h4>
                    <div class="grid grid-cols-3 gap-3">
                        <button 
                            v-for="amount in suggestedAmounts" 
                            :key="amount"
                            @click="selectAmount(amount)"
                            :class="[
                                'p-3 rounded-lg border transition-all duration-200 text-center',
                                selectedAmount === amount 
                                    ? 'border-amber-500 bg-amber-50 text-amber-700' 
                                    : 'border-gray-200 hover:border-amber-300 hover:bg-amber-50'
                            ]"
                        >
                            <div class="text-lg font-bold">{{ amount }}€</div>
                            <div class="text-xs text-gray-500">{{ getAmountLabel(amount) }}</div>
                        </button>
                    </div>
                </div>

                <!-- Custom amount -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Montant personnalisé
                    </label>
                    <div class="relative">
                        <input 
                            v-model.number="customAmount"
                            type="number" 
                            min="1" 
                            max="500" 
                            step="0.01"
                            class="w-full px-4 py-3 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            placeholder="Montant en euros"
                            @input="selectedAmount = null"
                        >
                        <span class="absolute right-3 top-3.5 text-gray-500">€</span>
                    </div>
                    <span v-if="errors.amount" class="text-red-500 text-xs mt-1">{{ errors.amount }}</span>
                </div>

                <!-- Message option -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Message d'encouragement (optionnel)
                    </label>
                    <textarea 
                        v-model="message"
                        rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none"
                        placeholder="Écrivez un petit mot d'encouragement à l'auteur..."
                        maxlength="200"
                    ></textarea>
                    <div class="text-xs text-gray-500 text-right mt-1">{{ message.length }}/200</div>
                </div>

                <!-- Anonymous option -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input v-model="isAnonymous" type="checkbox" class="form-checkbox text-amber-500 rounded mr-3">
                        <span class="text-sm text-gray-700">Faire un don anonyme</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1 ml-6">L'auteur ne verra pas votre nom</p>
                </div>

                <!-- Total -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-amber-800">Total du don :</span>
                        <span class="text-xl font-bold text-amber-800">{{ finalAmount }}€</span>
                    </div>
                    <p class="text-xs text-amber-700 mt-1">100% reversé à l'auteur</p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <button 
                        type="button"
                        @click="close"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors"
                        :disabled="processing"
                    >
                        Annuler
                    </button>
                    <button 
                        @click="processDonation"
                        :disabled="processing || !canSubmit"
                        class="bg-amber-500 hover:bg-amber-600 disabled:bg-gray-400 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center"
                    >
                        <svg v-if="processing" class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <i v-else class="fas fa-coffee mr-2"></i>
                        {{ processing ? 'Traitement...' : 'Offrir ce café' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div v-if="showErrorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Erreur</h3>
                <p class="text-gray-600 mb-6">{{ errorMessage }}</p>
                <button 
                    @click="closeErrorModal"
                    class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200"
                >
                    Compris
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    show: Boolean,
    publication: Object,
    isAuthenticated: Boolean
})

// Emits
const emit = defineEmits(['close', 'donation-completed'])

// State
const selectedAmount = ref(null)
const customAmount = ref(0)
const message = ref('')
const isAnonymous = ref(false)
const processing = ref(false)
const showErrorModal = ref(false)
const errorMessage = ref('')
const errors = ref({})

// Suggested amounts with labels
const suggestedAmounts = [3, 5, 10]

const amountLabels = {
    3: 'Un café',
    5: 'Un croissant',
    10: 'Un petit déj\'',
}

// Computed
const finalAmount = computed(() => {
    return selectedAmount.value || customAmount.value || 0
})

const canSubmit = computed(() => {
    return props.isAuthenticated && finalAmount.value >= 1 && finalAmount.value <= 500 && !processing.value
})

// Methods
const getAmountLabel = (amount) => {
    return amountLabels[amount] || 'Merci !'
}

const selectAmount = (amount) => {
    selectedAmount.value = amount
    customAmount.value = 0
}

const close = () => {
    if (!processing.value) {
        emit('close')
        resetForm()
    }
}

const resetForm = () => {
    selectedAmount.value = null
    customAmount.value = 0
    message.value = ''
    isAnonymous.value = false
    errors.value = {}
    showErrorModal.value = false
    errorMessage.value = ''
}

const showError = (msg) => {
    errorMessage.value = msg
    showErrorModal.value = true
}

const closeErrorModal = () => {
    showErrorModal.value = false
    errorMessage.value = ''
}

const processDonation = async () => {
    if (!canSubmit.value) {
        if (!props.isAuthenticated) {
            router.visit('/auth/login')
        }
        return
    }
    
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

// Watch for show prop changes to reset form
watch(() => props.show, (newShow) => {
    if (!newShow) {
        resetForm()
    }
})
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

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>