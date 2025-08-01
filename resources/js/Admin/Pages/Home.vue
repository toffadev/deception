<template>
  <AdminLayout>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <StatsCard 
        title="Singles"
        value="0"
        change="0%"
        icon="music"
        color="primary"
      />
      <StatsCard 
        title="Événements"
        value="0"
        change="0%"
        icon="calendar-alt"
        color="secondary"
      />
      <StatsCard 
        title="Actualités"
        value="0"
        change="0%"
        icon="newspaper"
        color="purple"
      />
      <StatsCard 
        title="Médias"
        value="0"
        change="0%"
        icon="photo-video"
        color="yellow"
      />
    </div>

    <!-- Content Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
        <h2 class="text-lg font-semibold text-dark mb-4">Actualités récentes</h2>
        <div class="space-y-4">
          <div v-if="!recentActualities.length" class="text-center py-8">
            <i class="fas fa-newspaper text-gray-300 text-4xl mb-2"></i>
            <p class="text-gray-500">Aucune actualité disponible</p>
          </div>
          <div v-for="item in recentActualities" :key="item.id" class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full" 
                  :class="{
                    'bg-blue-100 text-blue-600': item.category === 'event',
                    'bg-purple-100 text-purple-600': item.category === 'news',
                    'bg-green-100 text-green-600': item.category === 'release'
                  }">
                  <i :class="[
                    'fas',
                    item.category === 'event' ? 'fa-calendar-alt' : 
                    item.category === 'news' ? 'fa-newspaper' : 'fa-music'
                  ]"></i>
                </span>
              </div>
              <div class="ml-4 flex-1">
                <div class="flex items-center justify-between">
                  <h3 class="text-sm font-medium text-gray-900">{{ item.title }}</h3>
                  <span class="text-xs text-gray-500">{{ item.date }}</span>
                </div>
                <p class="text-sm text-gray-500 mt-1">{{ item.shortContent }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-4 text-center">
          <a href="/admin/actualities" class="text-primary font-medium hover:underline text-sm">Voir toutes les actualités</a>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-dark mb-4">Contenu du site</h2>
        <div class="space-y-6">
          <div v-for="stat in contentStats" :key="stat.id">
            <div class="flex items-center justify-between mb-2">
              <span class="text-gray-600">{{ stat.name }}</span>
              <span class="font-medium">{{ stat.count }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div 
                class="h-2 rounded-full" 
                :class="stat.color"
                :style="{ width: `${stat.percentage}%` }"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Upcoming Events & New Project -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-dark">Événements à venir</h2>
        </div>
        <div class="divide-y divide-gray-200">
          <div v-if="!upcomingEvents.length" class="text-center py-8">
            <i class="fas fa-calendar text-gray-300 text-4xl mb-2"></i>
            <p class="text-gray-500">Aucun événement à venir</p>
          </div>
          <div v-for="event in upcomingEvents" :key="event.id" class="p-4 flex items-center">
            <div class="flex-shrink-0 h-16 w-16 rounded-md overflow-hidden bg-gray-200">
              <img :src="event.image" :alt="event.title" class="h-full w-full object-cover">
            </div>
            <div class="ml-4 flex-1">
              <h3 class="text-sm font-medium text-dark">{{ event.title }}</h3>
              <p class="text-sm text-gray-500">{{ event.location }}</p>
              <p class="text-xs text-gray-400 mt-1">{{ event.date }}</p>
            </div>
            <div class="ml-4">
              <span :class="[
                'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                event.isSoldOut ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'
              ]">
                {{ event.isSoldOut ? 'Complet' : 'Places disponibles' }}
              </span>
            </div>
          </div>
        </div>
        <div class="p-4 border-t border-gray-200 text-center">
          <a href="/admin/events" class="text-primary font-medium hover:underline">Voir tous les événements</a>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-dark">Nouveau projet</h2>
        </div>
        <div class="p-6">
          <div v-if="!newProject" class="text-center py-8">
            <i class="fas fa-compact-disc text-gray-300 text-4xl mb-2"></i>
            <p class="text-gray-500">Aucun projet en cours</p>
            <a href="/admin/new-project" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark">
              <i class="fas fa-plus mr-2"></i>
              Ajouter un projet
            </a>
          </div>
          <div v-else class="flex items-start space-x-4">
            <img 
              :src="newProject.cover_image" 
              :alt="newProject.title"
              class="h-32 w-32 rounded-lg object-cover"
            >
            <div>
              <h3 class="text-xl font-bold text-gray-900">{{ newProject.title }}</h3>
              <div class="mt-1">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-blue-100 text-blue-800': newProject.type === 'single',
                    'bg-purple-100 text-purple-800': newProject.type === 'album',
                    'bg-green-100 text-green-800': newProject.type === 'ep'
                  }"
                >
                  {{ newProject.type.toUpperCase() }}
                </span>
              </div>
              <p class="mt-2 text-sm text-gray-600">{{ newProject.description }}</p>
              <p class="mt-2 text-sm text-gray-500">Date de sortie: {{ newProject.release_date }}</p>
              <div class="mt-4">
                <a href="/admin/new-project" class="text-primary hover:underline">Gérer ce projet</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '../Layouts/AdminLayout.vue'
import StatsCard from '../Components/StatsCard.vue'

// Sample data - in a real application, this would come from props or API calls
const recentActualities = ref([
  {
    id: 1,
    title: 'Nouveau concert annoncé',
    category: 'event',
    date: '10 avril 2023',
    shortContent: 'Un nouveau concert vient d\'être ajouté à la tournée.'
  },
  {
    id: 2,
    title: 'Interview exclusive',
    category: 'news',
    date: '5 avril 2023',
    shortContent: 'Découvrez notre nouvelle interview exclusive.'
  },
  {
    id: 3,
    title: 'Nouvel album en préparation',
    category: 'release',
    date: '1 avril 2023',
    shortContent: 'Un nouvel album est en cours de préparation.'
  }
])

const contentStats = ref([
  {
    id: 1,
    name: 'Musiques',
    count: 24,
    percentage: 80,
    color: 'bg-primary'
  },
  {
    id: 2,
    name: 'Événements',
    count: 12,
    percentage: 40,
    color: 'bg-secondary'
  },
  {
    id: 3,
    name: 'Actualités',
    count: 36,
    percentage: 60,
    color: 'bg-purple-500'
  },
  {
    id: 4,
    name: 'Médias',
    count: 45,
    percentage: 75,
    color: 'bg-yellow-500'
  }
])

const upcomingEvents = ref([
  {
    id: 1,
    title: 'Concert à Paris',
    location: 'Paris, France',
    date: '15 mai 2023, 20:00',
    isSoldOut: false,
    image: 'https://via.placeholder.com/80'
  },
  {
    id: 2,
    title: 'Festival d\'été',
    location: 'Lyon, France',
    date: '22 juin 2023, 18:30',
    isSoldOut: true,
    image: 'https://via.placeholder.com/80'
  },
  {
    id: 3,
    title: 'Showcase',
    location: 'Marseille, France',
    date: '10 juillet 2023, 19:00',
    isSoldOut: false,
    image: 'https://via.placeholder.com/80'
  }
])

const newProject = ref({
  title: 'Nouveau single',
  type: 'single',
  description: 'Mon nouveau single qui sortira prochainement',
  release_date: '15 juin 2023',
  cover_image: 'https://via.placeholder.com/300'
})
</script> 