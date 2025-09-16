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
                      Êtes-vous sûr de vouloir supprimer définitivement cette personne ? Cette action est irréversible.
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
    <div v-if="showAddForm || editingPerson" class="bg-white shadow-sm rounded-lg p-6 mb-6">
      <h2 class="text-lg font-medium text-gray-900 mb-6">
        {{ editingPerson ? 'Modifier la personne malvoyante' : 'Ajouter une personne malvoyante' }}
      </h2>
      
      <form @submit.prevent="handleFormSubmit" class="space-y-6" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Nom -->
          <div>
            <label for="nom" class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
            <input
              type="text"
              id="nom"
              v-model="form.nom"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
              required
            >
          </div>

          <!-- Prénom -->
          <div>
            <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom <span class="text-red-500">*</span></label>
            <input
              type="text"
              id="prenom"
              v-model="form.prenom"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
              required
            >
          </div>

          <!-- Sexe -->
          <div>
            <label for="sexe" class="block text-sm font-medium text-gray-700">Sexe <span class="text-red-500">*</span></label>
            <select
              id="sexe"
              v-model="form.sexe"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
              required
            >
              <option value="">Sélectionner le sexe</option>
              <option v-for="(label, key) in sexes" :key="key" :value="key">
                {{ label }}
              </option>
            </select>
          </div>

          <!-- Âge -->
          <div>
            <label for="age" class="block text-sm font-medium text-gray-700">Âge</label>
            <input
              type="number"
              id="age"
              v-model.number="form.age"
              min="0"
              max="150"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            >
          </div>

          <!-- Téléphone -->
          <div>
            <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone <span class="text-red-500">*</span></label>
            <input
              type="tel"
              id="telephone"
              v-model="form.telephone"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
              required
            >
          </div>

          <!-- Lieu de résidence -->
          <div>
            <label for="lieu_residence" class="block text-sm font-medium text-gray-700">Lieu de résidence</label>
            <input
              type="text"
              id="lieu_residence"
              v-model="form.lieu_residence"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            >
          </div>

          <!-- Type de voyance -->
          <div>
            <label for="type_voyance" class="block text-sm font-medium text-gray-700">Type de voyance</label>
            <select
              id="type_voyance"
              v-model="form.type_voyance"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            >
              <option value="">Sélectionner le type</option>
              <option v-for="(label, key) in typesVoyance" :key="key" :value="key">
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
        </div>

        <!-- Photo -->
        <div>
          <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>
          <input
            type="file"
            id="photo"
            ref="photoInput"
            @change="handlePhotoChange"
            accept="image/jpeg,image/png,image/jpg,image/gif"
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-opacity-90"
          >
          <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPEG, PNG, JPG, GIF. Taille max: 2MB</p>
          
          <!-- Aperçu de la photo actuelle -->
          <div v-if="editingPerson && editingPerson.photo_path && !form.remove_photo" class="mt-2">
            <p class="text-sm text-gray-700 mb-2">Photo actuelle :</p>
            <img :src="`/storage/${editingPerson.photo_path}`" alt="Photo actuelle" class="h-20 w-20 object-cover border rounded">
            <button
              type="button"
              @click="form.remove_photo = true"
              class="mt-2 text-sm text-red-600 hover:text-red-900"
            >
              Supprimer la photo actuelle
            </button>
          </div>
          
          <!-- Message de suppression -->
          <div v-if="form.remove_photo" class="mt-2 text-sm text-red-600">
            La photo sera supprimée lors de la sauvegarde.
            <button
              type="button"
              @click="form.remove_photo = false"
              class="ml-2 text-primary hover:text-primary-dark"
            >
              Annuler
            </button>
          </div>
        </div>

        <!-- Checkboxes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Traitement en cours -->
          <div>
            <div class="flex items-center">
              <input
                type="checkbox"
                id="traitement_en_cours"
                v-model="form.traitement_en_cours"
                class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
              >
              <label for="traitement_en_cours" class="ml-2 block text-sm text-gray-900">
                Traitement en cours
              </label>
            </div>
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
                Personne active
              </label>
            </div>
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
            {{ editingPerson ? 'Mettre à jour' : 'Créer' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Liste des personnes malvoyantes -->
    <div v-else>
      <!-- Actions Bar -->
      <div class="mb-6">
        <!-- Barre de recherche -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
          <div class="relative w-full lg:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-search text-gray-400"></i>
            </div>
            <input 
              type="text" 
              v-model="search" 
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm" 
              placeholder="Rechercher une personne..."
            >
          </div>
          
          <div class="flex justify-end">
            <button 
              @click="showAddForm = true"
              class="flex items-center space-x-2 px-4 py-2 bg-primary text-white rounded-md shadow-sm text-sm font-medium hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-primary whitespace-nowrap"
            >
              <i class="fas fa-plus"></i>
              <span>Ajouter une personne</span>
            </button>
          </div>
        </div>
        
        <!-- Filtres -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
          <select
            v-model="sexeFilter"
            class="pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
          >
            <option value="">Tous les sexes</option>
            <option v-for="(label, key) in sexes" :key="key" :value="key">
              {{ label }}
            </option>
          </select>

          <select
            v-model="typeVoyanceFilter"
            class="pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
          >
            <option value="">Tous les types</option>
            <option v-for="(label, key) in typesVoyance" :key="key" :value="key">
              {{ label }}
            </option>
          </select>

          <select
            v-model="statusFilter"
            class="pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
          >
            <option value="">Tous les statuts</option>
            <option value="active">Actif</option>
            <option value="inactive">Inactif</option>
          </select>

          <select
            v-model="traitementFilter"
            class="pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
          >
            <option value="">Tous les traitements</option>
            <option value="oui">En traitement</option>
            <option value="non">Sans traitement</option>
          </select>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Masculin</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.masculin }}</h3>
            </div>
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
              <i class="fas fa-mars text-xl"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Féminin</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.feminin }}</h3>
            </div>
            <div class="p-3 rounded-full bg-pink-100 text-pink-600">
              <i class="fas fa-venus text-xl"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">En traitement</p>
              <h3 class="text-2xl font-bold text-dark">{{ stats.enTraitement }}</h3>
            </div>
            <div class="p-3 rounded-full bg-green-100 text-green-600">
              <i class="fas fa-user-md text-xl"></i>
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
              <i class="fas fa-low-vision text-xl"></i>
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
                Personne
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Sexe
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Âge
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Type voyance
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Traitement
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Statut
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="person in paginatedPeople" :key="person.id">
              <td class="px-6 py-4">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-12 w-12">
                    <img 
                      v-if="person.photo_path" 
                      :src="`/storage/${person.photo_path}`" 
                      :alt="`Photo ${person.prenom} ${person.nom}`"
                      class="h-12 w-12 object-cover rounded-full border"
                    >
                    <div 
                      v-else 
                      class="h-12 w-12 bg-gray-200 rounded-full flex items-center justify-center"
                    >
                      <i class="fas fa-user text-gray-400"></i>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ person.prenom }} {{ person.nom }}</div>
                    <div class="text-sm text-gray-500">
                      <i class="fas fa-phone mr-1"></i>{{ person.telephone }}
                    </div>
                    <div v-if="person.lieu_residence" class="text-sm text-gray-500">
                      <i class="fas fa-map-marker-alt mr-1"></i>{{ person.lieu_residence }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-blue-100 text-blue-800': person.sexe === 'M',
                    'bg-pink-100 text-pink-800': person.sexe === 'F'
                  }"
                >
                  {{ getSexeLabel(person.sexe) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ person.age || '—' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span v-if="person.type_voyance" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                  {{ getTypeVoyanceLabel(person.type_voyance) }}
                </span>
                <span v-else class="text-gray-400">—</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-green-100 text-green-800': person.traitement_en_cours,
                    'bg-gray-100 text-gray-800': !person.traitement_en_cours
                  }"
                >
                  {{ person.traitement_en_cours ? 'En cours' : 'Aucun' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-green-100 text-green-800': person.is_active,
                    'bg-red-100 text-red-800': !person.is_active
                  }"
                >
                  {{ person.is_active ? 'Actif' : 'Inactif' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="viewPerson(person)" 
                    class="text-blue-600 hover:text-blue-900"
                    title="Voir"
                  >
                    <i class="fas fa-eye"></i>
                  </button>
                  <button 
                    @click="editPerson(person)" 
                    class="text-primary hover:text-primary-dark"
                    title="Modifier"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
                  <button 
                    @click="toggleStatus(person)" 
                    :class="person.is_active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900'"
                    :title="person.is_active ? 'Désactiver' : 'Activer'"
                  >
                    <i :class="person.is_active ? 'fas fa-pause' : 'fas fa-play'"></i>
                  </button>
                  <button 
                    @click="deletePerson(person.id)" 
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
        <div v-if="people.data && people.data.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              @click="changePage(people.current_page - 1)"
              :disabled="people.current_page <= 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Précédent
            </button>
            <button
              @click="changePage(people.current_page + 1)"
              :disabled="people.current_page >= people.last_page"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Suivant
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Affichage de
                <span class="font-medium">{{ people.total }}</span>
                résultats
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  @click="changePage(people.current_page - 1)"
                  :disabled="people.current_page <= 1"
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
                    page === people.current_page
                      ? 'z-10 bg-primary border-primary text-white'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                  ]"
                >
                  {{ page }}
                </button>
                <button
                  @click="changePage(people.current_page + 1)"
                  :disabled="people.current_page >= people.last_page"
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
                  {{ currentDetailPerson?.prenom }} {{ currentDetailPerson?.nom }}
                </h3>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-500">
                  <i class="fas fa-times"></i>
                </button>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                  <div class="space-y-4">
                    <!-- Photo -->
                    <div v-if="currentDetailPerson?.photo_path">
                      <label class="block text-sm font-medium text-gray-700">Photo</label>
                      <img 
                        :src="`/storage/${currentDetailPerson.photo_path}`" 
                        :alt="`Photo ${currentDetailPerson.prenom} ${currentDetailPerson.nom}`"
                        class="mt-1 h-32 w-32 object-cover rounded-lg border"
                      >
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Sexe</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-blue-100 text-blue-800': currentDetailPerson?.sexe === 'M',
                            'bg-pink-100 text-pink-800': currentDetailPerson?.sexe === 'F'
                          }"
                        >
                          {{ getSexeLabel(currentDetailPerson?.sexe) }}
                        </span>
                      </p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Âge</label>
                      <p class="mt-1 text-sm text-gray-900">{{ currentDetailPerson?.age || 'Non renseigné' }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                      <p class="mt-1 text-sm text-gray-900">
                        <i class="fas fa-phone mr-1"></i>{{ currentDetailPerson?.telephone }}
                      </p>
                    </div>
                  </div>
                </div>
                
                <div>
                  <div class="space-y-4">
                    <div v-if="currentDetailPerson?.lieu_residence">
                      <label class="block text-sm font-medium text-gray-700">Lieu de résidence</label>
                      <p class="mt-1 text-sm text-gray-900">
                        <i class="fas fa-map-marker-alt mr-1"></i>{{ currentDetailPerson.lieu_residence }}
                      </p>
                    </div>
                    
                    <div v-if="currentDetailPerson?.type_voyance">
                      <label class="block text-sm font-medium text-gray-700">Type de voyance</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                          {{ getTypeVoyanceLabel(currentDetailPerson.type_voyance) }}
                        </span>
                      </p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Traitement en cours</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-green-100 text-green-800': currentDetailPerson?.traitement_en_cours,
                            'bg-gray-100 text-gray-800': !currentDetailPerson?.traitement_en_cours
                          }"
                        >
                          {{ currentDetailPerson?.traitement_en_cours ? 'Oui' : 'Non' }}
                        </span>
                      </p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Statut</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-green-100 text-green-800': currentDetailPerson?.is_active,
                            'bg-red-100 text-red-800': !currentDetailPerson?.is_active
                          }"
                        >
                          {{ currentDetailPerson?.is_active ? 'Actif' : 'Inactif' }}
                        </span>
                      </p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Ordre de tri</label>
                      <p class="mt-1 text-sm text-gray-900">{{ currentDetailPerson?.sort_order }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Date d'enregistrement</label>
                      <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailPerson?.created_at) }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Dernière mise à jour</label>
                      <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailPerson?.updated_at) }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                @click="editPerson(currentDetailPerson); showDetailModal = false"
                class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm"
              >
                <i class="fas fa-edit mr-2"></i>
                Modifier
              </button>
              <button
                @click="toggleStatus(currentDetailPerson); showDetailModal = false"
                :class="[
                  'ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:text-sm',
                  currentDetailPerson?.is_active 
                    ? 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500' 
                    : 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
                ]"
              >
                <i :class="currentDetailPerson?.is_active ? 'fas fa-pause mr-2' : 'fas fa-play mr-2'"></i>
                {{ currentDetailPerson?.is_active ? 'Désactiver' : 'Activer' }}
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
  people: {
    type: Object,
    required: true
  },
  sexes: {
    type: Object,
    required: true
  },
  typesVoyance: {
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
const sexeFilter = ref(props.filters.sexe || '')
const typeVoyanceFilter = ref(props.filters.type_voyance || '')
const statusFilter = ref(props.filters.status || '')
const traitementFilter = ref(props.filters.traitement || '')
const showDeleteModal = ref(false)
const personToDelete = ref(null)
const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref('success')
const showAddForm = ref(false)
const editingPerson = ref(null)
const showDetailModal = ref(false)
const currentDetailPerson = ref(null)
const photoInput = ref(null)

// Form state
const form = ref({
  nom: '',
  prenom: '',
  sexe: '',
  age: null,
  lieu_residence: '',
  telephone: '',
  type_voyance: '',
  traitement_en_cours: false,
  photo: null,
  remove_photo: false,
  sort_order: null,
  is_active: true
})

// Computed
const paginatedPeople = computed(() => {
  return props.people.data || []
})

const stats = computed(() => {
  const allPeople = props.people.data || []
  return {
    masculin: allPeople.filter(p => p.sexe === 'M').length,
    feminin: allPeople.filter(p => p.sexe === 'F').length,
    enTraitement: allPeople.filter(p => p.traitement_en_cours).length,
    total: allPeople.length
  }
})

const visiblePages = computed(() => {
  const current = props.people.current_page
  const last = props.people.last_page
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

const getSexeLabel = (sexe) => {
  return props.sexes[sexe] || sexe
}

const getTypeVoyanceLabel = (type) => {
  return props.typesVoyance[type] || type
}

const handlePhotoChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.value.photo = file
    form.value.remove_photo = false
  }
}

const viewPerson = (person) => {
  currentDetailPerson.value = person
  showDetailModal.value = true
}

const editPerson = (person) => {
  editingPerson.value = person
  form.value = {
    nom: person.nom,
    prenom: person.prenom,
    sexe: person.sexe,
    age: person.age,
    lieu_residence: person.lieu_residence || '',
    telephone: person.telephone,
    type_voyance: person.type_voyance || '',
    traitement_en_cours: person.traitement_en_cours,
    photo: null,
    remove_photo: false,
    sort_order: person.sort_order,
    is_active: person.is_active
  }
  showAddForm.value = true
}

const deletePerson = (id) => {
  personToDelete.value = id
  showDeleteModal.value = true
}

const toggleStatus = (person) => {
  router.post(route('admin.visually-impaired.toggle-status', person.id), {}, {
    onSuccess: () => {
      const status = !person.is_active ? 'activée' : 'désactivée'
      notificationMessage.value = `Personne malvoyante ${status} avec succès`
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
  if (personToDelete.value) {
    router.post(route('admin.visually-impaired.destroy', personToDelete.value), {
      _method: 'delete'
    }, {
      onSuccess: () => {
        notificationMessage.value = 'Personne malvoyante supprimée avec succès'
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
  personToDelete.value = null
}

const handleFormSubmit = () => {
  const formData = new FormData()
  
  // Ajouter tous les champs du formulaire
  Object.keys(form.value).forEach(key => {
    if (key === 'photo' && form.value[key]) {
      formData.append('photo', form.value[key])
    } else if (key === 'remove_photo') {
      formData.append('remove_photo', form.value[key] ? '1' : '0')
    } else if (key === 'is_active' || key === 'traitement_en_cours') {
      formData.append(key, form.value[key] ? '1' : '0')
    } else if (form.value[key] !== null && form.value[key] !== '') {
      formData.append(key, form.value[key])
    }
  })

  if (editingPerson.value) {
    formData.append('_method', 'put')
    router.post(route('admin.visually-impaired.update', editingPerson.value.id), formData, {
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
    router.post(route('admin.visually-impaired.store'), formData, {
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
  editingPerson.value = null
  form.value = {
    nom: '',
    prenom: '',
    sexe: '',
    age: null,
    lieu_residence: '',
    telephone: '',
    type_voyance: '',
    traitement_en_cours: false,
    photo: null,
    remove_photo: false,
    sort_order: null,
    is_active: true
  }
  
  // Reset file input
  if (photoInput.value) {
    photoInput.value.value = ''
  }
}

const changePage = (page) => {
  if (page >= 1 && page <= props.people.last_page) {
    router.get(route('admin.visually-impaired.index'), {
      page: page,
      search: search.value,
      sexe: sexeFilter.value,
      type_voyance: typeVoyanceFilter.value,
      status: statusFilter.value,
      traitement: traitementFilter.value
    }, {
      preserveState: true,
      preserveScroll: true
    })
  }
}

// Watchers pour les filtres
watch([search, sexeFilter, typeVoyanceFilter, statusFilter, traitementFilter], () => {
  router.get(route('admin.visually-impaired.index'), {
    search: search.value,
    sexe: sexeFilter.value,
    type_voyance: typeVoyanceFilter.value,
    status: statusFilter.value,
    traitement: traitementFilter.value
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['people']
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