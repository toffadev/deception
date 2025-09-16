<template>
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 md:p-8">
            <h2 class="title-font text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-heart text-red-500 mr-3"></i> Soutenez {{ publication.author_name }}
            </h2>
            
            <p class="text-gray-700 mb-6">
                Si cette publication vous a touché, vous pouvez soutenir son auteur en lui offrant un café virtuel. 
                Votre soutien sera directement versé à l'auteur et l'encouragera à continuer d'écrire.
            </p>
            
            <!-- Options de don -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <button 
                    v-for="option in donationOptions" 
                    :key="option.value"
                    @click="selectDonationAmount(option)"
                    :class="[
                        'donation-option p-4 rounded-lg text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-md',
                        selectedDonation?.value === option.value 
                            ? 'bg-red-50 border-2 border-red-300 text-red-500 ring-2 ring-red-200' 
                            : 'bg-white border-2 border-red-100 text-red-500 hover:border-red-300 hover:bg-red-50'
                    ]"
                >
                    <div class="text-2xl mb-2">{{ option.emoji }}</div>
                    <div class="font-bold">{{ option.label }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ option.description }}</div>
                </button>
            </div>

            <!-- Montant personnalisé -->
            <div v-if="selectedDonation?.value === 'custom'" class="mb-4">
                <label for="customAmount" class="block text-gray-700 font-medium mb-2">Montant personnalisé (€)</label>
                <input 
                    v-model.number="customAmount"
                    type="number" 
                    id="customAmount"
                    min="1"
                    max="500"
                    step="0.01"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                    placeholder="Entrez un montant..."
                >
            </div>
            
            <!-- Message personnel -->
            <div class="mb-6">
                <label for="donationMessage" class="block text-gray-700 font-medium mb-2">Message personnel (optionnel)</label>
                <textarea 
                    v-model="donationForm.message"
                    id="donationMessage" 
                    rows="3" 
                    maxlength="500"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none" 
                    placeholder="Écrivez un message d'encouragement..."
                ></textarea>
                <div class="text-right text-xs text-gray-500 mt-1">
                    {{ donationForm.message.length }}/500 caractères
                </div>
            </div>

            <!-- Option anonymat -->
            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input v-model="donationForm.is_anonymous" type="checkbox" class="form-checkbox text-red-500 rounded">
                    <span class="ml-2 text-gray-700">Faire un don anonyme</span>
                </label>
            </div>
            
            <!-- Bouton de soumission -->
            <button 
                @click="processDonation"
                :disabled="!canSubmit || donationForm.processing"
                class="w-full bg-red-500 hover:bg-red-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-6 py-4 rounded-lg font-bold transition duration-300 flex items-center justify-center relative"
            >
                <span v-if="donationForm.processing" class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
                <span :class="{ 'invisible': donationForm.processing }">
                    <i class="fas fa-coffee mr-3"></i> 
                    Offrir {{ finalAmount }}€ et soutenir l'auteur
                </span>
            </button>

            <!-- Informations de sécurité -->
            <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-start">
                    <i class="fas fa-shield-alt text-blue-500 text-lg mt-0.5 mr-3"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Paiement sécurisé avec Stripe</p>
                        <p>Vos informations de paiement sont protégées et cryptées. Aucune donnée bancaire n'est stockée sur nos serveurs.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmation -->
        <div v-if="showConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-check text-green-500 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Don confirmé !</h3>
                    </div>
                    
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Merci pour votre générosité ! Votre don de {{ finalAmount }}€ a été envoyé à {{ publication.author_name }}.
                        {{ donationForm.message ? 'Votre message a également été transmis.' : '' }}
                    </p>
                    
                    <div class="flex justify-end">
                        <button 
                            @click="closeConfirmModal"
                            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200"
                        >
                            Parfait !
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal d'erreur -->
        <div v-if="showErrorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Erreur de paiement</h3>
                    </div>
                    
                    <p class="text-gray-600 mb-6 leading-relaxed">{{ errorMessage }}</p>
                    
                    <div class="flex justify-end space-x-3">
                        <button 
                            @click="closeErrorModal"
                            class="text-gray-500 hover:text-gray-700 px-4 py-2 font-medium transition duration-200"
                        >
                            Annuler
                        </button>
                        <button 
                            @click="closeErrorModal"
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200"
                        >
                            Réessayer
                        </button>
                    </div>
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
const selectedDonation = ref(null)
const customAmount = ref(0)
const showConfirmModal = ref(false)
const showErrorModal = ref(false)
const errorMessage = ref('')

