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
                      Êtes-vous sûr de vouloir supprimer définitivement ce projet ? Cette action est irréversible.
                      <br><strong>Note :</strong> Les projets avec des donations ne peuvent pas être supprimés.
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

    <!-- Modal de changement de statut -->
    <Teleport to="body">
      <div v-if="showStatusModal" class="fixed inset-0 z-[9998] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showStatusModal = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[9999]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Changer le statut du projet
              </h3>
              <div>
                <label class="block text-sm font-medium text-gray-700">Nouveau statut</label>
                <select
                  v-model="statusForm.status"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                  required
                >
                  <option v-for="(label, key) in statuses" :key="key" :value="key">
                    {{ label }}
                  </option>
                </select>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                @click="confirmStatusChange"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm"
              >
                Confirmer
              </button>
              <button
                type="button"
                @click="showStatusModal = false"
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
    <div v-if="showAddForm || editingProject" class="bg-white shadow-sm rounded-lg p-6 mb-6">
      <h2 class="text-lg font-medium text-gray-900 mb-6">
        {{ editingProject ? 'Modifier le projet' : 'Ajouter un projet de solidarité' }}
      </h2>
      
      <form @submit.prevent="handleFormSubmit" class="space-y-6" enctype="multipart/form-data">
        <!-- Titre -->
        <div>
          <label for="title" class="block text-sm font-medium text-gray-700">Titre du projet</label>
          <input
            type="text"
            id="title"
            v-model="form.title"
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

        <!-- Montants et devise -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label for="target_amount" class="block text-sm font-medium text-gray-700">Montant cible</label>
            <input
              type="number"
              id="target_amount"
              v-model.number="form.target_amount"
              step="0.01"
              min="0"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
              required
            >
          </div>
          
          <div>
            <label for="current_amount" class="block text-sm font-medium text-gray-700">Montant actuel</label>
            <input
              type="number"
              id="current_amount"
              v-model.number="form.current_amount"
              step="0.01"
              min="0"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            >
          </div>
          
          <div>
            <label for="currency" class="block text-sm font-medium text-gray-700">Devise</label>
            <select
              id="currency"
              v-model="form.currency"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
              required
            >
              <option v-for="currency in currencies" :key="currency" :value="currency">
                {{ currency }}
              </option>
            </select>
          </div>
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
            <option v-for="(label, key) in statuses" :key="key" :value="key">
              {{ label }}
            </option>
          </select>
        </div>

        <!-- Dates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700">Date de début</label>
            <input
              type="date"
              id="start_date"
              v-model="form.start_date"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
              required
            >
          </div>
          
          <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700">Date de fin (optionnelle)</label>
            <input
              type="date"
              id="end_date"
              v-model="form.end_date"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            >
          </div>
        </div>

        <!-- Image mise en avant -->
        <div>
          <label for="featured_image" class="block text-sm font-medium text-gray-700">Image mise en avant</label>
          <input
            type="file"
            id="featured_image"
            ref="imageInput"
            @change="handleImageChange"
            accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-opacity-90"
          >
          <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPEG, PNG, JPG, GIF, WEBP. Taille max: 5MB</p>
          
          <!-- Aperçu de l'image actuelle -->
          <div v-if="editingProject && editingProject.featured_image_path && !form.remove_image" class="mt-2">
            <p class="text-sm text-gray-700 mb-2">Image actuelle :</p>
            <img :src="`/storage/${editingProject.featured_image_path}`" alt="Image actuelle" class="h-32 w-48 object-cover border rounded">
            <button
              type="button"
              @click="form.remove_image = true"
              class="mt-2 text-sm text-red-600 hover:text-red-900"
            >
              Supprimer l'image actuelle
            </button>
          </div>
          
          <!-- Message de suppression -->
          <div v-if="form.remove_image" class="mt-2 text-sm text-red-600">
            L'image sera supprimée lors de la sauvegarde.
            <button
              type="button"
              @click="form.remove_image = false"
              class="ml-2 text-primary hover:text-primary-dark"
            >
              Annuler
            </button>
          </div>
        </div>

        <!-- Description d'impact -->
        <div>
          <label for="impact_description" class="block text-sm font-medium text-gray-700">Description d'impact (optionnelle)</label>
          <textarea
            id="impact_description"
            v-model="form.impact_description"
            rows="3"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            placeholder="Décrivez l'impact attendu de ce projet..."
          ></textarea>
        </div>

        <!-- Bénéficiaires -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Bénéficiaires (optionnel)</label>
          <div v-for="(beneficiary, index) in form.beneficiaries_info" :key="index" class="border rounded-lg p-4 mb-4">
            <div class="flex justify-between items-center mb-2">
              <h4 class="text-sm font-medium text-gray-700">Bénéficiaire {{ index + 1 }}</h4>
              <button
                type="button"
                @click="removeBeneficiary(index)"
                class="text-red-600 hover:text-red-900"
              >
                <i class="fas fa-trash"></i>
              </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-medium text-gray-600">Nom</label>
                <input
                  type="text"
                  v-model="beneficiary.name"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary text-sm"
                  required
                >
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-600">Contact</label>
                <input
                  type="text"
                  v-model="beneficiary.contact"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary text-sm"
                >
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-600">Description</label>
                <textarea
                  v-model="beneficiary.description"
                  rows="2"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary text-sm"
                ></textarea>
              </div>
            </div>
          </div>
          <button
            type="button"
            @click="addBeneficiary"
            class="flex items-center space-x-2 px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
          >
            <i class="fas fa-plus"></i>
            <span>Ajouter un bénéficiaire</span>
          </button>
        </div>

        <!-- Projet mis en avant -->
        <div>
          <div class="flex items-center">
            <input
              type="checkbox"
              id="is_featured"
              v-model="form.is_featured"
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
            >
            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
              Projet mis en avant
            </label>
          </div>
          <p class="mt-1 text-sm text-gray-500">Les projets mis en avant apparaissent en priorité sur le site</p>
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
            {{ editingProject ? 'Mettre à jour' : 'Créer' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Liste des projets -->
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
            placeholder="Rechercher un projet..."
          >
        </div>
        
        <div class="flex flex-wrap gap-3">
          <select
            v-model="statusFilter"
            class="pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
          >
            <option value="">Tous les statuts</option>
            <option v-for="(label, key) in statuses" :key="key" :value="key">
              {{ label }}
            </option>
          </select>

          <select
            v-model="featuredFilter"
            class="pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
          >
            <option value="">Tous les projets</option>
            <option value="yes">Mis en avant</option>
            <option value="no">Non mis en avant</option>
          </select>

          <select
            v-model="progressFilter"
            class="pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
          >
            <option value="">Tous les progrès</option>
            <option value="not_started">Pas commencé</option>
            <option value="in_progress">En cours</option>
            <option value="completed">Terminé</option>
          </select>

          <select
            v-model="dateRangeFilter"
            class="pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
          >
            <option value="">Toutes les dates</option>
            <option value="upcoming">À venir</option>
            <option value="current">En cours</option>
            <option value="expired">Expirés</option>
          </select>
          
          <button 
            @click="showAddForm = true"
            class="flex items-center space-x-2 px-4 py-2 bg-primary text-white rounded-md shadow-sm text-sm font-medium hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-primary"
          >
            <i class="fas fa-plus"></i>
            <span>Ajouter un projet</span>
          </button>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Projets actifs</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.active }}</h3>
            </div>
            <div class="p-3 rounded-full bg-green-100 text-green-600">
              <i class="fas fa-play text-xl"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Projets terminés</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.completed }}</h3>
            </div>
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
              <i class="fas fa-check text-xl"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Mis en avant</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.featured }}</h3>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
              <i class="fas fa-star text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Total</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.total }}</h3>
            </div>
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
              <i class="fas fa-heart text-xl"></i>
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
                Projet
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Statut
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Progression
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Montants
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Dates
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="project in paginatedProjects" :key="project.id">
              <td class="px-6 py-4">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-16 w-16">
                    <img 
                      v-if="project.featured_image_path" 
                      :src="`/storage/${project.featured_image_path}`" 
                      :alt="`Image ${project.title}`"
                      class="h-16 w-16 object-cover rounded border"
                    >
                    <div 
                      v-else 
                      class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center"
                    >
                      <i class="fas fa-image text-gray-400"></i>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{ project.title }}
                      <span v-if="project.is_featured" class="ml-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                        <i class="fas fa-star mr-1"></i>Mis en avant
                      </span>
                    </div>
                    <div class="text-sm text-gray-500 truncate max-w-xs">
                      {{ project.description.substring(0, 100) }}...
                    </div>
                    <div v-if="project.donations_count" class="text-xs text-gray-400 mt-1">
                      {{ project.donations_count }} donation(s)
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-gray-100 text-gray-800': project.status === 'planned',
                    'bg-green-100 text-green-800': project.status === 'active',
                    'bg-blue-100 text-blue-800': project.status === 'completed',
                    'bg-orange-100 text-orange-800': project.status === 'paused'
                  }"
                >
                  {{ getStatusLabel(project.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div 
                    class="bg-primary h-2 rounded-full" 
                    :style="{ width: `${Math.min(100, project.progress_percentage)}%` }"
                  ></div>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                  {{ Math.round(project.progress_percentage) }}%
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                  {{ formatCurrency(project.current_amount, project.currency) }}
                </div>
                <div class="text-xs text-gray-500">
                  sur {{ formatCurrency(project.target_amount, project.currency) }}
                </div>
                <div v-if="project.remaining_amount > 0" class="text-xs text-orange-600">
                  Reste: {{ formatCurrency(project.remaining_amount, project.currency) }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <div>Début: {{ formatDate(project.start_date) }}</div>
                <div v-if="project.end_date">Fin: {{ formatDate(project.end_date) }}</div>
                <div v-else class="text-gray-400">Pas de date de fin</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="viewProject(project)" 
                    class="text-blue-600 hover:text-blue-900"
                    title="Voir"
                  >
                    <i class="fas fa-eye"></i>
                  </button>
                  <button 
                    @click="editProject(project)" 
                    class="text-primary hover:text-primary-dark"
                    title="Modifier"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
                  <button 
                    @click="changeStatus(project)" 
                    class="text-purple-600 hover:text-purple-900"
                    title="Changer le statut"
                  >
                    <i class="fas fa-exchange-alt"></i>
                  </button>
                  <button 
                    @click="toggleFeatured(project)" 
                    :class="project.is_featured ? 'text-yellow-600 hover:text-yellow-900' : 'text-gray-600 hover:text-gray-900'"
                    :title="project.is_featured ? 'Retirer de la mise en avant' : 'Mettre en avant'"
                  >
                    <i :class="project.is_featured ? 'fas fa-star' : 'far fa-star'"></i>
                  </button>
                  <button 
                    @click="recalculateAmount(project)" 
                    class="text-green-600 hover:text-green-900"
                    title="Recalculer le montant"
                  >
                    <i class="fas fa-calculator"></i>
                  </button>
                  <button 
                    @click="deleteProject(project.id)" 
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
        <div v-if="projects.data && projects.data.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              @click="changePage(projects.current_page - 1)"
              :disabled="projects.current_page <= 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Précédent
            </button>
            <button
              @click="changePage(projects.current_page + 1)"
              :disabled="projects.current_page >= projects.last_page"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Suivant
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Affichage de
                <span class="font-medium">{{ projects.total }}</span>
                résultats
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  @click="changePage(projects.current_page - 1)"
                  :disabled="projects.current_page <= 1"
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
                    page === projects.current_page
                      ? 'z-10 bg-primary border-primary text-white'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                  ]"
                >
                  {{ page }}
                </button>
                <button
                  @click="changePage(projects.current_page + 1)"
                  :disabled="projects.current_page >= projects.last_page"
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
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full z-[9999]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  {{ currentDetailProject?.title }}
                  <span v-if="currentDetailProject?.is_featured" class="ml-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                    <i class="fas fa-star mr-1"></i>Mis en avant
                  </span>
                </h3>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-500">
                  <i class="fas fa-times"></i>
                </button>
              </div>
              
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                  <!-- Image -->
                  <div v-if="currentDetailProject?.featured_image_path" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image mise en avant</label>
                    <img 
                      :src="`/storage/${currentDetailProject.featured_image_path}`" 
                      :alt="`Image ${currentDetailProject.title}`"
                      class="w-full h-48 object-cover rounded border"
                    >
                  </div>
                  
                  <!-- Description -->
                  <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <div class="bg-gray-50 p-3 rounded-md text-sm text-gray-900">{{ currentDetailProject?.description }}</div>
                  </div>
                  
                  <!-- Impact -->
                  <div v-if="currentDetailProject?.impact_description" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description d'impact</label>
                    <div class="bg-gray-50 p-3 rounded-md text-sm text-gray-900">{{ currentDetailProject.impact_description }}</div>
                  </div>
                </div>
                
                <div>
                  <!-- Informations générales -->
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Statut</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-gray-100 text-gray-800': currentDetailProject?.status === 'planned',
                            'bg-green-100 text-green-800': currentDetailProject?.status === 'active',
                            'bg-blue-100 text-blue-800': currentDetailProject?.status === 'completed',
                            'bg-orange-100 text-orange-800': currentDetailProject?.status === 'paused'
                          }"
                        >
                          {{ getStatusLabel(currentDetailProject?.status) }}
                        </span>
                      </p>
                    </div>
                    
                    <!-- Progression -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Progression</label>
                      <div class="w-full bg-gray-200 rounded-full h-4">
                        <div 
                          class="bg-primary h-4 rounded-full flex items-center justify-center" 
                          :style="{ width: `${Math.min(100, currentDetailProject?.progress_percentage || 0)}%` }"
                        >
                          <span class="text-xs text-white font-medium">{{ Math.round(currentDetailProject?.progress_percentage || 0) }}%</span>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Montants -->
                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-gray-700">Montant actuel</label>
                        <p class="mt-1 text-lg font-bold text-primary">
                          {{ formatCurrency(currentDetailProject?.current_amount, currentDetailProject?.currency) }}
                        </p>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700">Montant cible</label>
                        <p class="mt-1 text-lg font-bold text-gray-900">
                          {{ formatCurrency(currentDetailProject?.target_amount, currentDetailProject?.currency) }}
                        </p>
                      </div>
                    </div>
                    
                    <div v-if="(currentDetailProject?.remaining_amount || 0) > 0">
                      <label class="block text-sm font-medium text-gray-700">Montant restant</label>
                      <p class="mt-1 text-sm text-orange-600">
                        {{ formatCurrency(currentDetailProject?.remaining_amount, currentDetailProject?.currency) }}
                      </p>
                    </div>
                    
                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-gray-700">Date de début</label>
                        <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailProject?.start_date) }}</p>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <p class="mt-1 text-sm text-gray-900">
                          {{ currentDetailProject?.end_date ? formatDate(currentDetailProject.end_date) : 'Pas de date de fin' }}
                        </p>
                      </div>
                    </div>
                    
                    <!-- Métadonnées -->
                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-gray-700">Créé le</label>
                        <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailProject?.created_at) }}</p>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700">Mis à jour le</label>
                        <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailProject?.updated_at) }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Bénéficiaires -->
              <div v-if="currentDetailProject?.beneficiaries_info && currentDetailProject.beneficiaries_info.length > 0" class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Bénéficiaires</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div 
                    v-for="(beneficiary, index) in currentDetailProject.beneficiaries_info" 
                    :key="index"
                    class="border rounded-lg p-3"
                  >
                    <h4 class="font-medium text-gray-900">{{ beneficiary.name }}</h4>
                    <p v-if="beneficiary.contact" class="text-sm text-gray-600">Contact: {{ beneficiary.contact }}</p>
                    <p v-if="beneficiary.description" class="text-sm text-gray-700 mt-1">{{ beneficiary.description }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                @click="editProject(currentDetailProject); showDetailModal = false"
                class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm"
              >
                <i class="fas fa-edit mr-2"></i>
                Modifier
              </button>
              <button
                @click="changeStatus(currentDetailProject); showDetailModal = false"
                class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:text-sm"
              >
                <i class="fas fa-exchange-alt mr-2"></i>
                Changer le statut
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
  projects: {
    type: Object,
    required: true
  },
  statuses: {
    type: Object,
    required: true
  },
  currencies: {
    type: Array,
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
const statusFilter = ref(props.filters.status || '')
const featuredFilter = ref(props.filters.featured || '')
const progressFilter = ref(props.filters.progress || '')
const dateRangeFilter = ref(props.filters.date_range || '')
const showDeleteModal = ref(false)
const showStatusModal = ref(false)
const projectToDelete = ref(null)
const projectToChangeStatus = ref(null)
const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref('success')
const showAddForm = ref(false)
const editingProject = ref(null)
const showDetailModal = ref(false)
const currentDetailProject = ref(null)
const imageInput = ref(null)

// Form state
const form = ref({
  title: '',
  description: '',
  target_amount: null,
  current_amount: null,
  currency: 'EUR',
  status: '',
  start_date: '',
  end_date: '',
  featured_image: null,
  remove_image: false,
  beneficiaries_info: [],
  impact_description: '',
  is_featured: false
})

// Status form
const statusForm = ref({
  status: ''
})

// Computed
const paginatedProjects = computed(() => {
  return props.projects.data || []
})

const stats = computed(() => {
  const allProjects = props.projects.data || []
  return {
    active: allProjects.filter(p => p.status === 'active').length,
    completed: allProjects.filter(p => p.status === 'completed').length,
    featured: allProjects.filter(p => p.is_featured).length,
    total: allProjects.length
  }
})

const visiblePages = computed(() => {
  const current = props.projects.current_page
  const last = props.projects.last_page
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
    month: 'short',
    day: 'numeric'
  })
}

const formatCurrency = (amount, currency) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: currency
  }).format(amount)
}

