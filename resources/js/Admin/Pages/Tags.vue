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
                      Êtes-vous sûr de vouloir supprimer ce tag ? Cette action est définitive et ne peut pas être annulée.
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

    <!-- Modal de confirmation de suppression multiple -->
    <Teleport to="body">
      <div v-if="showBulkDeleteModal" class="fixed inset-0 z-[9998] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showBulkDeleteModal = false">
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
                    Confirmer la suppression multiple
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500">
                      Êtes-vous sûr de vouloir supprimer les {{ selectedTags.length }} tags sélectionnés ? Cette action est définitive et ne peut pas être annulée.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                @click="confirmBulkDelete"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Supprimer
              </button>
              <button
                type="button"
                @click="showBulkDeleteModal = false"
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
    <div v-if="showAddForm || editingTag" class="bg-white shadow-sm rounded-lg p-6 mb-6">
      <h2 class="text-lg font-medium text-gray-900 mb-6">
        {{ editingTag ? 'Modifier le tag' : 'Ajouter un tag' }}
      </h2>
      
      <form @submit.prevent="handleFormSubmit" class="space-y-6">
        <!-- Nom -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
          <input
            type="text"
            id="name"
            v-model="form.name"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
        </div>

        <!-- Slug (optionnel) -->
        <div>
          <label for="slug" class="block text-sm font-medium text-gray-700">
            Slug <span class="text-xs text-gray-500">(optionnel, généré automatiquement si vide)</span>
          </label>
          <input
            type="text"
            id="slug"
            v-model="form.slug"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
          >
        </div>

        <!-- Couleur -->
        <div>
          <label for="color" class="block text-sm font-medium text-gray-700">Couleur</label>
          <div class="flex items-center mt-1">
            <input
              type="color"
              id="color"
              v-model="form.color"
              class="h-10 w-10 border border-gray-300 rounded-md shadow-sm"
            >
            <input
              type="text"
              v-model="form.color"
              class="ml-2 block w-32 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
              pattern="^#[0-9A-Fa-f]{6}$"
              placeholder="#3B82F6"
              required
            >
          </div>
        </div>

        <!-- Tag suggéré -->
        <div class="flex items-center">
          <input
            type="checkbox"
            id="is_suggested"
            v-model="form.is_suggested"
            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
          >
          <label for="is_suggested" class="ml-2 block text-sm text-gray-900">
            Tag suggéré
          </label>
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
            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
          >
            {{ editingTag ? 'Mettre à jour' : 'Créer' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Liste des tags -->
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
            placeholder="Rechercher un tag..."
          >
        </div>
        
        <div class="flex space-x-3">
          <div class="relative">
            <select
              v-model="suggestedFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous les tags</option>
              <option value="yes">Tags suggérés</option>
              <option value="no">Tags non suggérés</option>
            </select>
          </div>

          <div class="relative">
            <select
              v-model="usageFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous les usages</option>
              <option value="used">Utilisés</option>
              <option value="unused">Non utilisés</option>
            </select>
          </div>
          
          <button 
            @click="showAddForm = true"
            class="flex items-center space-x-2 px-4 py-2 bg-primary text-white rounded-md shadow-sm text-sm font-medium hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-primary"
          >
            <i class="fas fa-plus"></i>
            <span>Ajouter un tag</span>
          </button>
        </div>
      </div>

      <!-- Actions supplémentaires -->
      <div class="flex flex-wrap gap-2 mb-6">
        <button 
          @click="recalculateUsage"
          class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm text-sm font-medium hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <i class="fas fa-sync"></i>
          <span>Recalculer l'usage</span>
        </button>
        
        <button 
          @click="cleanupTags"
          class="flex items-center space-x-2 px-4 py-2 bg-yellow-500 text-white rounded-md shadow-sm text-sm font-medium hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500"
        >
          <i class="fas fa-broom"></i>
          <span>Nettoyer les tags inutilisés</span>
        </button>
        
        <button 
          @click="confirmBulkDeleteSelected"
          :disabled="selectedTags.length === 0"
          :class="[
            'flex items-center space-x-2 px-4 py-2 text-white rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2',
            selectedTags.length === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-red-500 hover:bg-red-600 focus:ring-red-500'
          ]"
        >
          <i class="fas fa-trash"></i>
          <span>Supprimer sélection ({{ selectedTags.length }})</span>
        </button>
      </div>

      <!-- Table -->
      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                <input
                  type="checkbox"
                  @change="toggleSelectAll"
                  :checked="selectAll"
                  class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                >
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="changeSort('name')">
                Nom
                <span v-if="sortBy === 'name'" class="ml-1">
                  <i :class="[sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down']"></i>
                </span>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Slug
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Couleur
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="changeSort('usage')">
                Utilisations
                <span v-if="sortBy === 'usage'" class="ml-1">
                  <i :class="[sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down']"></i>
                </span>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="changeSort('created')">
                Créé le
                <span v-if="sortBy === 'created'" class="ml-1">
                  <i :class="[sortDirection === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down']"></i>
                </span>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Suggéré
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="tag in paginatedTags" :key="tag.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <input
                  type="checkbox"
                  v-model="selectedTags"
                  :value="tag.id"
                  class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                >
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">
                  {{ tag.name }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">
                  {{ tag.slug }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div 
                    class="h-6 w-6 rounded-full mr-2" 
                    :style="{ backgroundColor: tag.color }"
                  ></div>
                  <span class="text-sm text-gray-500">{{ tag.color }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ tag.usage_count }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ formatDate(tag.created_at) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <button 
                  @click="toggleSuggested(tag)"
                  :class="[
                    'px-2 py-1 text-xs font-semibold rounded-full',
                    tag.is_suggested ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                  ]"
                >
                  {{ tag.is_suggested ? 'Oui' : 'Non' }}
                </button>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="editTag(tag)" 
                    class="text-primary hover:text-primary-dark"
                    title="Modifier"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
                  <button 
                    @click="deleteTag(tag.id)" 
                    class="text-red-600 hover:text-red-900"
                    :disabled="tag.publications_count > 0"
                    :class="{ 'opacity-50 cursor-not-allowed': tag.publications_count > 0 }"
                    title="Supprimer"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="tags.data && tags.data.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              @click="changePage(tags.current_page - 1)"
              :disabled="tags.current_page <= 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Précédent
            </button>
            <button
              @click="changePage(tags.current_page + 1)"
              :disabled="tags.current_page >= tags.last_page"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Suivant
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Affichage de
                <span class="font-medium">{{ tags.total }}</span>
                résultats
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  @click="changePage(tags.current_page - 1)"
                  :disabled="tags.current_page <= 1"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <i class="fas fa-chevron-left"></i>
                </button>
                <button
                  v-for="page in visiblePages"
                  :key="page"
                  @click="changePage(page)"
                  :class="[
                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                    page === tags.current_page
                      ? 'z-10 bg-primary border-primary text-white'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                  ]"
                >
                  {{ page }}
                </button>
                <button
                  @click="changePage(tags.current_page + 1)"
                  :disabled="tags.current_page >= tags.last_page"
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <i class="fas fa-chevron-right"></i>
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import AdminLayout from '../Layouts/AdminLayout.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  tags: {
    type: Object,
    required: true
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
const suggestedFilter = ref(props.filters.suggested || '')
const usageFilter = ref(props.filters.usage || '')
const sortBy = ref(props.filters.sort || 'name')
const sortDirection = ref(props.filters.direction || 'asc')
const showDeleteModal = ref(false)
const tagToDelete = ref(null)
const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref('success')
const showAddForm = ref(false)
const editingTag = ref(null)
const selectedTags = ref([])
const selectAll = ref(false)
const showBulkDeleteModal = ref(false)

// Form state
const form = ref({
  name: '',
  slug: '',
  color: '#3B82F6',
  is_suggested: false
})

// Computed
const paginatedTags = computed(() => {
  return props.tags.data || []
})

const visiblePages = computed(() => {
  const current = props.tags.current_page
  const last = props.tags.last_page
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
    day: 'numeric'
  })
}