// Formulaire de don
const donationForm = reactive({
    message: '',
    is_anonymous: false,
    processing: false
})

// Options de don prédéfinies
const donationOptions = [
    {
        value: 1,
        emoji: '☕',
        label: '1€',
        description: 'Un café'
    },
    {
        value: 3,
        emoji: '☕☕',
        label: '3€', 
        description: 'Un café et un croissant'
    },
    {
        value: 5,
        emoji: '☕☕☕',
        label: '5€',
        description: 'Un vrai moment de réconfort'
    },
    {
        value: 'custom',
        emoji: '💝',
        label: 'Montant libre',
        description: 'Autre somme'
    }
]

// Computed
const finalAmount = computed(() => {
    if (!selectedDonation.value) return 0
    if (selectedDonation.value.value === 'custom') {
        return customAmount.value || 0
    }
    return selectedDonation.value.value
})

const canSubmit = computed(() => {
    return selectedDonation.value && 
           finalAmount.value > 0 && 
           finalAmount.value <= 500 && 
           !donationForm.processing &&
           props.isAuthenticated
})

// Méthodes
const selectDonationAmount = (option) => {
    selectedDonation.value = option
    if (option.value !== 'custom') {
        customAmount.value = 0
    }
}

const processDonation = async () => {
    if (!canSubmit.value) {
        if (!props.isAuthenticated) {
            router.visit('/auth/login')
        }
        return
    }
    
    donationForm.processing = true
    
    try {
        const response = await fetch(`/publication/${props.publication.id}/donate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                amount: finalAmount.value,
                message: donationForm.message.trim() || null,
                is_anonymous: donationForm.is_anonymous
            })
        })
        
        const data = await response.json()
        
        if (data.success) {
            // Simuler le succès du paiement (en attendant l'intégration Stripe complète)
            showConfirmModal.value = true
            
            // Émettre l'événement pour mettre à jour l'interface parente
            emit('donation-completed', {
                amount: finalAmount.value,
                message: donationForm.message.trim(),
                is_anonymous: donationForm.is_anonymous
            })
            
            // Reset du formulaire
            resetForm()
            
        } else {
            showError(data.message || 'Une erreur est survenue lors du traitement du don.')
        }
    } catch (error) {
        console.error('Erreur lors du don:', error)
        showError('Une erreur réseau est survenue. Veuillez réessayer.')
    } finally {
        donationForm.processing = false
    }
}

const showError = (message) => {
    errorMessage.value = message
    showErrorModal.value = true
}

const closeConfirmModal = () => {
    showConfirmModal.value = false
}

const closeErrorModal = () => {
    showErrorModal.value = false
    errorMessage.value = ''
}

const resetForm = () => {
    selectedDonation.value = null
    customAmount.value = 0
    donationForm.message = ''
    donationForm.is_anonymous = false
}

// Fonction pour l'intégration Stripe future
const processStripePayment = async (paymentData) => {
    // Cette fonction sera utilisée quand Stripe sera configuré
    // const stripe = Stripe(window.stripePublishableKey)
    // const { error } = await stripe.confirmCardPayment(paymentData.client_secret)
    // return !error
    return true // Simulé pour l'instant
}
</script>

<style scoped>
.donation-option:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.title-font {
    font-family: 'Playfair Display', serif;
}
</style>