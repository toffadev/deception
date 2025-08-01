<template>
  <AdminLayout>
    <!-- Notification -->
    <div 
      v-if="showNotification"
      :class="[
        'fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-[9999]',
        notificationType === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
      ]"
    >
      {{ notificationMessage }}
    </div>

    <!-- Modal de confirmation de suppression -->
    <Teleport to="body">
      <div v-if="showDeleteModal" class="fixed inset-0 z-[9998] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showDeleteModal = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[9999]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                  <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Confirmer la suppression
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500">
                      Êtes-vous sûr de vouloir supprimer ce média ? Cette action est irréversible et supprimera également le fichier.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                @click="confirmDelete"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Supprimer
              </button>
              <button
                type="button"
                @click="showDeleteModal = false"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Formulaire d'ajout/modification -->
    <div v-if="showAddForm || editingMedia" class="bg-white shadow-sm rounded-lg p-6 mb-6">
      <h2 class="text-lg font-medium text-gray-900 mb-6">
        {{ editingMedia ? 'Modifier le média' : 'Ajouter un média' }}
      </h2>
      
      <form @submit.prevent="handleFormSubmit" class="space-y-6">
        <!-- Projet de solidarité -->
        <div>
          <label for="solidarity_project_id" class="block text-sm font-medium text-gray-700">Projet de solidarité</label>
          <select
            id="solidarity_project_id"
            v-model="form.solidarity_project_id"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
            <option value="">Sélectionner un projet</option>
            <option v-for="project in projects" :key="project.id" :value="project.id">
              {{ project.title }}
            </option>
          </select>
        </div>

        <!-- Type de média -->
        <div>
          <label for="type" class="block text-sm font-medium text-gray-700">Type de média</label>
          <select
            id="type"
            v-model="form.type"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
            <option value="">Sélectionner un type</option>
            <option value="image">Image</option>
            <option value="video">Vidéo</option>
          </select>
        </div>

        <!-- Fichier -->
        <div>
          <label for="file" class="block text-sm font-medium text-gray-700">
            Fichier {{ editingMedia ? '(laisser vide pour conserver le fichier actuel)' : '' }}
          </label>
          <input
            type="file"
            id="file"
            ref="fileInput"
            @change="handleFileChange"
            :accept="getAcceptedFileTypes()"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            :required="!editingMedia"
          >
          <p class="mt-1 text-sm text-gray-500">
            {{ getFileTypeHelp() }}
          </p>
        </div>

        <!-- Titre -->
        <div>
          <label for="title" class="block text-sm font-medium text-gray-700">Titre (optionnel)</label>
          <input
            type="text"
            id="title"
            v-model="form.title"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            placeholder="Titre du média"
          >
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700">Description (optionnel)</label>
          <textarea
            id="description"
            v-model="form.description"
            rows="3"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            placeholder="Description du média"
          ></textarea>
        </div>

        <!-- Texte alternatif -->
        <div>
          <label for="alt_text" class="block text-sm font-medium text-gray-700">Texte alternatif (optionnel)</label>
          <input
            type="text"
            id="alt_text"
            v-model="form.alt_text"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            placeholder="Texte alternatif pour l'accessibilité"
          >
        </div>

        <!-- Ordre de tri -->
        <div>
          <label for="sort_order" class="block text-sm font-medium text-gray-700">Ordre de tri (optionnel)</label>
          <input
            type="number"
            id="sort_order"
            v-model="form.sort_order"
            min="0"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            placeholder="0"
          >
        </div>

        <!-- Boutons -->
        <div class="flex justify-end space-x-3">
          <button 
            type="button" 
            @click="cancelForm" 
            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
          >
            Annuler
          </button>
          <button
            type="submit"
            :disabled="isUploading"
            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50"
          >
            {{ isUploading ? 'Upload en cours...' : (editingMedia ? 'Mettre à jour' : 'Créer') }}
          </button>
        </div>
      </form>
    </div>

    <!-- Liste des médias -->
    <div v-else>
      <!-- Actions Bar -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <div class="relative w-full md:w-64">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
          </div>
          <input 
            type="text" 
            v-model="search" 
            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm" 
            placeholder="Rechercher un média..."
          >
        </div>
        
        <div class="flex space-x-3">
          <div class="relative">
            <select
              v-model="typeFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous les types</option>
              <option value="image">Images</option>
              <option value="video">Vidéos</option>
            </select>
          </div>

          <div class="relative">
            <select
              v-model="projectFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous les projets</option>
              <option v-for="project in projects" :key="project.id" :value="project.id">
                {{ project.title }}
              </option>
            </select>
          </div>
          
          <button 
            @click="showAddForm = true"
            class="flex items-center space-x-2 px-4 py-2 bg-primary text-white rounded-md shadow-sm text-sm font-medium hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-primary"
          >
            <i class="fas fa-plus"></i>
            <span>Ajouter un média</span>
          </button>
        </div>
      </div>

      <!-- Grid des médias -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div 
          v-for="media in paginatedMedia" 
          :key="media.id" 
          class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow"
        >
          <!-- Prévisualisation du média -->
          <div class="aspect-video bg-gray-100 relative">
            <img 
              v-if="media.type === 'image'" 
              :src="getMediaUrl(media.file_path)" 
              :alt="media.alt_text || media.title || 'Image'"
              class="w-full h-full object-cover"
              @error="handleImageError"
            >
            <div 
              v-else-if="media.type === 'video'" 
              class="w-full h-full flex items-center justify-center bg-gray-200"
            >
              <div class="text-center">
                <i class="fas fa-play-circle text-4xl text-gray-500 mb-2"></i>
                <p class="text-sm text-gray-600">Vidéo</p>
              </div>
            </div>
            
            <!-- Badge du type -->
            <div class="absolute top-2 left-2">
              <span class="px-2 py-1 text-xs font-semibold rounded-full"
                :class="{
                  'bg-blue-100 text-blue-800': media.type === 'image',
                  'bg-purple-100 text-purple-800': media.type === 'video'
                }"
              >
                {{ getTypeLabel(media.type) }}
              </span>
            </div>

            <!-- Ordre de tri -->
            <div class="absolute top-2 right-2">
              <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                #{{ media.sort_order }}
              </span>
            </div>
          </div>

          <!-- Informations du média -->
          <div class="p-4">
            <div class="mb-2">
              <h3 class="font-medium text-gray-900 truncate">
                {{ media.title || 'Sans titre' }}
              </h3>
              <p class="text-sm text-gray-600">
                {{ media.solidarity_project?.title || 'Projet supprimé' }}
              </p>
            </div>
            
            <div v-if="media.description" class="mb-3">
              <p class="text-sm text-gray-500 line-clamp-2">
                {{ media.description }}
              </p>
            </div>

            <div class="text-xs text-gray-400 mb-3">
              Créé {{ formatDate(media.created_at) }}
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center">
              <div class="flex space-x-2">
                <button 
                  @click="viewMedia(media)" 
                  class="text-blue-600 hover:text-blue-900 text-sm"
                  title="Voir"
                >
                  <i class="fas fa-eye"></i>
                </button>
                <button 
                  @click="editMedia(media)" 
                  class="text-primary hover:text-primary-dark text-sm"
                  title="Modifier"
                >
                  <i class="fas fa-edit"></i>
                </button>
                <button 
                  @click="deleteMedia(media.id)" 
                  class="text-red-600 hover:text-red-900 text-sm"
                  title="Supprimer"
                >
                  <i class="fas fa-trash"></i>
                </button>
              </div>
              
              <a 
                :href="getMediaUrl(media.file_path)" 
                target="_blank"
                class="text-gray-600 hover:text-gray-900 text-sm"
                title="Télécharger"
              >
                <i class="fas fa-download"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="projectMedia.data && projectMedia.data.length > 0" class="mt-8 flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
          <button
            @click="changePage(projectMedia.current_page - 1)"
            :disabled="projectMedia.current_page <= 1"
            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Précédent
          </button>
          <button
            @click="changePage(projectMedia.current_page + 1)"
            :disabled="projectMedia.current_page >= projectMedia.last_page"
            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Suivant
          </button>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Affichage de
              <span class="font-medium">{{ projectMedia.total }}</span>
              résultats
            </p>
          </div>
          <div>
            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
              <button
                @click="changePage(projectMedia.current_page - 1)"
                :disabled="projectMedia.current_page <= 1"
                class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <i class="fas fa-chevron-left"></i>
              </button>
              <button
                v-for="page in visiblePages"
                :key="page"
                @click="changePage(page)"
                :class="[
                  'relative inline-flex items-center px-4 py-2 text-sm font-semibold',
                  page === projectMedia.current_page
                    ? 'z-10 bg-primary text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary'
                    : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0'
                ]"
              >
                {{ page }}
              </button>
              <button
                @click="changePage(projectMedia.current_page + 1)"
                :disabled="projectMedia.current_page >= projectMedia.last_page"
                class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <i class="fas fa-chevron-right"></i>
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de prévisualisation -->
    <Teleport to="body">
      <div v-if="showPreviewModal" class="fixed inset-0 z-[9998] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showPreviewModal = false">
            <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
          </div>
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full z-[9999]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  {{ currentPreviewMedia?.title || 'Prévisualisation du média' }}
                </h3>
                <button @click="showPreviewModal = false" class="text-gray-400 hover:text-gray-500">
                  <i class="fas fa-times"></i>
                </button>
              </div>
              
              <div v-if="currentPreviewMedia" class="space-y-4">
                <!-- Prévisualisation -->
                <div class="bg-gray-100 rounded-lg overflow-hidden">
                  <img 
                    v-if="currentPreviewMedia.type === 'image'" 
                    :src="getMediaUrl(currentPreviewMedia.file_path)" 
                    :alt="currentPreviewMedia.alt_text || currentPreviewMedia.title || 'Image'"
                    class="w-full max-h-96 object-contain"
                  >
                  <video 
                    v-else-if="currentPreviewMedia.type === 'video'" 
                    :src="getMediaUrl(currentPreviewMedia.file_path)"
                    controls
                    class="w-full max-h-96"
                  >
                    Votre navigateur ne supporte pas la lecture de vidéos.
                  </video>
                </div>

                <!-- Informations -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="space-y-2">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Type</label>
                      <p class="text-sm text-gray-900">{{ getTypeLabel(currentPreviewMedia.type) }}</p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Projet</label>
                      <p class="text-sm text-gray-900">{{ currentPreviewMedia.solidarity_project?.title || 'Projet supprimé' }}</p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Ordre de tri</label>
                      <p class="text-sm text-gray-900">{{ currentPreviewMedia.sort_order }}</p>
                    </div>
                  </div>
                  <div class="space-y-2">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Créé le</label>
                      <p class="text-sm text-gray-900">{{ formatDate(currentPreviewMedia.created_at) }}</p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Modifié le</label>
                      <p class="text-sm text-gray-900">{{ formatDate(currentPreviewMedia.updated_at) }}</p>
                    </div>
                  </div>
                </div>

                <div v-if="currentPreviewMedia.description">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                  <p class="text-sm text-gray-900">{{ currentPreviewMedia.description }}</p>
                </div>

                <div v-if="currentPreviewMedia.alt_text">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Texte alternatif</label>
                  <p class="text-sm text-gray-900">{{ currentPreviewMedia.alt_text }}</p>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <a
                :href="getMediaUrl(currentPreviewMedia?.file_path)"
                target="_blank"
                class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm"
              >
                <i class="fas fa-download mr-2"></i>
                Télécharger
              </a>
              <button
                @click="editMedia(currentPreviewMedia); showPreviewModal = false"
                class="ml-3 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm"
              >
                <i class="fas fa-edit mr-2"></i>
                Modifier
              </button>
              <button
                type="button"
                @click="showPreviewModal = false"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Fermer
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import AdminLayout from '../Layouts/AdminLayout.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  projectMedia: {
    type: Object,
    required: true
  },
  projects: {
    type: Array,
    default: () => []
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  flash: {
    type: Object,
    default: () => ({
      success: null,
      error: null
    })
  }
})

