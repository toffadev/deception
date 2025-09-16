<template>
    <MainLayout>
        <!-- Hero Section -->
        <section class="relative py-20 md:py-32 bg-gradient-to-r from-red-500 to-pink-500">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute inset-0 bg-black"></div>
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1423666639041-f56000c27a9a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1374&q=80')] bg-cover bg-center mix-blend-overlay"></div>
            </div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-3xl mx-auto text-center">
                    <h1 class="title-font text-4xl md:text-5xl font-bold text-white mb-6">
                        Contactez <span class="text-red-100">Von Tränen zu Taten</span>
                    </h1>
                    <h2 class="text-xl text-white mb-6">
                        Une question sur notre mission ? Une proposition de partenariat ? N'hésitez pas à nous contacter !
                    </h2>
                </div>
            </div>
        </section>

        <!-- Contact Cards Section -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-6xl mx-auto">
                    <div class="p-8 md:p-12">
                        <div class="text-center mb-10">
                            <h2 class="title-font text-3xl font-bold text-gray-800 mb-4">Comment pouvons-nous vous aider ?</h2>
                            <p class="text-gray-600 max-w-2xl mx-auto">
                                Choisissez le moyen de communication qui vous convient le mieux pour entrer en contact avec notre équipe 
                                dédiée au projet Von Tränen zu Taten.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <!-- Email Contact Card -->
                            <div class="bg-gray-50 rounded-xl p-6 shadow-md transform transition-transform hover:scale-105 hover:shadow-red-900/30 hover:shadow-xl">
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-4 shadow-lg">
                                        <i class="fas fa-envelope text-red-500 text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Par Email</h3>
                                    <p class="text-gray-600 mb-6">
                                        Envoyez-nous un email et nous vous répondrons dans les 48 heures
                                    </p>
                                    <a href="mailto:contact@solidarite-coeur-brise.org" 
                                       class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-full transition duration-300 flex items-center shadow-lg">
                                        <i class="fas fa-envelope mr-2"></i> Nous écrire
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Phone Contact Card -->
                            <div class="bg-gray-50 rounded-xl p-6 shadow-md transform transition-transform hover:scale-105 hover:shadow-pink-900/30 hover:shadow-xl">
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-20 h-20 bg-pink-100 rounded-full flex items-center justify-center mb-4 shadow-lg">
                                        <i class="fas fa-phone-alt text-pink-500 text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Par Téléphone</h3>
                                    <p class="text-gray-600 mb-6">
                                        Contactez directement notre équipe pour toute demande urgente
                                    </p>
                                    <a href="tel:+33123456789" 
                                       class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-3 px-6 rounded-full transition duration-300 flex items-center shadow-lg">
                                        <i class="fas fa-phone-alt mr-2"></i> Appeler
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Support/Help Contact Card -->
                            <div class="bg-gray-50 rounded-xl p-6 shadow-md transform transition-transform hover:scale-105 hover:shadow-blue-900/30 hover:shadow-xl">
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-4 shadow-lg">
                                        <i class="fas fa-question-circle text-blue-500 text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Centre d'Aide</h3>
                                    <p class="text-gray-600 mb-6">
                                        Consultez notre centre d'aide pour des réponses rapides aux questions fréquentes
                                    </p>
                                    <Link href="/solidarity" 
                                          class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-full transition duration-300 flex items-center shadow-lg">
                                        <i class="fas fa-question-circle mr-2"></i> Centre d'aide
                                    </Link>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Availability Hours Section -->
                        <div class="mt-16">
                            <div class="flex items-center justify-center mb-6">
                                <div class="h-0.5 bg-gray-300 flex-1"></div>
                                <h3 class="text-xl font-bold text-gray-800 px-4">Horaires de Disponibilité</h3>
                                <div class="h-0.5 bg-gray-300 flex-1"></div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl p-6 shadow-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex justify-between p-3 border-b border-gray-200">
                                        <span class="font-medium text-gray-700">Lundi - Vendredi</span>
                                        <span class="text-red-500 font-semibold">09h00 - 18h00</span>
                                    </div>
                                    <div class="flex justify-between p-3 border-b border-gray-200">
                                        <span class="font-medium text-gray-700">Samedi</span>
                                        <span class="text-red-500 font-semibold">10h00 - 16h00</span>
                                    </div>
                                    <div class="flex justify-between p-3 border-b border-gray-200 md:border-b-0">
                                        <span class="font-medium text-gray-700">Dimanche</span>
                                        <span class="text-gray-400">Fermé</span>
                                    </div>
                                    <div class="flex justify-between p-3">
                                        <span class="font-medium text-gray-700">Jours fériés</span>
                                        <span class="text-gray-400">Fermé</span>
                                    </div>
                                </div>
                                
                                <div class="mt-6 p-4 bg-red-50 rounded-lg border border-red-200">
                                    <p class="text-sm text-red-800 flex items-center">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <strong>Note importante :</strong> 
                                        <span class="ml-1">
                                            Pour les situations d'urgence émotionnelle, nous recommandons de contacter 
                                            <a href="tel:3114" class="underline font-semibold">3114</a> (numéro national de prévention du suicide, gratuit, 24h/24).
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Form Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-12">
                        <h2 class="title-font text-3xl font-bold text-gray-800 mb-4">Envoyez-nous un message</h2>
                        <p class="text-gray-600">
                            Vous avez une question spécifique ? Remplissez le formulaire ci-dessous et nous vous répondrons rapidement.
                        </p>
                    </div>
                    
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-8 shadow-lg">
                        <form @submit.prevent="submitContactForm" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-gray-700 font-medium mb-2">
                                        Nom complet *
                                    </label>
                                    <input 
                                        v-model="contactForm.name"
                                        type="text" 
                                        id="name" 
                                        required 
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                        :class="{ 'border-red-500': errors.name }"
                                        placeholder="Votre nom complet"
                                    >
                                    <span v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</span>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-gray-700 font-medium mb-2">
                                        Email *
                                    </label>
                                    <input 
                                        v-model="contactForm.email"
                                        type="email" 
                                        id="email" 
                                        required 
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                        :class="{ 'border-red-500': errors.email }"
                                        placeholder="votre.email@exemple.com"
                                    >
                                    <span v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</span>
                                </div>
                            </div>
                            
                            <div>
                                <label for="subject" class="block text-gray-700 font-medium mb-2">
                                    Sujet *
                                </label>
                                <select 
                                    v-model="contactForm.subject"
                                    id="subject" 
                                    required 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                    :class="{ 'border-red-500': errors.subject }"
                                >
                                    <option value="">Choisissez un sujet</option>
                                    <option value="Partenariat">Proposition de partenariat</option>
                                    <option value="Support technique">Support technique</option>
                                    <option value="Question générale">Question générale</option>
                                    <option value="Projet solidaire">Questions sur le projet solidaire</option>
                                    <option value="Signalement">Signalement de contenu</option>
                                    <option value="Média/Presse">Demande médias/presse</option>
                                    <option value="Autre">Autre</option>
                                </select>
                                <span v-if="errors.subject" class="text-red-500 text-sm mt-1">{{ errors.subject }}</span>
                            </div>
                            
                            <div>
                                <label for="message" class="block text-gray-700 font-medium mb-2">
                                    Message *
                                </label>
                                <textarea 
                                    v-model="contactForm.message"
                                    id="message" 
                                    required 
                                    rows="6"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                                    :class="{ 'border-red-500': errors.message }"
                                    placeholder="Décrivez votre demande en détail..."
                                ></textarea>
                                <div class="flex justify-between items-center mt-2">
                                    <span v-if="errors.message" class="text-red-500 text-sm">{{ errors.message }}</span>
                                    <span class="text-gray-500 text-sm">{{ contactForm.message.length }}/2000 caractères</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <input 
                                    v-model="contactForm.agreeToPrivacy"
                                    type="checkbox" 
                                    id="privacy"
                                    required
                                    class="form-checkbox text-red-500 rounded mr-3"
                                >
                                <label for="privacy" class="text-gray-700 text-sm">
                                    J'accepte que mes données soient utilisées pour traiter ma demande de contact selon notre 
                                    <a href="#" class="text-red-500 hover:underline">politique de confidentialité</a> *
                                </label>
                            </div>
                            
                            <div class="flex justify-center">
                                <button 
                                    type="submit" 
                                    :disabled="isSubmitting"
                                    class="bg-red-500 hover:bg-red-600 disabled:bg-gray-400 text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 shadow-lg flex items-center"
                                >
                                    <span v-if="isSubmitting" class="flex items-center">
                                        <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Envoi en cours...
                                    </span>
                                    <span v-else class="flex items-center">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Envoyer le message
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Additional Info Section -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <h3 class="title-font text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-heart text-red-500 mr-3"></i>
                                Notre Engagement
                            </h3>
                            <p class="text-gray-600 mb-4">
                                Von Tränen zu Taten s'engage à traiter chaque message avec le plus grand soin et la confidentialité 
                                qu'il mérite. Votre bien-être et votre vie privée sont nos priorités absolues.
                            </p>
                            <ul class="text-gray-600 space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                    <span>Réponse garantie sous 48h</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                    <span>Confidentialité absolue</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                    <span>Équipe bienveillante et professionnelle</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="bg-white rounded-xl p-6 shadow-md">
                            <h3 class="title-font text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-handshake text-blue-500 mr-3"></i>
                                Partenariats & Collaborations
                            </h3>
                            <p class="text-gray-600 mb-4">
                                Vous représentez une organisation, une association ou une entreprise souhaitant s'associer 
                                à notre mission ? Nous serions ravis d'échanger avec vous.
                            </p>
                            <Link href="/solidarity" 
                                  class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-full font-medium transition duration-300">
                                <i class="fas fa-users mr-2"></i>
                                Découvrir nos partenaires
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Success Modal -->
        <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check text-green-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Message envoyé !</h3>
                    <p class="text-gray-600 mb-6">{{ successMessage }}</p>
                    <button 
                        @click="closeSuccessModal"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200"
                    >
                        Parfait
                    </button>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Client/Layouts/MainLayout.vue';

