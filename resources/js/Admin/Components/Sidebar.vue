<template>
  <div class="sidebar bg-white w-64 md:w-20 lg:w-64 border-r border-gray-200 flex flex-col flex-shrink-0">
    <!-- Logo -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
      <div class="flex items-center space-x-2">
        <i class="fas fa-tachometer-alt text-primary text-2xl"></i>
        <span class="text-xl font-bold text-dark md:hidden lg:block">DECEPTION</span>
      </div>
    </div>

    <!-- Menu -->
    <nav class="flex-1 overflow-y-auto p-4">
      <div class="space-y-2">
        <div class="mb-4">
          <p class="text-xs uppercase text-gray-500 font-semibold tracking-wider md:hidden lg:block">Menu Principal</p>
        </div>
        <Link :href="$route('admin.home')"
          :class="['flex items-center space-x-3 p-3 rounded-lg', 
                      $page.component === 'Admin/Pages/Home' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100']">
        <i class="fas fa-tachometer-alt"></i>
        <span class="md:hidden lg:block">Tableau de bord</span>
        </Link>

        <Link :href="$route('admin.publications.index')"
          :class="['flex items-center space-x-3 p-3 rounded-lg',
          $page.component === 'Admin/Pages/Publications' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100']">
        <i class="fas fa-file-alt"></i>
        <span class="md:hidden lg:block">Publications</span>
        </Link>

        <Link :href="$route('admin.reports.index')"
          :class="['flex items-center space-x-3 p-3 rounded-lg',
          $page.component === 'Admin/Pages/Reports' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100']">
        <i class="fas fa-flag"></i>
        <span class="md:hidden lg:block">Signalements</span>
        </Link>

        <Link :href="$route('admin.tags.index')"
          :class="['flex items-center space-x-3 p-3 rounded-lg',
          $page.component === 'Admin/Pages/Tags' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100']">
        <i class="fas fa-tags"></i>
        <span class="md:hidden lg:block">Tags</span>
        </Link>

        <Link :href="$route('admin.users.index')"
          :class="['flex items-center space-x-3 p-3 rounded-lg',
          $page.component === 'Admin/Pages/Users' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100']">
        <i class="fas fa-users"></i>
        <span class="md:hidden lg:block">Utilisateurs</span>
        </Link>

        <Link :href="$route('admin.partners.index')"
          :class="['flex items-center space-x-3 p-3 rounded-lg',
          $page.component === 'Admin/Pages/Partners' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100']">
        <i class="fas fa-handshake"></i>
        <span class="md:hidden lg:block">Partenaires</span>
        </Link>

        <Link :href="$route('admin.solidarity-projects.index')"
          :class="['flex items-center space-x-3 p-3 rounded-lg',
          $page.component === 'Admin/Pages/SolidarityProjects' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100']">
        <i class="fas fa-heart"></i>
        <span class="md:hidden lg:block">Projets de solidarité</span>
        </Link>

        <Link :href="$route('admin.project-media.index')"
          :class="['flex items-center space-x-3 p-3 rounded-lg',
          $page.component === 'Admin/Pages/ProjectMedias' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100']">
        <i class="fas fa-photo-video"></i>
        <span class="md:hidden lg:block">Médias de projet</span>
        </Link>

        <Link :href="$route('admin.visually-impaired.index')"
          :class="['flex items-center space-x-3 p-3 rounded-lg',
          $page.component === 'Admin/Pages/VisuallyImpairedPeople' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100']">
        <i class="fas fa-eye"></i>
        <span class="md:hidden lg:block">Personnes aveugles</span>
        </Link>

      </div>

      <!-- Profile -->
      <div class="mt-auto p-4 border-t border-gray-200">
        <div class="flex items-center space-x-3">
          <img 
            :src="userAvatarUrl" 
            :alt="userName"
            class="w-10 h-10 rounded-full">
          <div class="md:hidden lg:block">
            <p class="font-medium text-dark">{{ userName }}</p>
            <p class="text-xs text-gray-500 mb-1">{{ userEmail }}</p>
            <Link 
              :href="$route('admin.auth.logout')" 
              method="post" 
              as="button"
              class="text-xs text-red-500 hover:text-red-600 flex items-center"
              @click="handleLogout">
              <i class="fas fa-sign-out-alt mr-1"></i>
              Déconnexion
            </Link>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()

// Données utilisateur dynamiques
const user = computed(() => page.props.auth?.user || {})
const userName = computed(() => user.value.name || user.value.pseudo || 'Admin')
const userEmail = computed(() => user.value.email || 'admin@exemple.com')
const userAvatarUrl = computed(() => {
  const name = encodeURIComponent(userName.value)
  return user.value.avatar_url || `https://ui-avatars.com/api/?name=${name}&background=4f46e5&color=fff`
})

// Gérer la déconnexion
const handleLogout = () => {
  if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
    // La déconnexion sera gérée par Inertia Link
    return true
  }
  return false
}
</script>

<style scoped>
.sidebar {
  transition: all 0.3s ease;
}
</style> 