// État local
const search = ref(props.filters.search || '')
const typeFilter = ref(props.filters.type || '')
const projectFilter = ref(props.filters.project_id || '')
const showDeleteModal = ref(false)
const showPreviewModal = ref(false)
const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref('success')
const showAddForm = ref(false)
const editingMedia = ref(null)
const currentPreviewMedia = ref(null)
const mediaToDelete = ref(null)
const isUploading = ref(false)
const fileInput = ref(null)

// Form state
const form = ref({
  solidarity_project_id: '',
  type: '',
  file: null,
  title: '',
  description: '',
  alt_text: '',
  sort_order: ''
})

// Computed
const paginatedMedia = computed(() => {
  return props.projectMedia.data || []
})

const visiblePages = computed(() => {
  const current = props.projectMedia.current_page
  const last = props.projectMedia.last_page
  const pages = []
  
  for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
    pages.push(i)
  }
  
  return pages
})

// Méthodes
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getTypeLabel = (type) => {
  const labels = {
    image: 'Image',
    video: 'Vidéo'
  }
  return labels[type] || type
}

const getMediaUrl = (filePath) => {
  return `/storage/${filePath}`
}

const getAcceptedFileTypes = () => {
  if (form.value.type === 'image') {
    return 'image/jpeg,image/png,image/jpg,image/gif,image/webp'
  } else if (form.value.type === 'video') {
    return 'video/mp4,video/avi,video/mov,video/wmv,video/webm'
  }
  return ''
}

