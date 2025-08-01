<template>
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3">
      <div class="flex justify-between items-center">
        <div class="flex items-center space-x-2">
          <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">SCB</div>
          <span class="title-font text-xl font-bold text-gray-800">Solidarité Cœur Brisé</span>
        </div>
        
        <div class="hidden md:flex space-x-6">
          <a href="#" class="text-gray-700 hover:text-red-500 font-medium">Accueil</a>
          <a href="#" class="text-gray-700 hover:text-red-500 font-medium">Témoignages</a>
          <a href="#" class="text-gray-700 hover:text-red-500 font-medium">Projet Solidaire</a>
          <a href="#" class="text-gray-700 hover:text-red-500 font-medium">Dons</a>
          <a href="#" class="text-gray-700 hover:text-red-500 font-medium">Partenaires</a>
        </div>
        
        <div class="flex items-center space-x-4">
          <!-- Si l'utilisateur est connecté -->
          <div v-if="$page.props.auth?.user" class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
              <img 
                :src="$page.props.auth.user.avatar_url" 
                :alt="$page.props.auth.user.pseudo"
                class="w-8 h-8 rounded-full"
              >
              <span class="hidden md:block text-gray-700 font-medium">{{ $page.props.auth.user.pseudo }}</span>
            </div>
            <Link 
              :href="$route('auth.logout')" 
              method="post"
              as="button"
              class="hidden md:block bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full font-medium transition duration-300"
            >
              Déconnexion
            </Link>
          </div>
          
          <!-- Si l'utilisateur n'est pas connecté -->
          <div v-else class="flex items-center space-x-4">
            <Link :href="$route('auth.login')" class="hidden md:block bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full font-medium transition duration-300">Connexion</Link>
            <Link :href="$route('auth.register')" class="hidden md:block border border-red-500 text-red-500 hover:bg-red-50 px-4 py-2 rounded-full font-medium transition duration-300">Inscription</Link>
          </div>
          
          <button class="md:hidden text-gray-700">
            <i class="fas fa-bars text-2xl"></i>
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';


// Composant Header pour la partie client
</script>
