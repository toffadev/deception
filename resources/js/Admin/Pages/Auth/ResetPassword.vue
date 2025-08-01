<template>
  <div class="bg-gray-100 font-sans min-h-screen flex flex-col lg:flex-row">
    <!-- Left Side - Branding/Image -->
    <div class="auth-bg lg:w-1/2 relative hidden lg:block">
      <div class="auth-overlay absolute inset-0 flex items-center justify-center p-12">
        <div class="text-center text-white max-w-md">
          <div class="flex items-center justify-center space-x-2 mb-6">
            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">SCB</div>
            <span class="text-3xl font-bold">Solidarité Cœur Brisé</span>
          </div>
          <h1 class="text-4xl font-bold mb-4">Nouveau mot de passe</h1>
          <p class="text-lg opacity-90 mb-8">Définissez un nouveau mot de passe sécurisé pour votre compte administrateur.</p>
          <div class="flex items-center justify-center space-x-4">
            <div class="flex items-center">
              <i class="fas fa-check-circle text-green-400 mr-2"></i>
              <span>Mot de passe fort</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-check-circle text-green-400 mr-2"></i>
              <span>Accès sécurisé</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Right Side - Reset Password Form -->
    <div class="lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
      <div class="w-full max-w-md">
        <div class="text-center lg:hidden mb-8">
          <div class="flex items-center justify-center space-x-2">
            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">SCB</div>
            <span class="text-2xl font-bold text-gray-800">Solidarité Cœur Brisé</span>
          </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-xl p-8 sm:p-10">
          <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
              <i class="fas fa-lock"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Nouveau mot de passe</h2>
            <p class="text-gray-600 mt-2">Définissez votre nouveau mot de passe administrateur</p>
          </div>
          
          <form @submit.prevent="submit" class="space-y-6">
            <input type="hidden" v-model="form.token" />
            
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
                  readonly
                  class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none sm:text-sm" 
                >
              </div>
              <div v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</div>
            </div>

            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input 
                  id="password" 
                  v-model="form.password" 
                  :type="showPassword ? 'text' : 'password'" 
                  autocomplete="new-password" 
                  required 
                  class="input-focus pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150" 
                  :class="{ 'border-red-500': errors.password }"
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
              <p class="mt-2 text-xs text-gray-500">8 caractères minimum avec au moins une majuscule et un chiffre</p>
              <div v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</div>
            </div>
            
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le nouveau mot de passe</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input 
                  id="password_confirmation" 
                  v-model="form.password_confirmation" 
                  :type="showPasswordConfirmation ? 'text' : 'password'" 
                  autocomplete="new-password" 
                  required 
                  class="input-focus pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150" 
                  placeholder="••••••••"
                >
                <button 
                  type="button" 
                  @click="showPasswordConfirmation = !showPasswordConfirmation"
                  class="absolute right-3 top-3 text-gray-400 hover:text-gray-500"
                >
                  <i :class="showPasswordConfirmation ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
            </div>
            
            <div>
              <button 
                type="submit" 
                :disabled="processing"
                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 disabled:opacity-50"
              >
                <span v-if="processing" class="mr-2">
                  <i class="fas fa-spinner fa-spin"></i>
                </span>
                <i class="fas fa-check mr-2"></i>
                Réinitialiser le mot de passe
              </button>
            </div>
          </form>
          
          <div class="mt-6 text-center">
            <Link :href="route('admin.auth.login')" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
              <i class="fas fa-arrow-left mr-1"></i>
              Retour à la connexion
            </Link>
          </div>
          
          <div class="mt-6">
            <div class="relative">
              <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
              </div>
              <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">
                  Sécurité administrateur
                </span>
              </div>
            </div>
            
            <div class="mt-6 flex items-center justify-center space-x-4">
              <div class="flex items-center text-sm text-gray-500">
                <i class="fas fa-shield-alt text-green-500 mr-1"></i>
                <span>Protection renforcée</span>
              </div>
              <div class="flex items-center text-sm text-gray-500">
                <i class="fas fa-user-shield text-green-500 mr-1"></i>
                <span>Compte admin</span>
              </div>
            </div>
          </div>
        </div>
        
        <div class="mt-8 text-center text-sm text-gray-600">
          <p>© 2023 Solidarité Cœur Brisé Admin. Tous droits réservés.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  token: String,
  email: String,
  errors: Object,
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const { processing } = form;

const submit = () => {
  form.post(route('admin.auth.password.update'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
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