const getFileTypeHelp = () => {
  if (form.value.type === 'image') {
    return 'Formats acceptés : JPEG, PNG, JPG, GIF, WebP (max 5MB)'
  } else if (form.value.type === 'video') {
    return 'Formats acceptés : MP4, AVI, MOV, WMV, WebM (max 50MB)'
  }
  return 'Sélectionnez d\'abord un type de média'
}

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.value.file = file
  }
}

const handleImageError = (event) => {
  event.target.src = '/images/placeholder.png' // Image par défaut en cas d'erreur
}

const viewMedia = (media) => {
  currentPreviewMedia.value = media
  showPreviewModal.value = true
}

const editMedia = (media) => {
  editingMedia.value = media
  form.value = {
    solidarity_project_id: media.solidarity_project_id,
    type: media.type,
    file: null,
    title: media.title || '',
    description: media.description || '',
    alt_text: media.alt_text || '',
    sort_order: media.sort_order
  }
}

const deleteMedia = (id) => {
  mediaToDelete.value = id
  showDeleteModal.value = true
}

const confirmDelete = () => {
  if (mediaToDelete.value) {
    router.post(route('admin.project-media.destroy', mediaToDelete.value), {
      _method: 'delete'
    }, {
      onSuccess: () => {
        notificationMessage.value = 'Média supprimé avec succès'
        notificationType.value = 'success'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      },
      onError: (errors) => {
        notificationMessage.value = Object.values(errors)[0] || 'Une erreur est survenue'
        notificationType.value = 'error'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      }
    })
  }
  showDeleteModal.value = false
  mediaToDelete.value = null
}