const editTag = (tag) => {
  editingTag.value = tag
  
  form.value = {
    name: tag.name,
    slug: tag.slug,
    color: tag.color,
    is_suggested: tag.is_suggested
  }
}

const deleteTag = (id) => {
  tagToDelete.value = id
  showDeleteModal.value = true
}

const confirmDelete = () => {
  if (tagToDelete.value) {
    router.delete(route('admin.tags.destroy', tagToDelete.value), {
      onSuccess: () => {
        notificationMessage.value = 'Tag supprimé avec succès'
        notificationType.value = 'success'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      },
      onError: (errors) => {
        notificationMessage.value = Object.values(errors)[0] || 'Une erreur est survenue lors de la suppression'
        notificationType.value = 'error'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      }
    })
  }
  showDeleteModal.value = false
  tagToDelete.value = null
}

const handleFormSubmit = () => {
  if (editingTag.value) {
    router.put(route('admin.tags.update', editingTag.value.id), form.value, {
      preserveScroll: true,
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
        notificationMessage.value = Array.isArray(firstError) ? firstError[0] : firstError || 'Une erreur est survenue lors de la mise à jour'
        notificationType.value = 'error'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      }
    })
  } else {
    router.post(route('admin.tags.store'), form.value, {
      preserveScroll: true,
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
        notificationMessage.value = Array.isArray(firstError) ? firstError[0] : firstError || 'Une erreur est survenue lors de la création'
        notificationType.value = 'error'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      }
    })
  }
}

