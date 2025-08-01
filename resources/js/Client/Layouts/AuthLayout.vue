<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Header simplifié pour l'authentification -->
    <header class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
          <Link :href="$route('client.home')" class="flex items-center space-x-2">
            <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">SCB</div>
            <span class="title-font text-xl font-bold text-gray-800">Solidarité Cœur Brisé</span>
          </Link>
          
          <div class="flex items-center space-x-4">
            <Link 
              v-if="$page.url !== '/auth/login'" 
              :href="$route('auth.login')" 
              class="text-gray-600 hover:text-red-500 font-medium transition duration-300"
            >
              Connexion
            </Link>
            <Link 
              v-if="$page.url !== '/auth/register'" 
              :href="$route('auth.register')" 
              class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full font-medium transition duration-300"
            >
              Inscription
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Contenu principal -->
    <main class="flex-grow">
      <slot />
    </main>

    <!-- Footer simplifié pour l'authentification -->
    <footer class="bg-gray-900 text-white py-8">
      <div class="container mx-auto px-4">
        <div class="text-center">
          <div class="flex items-center justify-center space-x-3 mb-4">
            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">SCB</div>
            <span class="title-font text-2xl font-bold bg-gradient-to-r from-red-400 to-pink-400 bg-clip-text text-transparent">Solidarité Cœur Brisé</span>
          </div>
          <p class="text-gray-400 mb-4">Un espace bienveillant pour partager et soutenir.</p>
          
          <div class="flex justify-center space-x-6 mb-6">
            <Link href="/" class="text-gray-400 hover:text-white transition">Accueil</Link>
            <a href="#" class="text-gray-400 hover:text-white transition">À propos</a>
            <a href="#" class="text-gray-400 hover:text-white transition">Contact</a>
            <a href="#" class="text-gray-400 hover:text-white transition">Aide</a>
          </div>
          
          <div class="border-t border-gray-800 pt-6">
            <p class="text-gray-500 text-sm">&copy; 2023 Solidarité Cœur Brisé. Tous droits réservés.</p>
            <div class="mt-2 space-x-4">
              <a href="#" class="text-gray-500 hover:text-white transition text-sm">Mentions légales</a>
              <a href="#" class="text-gray-500 hover:text-white transition text-sm">Politique de confidentialité</a>
              <a href="#" class="text-gray-500 hover:text-white transition text-sm">Conditions d'utilisation</a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600;700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
}

.title-font {
  font-family: 'Playfair Display', serif;
}
</style>