const getStatusLabel = (status) => {
  return props.statuses[status] || status
}

const handleImageChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.value.featured_image = file
    form.value.remove_image = false
  }
}

const addBeneficiary = () => {
  form.value.beneficiaries_info.push({
    name: '',
    description: '',
    contact: ''
  })
}

const removeBeneficiary = (index) => {
  form.value.beneficiaries_info.splice(index, 1)
}

const viewProject = (project) => {
  currentDetailProject.value = project
  showDetailModal.value = true
}

const editProject = (project) => {
  editingProject.value = project
  form.value = {
    title: project.title,
    description: project.description,
    target_amount: project.target_amount,
    current_amount: project.current_amount,
    currency: project.currency,
    status: project.status,
    start_date: project.start_date,
    end_date: project.end_date || '',
    featured_image: null,
    remove_image: false,
    beneficiaries_info: project.beneficiaries_info || [],
    impact_description: project.impact_description || '',
    is_featured: project.is_featured
  }
}

const deleteProject = (id) => {
  projectToDelete.value = id
  showDeleteModal.value = true
}

const changeStatus = (project) => {
  projectToChangeStatus.value = project
  statusForm.value.status = project.status
  showStatusModal.value = true
}

const toggleFeatured = (project) => {
  router.post(route('admin.solidarity-projects.toggle-featured', project.id), {}, {
    onSuccess: () => {
      const status = !project.is_featured ? 'mis en avant' : 'retiré de la mise en avant'
      notificationMessage.value = `Projet ${status} avec succès`
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

const recalculateAmount = (project) => {
  router.post(route('admin.solidarity-projects.recalculate-amount', project.id), {}, {
    onSuccess: () => {
      notificationMessage.value = 'Montant recalculé avec succès'
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
  if (projectToDelete.value) {
    router.post(route('admin.solidarity-projects.destroy', projectToDelete.value), {
      _method: 'delete'
    }, {
      onSuccess: () => {
        notificationMessage.value = 'Projet supprimé avec succès'
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
  projectToDelete.value = null
}

const confirmStatusChange = () => {
  if (projectToChangeStatus.value) {
    router.post(route('admin.solidarity-projects.update-status', projectToChangeStatus.value.id), statusForm.value, {
      onSuccess: () => {
        notificationMessage.value = 'Statut mis à jour avec succès'
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
  showStatusModal.value = false
  projectToChangeStatus.value = null
}

const handleFormSubmit = () => {
  const formData = new FormData()
  
  // Ajouter tous les champs du formulaire
  Object.keys(form.value).forEach(key => {
    if (key === 'featured_image' && form.value[key]) {
      formData.append('featured_image', form.value[key])
    } else if (key === 'remove_image') {
      formData.append('remove_image', form.value[key] ? '1' : '0')
    } else if (key === 'is_featured') {
      formData.append('is_featured', form.value[key] ? '1' : '0')
    } else if (key === 'beneficiaries_info') {
      if (form.value[key] && form.value[key].length > 0) {
        form.value[key].forEach((beneficiary, index) => {
          Object.keys(beneficiary).forEach(field => {
            if (beneficiary[field]) {
              formData.append(`beneficiaries_info[${index}][${field}]`, beneficiary[field])
            }
          })
        })
      }
    } else if (form.value[key] !== null && form.value[key] !== '') {
      formData.append(key, form.value[key])
    }
  })

  if (editingProject.value) {
    formData.append('_method', 'put')
    router.post(route('admin.solidarity-projects.update', editingProject.value.id), formData, {
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
    router.post(route('admin.solidarity-projects.store'), formData, {
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
  editingProject.value = null
  form.value = {
    title: '',
    description: '',
    target_amount: null,
    current_amount: null,
    currency: 'EUR',
    status: '',
    start_date: '',
    end_date: '',
    featured_image: null,
    remove_image: false,
    beneficiaries_info: [],
    impact_description: '',
    is_featured: false
  }
  
  // Reset file input
  if (imageInput.value) {
    imageInput.value.value = ''
  }
}

const changePage = (page) => {
  if (page >= 1 && page <= props.projects.last_page) {
    router.get(route('admin.solidarity-projects.index'), {
      page: page,
      search: search.value,
      status: statusFilter.value,
      featured: featuredFilter.value,
      progress: progressFilter.value,
      date_range: dateRangeFilter.value
    }, {
      preserveState: true,
      preserveScroll: true
    })
  }
}

// Watchers pour les filtres
watch([search, statusFilter, featuredFilter, progressFilter, dateRangeFilter], () => {
  router.get(route('admin.solidarity-projects.index'), {
    search: search.value,
    status: statusFilter.value,
    featured: featuredFilter.value,
    progress: progressFilter.value,
    date_range: dateRangeFilter.value
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['projects']
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