<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200" @click.stop>
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Signaler ce contenu</h3>
                            <p class="text-sm text-gray-500">Aidez-nous à maintenir une communauté saine</p>
                        </div>
                    </div>
                    <button @click="close" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Form -->
                <form @submit.prevent="submitReport" class="space-y-4">
                    <!-- Reason selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Motif du signalement *
                        </label>
                        <div class="space-y-2">
                            <label v-for="(label, value) in reasons" :key="value" class="flex items-center">
                                <input 
                                    v-model="form.reason" 
                                    :value="value" 
                                    type="radio" 
                                    class="form-radio text-red-500 mr-3"
                                    required
                                >
                                <span class="text-sm text-gray-700">{{ label }}</span>
                            </label>
                        </div>
                        <span v-if="errors.reason" class="text-red-500 text-xs mt-1">{{ errors.reason }}</span>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description détaillée *
                        </label>
                        <textarea 
                            v-model="form.description"
                            id="description"
                            rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                            placeholder="Décrivez en détail pourquoi vous signalez ce contenu..."
                            maxlength="1000"
                            required
                        ></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <span v-if="errors.description" class="text-red-500 text-xs">{{ errors.description }}</span>
                            <span class="text-xs text-gray-500">{{ form.description.length }}/1000</span>
                        </div>
                    </div>
                    
                    <!-- Important notice -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <div class="flex">
                            <i class="fas fa-info-circle text-amber-500 text-sm mt-0.5 mr-2"></i>
                            <div class="text-xs text-amber-800">
                                <p class="font-medium mb-1">Important :</p>
                                <ul class="space-y-1">
                                    <li>• Les signalements abusifs peuvent entraîner des sanctions</li>
                                    <li>• Notre équipe examine tous les signalements sous 24h</li>
                                    <li>• Vous serez notifié des suites données</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button 
                            type="button"
                            @click="close"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors"
                            :disabled="processing"
                        >
                            Annuler
                        </button>
                        <button 
                            type="submit"
                            :disabled="processing || !canSubmit"
                            class="bg-red-500 hover:bg-red-600 disabled:bg-gray-400 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center"
                        >
                            <svg v-if="processing" class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ processing ? 'Envoi...' : 'Envoyer le signalement' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-500 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Signalement envoyé</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Merci pour votre signalement. Notre équipe de modération l'examinera dans les plus brefs délais.
                </p>
                <button 
                    @click="closeSuccessModal"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200"
                >
                    Compris
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

// Props
const props = defineProps({
    show: Boolean,
    reportableType: String,
    reportableId: Number
})

// Emits
const emit = defineEmits(['close', 'reported'])

// State
const processing = ref(false)
const showSuccessModal = ref(false)
const errors = ref({})

const form = ref({
    reason: '',
    description: ''
})

// Reasons mapping
const reasons = {
    'inappropriate_content': 'Contenu inapproprié',
    'harassment': 'Harcèlement',
    'spam': 'Spam',
    'violence': 'Violence',
    'hate_speech': 'Discours de haine',
    'misinformation': 'Désinformation',
    'other': 'Autre'
}

// Computed
const canSubmit = computed(() => {
    return form.value.reason && form.value.description.trim().length >= 10
})

// Methods
const close = () => {
    if (!processing.value) {
        emit('close')
        resetForm()
    }
}

const resetForm = () => {
    form.value.reason = ''
    form.value.description = ''
    errors.value = {}
}

const closeSuccessModal = () => {
    showSuccessModal.value = false
    close()
}

const submitReport = async () => {
    if (!canSubmit.value || processing.value) return
    
    processing.value = true
    errors.value = {}
    
    try {
        const response = await fetch('/report', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                reportable_type: props.reportableType,
                reportable_id: props.reportableId,
                reason: form.value.reason,
                description: form.value.description.trim()
            })
        })
        
        const data = await response.json()
        
        if (data.success) {
            showSuccessModal.value = true
            emit('reported', data)
        } else {
            // Handle validation errors
            if (response.status === 422 && data.errors) {
                errors.value = data.errors
            } else {
                errors.value = { general: data.message || 'Une erreur est survenue' }
            }
        }
    } catch (error) {
        console.error('Erreur lors du signalement:', error)
        errors.value = { general: 'Une erreur réseau est survenue' }
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
.form-radio {
    border-radius: 50%;
    border-color: #d1d5db;
    color: #ef4444;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.form-radio:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.form-radio:checked {
    background-color: #ef4444;
    border-color: #ef4444;
}
</style>