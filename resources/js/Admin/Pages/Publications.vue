<template>
  <AdminLayout>
    <!-- Notification -->
    <div 
      v-if="showNotification"
      :class="[
        'fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-[9999]', // Z-index élevé
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
                      Êtes-vous sûr de vouloir supprimer cette publication ? Cette action peut être annulée.
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

    <!-- Modal de confirmation de suppression définitive -->
    <Teleport to="body">
      <div v-if="showForceDeleteModal" class="fixed inset-0 z-[9998] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showForceDeleteModal = false">
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
                    Confirmer la suppression définitive
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500">
                      Êtes-vous sûr de vouloir supprimer définitivement cette publication ? Cette action est irréversible.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                @click="confirmForceDelete"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-800 text-base font-medium text-white hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-700 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Supprimer définitivement
              </button>
              <button
                type="button"
                @click="showForceDeleteModal = false"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Modal de modération -->
    <Teleport to="body">
      <div v-if="showModerationModal" class="fixed inset-0 z-[9998] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showModerationModal = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[9999]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Modérer la publication
              </h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Statut</label>
                  <select
                    v-model="moderationForm.status"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                    required
                  >
                    <option value="published">Publié</option>
                    <option value="moderated">Modéré</option>
                    <option value="hidden">Masqué</option>
                  </select>
                </div>
                <div v-if="moderationForm.status === 'moderated' || moderationForm.status === 'hidden'">
                  <label class="block text-sm font-medium text-gray-700">Raison de modération</label>
                  <textarea
                    v-model="moderationForm.moderation_reason"
                    rows="3"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                    placeholder="Expliquez la raison de cette modération..."
                    required
                  ></textarea>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                @click="confirmModeration"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm"
              >
                Confirmer
              </button>
              <button
                type="button"
                @click="showModerationModal = false"
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
    <div v-if="showAddForm || editingPublication" class="bg-white shadow-sm rounded-lg p-6 mb-6">
      <h2 class="text-lg font-medium text-gray-900 mb-6">
        {{ editingPublication ? 'Modifier la publication' : 'Ajouter une publication' }}
      </h2>
      
      <form @submit.prevent="handleFormSubmit" class="space-y-6">
        <!-- Type d'auteur -->
        <div>
          <label for="author_type" class="block text-sm font-medium text-gray-700">Type d'auteur</label>
          <select
            id="author_type"
            v-model="form.author_type"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
            <option value="">Sélectionner un type d'auteur</option>
            <option value="existing_user">Utilisateur existant</option>
            <option value="custom">Auteur personnalisé</option>
            <option value="anonymous">Anonyme</option>
          </select>
        </div>

        <!-- Auteur existant (conditionnel) -->
        <div v-if="form.author_type === 'existing_user'">
          <label for="user_id" class="block text-sm font-medium text-gray-700">Sélectionner un utilisateur</label>
          <select
            id="user_id"
            v-model="form.user_id"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
            <option value="">Sélectionner un utilisateur</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.pseudo }}
            </option>
          </select>
        </div>

        <!-- Auteur personnalisé (conditionnel) -->
        <div v-if="form.author_type === 'custom'">
          <label for="custom_author_name" class="block text-sm font-medium text-gray-700">Nom de l'auteur</label>
          <input
            type="text"
            id="custom_author_name"
            v-model="form.custom_author_name"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            placeholder="Entrez le nom de l'auteur"
            required
          >
        </div>

        <!-- Titre -->
        <div>
          <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
          <input
            type="text"
            id="title"
            v-model="form.title"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
        </div>

        <!-- Contenu -->
        <div>
          <label for="content" class="block text-sm font-medium text-gray-700">Contenu</label>
          <textarea
            id="content"
            v-model="form.content"
            rows="6"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          ></textarea>
        </div>

        <!-- Type -->
        <div>
          <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
          <select
            id="type"
            v-model="form.type"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
            <option value="">Sélectionner un type</option>
            <option value="testimony">Témoignage</option>
            <option value="reflection">Réflexion</option>
            <option value="poetry">Poésie</option>
          </select>
        </div>

        <!-- Statut -->
        <div>
          <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
          <select
            id="status"
            v-model="form.status"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
            <option value="">Sélectionner un statut</option>
            <option value="draft">Brouillon</option>
            <option value="published">Publié</option>
            <option value="moderated">Modéré</option>
            <option value="hidden">Masqué</option>
          </select>
        </div>

        <!-- Raison de modération -->
        <div v-if="form.status === 'moderated' || form.status === 'hidden'">
          <label for="moderation_reason" class="block text-sm font-medium text-gray-700">Raison de modération</label>
          <textarea
            id="moderation_reason"
            v-model="form.moderation_reason"
            rows="3"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            placeholder="Expliquez la raison de cette modération..."
          ></textarea>
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
            {{ editingPublication ? 'Mettre à jour' : 'Créer' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Liste des publications -->
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
            placeholder="Rechercher une publication..."
          >
        </div>
        
        <div class="flex space-x-3">
          <div class="relative">
            <select
              v-model="typeFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous les types</option>
              <option value="testimony">Témoignage</option>
              <option value="reflection">Réflexion</option>
              <option value="poetry">Poésie</option>
            </select>
          </div>

          <div class="relative">
            <select
              v-model="statusFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous les statuts</option>
              <option value="draft">Brouillon</option>
              <option value="published">Publié</option>
              <option value="moderated">Modéré</option>
              <option value="hidden">Masqué</option>
            </select>
          </div>
          
          <button 
            @click="showAddForm = true"
            class="flex items-center space-x-2 px-4 py-2 bg-primary text-white rounded-md shadow-sm text-sm font-medium hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-primary"
          >
            <i class="fas fa-plus"></i>
            <span>Ajouter une publication</span>
          </button>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Témoignages</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.testimonies }}</h3>
            </div>
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
              <i class="fas fa-heart text-xl"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Réflexions</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.reflections }}</h3>
            </div>
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
              <i class="fas fa-lightbulb text-xl"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Poésies</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.poetry }}</h3>
            </div>
            <div class="p-3 rounded-full bg-green-100 text-green-600">
              <i class="fas fa-feather text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Total</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.total }}</h3>
            </div>
            <div class="p-3 rounded-full bg-gray-100 text-gray-600">
              <i class="fas fa-file-alt text-xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Titre
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Auteur
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Type
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Statut
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date de création
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="publication in paginatedPublications" :key="publication.id" :class="publication.deleted_at ? 'bg-red-50' : ''">
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">
                  {{ publication.title }}
                  <span v-if="publication.is_anonymous" class="ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">
                    Anonyme
                  </span>
                  <span v-if="publication.deleted_at" class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                    Supprimé
                  </span>
                </div>
                <div class="text-sm text-gray-500 truncate max-w-xs">
                  {{ publication.content.substring(0, 100) }}...
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                  {{ getAuthorDisplay(publication) }}
                  <span v-if="publication.is_anonymous" class="ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">
                    Anonyme
                  </span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-blue-100 text-blue-800': publication.type === 'testimony',
                    'bg-purple-100 text-purple-800': publication.type === 'reflection',
                    'bg-green-100 text-green-800': publication.type === 'poetry'
                  }"
                >
                  {{ getTypeLabel(publication.type) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-gray-100 text-gray-800': publication.status === 'draft',
                    'bg-green-100 text-green-800': publication.status === 'published',
                    'bg-yellow-100 text-yellow-800': publication.status === 'moderated',
                    'bg-red-100 text-red-800': publication.status === 'hidden'
                  }"
                >
                  {{ getStatusLabel(publication.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ formatDate(publication.created_at) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="viewPublication(publication)" 
                    class="text-blue-600 hover:text-blue-900"
                    title="Voir"
                  >
                    <i class="fas fa-eye"></i>
                  </button>
                  <button 
                    v-if="!publication.deleted_at"
                    @click="editPublication(publication)" 
                    class="text-primary hover:text-primary-dark"
                    title="Modifier"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
                  <button 
                    v-if="!publication.deleted_at"
                    @click="moderatePublication(publication)" 
                    class="text-yellow-600 hover:text-yellow-900"
                    title="Modérer"
                  >
                    <i class="fas fa-gavel"></i>
                  </button>
                  <button 
                    v-if="!publication.deleted_at"
                    @click="deletePublication(publication.id)" 
                    class="text-red-600 hover:text-red-900"
                    title="Supprimer"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                  <button 
                    v-if="publication.deleted_at"
                    @click="restorePublication(publication.id)" 
                    class="text-green-600 hover:text-green-900"
                    title="Restaurer"
                  >
                    <i class="fas fa-undo"></i>
                  </button>
                  <button 
                    v-if="publication.deleted_at"
                    @click="forceDeletePublication(publication.id)" 
                    class="text-red-800 hover:text-red-900"
                    title="Supprimer définitivement"
                  >
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="publications.data && publications.data.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              @click="changePage(publications.current_page - 1)"
              :disabled="publications.current_page <= 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Précédent
            </button>
            <button
              @click="changePage(publications.current_page + 1)"
              :disabled="publications.current_page >= publications.last_page"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Suivant
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Affichage de
                <span class="font-medium">{{ publications.total }}</span>
                résultats
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  @click="changePage(publications.current_page - 1)"
                  :disabled="publications.current_page <= 1"
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
                    page === publications.current_page
                      ? 'z-10 bg-primary border-primary text-white'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                  ]"
                >
                  {{ page }}
                </button>
                <button
                  @click="changePage(publications.current_page + 1)"
                  :disabled="publications.current_page >= publications.last_page"
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

    <!-- Modal de détail -->
    <Teleport to="body">
      <div v-if="showDetailModal" class="fixed inset-0 z-[9998] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showDetailModal = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full z-[9999]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  {{ currentDetailPublication?.title }}
                </h3>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-500">
                  <i class="fas fa-times"></i>
                </button>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Auteur</label>
                      <p class="mt-1 text-sm text-gray-900">
                        {{ getAuthorDisplay(currentDetailPublication) }}
                        <span v-if="currentDetailPublication?.is_anonymous" class="ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">
                          Anonyme
                        </span>
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Type</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-blue-100 text-blue-800': currentDetailPublication?.type === 'testimony',
                            'bg-purple-100 text-purple-800': currentDetailPublication?.type === 'reflection',
                            'bg-green-100 text-green-800': currentDetailPublication?.type === 'poetry'
                          }"
                        >
                          {{ getTypeLabel(currentDetailPublication?.type) }}
                        </span>
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Statut</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-gray-100 text-gray-800': currentDetailPublication?.status === 'draft',
                            'bg-green-100 text-green-800': currentDetailPublication?.status === 'published',
                            'bg-yellow-100 text-yellow-800': currentDetailPublication?.status === 'moderated',
                            'bg-red-100 text-red-800': currentDetailPublication?.status === 'hidden'
                          }"
                        >
                          {{ getStatusLabel(currentDetailPublication?.status) }}
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
                
                <div>
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Date de création</label>
                      <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailPublication?.created_at) }}</p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Dernière mise à jour</label>
                      <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailPublication?.updated_at) }}</p>
                    </div>
                    <div v-if="currentDetailPublication?.auto_tags && currentDetailPublication?.auto_tags.length > 0">
                      <label class="block text-sm font-medium text-gray-700">Tags</label>
                      <div class="mt-1 flex flex-wrap gap-2">
                        <span 
                          v-for="tag in currentDetailPublication.auto_tags" 
                          :key="tag"
                          class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded-full"
                        >
                          {{ tag }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Modération -->
              <div v-if="currentDetailPublication?.moderation_reason" class="mb-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                      <h3 class="text-sm font-medium text-yellow-800">
                        Publication modérée
                      </h3>
                      <div class="mt-2 text-sm text-yellow-700">
                        <p>
                          <strong>Raison :</strong> {{ currentDetailPublication.moderation_reason }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Contenu -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contenu</label>
                <div class="bg-gray-50 p-4 rounded-md overflow-auto max-h-80">
                  <div class="whitespace-pre-wrap text-gray-900">{{ currentDetailPublication?.content }}</div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                v-if="!currentDetailPublication?.deleted_at"
                @click="editPublication(currentDetailPublication); showDetailModal = false"
                class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm"
              >
                <i class="fas fa-edit mr-2"></i>
                Modifier
              </button>
              <button
                v-if="!currentDetailPublication?.deleted_at"
                @click="moderatePublication(currentDetailPublication); showDetailModal = false"
                class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:text-sm"
              >
                <i class="fas fa-gavel mr-2"></i>
                Modérer
              </button>
              <button
                type="button"
                @click="showDetailModal = false"
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
  publications: {
    type: Object,
    required: true
  },
  users: {
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
const statusFilter = ref(props.filters.status || '')
const showDeleteModal = ref(false)
const showModerationModal = ref(false)
const publicationToDelete = ref(null)
const publicationToModerate = ref(null)
const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref('success')
const showAddForm = ref(false)
const editingPublication = ref(null)
const showDetailModal = ref(false)
const currentDetailPublication = ref(null)
const showForceDeleteModal = ref(false) // Nouveau modal pour la suppression définitive
const publicationToForceDelete = ref(null) // ID de la publication à supprimer définitivement

// Form state
const form = ref({
  user_id: '',
  title: '',
  content: '',
  type: '',
  status: '',
  moderation_reason: '',
  author_type: '',
  custom_author_name: ''
})

// Moderation form
const moderationForm = ref({
  status: '',
  moderation_reason: ''
})

// Computed
const filteredPublications = computed(() => {
  let pubs = props.publications.data || []
  
  if (search.value) {
    const searchLower = search.value.toLowerCase()
    pubs = pubs.filter(pub => 
      pub.title.toLowerCase().includes(searchLower) ||
      pub.content.toLowerCase().includes(searchLower) ||
      (pub.user && pub.user.pseudo.toLowerCase().includes(searchLower))
    )
  }
  
  if (typeFilter.value) {
    pubs = pubs.filter(pub => pub.type === typeFilter.value)
  }
  
  if (statusFilter.value) {
    pubs = pubs.filter(pub => pub.status === statusFilter.value)
  }
  
  return pubs
})

const paginatedPublications = computed(() => {
  return props.publications.data || []
})

const stats = computed(() => {
  const allPubs = props.publications.data || []
  return {
    testimonies: allPubs.filter(p => p.type === 'testimony').length,
    reflections: allPubs.filter(p => p.type === 'reflection').length,
    poetry: allPubs.filter(p => p.type === 'poetry').length,
    total: allPubs.length
  }
})

const visiblePages = computed(() => {
  const current = props.publications.current_page
  const last = props.publications.last_page
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
    testimony: 'Témoignage',
    reflection: 'Réflexion',
    poetry: 'Poésie'
  }
  return labels[type] || type
}

const getStatusLabel = (status) => {
  const labels = {
    draft: 'Brouillon',
    published: 'Publié',
    moderated: 'Modéré',
    hidden: 'Masqué'
  }
  return labels[status] || status
}

const getAuthorDisplay = (publication) => {
  if (publication.is_anonymous) {
    return 'Anonyme'
  }
  if (publication.custom_author_name) {
    return publication.custom_author_name
  }
  if (publication.user) {
    return publication.user.pseudo
  }
  return 'Utilisateur supprimé'
}

// CORRECTION: Fonction viewPublication corrigée
const viewPublication = (publication) => {
  // Afficher dans une modal au lieu de naviguer vers une autre page
  currentDetailPublication.value = publication
  showDetailModal.value = true
}

const editPublication = (publication) => {
  editingPublication.value = publication
  
  // Déterminer le type d'auteur
  let authorType = 'existing_user'; // Par défaut
  if (publication.is_anonymous) {
    authorType = 'anonymous';
  } else if (publication.custom_author_name) {
    authorType = 'custom';
  }
  
  form.value = {
    user_id: publication.user_id || '',
    title: publication.title,
    content: publication.content,
    type: publication.type,
    status: publication.status,
    moderation_reason: publication.moderation_reason || '',
    author_type: authorType,
    custom_author_name: publication.custom_author_name || ''
  }
}

const moderatePublication = (publication) => {
  publicationToModerate.value = publication
  moderationForm.value = {
    status: publication.status,
    moderation_reason: publication.moderation_reason || ''
  }
  showModerationModal.value = true
}

const deletePublication = (id) => {
  publicationToDelete.value = id
  showDeleteModal.value = true
}

const restorePublication = (id) => {
  router.post(route('admin.publications.restore', id), {}, {
    onSuccess: () => {
      notificationMessage.value = 'Publication restaurée avec succès'
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

const forceDeletePublication = (id) => {
  publicationToForceDelete.value = id
  showForceDeleteModal.value = true
}

const confirmForceDelete = () => {
  if (publicationToForceDelete.value) {
    router.post(route('admin.publications.force-delete', publicationToForceDelete.value), {
      _method: 'delete'
    }, {
      onSuccess: () => {
        notificationMessage.value = 'Publication supprimée définitivement'
        notificationType.value = 'success'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      },
      onError: (errors) => {
        console.error('Erreur lors de la suppression définitive:', errors)
        notificationMessage.value = Object.values(errors)[0] || 'Une erreur est survenue lors de la suppression définitive'
        notificationType.value = 'error'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      }
    })
  }
  showForceDeleteModal.value = false
  publicationToForceDelete.value = null
}

const confirmDelete = () => {
  if (publicationToDelete.value) {
    // Utiliser la méthode post avec _method=delete pour éviter les problèmes
    router.post(route('admin.publications.destroy', publicationToDelete.value), {
      _method: 'delete'
    }, {
      onSuccess: () => {
        notificationMessage.value = 'Publication supprimée avec succès'
        notificationType.value = 'success'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      },
      onError: (errors) => {
        console.error('Erreur lors de la suppression:', errors)
        notificationMessage.value = Object.values(errors)[0] || 'Une erreur est survenue lors de la suppression'
        notificationType.value = 'error'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      }
    })
  }
  showDeleteModal.value = false
  publicationToDelete.value = null
}

const confirmModeration = () => {
  if (publicationToModerate.value) {
    router.post(route('admin.publications.moderate', publicationToModerate.value.id), moderationForm.value, {
      onSuccess: () => {
        notificationMessage.value = 'Publication modérée avec succès'
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
  showModerationModal.value = false
  publicationToModerate.value = null
}

// CORRECTION: Fonction handleFormSubmit corrigée pour utiliser PUT correctement
const handleFormSubmit = () => {
  if (editingPublication.value) {
    // Utiliser la méthode post avec _method=put pour éviter les problèmes
    router.post(route('admin.publications.update', editingPublication.value.id), {
      ...form.value,
      _method: 'put'
    }, {
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
        console.error('Erreurs de validation lors de la mise à jour:', errors)
        const firstError = Object.values(errors)[0]
        notificationMessage.value = Array.isArray(firstError) ? firstError[0] : firstError || 'Une erreur est survenue lors de la mise à jour'
        notificationType.value = 'error'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      }
    })
  } else {
    router.post(route('admin.publications.store'), form.value, {
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
        console.error('Erreurs de validation lors de la création:', errors)
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
  editingPublication.value = null
  form.value = {
    user_id: '',
    title: '',
    content: '',
    type: '',
    status: '',
    moderation_reason: '',
    author_type: '',
    custom_author_name: ''
  }
}

const changePage = (page) => {
  if (page >= 1 && page <= props.publications.last_page) {
    router.get(route('admin.publications.index'), {
      page: page,
      search: search.value,
      type: typeFilter.value,
      status: statusFilter.value
    }, {
      preserveState: true,
      preserveScroll: true
    })
  }
}

// Watchers pour les filtres
watch([search, typeFilter, statusFilter], () => {
  router.get(route('admin.publications.index'), {
    search: search.value,
    type: typeFilter.value,
    status: statusFilter.value
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['publications']
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