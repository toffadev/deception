<template>
  <header class="bg-white shadow-sm sticky top-0 z-50 transition-all duration-300" :class="{ 'shadow-lg': isScrolled }">
    <div class="container mx-auto px-4 py-3">
      <div class="flex justify-between items-center">
        <!-- Logo et nom -->
        <Link href="/" class="flex items-center space-x-2 group">
          <!-- <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl transition-transform duration-300 group-hover:scale-110">
            SCB
          </div>
          <span class="title-font text-xl font-bold text-gray-800 transition-colors duration-300 group-hover:text-red-500">
            Von Tränen zu Taten
          </span> -->
          <div class="relative p-1">
            <img 
              src="/assets/img/logo3.png" 
              alt="Logo"  
              class="w-28 h-14 object-contain transition-all duration-300 group-hover:scale-110 drop-shadow-md group-hover:drop-shadow-lg"
            >
          </div>
        </Link>
        
        <!-- Navigation principale (desktop) -->
        <nav class="hidden md:flex space-x-6">
          <Link 
            href="/" 
            :class="[
              'font-medium transition-all duration-300 relative py-2 px-1',
              isCurrentPage('/') 
                ? 'text-red-500 font-semibold' 
                : 'text-gray-700 hover:text-red-500'
            ]"
          >
            <span class="relative z-10">Accueil</span>
            <div 
              v-if="isCurrentPage('/')" 
              class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-red-500 to-pink-500 rounded-full"
            ></div>
          </Link>
          
          <Link 
            href="/publication" 
            :class="[
              'font-medium transition-all duration-300 relative py-2 px-1',
              isCurrentPage('/publication') 
                ? 'text-red-500 font-semibold' 
                : 'text-gray-700 hover:text-red-500'
            ]"
          >
            <span class="relative z-10">Publications</span>
            <div 
              v-if="isCurrentPage('/publication')" 
              class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-red-500 to-pink-500 rounded-full"
            ></div>
          </Link>
          
          <Link 
            href="/solidarity" 
            :class="[
              'font-medium transition-all duration-300 relative py-2 px-1',
              isCurrentPage('/solidarity') 
                ? 'text-red-500 font-semibold' 
                : 'text-gray-700 hover:text-red-500'
            ]"
          >
            <span class="relative z-10">Projet Solidaire</span>
            <div 
              v-if="isCurrentPage('/solidarity')" 
              class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-red-500 to-pink-500 rounded-full"
            ></div>
          </Link>

          <Link 
            href="/visually-impaired-people" 
            :class="[
              'font-medium transition-all duration-300 relative py-2 px-1',
              isCurrentPage('/visually-impaired-people') 
                ? 'text-blue-500 font-semibold' 
                : 'text-gray-700 hover:text-blue-500'
            ]"
          >
            <span class="relative z-10">Nos bénéficiaires</span>
            <div 
              v-if="isCurrentPage('/visually-impaired-people')" 
              class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full"
            ></div>
          </Link>
          
          <Link 
            href="/contact" 
            :class="[
              'font-medium transition-all duration-300 relative py-2 px-1',
              isCurrentPage('/contact') 
                ? 'text-red-500 font-semibold' 
                : 'text-gray-700 hover:text-red-500'
            ]"
          >
            <span class="relative z-10">Contact</span>
            <div 
              v-if="isCurrentPage('/contact')" 
              class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-red-500 to-pink-500 rounded-full"
            ></div>
          </Link>
        </nav>
        
        <!-- Actions utilisateur et menu mobile -->
        <div class="flex items-center space-x-4">
          <!-- Si l'utilisateur est connecté -->
          <div v-if="$page.props.auth?.user" class="flex items-center space-x-4">
            <!-- Notifications (futures) -->
            <button class="hidden md:block relative p-2 text-gray-500 hover:text-red-500 transition-colors duration-300">
              <i class="far fa-bell text-lg"></i>
              <!-- Badge de notification (exemple) -->
              <span v-if="false" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                3
              </span>
            </button>
            
            <!-- Profil utilisateur -->
            <div class="relative" ref="userMenuRef">
              <button 
                @click="toggleUserMenu"
                class="flex items-center space-x-2 p-2 rounded-full hover:bg-gray-50 transition-colors duration-300"
              >
                <div class="relative w-8 h-8">
                  <img 
                    v-if="!avatarError && $page.props.auth.user.avatar_url" 
                    :src="$page.props.auth.user.avatar_url" 
                    :alt="$page.props.auth.user.pseudo"
                    class="w-8 h-8 rounded-full object-cover border-2 border-gray-200 transition-all duration-300 hover:border-red-300"
                    @error="avatarError = true"
                  >
                  <div 
                    v-else 
                    class="w-8 h-8 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white text-sm font-medium border-2 border-gray-200 transition-all duration-300 hover:border-red-300"
                  >
                    <i class="fas fa-user"></i>
                  </div>
                </div>
                <span class="hidden md:block text-gray-700 font-medium">{{ $page.props.auth.user.pseudo }}</span>
                <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform duration-300" :class="{ 'rotate-180': showUserMenu }"></i>
              </button>
              
              <!-- Menu déroulant utilisateur -->
              <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
              >
                <div 
                  v-if="showUserMenu"
                  class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 py-2 z-50"
                >
                  <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-medium text-gray-900">{{ $page.props.auth.user.pseudo }}</p>
                    <p class="text-xs text-gray-500">{{ $page.props.auth.user.email }}</p>
                  </div>
                  
                  <!-- <Link 
                    href="/profile" 
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200"
                  >
                    <i class="fas fa-user-circle mr-3 text-gray-400"></i>
                    Mon profil
                  </Link> -->
                  
                  <Link 
                    href="/publication" 
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200"
                  >
                    <i class="fas fa-file-alt mr-3 text-gray-400"></i>
                    Mes publications
                  </Link>
                  
                  <!-- <Link 
                    href="/mes-dons" 
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200"
                  >
                    <i class="fas fa-heart mr-3 text-gray-400"></i>
                    Mes contributions
                  </Link> -->
                  
                  <div class="border-t border-gray-100 my-1"></div>
                  
                  <Link 
                    :href="$route('auth.logout')" 
                    method="post"
                    as="button"
                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200"
                  >
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Déconnexion
                  </Link>
                </div>
              </Transition>
            </div>
          </div>
          
          <!-- Si l'utilisateur n'est pas connecté -->
          <div v-else class="hidden md:flex items-center space-x-3">
            <Link 
              href="/auth/login" 
              class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full font-medium transition-all duration-300 hover:shadow-lg transform hover:-translate-y-0.5"
            >
              <i class="fas fa-sign-in-alt mr-2"></i>
              Connexion
            </Link>
            <Link 
              href="/auth/register" 
              class="border border-red-500 text-red-500 hover:bg-red-50 px-4 py-2 rounded-full font-medium transition-all duration-300 hover:shadow-lg transform hover:-translate-y-0.5"
            >
              <i class="fas fa-user-plus mr-2"></i>
              Inscription
            </Link>
          </div>
          
          <!-- Bouton menu mobile -->
          <button 
            @click="toggleMobileMenu"
            class="md:hidden p-2 text-gray-700 hover:text-red-500 transition-colors duration-300"
          >
            <Transition mode="out-in">
              <i v-if="!showMobileMenu" key="menu" class="fas fa-bars text-xl"></i>
              <i v-else key="close" class="fas fa-times text-xl"></i>
            </Transition>
          </button>
        </div>
      </div>
      
      <!-- Menu mobile -->
      <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 max-h-0"
        enter-to-class="opacity-100 max-h-96"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 max-h-96"
        leave-to-class="opacity-0 max-h-0"
      >
        <div v-if="showMobileMenu" class="md:hidden overflow-hidden">
          <nav class="px-2 pt-4 pb-3 bg-gray-50 rounded-b-xl mt-3 space-y-2">
            <Link 
              href="/" 
              @click="closeMobileMenu"
              :class="[
                'block px-4 py-3 rounded-lg font-medium transition-all duration-300',
                isCurrentPage('/') 
                  ? 'bg-red-500 text-white shadow-lg' 
                  : 'text-gray-700 hover:bg-red-50 hover:text-red-600'
              ]"
            >
              <i class="fas fa-home mr-3"></i>
              Accueil
            </Link>
            
            <Link 
              href="/publication" 
              @click="closeMobileMenu"
              :class="[
                'block px-4 py-3 rounded-lg font-medium transition-all duration-300',
                isCurrentPage('/publication') 
                  ? 'bg-red-500 text-white shadow-lg' 
                  : 'text-gray-700 hover:bg-red-50 hover:text-red-600'
              ]"
            >
              <i class="fas fa-book-open mr-3"></i>
              Publications
            </Link>
            
            <Link 
              href="/solidarity" 
              @click="closeMobileMenu"
              :class="[
                'block px-4 py-3 rounded-lg font-medium transition-all duration-300',
                isCurrentPage('/solidarity') 
                  ? 'bg-red-500 text-white shadow-lg' 
                  : 'text-gray-700 hover:bg-red-50 hover:text-red-600'
              ]"
            >
              <i class="fas fa-hands-helping mr-3"></i>
              Projet Solidaire
            </Link>

            <Link 
              href="/visually-impaired-people" 
              @click="closeMobileMenu"
              :class="[
                'block px-4 py-3 rounded-lg font-medium transition-all duration-300',
                isCurrentPage('/visually-impaired-people') 
                  ? 'bg-blue-500 text-white shadow-lg' 
                  : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'
              ]"
            >
              <i class="fas fa-users mr-3"></i>
              Nos bénéficiaires
            </Link>
            
            <Link 
              href="/contact" 
              @click="closeMobileMenu"
              :class="[
                'block px-4 py-3 rounded-lg font-medium transition-all duration-300',
                isCurrentPage('/contact') 
                  ? 'bg-red-500 text-white shadow-lg' 
                  : 'text-gray-700 hover:bg-red-50 hover:text-red-600'
              ]"
            >
              <i class="fas fa-envelope mr-3"></i>
              Contact
            </Link>
            
            <!-- Actions mobile pour utilisateurs non connectés -->
            <div v-if="!$page.props.auth?.user" class="border-t border-gray-200 pt-3 mt-3 space-y-2">
              <Link 
                href="/auth/login" 
                @click="closeMobileMenu"
                class="block px-4 py-3 rounded-lg font-medium text-white bg-red-500 hover:bg-red-600 transition-all duration-300 text-center"
              >
                <i class="fas fa-sign-in-alt mr-2"></i>
                Connexion
              </Link>
              <Link 
                href="/auth/register" 
                @click="closeMobileMenu"
                class="block px-4 py-3 rounded-lg font-medium text-red-500 border border-red-500 hover:bg-red-50 transition-all duration-300 text-center"
              >
                <i class="fas fa-user-plus mr-2"></i>
                Inscription
              </Link>
            </div>
            
            <!-- Actions mobile pour utilisateurs connectés -->
            <div v-else class="border-t border-gray-200 pt-3 mt-3 space-y-2">
              <div class="px-4 py-2 text-sm text-gray-600">
                Connecté en tant que <strong>{{ $page.props.auth.user.pseudo }}</strong>
              </div>
              
              <Link 
                href="/profile" 
                @click="closeMobileMenu"
                class="block px-4 py-3 rounded-lg font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-300"
              >
                <i class="fas fa-user-circle mr-3"></i>
                Mon profil
              </Link>
              
              <Link 
                href="/mes-publications" 
                @click="closeMobileMenu"
                class="block px-4 py-3 rounded-lg font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-300"
              >
                <i class="fas fa-file-alt mr-3"></i>
                Mes publications
              </Link>
              
              <Link 
                :href="$route('auth.logout')" 
                method="post"
                as="button"
                @click="closeMobileMenu"
                class="block w-full text-left px-4 py-3 rounded-lg font-medium text-red-600 hover:bg-red-50 transition-all duration-300"
              >
                <i class="fas fa-sign-out-alt mr-3"></i>
                Déconnexion
              </Link>
            </div>
          </nav>
        </div>
      </Transition>
    </div>
  </header>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

