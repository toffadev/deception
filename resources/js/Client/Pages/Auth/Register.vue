<template>
  <MainLayout>
    <!-- Background with animated gradient -->
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 relative overflow-hidden">
      <!-- Floating animation elements -->
      <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-4 -right-4 w-72 h-72 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-8 -left-8 w-72 h-72 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse" style="animation-delay: 4s;"></div>
      </div>

      <!-- Main Content -->
      <main class="relative z-10 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
          <!-- Card container with glassmorphism effect -->
          <div class="bg-white/80 backdrop-blur-xl p-10 rounded-2xl shadow-2xl border border-white/20 register-card">
            <div class="text-center mb-8">
              <!-- Enhanced logo with glow effect -->
              
              <h2 class="title-font text-4xl font-bold bg-gradient-to-r from-gray-900 via-blue-600 to-indigo-600 bg-clip-text text-transparent mb-3">
                Rejoignez-nous
              </h2>
              <p class="text-gray-600 text-lg">
                Ensemble, transformons la douleur en espoir
              </p>
            </div>
            
            <!-- Affichage des erreurs générales avec design amélioré -->
            <div v-if="Object.keys(form.errors).length > 0" class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl p-4 mb-6">
              <div class="flex">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation text-white text-sm"></i>
                  </div>
                </div>
                <div class="ml-3">
                  <h3 class="text-sm font-semibold text-red-800 mb-2">
                    Erreurs de validation
                  </h3>
                  <div class="text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                      <li v-for="(error, field) in form.errors" :key="field">
                        {{ Array.isArray(error) ? error[0] : error }}
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Google Sign-in Button -->
            <div class="mb-6">
              <a :href="$route('auth.google')" class="google-btn w-full flex items-center justify-center px-6 py-4 rounded-xl text-white font-semibold bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M22.56 12.25C22.56 11.47 22.49 10.72 22.36 10H12V14.26H17.92C17.66 15.63 16.88 16.79 15.71 17.57V20.34H19.28C21.36 18.42 22.56 15.6 22.56 12.25Z" fill="#4285F4"/>
                  <path d="M12 23C14.97 23 17.46 22.02 19.28 20.34L15.71 17.57C14.73 18.23 13.48 18.64 12 18.64C9.14 18.64 6.72 16.64 5.84 14H2.18V16.82C4 20.54 7.7 23 12 23Z" fill="#34A853"/>
                  <path d="M5.84 14C5.44 12.85 5.44 11.65 5.84 10.5V7.68H2.18C0.91 10.15 0.91 13.35 2.18 15.82L5.84 14Z" fill="#FBBC05"/>
                  <path d="M12 5.38C13.62 5.38 15.06 5.94 16.18 7.02L19.34 3.86C17.45 2.09 14.97 1 12 1C7.7 1 4 3.46 2.18 7.18L5.84 10.5C6.72 7.36 9.14 5.38 12 5.38Z" fill="#EA4335"/>
                </svg>
                S'inscrire avec Google
              </a>
            </div>
            
            <!-- Enhanced Divider -->
            <div class="relative flex items-center justify-center my-6">
              <div class="border-t border-gray-300 w-full"></div>
              <div class="bg-gradient-to-r from-gray-100 to-gray-200 px-4 py-1 rounded-full border border-gray-300">
                <span class="text-sm font-medium text-gray-600">OU</span>
              </div>
            </div>
            
            <!-- Registration Form -->
            <form @submit.prevent="submit" class="space-y-5">
              <div class="space-y-4">
                <!-- Pseudo field -->
                <div class="form-group">
                  <label for="pseudo" class="block text-sm font-semibold text-gray-700 mb-2">Nom d'utilisateur</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                      <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input 
                      id="pseudo" 
                      v-model="form.pseudo" 
                      type="text" 
                      class="modern-input w-full pl-12 pr-4 py-4 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                      :class="{ 'border-red-500 bg-red-50': form.errors.pseudo }"
                      placeholder="Votre pseudonyme"
                    >
                  </div>
                  <div v-if="form.errors.pseudo" class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ Array.isArray(form.errors.pseudo) ? form.errors.pseudo[0] : form.errors.pseudo }}
                  </div>
                </div>
                
                <!-- Email field -->
                <div class="form-group">
                  <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                      <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input 
                      id="email" 
                      v-model="form.email" 
                      type="email" 
                      autocomplete="email" 
                      class="modern-input w-full pl-12 pr-4 py-4 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                      :class="{ 'border-red-500 bg-red-50': form.errors.email }"
                      placeholder="email@exemple.com"
                    >
                  </div>
                  <div v-if="form.errors.email" class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ Array.isArray(form.errors.email) ? form.errors.email[0] : form.errors.email }}
                  </div>
                </div>

                <!-- Birth date field -->
                <div class="form-group">
                  <label for="birth_date" class="block text-sm font-semibold text-gray-700 mb-2">Date de naissance</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                      <i class="fas fa-calendar text-gray-400"></i>
                    </div>
                    <input 
                      id="birth_date" 
                      v-model="form.birth_date" 
                      type="date" 
                      class="modern-input w-full pl-12 pr-4 py-4 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                      :class="{ 'border-red-500 bg-red-50': form.errors.birth_date }"
                    >
                  </div>
                  <div v-if="form.errors.birth_date" class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ Array.isArray(form.errors.birth_date) ? form.errors.birth_date[0] : form.errors.birth_date }}
                  </div>
                </div>
                
                <!-- Password field -->
                <div class="form-group">
                  <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                      <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input 
                      id="password" 
                      v-model="form.password" 
                      type="password" 
                      autocomplete="new-password" 
                      class="modern-input w-full pl-12 pr-4 py-4 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                      :class="{ 'border-red-500 bg-red-50': form.errors.password }"
                      placeholder="••••••••"
                    >
                  </div>
                  <p class="mt-2 text-xs text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    8 caractères minimum avec au moins une majuscule et un chiffre
                  </p>
                  <div v-if="form.errors.password" class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ Array.isArray(form.errors.password) ? form.errors.password[0] : form.errors.password }}
                  </div>
                </div>
                
                <!-- Password confirmation field -->
                <div class="form-group">
                  <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le mot de passe</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                      <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input 
                      id="password_confirmation" 
                      v-model="form.password_confirmation" 
                      type="password" 
                      autocomplete="new-password" 
                      class="modern-input w-full pl-12 pr-4 py-4 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                      :class="{ 'border-red-500 bg-red-50': form.errors.password_confirmation }"
                      placeholder="••••••••"
                    >
                  </div>
                  <div v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600 flex items-center">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ Array.isArray(form.errors.password_confirmation) ? form.errors.password_confirmation[0] : form.errors.password_confirmation }}
                  </div>
                </div>
              </div>
              
              <!-- Terms checkbox -->
              <div class="form-group">
                <div class="flex items-start">
                  <div class="flex items-center h-5">
                    <input 
                      id="terms" 
                      v-model="form.terms" 
                      type="checkbox" 
                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition duration-200"
                      :class="{ 'border-red-500': form.errors.terms }"
                    >
                  </div>
                  <div class="ml-3 text-sm">
                    <label for="terms" class="text-gray-700">
                      J'accepte les <a href="#" class="font-semibold text-blue-500 hover:text-blue-600">conditions d'utilisation</a> et la <a href="#" class="font-semibold text-blue-500 hover:text-blue-600">politique de confidentialité</a>
                    </label>
                  </div>
                </div>
                <div v-if="form.errors.terms" class="mt-2 text-sm text-red-600 flex items-center">
                  <i class="fas fa-exclamation-circle mr-1"></i>
                  {{ Array.isArray(form.errors.terms) ? form.errors.terms[0] : form.errors.terms }}
                </div>
              </div>
              
              <!-- Newsletter checkbox -->
              <div class="form-group">
                <div class="flex items-start">
                  <div class="flex items-center h-5">
                    <input 
                      id="newsletter" 
                      v-model="form.newsletter" 
                      type="checkbox"
                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition duration-200"
                    >
                  </div>
                  <div class="ml-3 text-sm">
                    <label for="newsletter" class="text-gray-700">
                      Je souhaite recevoir la newsletter et des informations sur les projets solidaires
                    </label>
                  </div>
                </div>
              </div>
              
              <!-- Submit button -->
              <div class="pt-4">
                <button 
                  type="submit" 
                  :disabled="form.processing"
                  class="modern-button w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-base font-semibold text-white bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                >
                  <span v-if="form.processing" class="mr-3">
                    <i class="fas fa-spinner fa-spin"></i>
                  </span>
                  <span v-else class="mr-2">
                    <i class="fas fa-user-plus"></i>
                  </span>
                  {{ form.processing ? 'Inscription...' : "S'inscrire" }}
                </button>
              </div>
            </form>
            
            <div class="text-center mt-8">
              <p class="text-gray-600">
                Vous avez déjà un compte? 
                <Link :href="$route('auth.login')" class="font-semibold text-blue-500 hover:text-blue-600 transition duration-200">
                  Connectez-vous
                </Link>
              </p>
            </div>
          </div>
          
          <!-- Trust indicators -->
          <div class="text-center mt-6">
            <div class="flex items-center justify-center space-x-6 text-gray-500">
              <div class="flex items-center">
                <i class="fas fa-shield-alt mr-2"></i>
                <span class="text-sm">Sécurisé</span>
              </div>
              <div class="flex items-center">
                <i class="fas fa-users mr-2"></i>
                <span class="text-sm">Communauté</span>
              </div>
              <div class="flex items-center">
                <i class="fas fa-heart mr-2"></i>
                <span class="text-sm">Solidaire</span>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </MainLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AuthLayout from '@client/Layouts/AuthLayout.vue';
