<template>
  <div class="bg-gray-100 font-sans min-h-screen flex flex-col lg:flex-row">
    <!-- Left Side - Branding/Image -->
    <div class="auth-bg lg:w-1/2 relative hidden lg:block">
      <div class="auth-overlay absolute inset-0 flex items-center justify-center p-12">
        <div class="text-center text-white max-w-md">
          <div class="flex items-center justify-center space-x-2 mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">VTT</div>
            <span class="text-3xl font-bold">Von Tränen zu Taten</span>
          </div>
          <h1 class="text-4xl font-bold mb-4">Tableau de bord Admin</h1>
          <p class="text-lg opacity-90 mb-8">Gérez votre plateforme de solidarité avec notre interface complète d'administration.</p>
          <div class="flex items-center justify-center space-x-4">
            <div class="flex items-center">
              <i class="fas fa-check-circle text-green-400 mr-2"></i>
              <span>Gestion des utilisateurs</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-check-circle text-green-400 mr-2"></i>
              <span>Modération</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Right Side - Login Form -->
    <div class="lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
      <div class="w-full max-w-md">
        <div class="text-center lg:hidden mb-8">
          <div class="flex items-center justify-center space-x-2">
            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">SCB</div>
            <span class="text-2xl font-bold text-gray-800">Von Tränen zu Taten</span>
          </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-xl p-8 sm:p-10">
          <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Connexion Admin</h2>
            <p class="text-gray-600 mt-2">Entrez vos identifiants pour accéder au tableau de bord</p>
          </div>

          <!-- Success/Error Messages -->
          <div v-if="$page.props.flash?.success" class="mb-6 rounded-md bg-green-50 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-green-800">
                  {{ $page.props.flash.success }}
                </p>
              </div>
            </div>
          </div>

          <div v-if="$page.props.flash?.error" class="mb-6 rounded-md bg-red-50 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-red-800">
                  {{ $page.props.flash.error }}
                </p>
              </div>
            </div>
          </div>
          
          <form @submit.prevent="submit" class="space-y-6">
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse Email</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input 
                  id="email" 
                  v-model="form.email" 
                  type="email" 
                  autocomplete="email" 
                  required 
                  class="input-focus pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150" 
                  :class="{ 'border-red-500 bg-red-50': form.errors.email }"
                  placeholder="admin@exemple.com"
                >
              </div>
              <div v-if="form.errors.email" class="mt-1 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ Array.isArray(form.errors.email) ? form.errors.email[0] : form.errors.email }}
              </div>
            </div>
            
            <div>
              <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <Link :href="$route('admin.auth.password.request')" class="text-sm text-indigo-600 hover:underline">Mot de passe oublié?</Link>
              </div>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input 
                  id="password" 
                  v-model="form.password" 
                  :type="showPassword ? 'text' : 'password'" 
                  autocomplete="current-password" 
                  required 
                  class="input-focus pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150" 
                  :class="{ 'border-red-500 bg-red-50': form.errors.password }"
                  placeholder="••••••••"
                >
                <button 
                  type="button" 
                  @click="showPassword = !showPassword"
                  class="absolute right-3 top-3 text-gray-400 hover:text-gray-500"
                >
                  <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
              <div v-if="form.errors.password" class="mt-1 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ Array.isArray(form.errors.password) ? form.errors.password[0] : form.errors.password }}
              </div>
            </div>
            
            <div class="flex items-center">
              <input 
                id="remember-me" 
                v-model="form.remember" 
                type="checkbox" 
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              >
              <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                Se souvenir de moi
              </label>
            </div>
            
            <div>
              <button 
                type="submit" 
                :disabled="form.processing"
                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 disabled:opacity-50"
              >
                <span v-if="form.processing" class="mr-2">
                  <i class="fas fa-spinner fa-spin"></i>
                </span>
                <span v-else class="mr-2">
                  <i class="fas fa-sign-in-alt"></i>
                </span>
                {{ form.processing ? 'Connexion...' : 'Se connecter' }}
              </button>
            </div>
          </form>
          
          <div class="mt-6">
            <div class="relative">
              <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
              </div>
              <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">
                  Sécurité du système
                </span>
              </div>
            </div>
            
            <div class="mt-6 flex items-center justify-center space-x-4">
              <div class="flex items-center text-sm text-gray-500">
                <i class="fas fa-shield-alt text-green-500 mr-1"></i>
                <span>SSL Sécurisé</span>
              </div>
              <div class="flex items-center text-sm text-gray-500">
                <i class="fas fa-lock text-green-500 mr-1"></i>
                <span>Données cryptées</span>
              </div>
            </div>
          </div>
        </div>
        
        <div class="mt-8 text-center text-sm text-gray-600">
          <p>© 2023 Von Tränen zu Taten Admin. Tous droits réservés.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
  errors: {
    type: Object,
    default: () => ({})
  },
});

// Utiliser les erreurs directement depuis le form Inertia comme côté client

const showPassword = ref(false);

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('admin.auth.login.store'), {
    onFinish: () => {
      // Ne réinitialiser le mot de passe que si pas d'erreur
      if (!form.hasErrors) {
        form.reset('password');
      }
    },
  });
};
</script>

<style scoped>
.auth-bg {
  background-image: url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80');
  background-size: cover;
  background-position: center;
}

.auth-overlay {
  background: rgba(79, 70, 229, 0.8);
  background: linear-gradient(135deg, rgba(239, 68, 68, 0.9) 0%, rgba(220, 38, 127, 0.9) 100%);
}

.input-focus:focus {
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
}
</style>