// État pour gérer les erreurs de chargement d'avatar et les menus
const avatarError = ref(false);
const showMobileMenu = ref(false);
const showUserMenu = ref(false);
const isScrolled = ref(false);
const userMenuRef = ref(null);

// Page actuelle pour la navigation active
const page = usePage();

// Fonction pour déterminer si une page est active
const isCurrentPage = (path) => {
  const currentPath = page.url;
  if (path === '/') {
    return currentPath === '/';
  }
  return currentPath.startsWith(path);
};

// Gestion du scroll pour l'ombre du header
const handleScroll = () => {
  isScrolled.value = window.scrollY > 10;
};

// Gestion du menu mobile
const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value;
  if (showMobileMenu.value) {
    showUserMenu.value = false;
  }
};

const closeMobileMenu = () => {
  showMobileMenu.value = false;
};

// Gestion du menu utilisateur
const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
  if (showUserMenu.value) {
    showMobileMenu.value = false;
  }
};

// Fermer les menus en cliquant à l'extérieur
const handleClickOutside = (event) => {
  // Fermer le menu utilisateur si on clique à l'extérieur
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    showUserMenu.value = false;
  }
  
  // Fermer le menu mobile si on clique à l'extérieur
  if (showMobileMenu.value && !event.target.closest('.md\\:hidden')) {
    showMobileMenu.value = false;
  }
};

// Gestion des raccourcis clavier
const handleKeydown = (event) => {
  if (event.key === 'Escape') {
    showMobileMenu.value = false;
    showUserMenu.value = false;
  }
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
  document.addEventListener('click', handleClickOutside);
  document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
  document.removeEventListener('click', handleClickOutside);
  document.removeEventListener('keydown', handleKeydown);
});
</script>