import MainLayout from '@client/Layouts/MainLayout.vue';
import { route } from 'ziggy-js';

const form = useForm({
  pseudo: '',
  email: '',
  birth_date: '',
  password: '',
  password_confirmation: '',
  terms: false,
  newsletter: false,
});

const submit = () => {
  form.post(route('auth.register.store'), {
    onSuccess: () => {
      console.log('Inscription réussie !');
    },
    onError: (errors) => {
      console.log('Erreurs de validation:', errors);
      console.log('Form errors:', form.errors);
    },
    onFinish: () => {
      // Ne réinitialiser les mots de passe que si pas d'erreur
      if (!form.hasErrors) {
        form.reset('password', 'password_confirmation');
      }
    },
  });
};
</script> 

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600;700&display=swap');

.title-font {
  font-family: 'Playfair Display', serif;
}

/* Glassmorphism card effect */
.register-card {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  animation: fadeInUp 0.8s ease-out;
}

/* Logo glow animation */
.logo-glow {
  animation: logoGlow 3s ease-in-out infinite alternate;
}

@keyframes logoGlow {
  0% {
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
  }
  100% {
    box-shadow: 0 0 30px rgba(59, 130, 246, 0.6), 0 0 40px rgba(99, 102, 241, 0.3);
  }
}