const handleFormSubmit = () => {
  if (!form.value.type) {
    notificationMessage.value = 'Veuillez sélectionner un type de média'
    notificationType.value = 'error'
    showNotification.value = true
    setTimeout(() => showNotification.value = false, 3000)
    return
  }

  if (!editingMedia.value && !form.value.file) {
    notificationMessage.value = 'Veuillez sélectionner un fichier'
    notificationType.value = 'error'
    showNotification.value = true
    setTimeout(() => showNotification.value = false, 3000)
    return
  }

  isUploading.value = true

  const formData = new FormData()
  formData.append('solidarity_project_id', form.value.solidarity_project_id)
  formData.append('type', form.value.type)
  formData.append('title', form.value.title)
  formData.append('description', form.value.description)
  formData.append('alt_text', form.value.alt_text)
  formData.append('sort_order', form.value.sort_order || '0')

  if (form.value.file) {
    formData.append('file', form.value.file)
  }

  if (editingMedia.value) {
    formData.append('_method', 'put')
    router.post(route('admin.project-media.update', editingMedia.value.id), formData, {
      forceFormData: true,
      onSuccess: (page) => {
        cancelForm()
        if (page.props.flash && page.props.flash.success) {
          notificationMessage.value = page.props.flash.success
          notificationType.value = 'success'
          showNotification.value = true
          setTimeout(() => showNotification.value = false, 3000)
        }
      },
      onError: (errors) => {
        const firstError = Object.values(errors)[0]
        notificationMessage.value = Array.isArray(firstError) ? firstError[0] : firstError || 'Une erreur est survenue'
        notificationType.value = 'error'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      },
      onFinish: () => {
        isUploading.value = false
      }
    })
  } else {
    router.post(route('admin.project-media.store'), formData, {
      forceFormData: true,
      onSuccess: (page) => {
        cancelForm()
        if (page.props.flash && page.props.flash.success) {
          notificationMessage.value = page.props.flash.success
          notificationType.value = 'success'
          showNotification.value = true
          setTimeout(() => showNotification.value = false, 3000)
        }
      },
      onError: (errors) => {
        const firstError = Object.values(errors)[0]
        notificationMessage.value = Array.isArray(firstError) ? firstError[0] : firstError || 'Une erreur est survenue'
        notificationType.value = 'error'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      },
      onFinish: () => {
        isUploading.value = false
      }
    })
  }
}

const cancelForm = () => {
  showAddForm.value = false
  editingMedia.value = null
  form.value = {
    solidarity_project_id: '',
    type: '',
    file: null,
    title: '',
    description: '',
    alt_text: '',
    sort_order: ''
  }
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const changePage = (page) => {
  if (page >= 1 && page <= props.projectMedia.last_page) {
    router.get(route('admin.project-media.index'), {
      page: page,
      search: search.value,
      type: typeFilter.value,
      project_id: projectFilter.value
    }, {
      preserveState: true,
      preserveScroll: true
    })
  }
}

// Watchers pour les filtres
watch([search, typeFilter, projectFilter], () => {
  router.get(route('admin.project-media.index'), {
    search: search.value,
    type: typeFilter.value,
    project_id: projectFilter.value
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['projectMedia']
  })
}, { debounce: 300 })

// Watcher pour les messages flash
watch(() => props.flash, (newFlash) => {
  if (newFlash.success) {
    notificationMessage.value = newFlash.success
    notificationType.value = 'success'
    showNotification.value = true
    setTimeout(() => showNotification.value = false, 3000)
  }
  if (newFlash.error) {
    notificationMessage.value = newFlash.error
    notificationType.value = 'error'
    showNotification.value = true
    setTimeout(() => showNotification.value = false, 3000)
  }
}, { immediate: true })
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
