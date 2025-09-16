<template>
  <MainLayout>
    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
        <div class="text-center">
          <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
            <i class="fas fa-lock"></i>
          </div>
          <h2 class="title-font text-3xl font-bold text-gray-900">Nouveau mot de passe</h2>
          <p class="mt-2 text-gray-600">
            Définissez votre nouveau mot de passe
          </p>
        </div>
        
        <!-- Reset Password Form -->
        <form @submit.prevent="submit" class="mt-6 space-y-6">
          <input type="hidden" v-model="form.token" />
          
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
            <div class="mt-1 relative">
              <input 
                id="email" 
                v-model="form.email" 
                type="email" 
                autocomplete="email" 
                required 
                readonly
                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 bg-gray-50 focus:outline-none sm:text-sm"
              >
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
              </div>
            </div>
            <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ Array.isArray(form.errors.email) ? form.errors.email[0] : form.errors.email }}</div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
            <div class="mt-1 relative">
              <input 
                id="password" 
                v-model="form.password" 
                :type="showPassword ? 'text' : 'password'" 
                autocomplete="new-password" 
                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 input-focus focus:outline-none sm:text-sm"
                :class="{ 'border-red-500': form.errors.password }"
                placeholder="••••••••"
              >
              <button 
                type="button" 
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-gray-400 hover:text-gray-500"></i>
              </button>
            </div>
            <p class="mt-2 text-xs text-gray-500">8 caractères minimum avec au moins une majuscule et un chiffre</p>
            <div v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ Array.isArray(form.errors.password) ? form.errors.password[0] : form.errors.password }}</div>
          </div>
          
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le nouveau mot de passe</label>
            <div class="mt-1 relative">
              <input 
                id="password_confirmation" 
                v-model="form.password_confirmation" 
                :type="showPasswordConfirmation ? 'text' : 'password'" 
                autocomplete="new-password" 
                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 input-focus focus:outline-none sm:text-sm"
                :class="{ 'border-red-500': form.errors.password_confirmation }"
                placeholder="••••••••"
              >
              <button 
                type="button" 
                @click="showPasswordConfirmation = !showPasswordConfirmation"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <i :class="showPasswordConfirmation ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-gray-400 hover:text-gray-500"></i>
              </button>
            </div>
            <div v-if="form.errors.password_confirmation" class="mt-1 text-sm text-red-600">{{ Array.isArray(form.errors.password_confirmation) ? form.errors.password_confirmation[0] : form.errors.password_confirmation }}</div>
          </div>
          
          <div>
            <button 
              type="submit" 
              :disabled="form.processing"
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-300 disabled:opacity-50"
            >
              <span v-if="form.processing" class="mr-2">
                <i class="fas fa-spinner fa-spin"></i>
              </span>
              <i class="fas fa-check mr-2"></i>
              Réinitialiser le mot de passe
            </button>
          </div>
        </form>
        
        <div class="text-center text-sm">
          <p class="text-gray-600">
            Vous vous souvenez de votre mot de passe? 
            <Link :href="$route('auth.login')" class="font-medium text-red-500 hover:text-red-600">Connectez-vous</Link>
          </p>
        </div>
      </div>
    </main>
  </MainLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AuthLayout from '@client/Layouts/AuthLayout.vue';
import MainLayout from '@client/Layouts/MainLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps({
  token: String,
  email: String,
});

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});



const submit = () => {
  form.post(route('auth.password.update'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600;700&display=swap');

  .title-font {
    font-family: 'Playfair Display', serif;
  }

  .input-focus:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
  }
</style>