const cancelForm = () => {
  showAddForm.value = false
  editingTag.value = null
  form.value = {
    name: '',
    slug: '',
    color: '#3B82F6',
    is_suggested: false
  }
}

const toggleSuggested = (tag) => {
  router.post(route('admin.tags.toggle-suggested', tag.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      notificationMessage.value = tag.is_suggested 
        ? 'Tag retiré des suggestions'        : 'Tag ajouté aux suggestions'
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

const changePage = (page) => {
  if (page >= 1 && page <= props.tags.last_page) {
    router.get(route('admin.tags.index'), {
      page: page,
      search: search.value,
      suggested: suggestedFilter.value,
      usage: usageFilter.value,
      sort: sortBy.value,
      direction: sortDirection.value
    }, {
      preserveState: true,
      preserveScroll: true
    })
  }
}

const changeSort = (column) => {
  if (sortBy.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortBy.value = column
    sortDirection.value = 'asc'
  }
  
  router.get(route('admin.tags.index'), {
    search: search.value,
    suggested: suggestedFilter.value,
    usage: usageFilter.value,
    sort: sortBy.value,
    direction: sortDirection.value
  }, {
    preserveState: true,
    preserveScroll: true
  })
}

const toggleSelectAll = () => {
  selectAll.value = !selectAll.value
  
  if (selectAll.value) {
    selectedTags.value = props.tags.data.map(tag => tag.id)
  } else {
    selectedTags.value = []
  }
}

const confirmBulkDeleteSelected = () => {
  if (selectedTags.value.length > 0) {
    showBulkDeleteModal.value = true
  }
}

const confirmBulkDelete = () => {
  if (selectedTags.value.length > 0) {
    router.post(route('admin.tags.bulk-destroy'), {
      tag_ids: selectedTags.value
    }, {
      onSuccess: () => {
        notificationMessage.value = `${selectedTags.value.length} tag(s) supprimé(s) avec succès`
        notificationType.value = 'success'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
        selectedTags.value = []
        selectAll.value = false
      },
      onError: (errors) => {
        notificationMessage.value = Object.values(errors)[0] || 'Une erreur est survenue lors de la suppression multiple'
        notificationType.value = 'error'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      }
    })
  }
  showBulkDeleteModal.value = false
}

const recalculateUsage = () => {
  router.post(route('admin.tags.recalculate-usage'), {}, {
    preserveScroll: true,
    onSuccess: () => {
      notificationMessage.value = 'Compteurs d\'utilisation recalculés avec succès'
      notificationType.value = 'success'
      showNotification.value = true
      setTimeout(() => showNotification.value = false, 3000)
    },
    onError: (errors) => {
      notificationMessage.value = Object.values(errors)[0] || 'Une erreur est survenue lors du recalcul'
      notificationType.value = 'error'
      showNotification.value = true
      setTimeout(() => showNotification.value = false, 3000)
    }
  })
}

const cleanupTags = () => {
  router.post(route('admin.tags.cleanup'), {}, {
    preserveScroll: true,
    onSuccess: (page) => {
      if (page.props.flash && page.props.flash.success) {
        notificationMessage.value = page.props.flash.success
        notificationType.value = 'success'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      }
    },
    onError: (errors) => {
      notificationMessage.value = Object.values(errors)[0] || 'Une erreur est survenue lors du nettoyage'
      notificationType.value = 'error'
      showNotification.value = true
      setTimeout(() => showNotification.value = false, 3000)
    }
  })
}

// Watchers pour les filtres
watch([search, suggestedFilter, usageFilter], () => {
  router.get(route('admin.tags.index'), {
    search: search.value,
    suggested: suggestedFilter.value,
    usage: usageFilter.value,
    sort: sortBy.value,
    direction: sortDirection.value
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['tags']
  })
}, { debounce: 300 })

// Ajouter un watcher pour les messages flash
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