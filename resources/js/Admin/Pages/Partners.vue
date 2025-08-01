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
                      Êtes-vous sûr de vouloir supprimer définitivement ce partenaire ? Cette action est irréversible.
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
                Supprimer définitivement
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
    <div v-if="showAddForm || editingPartner" class="bg-white shadow-sm rounded-lg p-6 mb-6">
      <h2 class="text-lg font-medium text-gray-900 mb-6">
        {{ editingPartner ? 'Modifier le partenaire' : 'Ajouter un partenaire' }}
      </h2>
      
      <form @submit.prevent="handleFormSubmit" class="space-y-6" enctype="multipart/form-data">
        <!-- Nom -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Nom du partenaire</label>
          <input
            type="text"
            id="name"
            v-model="form.name"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
          <textarea
            id="description"
            v-model="form.description"
            rows="4"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          ></textarea>
        </div>

        <!-- Logo -->
        <div>
          <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
          <input
            type="file"
            id="logo"
            ref="logoInput"
            @change="handleLogoChange"
            accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-opacity-90"
          >
          <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPEG, PNG, JPG, GIF, SVG. Taille max: 2MB</p>
          
          <!-- Aperçu du logo actuel -->
          <div v-if="editingPartner && editingPartner.logo_path && !form.remove_logo" class="mt-2">
            <p class="text-sm text-gray-700 mb-2">Logo actuel :</p>
            <img :src="`/storage/${editingPartner.logo_path}`" alt="Logo actuel" class="h-20 w-20 object-contain border rounded">
            <button
              type="button"
              @click="form.remove_logo = true"
              class="mt-2 text-sm text-red-600 hover:text-red-900"
            >
              Supprimer le logo actuel
            </button>
          </div>
          
          <!-- Message de suppression -->
          <div v-if="form.remove_logo" class="mt-2 text-sm text-red-600">
            Le logo sera supprimé lors de la sauvegarde.
            <button
              type="button"
              @click="form.remove_logo = false"
              class="ml-2 text-primary hover:text-primary-dark"
            >
              Annuler
            </button>
          </div>
        </div>

        <!-- URL du site web -->
        <div>
          <label for="website_url" class="block text-sm font-medium text-gray-700">URL du site web</label>
          <input
            type="url"
            id="website_url"
            v-model="form.website_url"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            placeholder="https://example.com"
          >
        </div>

        <!-- Catégorie -->
        <div>
          <label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
          <select
            id="category"
            v-model="form.category"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
            <option value="">Sélectionner une catégorie</option>
            <option v-for="(label, key) in categories" :key="key" :value="key">
              {{ label }}
            </option>
          </select>
        </div>

        <!-- Ordre de tri -->
        <div>
          <label for="sort_order" class="block text-sm font-medium text-gray-700">Ordre de tri</label>
          <input
            type="number"
            id="sort_order"
            v-model.number="form.sort_order"
            min="0"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
          >
          <p class="mt-1 text-sm text-gray-500">Laissez vide pour placer automatiquement à la fin</p>
        </div>

        <!-- Statut actif -->
        <div>
          <div class="flex items-center">
            <input
              type="checkbox"
              id="is_active"
              v-model="form.is_active"
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
            >
            <label for="is_active" class="ml-2 block text-sm text-gray-900">
              Partenaire actif
            </label>
          </div>
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
            {{ editingPartner ? 'Mettre à jour' : 'Créer' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Liste des partenaires -->
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
            placeholder="Rechercher un partenaire..."
          >
        </div>
        
        <div class="flex space-x-3">
          <div class="relative">
            <select
              v-model="categoryFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Toutes les catégories</option>
              <option v-for="(label, key) in categories" :key="key" :value="key">
                {{ label }}
              </option>
            </select>
          </div>

          <div class="relative">
            <select
              v-model="statusFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous les statuts</option>
              <option value="active">Actif</option>
              <option value="inactive">Inactif</option>
            </select>
          </div>
          
          <button 
            @click="showAddForm = true"
            class="flex items-center space-x-2 px-4 py-2 bg-primary text-white rounded-md shadow-sm text-sm font-medium hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-primary"
          >
            <i class="fas fa-plus"></i>
            <span>Ajouter un partenaire</span>
          </button>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Mécénat</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.mecenas }}</h3>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
              <i class="fas fa-crown text-xl"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Associations</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.associations }}</h3>
            </div>
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
              <i class="fas fa-users text-xl"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Experts</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.experts }}</h3>
            </div>
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
              <i class="fas fa-user-tie text-xl"></i>
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
              <i class="fas fa-handshake text-xl"></i>
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
                Partenaire
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Catégorie
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Site web
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Statut
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Ordre
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="partner in paginatedPartners" :key="partner.id">
              <td class="px-6 py-4">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-12 w-12">
                    <img 
                      v-if="partner.logo_path" 
                      :src="`/storage/${partner.logo_path}`" 
                      :alt="`Logo ${partner.name}`"
                      class="h-12 w-12 object-contain rounded border"
                    >
                    <div 
                      v-else 
                      class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center"
                    >
                      <i class="fas fa-image text-gray-400"></i>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ partner.name }}</div>
                    <div class="text-sm text-gray-500 truncate max-w-xs">
                      {{ partner.description.substring(0, 80) }}...
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-yellow-100 text-yellow-800': partner.category === 'mecenas',
                    'bg-blue-100 text-blue-800': partner.category === 'association',
                    'bg-purple-100 text-purple-800': partner.category === 'expert'
                  }"
                >
                  {{ getCategoryLabel(partner.category) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <a 
                  v-if="partner.website_url" 
                  :href="partner.website_url" 
                  target="_blank" 
                  class="text-primary hover:text-primary-dark"
                >
                  <i class="fas fa-external-link-alt mr-1"></i>
                  Visiter
                </a>
                <span v-else class="text-gray-400">—</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-green-100 text-green-800': partner.is_active,
                    'bg-red-100 text-red-800': !partner.is_active
                  }"
                >
                  {{ partner.is_active ? 'Actif' : 'Inactif' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ partner.sort_order }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="viewPartner(partner)" 
                    class="text-blue-600 hover:text-blue-900"
                    title="Voir"
                  >
                    <i class="fas fa-eye"></i>
                  </button>
                  <button 
                    @click="editPartner(partner)" 
                    class="text-primary hover:text-primary-dark"
                    title="Modifier"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
                  <button 
                    @click="toggleStatus(partner)" 
                    :class="partner.is_active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900'"
                    :title="partner.is_active ? 'Désactiver' : 'Activer'"
                  >
                    <i :class="partner.is_active ? 'fas fa-pause' : 'fas fa-play'"></i>
                  </button>
                  <button 
                    @click="deletePartner(partner.id)" 
                    class="text-red-600 hover:text-red-900"
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
        <div v-if="partners.data && partners.data.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              @click="changePage(partners.current_page - 1)"
              :disabled="partners.current_page <= 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Précédent
            </button>
            <button
              @click="changePage(partners.current_page + 1)"
              :disabled="partners.current_page >= partners.last_page"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Suivant
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Affichage de
                <span class="font-medium">{{ partners.total }}</span>
                résultats
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  @click="changePage(partners.current_page - 1)"
                  :disabled="partners.current_page <= 1"
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
                    page === partners.current_page
                      ? 'z-10 bg-primary border-primary text-white'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                  ]"
                >
                  {{ page }}
                </button>
                <button
                  @click="changePage(partners.current_page + 1)"
                  :disabled="partners.current_page >= partners.last_page"
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
                  {{ currentDetailPartner?.name }}
                </h3>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-500">
                  <i class="fas fa-times"></i>
                </button>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                  <div class="space-y-4">
                    <!-- Logo -->
                    <div v-if="currentDetailPartner?.logo_path">
                      <label class="block text-sm font-medium text-gray-700">Logo</label>
                      <img 
                        :src="`/storage/${currentDetailPartner.logo_path}`" 
                        :alt="`Logo ${currentDetailPartner.name}`"
                        class="mt-1 h-20 w-20 object-contain border rounded"
                      >
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Catégorie</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-yellow-100 text-yellow-800': currentDetailPartner?.category === 'mecenas',
                            'bg-blue-100 text-blue-800': currentDetailPartner?.category === 'association',
                            'bg-purple-100 text-purple-800': currentDetailPartner?.category === 'expert'
                          }"
                        >
                          {{ getCategoryLabel(currentDetailPartner?.category) }}
                        </span>
                      </p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Statut</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-green-100 text-green-800': currentDetailPartner?.is_active,
                            'bg-red-100 text-red-800': !currentDetailPartner?.is_active
                          }"
                        >
                          {{ currentDetailPartner?.is_active ? 'Actif' : 'Inactif' }}
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
                
                <div>
                  <div class="space-y-4">
                    <div v-if="currentDetailPartner?.website_url">
                      <label class="block text-sm font-medium text-gray-700">Site web</label>
                      <p class="mt-1">
                        <a 
                          :href="currentDetailPartner.website_url" 
                          target="_blank" 
                          class="text-primary hover:text-primary-dark"
                        >
                          {{ currentDetailPartner.website_url }}
                          <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                      </p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Ordre de tri</label>
                      <p class="mt-1 text-sm text-gray-900">{{ currentDetailPartner?.sort_order }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Date de création</label>
                      <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailPartner?.created_at) }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Dernière mise à jour</label>
                      <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailPartner?.updated_at) }}</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Description -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <div class="bg-gray-50 p-4 rounded-md overflow-auto max-h-40">
                  <div class="whitespace-pre-wrap text-gray-900">{{ currentDetailPartner?.description }}</div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                @click="editPartner(currentDetailPartner); showDetailModal = false"
                class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm"
              >
                <i class="fas fa-edit mr-2"></i>
                Modifier
              </button>
              <button
                @click="toggleStatus(currentDetailPartner); showDetailModal = false"
                :class="[
                  'ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:text-sm',
                  currentDetailPartner?.is_active 
                    ? 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500' 
                    : 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
                ]"
              >
                <i :class="currentDetailPartner?.is_active ? 'fas fa-pause mr-2' : 'fas fa-play mr-2'"></i>
                {{ currentDetailPartner?.is_active ? 'Désactiver' : 'Activer' }}
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
  partners: {
    type: Object,
    required: true
  },
  categories: {
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
const categoryFilter = ref(props.filters.category || '')
const statusFilter = ref(props.filters.status || '')
const showDeleteModal = ref(false)
const partnerToDelete = ref(null)
const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref('success')
const showAddForm = ref(false)
const editingPartner = ref(null)
const showDetailModal = ref(false)
const currentDetailPartner = ref(null)
const logoInput = ref(null)

// Form state
const form = ref({
  name: '',
  description: '',
  logo: null,
  remove_logo: false,
  website_url: '',
  category: '',
  sort_order: null,
  is_active: true
})

// Computed
const paginatedPartners = computed(() => {
  return props.partners.data || []
})

const stats = computed(() => {
  const allPartners = props.partners.data || []
  return {
    mecenas: allPartners.filter(p => p.category === 'mecenas').length,
    associations: allPartners.filter(p => p.category === 'association').length,
    experts: allPartners.filter(p => p.category === 'expert').length,
    total: allPartners.length
  }
})

const visiblePages = computed(() => {
  const current = props.partners.current_page
  const last = props.partners.last_page
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

const getCategoryLabel = (category) => {
  return props.categories[category] || category
}

const handleLogoChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.value.logo = file
    form.value.remove_logo = false
  }
}

const viewPartner = (partner) => {
  currentDetailPartner.value = partner
  showDetailModal.value = true
}

const editPartner = (partner) => {
  editingPartner.value = partner
  form.value = {
    name: partner.name,
    description: partner.description,
    logo: null,
    remove_logo: false,
    website_url: partner.website_url || '',
    category: partner.category,
    sort_order: partner.sort_order,
    is_active: partner.is_active
  }
}

const deletePartner = (id) => {
  partnerToDelete.value = id
  showDeleteModal.value = true
}

const toggleStatus = (partner) => {
  router.post(route('admin.partners.toggle-status', partner.id), {}, {
    onSuccess: () => {
      const status = !partner.is_active ? 'activé' : 'désactivé'
      notificationMessage.value = `Partenaire ${status} avec succès`
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

const confirmDelete = () => {
  if (partnerToDelete.value) {
    router.post(route('admin.partners.destroy', partnerToDelete.value), {
      _method: 'delete'
    }, {
      onSuccess: () => {
        notificationMessage.value = 'Partenaire supprimé avec succès'
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
  partnerToDelete.value = null
}

const handleFormSubmit = () => {
  const formData = new FormData()
  
  // Ajouter tous les champs du formulaire
  Object.keys(form.value).forEach(key => {
    if (key === 'logo' && form.value[key]) {
      formData.append('logo', form.value[key])
    } else if (key === 'remove_logo') {
      formData.append('remove_logo', form.value[key] ? '1' : '0')
    } else if (key === 'is_active') {
      formData.append('is_active', form.value[key] ? '1' : '0')
    } else if (form.value[key] !== null && form.value[key] !== '') {
      formData.append(key, form.value[key])
    }
  })

  if (editingPartner.value) {
    formData.append('_method', 'put')
    router.post(route('admin.partners.update', editingPartner.value.id), formData, {
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
    router.post(route('admin.partners.store'), formData, {
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
  editingPartner.value = null
  form.value = {
    name: '',
    description: '',
    logo: null,
    remove_logo: false,
    website_url: '',
    category: '',
    sort_order: null,
    is_active: true
  }
  
  // Reset file input
  if (logoInput.value) {
    logoInput.value.value = ''
  }
}

const changePage = (page) => {
  if (page >= 1 && page <= props.partners.last_page) {
    router.get(route('admin.partners.index'), {
      page: page,
      search: search.value,
      category: categoryFilter.value,
      status: statusFilter.value
    }, {
      preserveState: true,
      preserveScroll: true
    })
  }
}

// Watchers pour les filtres
watch([search, categoryFilter, statusFilter], () => {
  router.get(route('admin.partners.index'), {
    search: search.value,
    category: categoryFilter.value,
    status: statusFilter.value
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['partners']
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