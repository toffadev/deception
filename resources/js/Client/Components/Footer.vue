<template>
  <footer class="bg-gray-900 text-white pt-16 pb-8 border-t border-gray-800">
    <div class="container mx-auto px-4">
      <div class="grid md:grid-cols-4 gap-8">
        <div>
          <div class="flex items-center space-x-3 mb-4">
            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
              <img 
              src="/assets/img/logo3.png" 
              alt="Logo"  
              class="w-28 h-14 object-contain transition-all duration-300 group-hover:scale-110 drop-shadow-md group-hover:drop-shadow-lg"
            >
            </div>
            <span class="title-font text-2xl font-bold bg-gradient-to-r from-red-400 to-pink-400 bg-clip-text text-transparent">Von Tränen zu Taten</span>
          </div>
          <p class="text-gray-400 text-lg">Un espace bienveillant pour partager et soutenir.</p>
        </div>
        
        <div>
          <h3 class="title-font text-lg font-bold mb-4">Navigation</h3>
          <ul class="space-y-2">
            <li><Link href="/" class="text-gray-400 hover:text-white transition">Accueil</Link></li>
            <li><Link href="/publication" class="text-gray-400 hover:text-white transition">Publications</Link></li>
            <li><Link href="/solidarity" class="text-gray-400 hover:text-white transition">Projet Solidaire</Link></li>
            <li><Link href="/contact" class="text-gray-400 hover:text-white transition">Contact</Link></li>
          </ul>
        </div>
        
        <div>
          <h3 class="title-font text-lg font-bold mb-4">Contact</h3>
          <ul class="space-y-2">
            <li class="flex items-center space-x-2">
              <i class="fas fa-envelope text-gray-400"></i>
              <span class="text-gray-400">kontakt@vontranenzutaten.org</span>
            </li>
            <li class="flex items-center space-x-2">
              <i class="fas fa-phone text-gray-400"></i>
              <span class="text-gray-400">+33 1 23 45 67 89</span>
            </li>
          </ul>
        </div>
        
        <div>
          <h3 class="title-font text-lg font-bold mb-4">Réseaux sociaux</h3>
          <div class="flex space-x-4">
            <a href="#" class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center hover:bg-red-500 transition duration-300 transform hover:-translate-y-1">
              <i class="fab fa-facebook-f text-xl"></i>
            </a>
            <a href="#" class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center hover:bg-red-500 transition duration-300 transform hover:-translate-y-1">
              <i class="fab fa-twitter text-xl"></i>
            </a>
            <a href="#" class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center hover:bg-red-500 transition duration-300 transform hover:-translate-y-1">
              <i class="fab fa-instagram text-xl"></i>
            </a>
            <a href="#" class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center hover:bg-red-500 transition duration-300 transform hover:-translate-y-1">
              <i class="fab fa-linkedin-in text-xl"></i>
            </a>
          </div>
        </div>
      </div>
      
      <div class="border-t border-gray-800 mt-16 pt-8 text-center text-gray-500">
        <p>&copy; 2023 Von Tränen zu Taten. Tous droits réservés.</p>
        <div class="mt-2 space-x-4">
          <a href="#" class="text-sm hover:text-white transition">Mentions légales</a>
          <a href="#" class="text-sm hover:text-white transition">Politique de confidentialité</a>
          <a href="#" class="text-sm hover:text-white transition">Conditions d'utilisation</a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

// Composant Footer pour la partie client
</script>