/* Modern input styling */
.modern-input {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  transition: all 0.3s ease;
}

.modern-input:focus {
  background: rgba(255, 255, 255, 1);
  transform: translateY(-1px);
  box-shadow: 0 10px 25px rgba(59, 130, 246, 0.1);
}

/* Modern button styling */
.modern-button {
  background: linear-gradient(135deg, #3b82f6, #6366f1);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-button:hover:not(:disabled) {
  box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.3), 0 10px 10px -5px rgba(59, 130, 246, 0.2);
}

/* Form group animations */
.form-group {
  animation: slideInLeft 0.6s ease-out;
}

.form-group:nth-child(2) { animation-delay: 0.1s; }
.form-group:nth-child(3) { animation-delay: 0.2s; }
.form-group:nth-child(4) { animation-delay: 0.3s; }
.form-group:nth-child(5) { animation-delay: 0.4s; }
.form-group:nth-child(6) { animation-delay: 0.5s; }

/* Fade in animation */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Floating elements responsive */
@media (max-width: 768px) {
  .register-card {
    margin: 1rem;
    padding: 2rem;
  }
  
  .absolute {
    opacity: 0.1;
  }
}

/* Custom checkbox styling */
input[type="checkbox"]:checked {
  background-color: #3b82f6;
  border-color: #3b82f6;
}

/* Enhanced hover effects */
.google-btn:hover {
  transform: translateY(-2px);
}

/* Additional responsive improvements */
@media (max-width: 640px) {
  .title-font {
    font-size: 2rem;
  }
  
  .modern-input {
    padding: 1rem 1rem 1rem 3rem;
  }
  
  .modern-button {
    padding: 1rem;
  }
}
</style>