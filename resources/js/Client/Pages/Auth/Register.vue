<template>
  <AuthLayout>
    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
        <div class="text-center">
          <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">SCB</div>
          <h2 class="title-font text-3xl font-bold text-gray-900">Créer un compte</h2>
          <p class="mt-2 text-gray-600">
            Rejoignez notre communauté bienveillante
          </p>
        </div>
        
        <!-- Affichage des erreurs générales -->
        <div v-if="Object.keys(form.errors).length > 0" class="bg-red-50 border border-red-200 rounded-md p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">
                Erreurs de validation
              </h3>
              <div class="mt-2 text-sm text-red-700">
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
        <div>
          <a :href="$route('auth.google')" class="google-btn w-full flex items-center justify-center px-4 py-3 rounded-md text-white font-medium bg-blue-600 hover:bg-blue-700 transition duration-300">
            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M22.56 12.25C22.56 11.47 22.49 10.72 22.36 10H12V14.26H17.92C17.66 15.63 16.88 16.79 15.71 17.57V20.34H19.28C21.36 18.42 22.56 15.6 22.56 12.25Z" fill="#4285F4"/>
              <path d="M12 23C14.97 23 17.46 22.02 19.28 20.34L15.71 17.57C14.73 18.23 13.48 18.64 12 18.64C9.14 18.64 6.72 16.64 5.84 14H2.18V16.82C4 20.54 7.7 23 12 23Z" fill="#34A853"/>
              <path d="M5.84 14C5.44 12.85 5.44 11.65 5.84 10.5V7.68H2.18C0.91 10.15 0.91 13.35 2.18 15.82L5.84 14Z" fill="#FBBC05"/>
              <path d="M12 5.38C13.62 5.38 15.06 5.94 16.18 7.02L19.34 3.86C17.45 2.09 14.97 1 12 1C7.7 1 4 3.46 2.18 7.18L5.84 10.5C6.72 7.36 9.14 5.38 12 5.38Z" fill="#EA4335"/>
            </svg>
            S'inscrire avec Google
          </a>
        </div>
        
        <!-- Divider -->
        <div class="divider">
          <span class="text-sm">OU</span>
        </div>
        
        <!-- Registration Form -->
        <form @submit.prevent="submit" class="mt-6 space-y-6">
          <div class="space-y-4">
            <div>
              <label for="pseudo" class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
              <div class="mt-1 relative">
                <input 
                  id="pseudo" 
                  v-model="form.pseudo" 
                  type="text" 
                   
                  class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 input-focus focus:outline-none sm:text-sm"
                  :class="{ 'border-red-500': form.errors.pseudo }"
                  placeholder="Votre pseudonyme"
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <i class="fas fa-user text-gray-400"></i>
                </div>
              </div>
                             <div v-if="form.errors.pseudo" class="mt-1 text-sm text-red-600">{{ Array.isArray(form.errors.pseudo) ? form.errors.pseudo[0] : form.errors.pseudo }}</div>
            </div>
            
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
              <div class="mt-1 relative">
                <input 
                  id="email" 
                  v-model="form.email" 
                  type="email" 
                  autocomplete="email" 
                   
                  class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 input-focus focus:outline-none sm:text-sm"
                  :class="{ 'border-red-500': form.errors.email }"
                  placeholder="email@exemple.com"
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <i class="fas fa-envelope text-gray-400"></i>
                </div>
              </div>
                             <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ Array.isArray(form.errors.email) ? form.errors.email[0] : form.errors.email }}</div>
            </div>

            <div>
              <label for="birth_date" class="block text-sm font-medium text-gray-700">Date de naissance</label>
              <div class="mt-1 relative">
                <input 
                  id="birth_date" 
                  v-model="form.birth_date" 
                  type="date" 
                   
                  class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 input-focus focus:outline-none sm:text-sm"
                  :class="{ 'border-red-500': form.errors.birth_date }"
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <i class="fas fa-calendar text-gray-400"></i>
                </div>
              </div>
                             <div v-if="form.errors.birth_date" class="mt-1 text-sm text-red-600">{{ Array.isArray(form.errors.birth_date) ? form.errors.birth_date[0] : form.errors.birth_date }}</div>
            </div>
            
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
              <div class="mt-1 relative">
                <input 
                  id="password" 
                  v-model="form.password" 
                  type="password" 
                  autocomplete="new-password" 
                   
                  class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 input-focus focus:outline-none sm:text-sm"
                  :class="{ 'border-red-500': form.errors.password }"
                  placeholder="••••••••"
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <i class="fas fa-lock text-gray-400"></i>
                </div>
              </div>
              <p class="mt-2 text-xs text-gray-500">8 caractères minimum avec au moins une majuscule et un chiffre</p>
                             <div v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ Array.isArray(form.errors.password) ? form.errors.password[0] : form.errors.password }}</div>
            </div>
            
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
              <div class="mt-1 relative">
                <input 
                  id="password_confirmation" 
                  v-model="form.password_confirmation" 
                  type="password" 
                  autocomplete="new-password" 
                   
                  class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 input-focus focus:outline-none sm:text-sm"
                  :class="{ 'border-red-500': form.errors.password_confirmation }"
                  placeholder="••••••••"
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <i class="fas fa-lock text-gray-400"></i>
                </div>
              </div>
                             <div v-if="form.errors.password_confirmation" class="mt-1 text-sm text-red-600">{{ Array.isArray(form.errors.password_confirmation) ? form.errors.password_confirmation[0] : form.errors.password_confirmation }}</div>
            </div>
          </div>
          
          <div class="flex items-center">
            <input 
              id="terms" 
              v-model="form.terms" 
              type="checkbox" 
              
              class="h-4 w-4 form-checkbox text-red-500 focus:ring-red-500 border-gray-300 rounded"
              :class="{ 'border-red-500': form.errors.terms }"
            >
            <label for="terms" class="ml-2 block text-sm text-gray-700">
              J'accepte les <a href="#" class="font-medium text-red-500 hover:text-red-600">conditions d'utilisation</a> et la <a href="#" class="font-medium text-red-500 hover:text-red-600">politique de confidentialité</a>
            </label>
          </div>
                     <div v-if="form.errors.terms" class="text-sm text-red-600">{{ Array.isArray(form.errors.terms) ? form.errors.terms[0] : form.errors.terms }}</div>
          
          <div class="flex items-center">
            <input 
              id="newsletter" 
              v-model="form.newsletter" 
              type="checkbox"
              class="h-4 w-4 form-checkbox text-red-500 focus:ring-red-500 border-gray-300 rounded"
            >
            <label for="newsletter" class="ml-2 block text-sm text-gray-700">
              Je souhaite recevoir la newsletter et des informations sur les projets solidaires
            </label>
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
              S'inscrire
            </button>
          </div>
        </form>
        
        <div class="text-center text-sm">
          <p class="text-gray-600">
            Vous avez déjà un compte? 
            <Link :href="$route('auth.login')" class="font-medium text-red-500 hover:text-red-600">Connectez-vous</Link>
          </p>
        </div>
      </div>
    </main>
  </AuthLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AuthLayout from '@client/Layouts/AuthLayout.vue';
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

.input-focus:focus {
  border-color: #ef4444;
  box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
}

.divider {
  display: flex;
  align-items: center;
  text-align: center;
  color: #6b7280;
}

.divider::before, .divider::after {
  content: "";
  flex: 1;
  border-bottom: 1px solid #e5e7eb;
}

.divider::before {
  margin-right: 1rem;
}

.divider::after {
  margin-left: 1rem;
}

.form-checkbox:checked {
  background-color: #ef4444;
  border-color: #ef4444;
}
</style>