// Props reçues du contrôleur
const props = defineProps({
    isAuthenticated: Boolean,
    user: Object,
});

// État réactif pour le formulaire de contact
const contactForm = reactive({
    name: props.user?.pseudo || '',
    email: props.user?.email || '',
    subject: '',
    message: '',
    agreeToPrivacy: false,
});

const isSubmitting = ref(false);
const errors = ref({});
const showSuccessModal = ref(false);
const successMessage = ref('');

// Méthodes
const submitContactForm = async () => {
    if (!validateForm()) return;
    
    isSubmitting.value = true;
    errors.value = {};
    
    try {
        const response = await fetch('/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            },
            body: JSON.stringify(contactForm),
        });
        
        const result = await response.json();
        
        if (result.success) {
            successMessage.value = result.message;
            showSuccessModal.value = true;
            resetForm();
        } else {
            errors.value = result.errors || { general: result.message };
        }
    } catch (error) {
        console.error('Erreur lors de l\'envoi du message:', error);
        errors.value = { general: 'Une erreur est survenue. Veuillez réessayer.' };
    } finally {
        isSubmitting.value = false;
    }
};

const validateForm = () => {
    const newErrors = {};
    
    if (!contactForm.name.trim()) {
        newErrors.name = 'Le nom est requis.';
    }
    
    if (!contactForm.email.trim()) {
        newErrors.email = 'L\'email est requis.';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(contactForm.email)) {
        newErrors.email = 'L\'email doit être valide.';
    }
    
    if (!contactForm.subject) {
        newErrors.subject = 'Le sujet est requis.';
    }
    
    if (!contactForm.message.trim()) {
        newErrors.message = 'Le message est requis.';
    } else if (contactForm.message.length < 10) {
        newErrors.message = 'Le message doit contenir au moins 10 caractères.';
    } else if (contactForm.message.length > 2000) {
        newErrors.message = 'Le message ne peut pas dépasser 2000 caractères.';
    }
    
    if (!contactForm.agreeToPrivacy) {
        newErrors.privacy = 'Vous devez accepter la politique de confidentialité.';
    }
    
    errors.value = newErrors;
    return Object.keys(newErrors).length === 0;
};

const resetForm = () => {
    contactForm.name = props.user?.pseudo || '';
    contactForm.email = props.user?.email || '';
    contactForm.subject = '';
    contactForm.message = '';
    contactForm.agreeToPrivacy = false;
};

const closeSuccessModal = () => {
    showSuccessModal.value = false;
    successMessage.value = '';
};
</script>

<style scoped>
.title-font {
    font-family: 'Playfair Display', serif;
}

.form-checkbox {
    width: 1.2rem;
    height: 1.2rem;
}
</style>