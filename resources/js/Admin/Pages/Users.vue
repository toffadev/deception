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
                      Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action peut être annulée.
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
                      Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ? Cette action est irréversible.
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
                Changer le statut de l'utilisateur
              </h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Statut</label>
                  <select
                    v-model="statusForm.status"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                    required
                  >
                    <option value="active">Actif</option>
                    <option value="suspended">Suspendu</option>
                    <option value="banned">Banni</option>
                  </select>
                </div>
                <div v-if="statusForm.status !== 'active'">
                  <label class="block text-sm font-medium text-gray-700">Raison</label>
                  <textarea
                    v-model="statusForm.reason"
                    rows="3"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                    placeholder="Expliquez la raison de ce changement de statut..."
                  ></textarea>
                </div>
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

    <!-- Modal de changement de rôle -->
    <Teleport to="body">
      <div v-if="showRoleModal" class="fixed inset-0 z-[9998] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showRoleModal = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[9999]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Changer le rôle de l'utilisateur
              </h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Rôle</label>
                  <select
                    v-model="roleForm.role"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                    required
                  >
                    <option value="client">Client</option>
                    <option value="admin">Administrateur</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                @click="confirmRoleChange"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm"
              >
                Confirmer
              </button>
              <button
                type="button"
                @click="showRoleModal = false"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Modal de réinitialisation de mot de passe -->
    <Teleport to="body">
      <div v-if="showPasswordModal" class="fixed inset-0 z-[9998] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showPasswordModal = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[9999]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Réinitialiser le mot de passe
              </h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                  <input
                    type="password"
                    v-model="passwordForm.new_password"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                    required
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                  <input
                    type="password"
                    v-model="passwordForm.new_password_confirmation"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                    required
                  >
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                @click="confirmPasswordReset"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm"
              >
                Réinitialiser
              </button>
              <button
                type="button"
                @click="showPasswordModal = false"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Formulaire d'édition -->
    <div v-if="editingUser" class="bg-white shadow-sm rounded-lg p-6 mb-6">
      <h2 class="text-lg font-medium text-gray-900 mb-6">
        Modifier l'utilisateur
      </h2>
      
      <form @submit.prevent="handleFormSubmit" class="space-y-6">
        <!-- Pseudo -->
        <div>
          <label for="pseudo" class="block text-sm font-medium text-gray-700">Pseudo</label>
          <input
            type="text"
            id="pseudo"
            v-model="form.pseudo"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
        </div>

        <!-- Rôle -->
        <div>
          <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
          <select
            id="role"
            v-model="form.role"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
            <option value="client">Client</option>
            <option value="admin">Administrateur</option>
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
            <option value="active">Actif</option>
            <option value="suspended">Suspendu</option>
            <option value="banned">Banni</option>
          </select>
        </div>

        <!-- Date de naissance -->
        <div>
          <label for="birth_date" class="block text-sm font-medium text-gray-700">Date de naissance</label>
          <input
            type="date"
            id="birth_date"
            v-model="form.birth_date"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
            required
          >
        </div>

        <!-- Anonyme par défaut -->
        <div>
          <label class="flex items-center">
            <input
              type="checkbox"
              v-model="form.anonymous_by_default"
              class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary"
            >
            <span class="ml-2 text-sm text-gray-700">Anonyme par défaut</span>
          </label>
        </div>

        <!-- Mot de passe (optionnel) -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe (optionnel)</label>
          <input
            type="password"
            id="password"
            v-model="form.password"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
          >
        </div>

        <!-- Confirmation mot de passe -->
        <div v-if="form.password">
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
          <input
            type="password"
            id="password_confirmation"
            v-model="form.password_confirmation"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
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
            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
          >
            Mettre à jour
          </button>
        </div>
      </form>
    </div>

    <!-- Liste des utilisateurs -->
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
            placeholder="Rechercher un utilisateur..."
          >
        </div>
        
        <div class="flex space-x-3">
          <div class="relative">
            <select
              v-model="roleFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous les rôles</option>
              <option value="client">Client</option>
              <option value="admin">Administrateur</option>
            </select>
          </div>

          <div class="relative">
            <select
              v-model="statusFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous les statuts</option>
              <option value="active">Actif</option>
              <option value="suspended">Suspendu</option>
              <option value="banned">Banni</option>
            </select>
          </div>

          <div class="relative">
            <select
              v-model="authProviderFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous les types</option>
              <option value="local">Local</option>
              <option value="google">Google</option>
            </select>
          </div>

          <div class="relative">
            <select
              v-model="deletedFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous</option>
              <option value="without">Actifs</option>
              <option value="only">Supprimés</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Total</p>
              <h3 class="text-2xl font-bold text-dark">{{ statistics.total_users }}</h3>
            </div>
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
              <i class="fas fa-users text-xl"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Actifs</p>
              <h3 class="text-2xl font-bold text-dark">{{ statistics.active_users }}</h3>
            </div>
            <div class="p-3 rounded-full bg-green-100 text-green-600">
              <i class="fas fa-user-check text-xl"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Suspendus</p>
              <h3 class="text-2xl font-bold text-dark">{{ statistics.suspended_users }}</h3>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
              <i class="fas fa-user-clock text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Bannis</p>
              <h3 class="text-2xl font-bold text-dark">{{ statistics.banned_users }}</h3>
            </div>
            <div class="p-3 rounded-full bg-red-100 text-red-600">
              <i class="fas fa-user-slash text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Admins</p>
              <h3 class="text-2xl font-bold text-dark">{{ statistics.admins_count }}</h3>
            </div>
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
              <i class="fas fa-user-shield text-xl"></i>
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
                Utilisateur
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Rôle
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Statut
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Type d'auth
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Activité
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date d'inscription
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in paginatedUsers" :key="user.id" :class="user.deleted_at ? 'bg-red-50' : ''">
              <td class="px-6 py-4">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img 
                      v-if="user.avatar" 
                      :src="user.avatar" 
                      :alt="user.pseudo"
                      class="h-10 w-10 rounded-full"
                    >
                    <div 
                      v-else 
                      class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center"
                    >
                      <i class="fas fa-user text-gray-600"></i>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{ user.pseudo }}
                      <span v-if="user.deleted_at" class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                        Supprimé
                      </span>
                    </div>
                    <div class="text-sm text-gray-500">{{ user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-purple-100 text-purple-800': user.role === 'admin',
                    'bg-gray-100 text-gray-800': user.role === 'client'
                  }"
                >
                  {{ getRoleLabel(user.role) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-green-100 text-green-800': user.status === 'active',
                    'bg-yellow-100 text-yellow-800': user.status === 'suspended',
                    'bg-red-100 text-red-800': user.status === 'banned'
                  }"
                >
                  {{ getStatusLabel(user.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-blue-100 text-blue-800': user.auth_provider === 'google',
                    'bg-gray-100 text-gray-800': user.auth_provider === 'local'
                  }"
                >
                  {{ getAuthProviderLabel(user.auth_provider) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                  <div>{{ user.publications_count }} publications</div>
                  <div class="text-gray-500">{{ user.comments_count }} commentaires</div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ formatDate(user.created_at) }}</div>
                <div class="text-sm text-gray-500">
                  {{ user.last_login_at ? 'Connecté ' + formatDate(user.last_login_at) : 'Jamais connecté' }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="viewUser(user)" 
                    class="text-blue-600 hover:text-blue-900"
                    title="Voir"
                  >
                    <i class="fas fa-eye"></i>
                  </button>
                  <button 
                    v-if="!user.deleted_at && user.id !== currentUser?.id"
                    @click="editUser(user)" 
                    class="text-primary hover:text-primary-dark"
                    title="Modifier"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
                  <button 
                    v-if="!user.deleted_at && user.id !== currentUser?.id"
                    @click="changeUserStatus(user)" 
                    class="text-yellow-600 hover:text-yellow-900"
                    title="Changer le statut"
                  >
                    <i class="fas fa-user-cog"></i>
                  </button>
                  <button 
                    v-if="!user.deleted_at && user.id !== currentUser?.id"
                    @click="changeUserRole(user)" 
                    class="text-purple-600 hover:text-purple-900"
                    title="Changer le rôle"
                  >
                    <i class="fas fa-user-tag"></i>
                  </button>
                  <button 
                    v-if="!user.deleted_at && user.id !== currentUser?.id"
                    @click="resetUserPassword(user)" 
                    class="text-orange-600 hover:text-orange-900"
                    title="Réinitialiser le mot de passe"
                  >
                    <i class="fas fa-key"></i>
                  </button>
                  <button 
                    v-if="!user.deleted_at && user.id !== currentUser?.id"
                    @click="deleteUser(user.id)" 
                    class="text-red-600 hover:text-red-900"
                    title="Supprimer"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                  <button 
                    v-if="user.deleted_at"
                    @click="restoreUser(user.id)" 
                    class="text-green-600 hover:text-green-900"
                    title="Restaurer"
                  >
                    <i class="fas fa-undo"></i>
                  </button>
                  <button 
                    v-if="user.deleted_at"
                    @click="forceDeleteUser(user.id)" 
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
        <div v-if="users.data && users.data.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              @click="changePage(users.current_page - 1)"
              :disabled="users.current_page <= 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Précédent
            </button>
            <button
              @click="changePage(users.current_page + 1)"
              :disabled="users.current_page >= users.last_page"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Suivant
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Affichage de
                <span class="font-medium">{{ users.total }}</span>
                résultats
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  @click="changePage(users.current_page - 1)"
                  :disabled="users.current_page <= 1"
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
                    page === users.current_page
                      ? 'z-10 bg-primary border-primary text-white'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                  ]"
                >
                  {{ page }}
                </button>
                <button
                  @click="changePage(users.current_page + 1)"
                  :disabled="users.current_page >= users.last_page"
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
                  Détails de l'utilisateur
                </h3>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-500">
                  <i class="fas fa-times"></i>
                </button>
              </div>
              
              <div v-if="currentDetailUser" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                  <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                      <div class="flex-shrink-0 h-16 w-16">
                        <img 
                          v-if="currentDetailUser.avatar" 
                          :src="currentDetailUser.avatar" 
                          :alt="currentDetailUser.pseudo"
                          class="h-16 w-16 rounded-full"
                        >
                        <div 
                          v-else 
                          class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center"
                        >
                          <i class="fas fa-user text-gray-600 text-2xl"></i>
                        </div>
                      </div>
                      <div>
                        <h4 class="text-xl font-semibold">{{ currentDetailUser.pseudo }}</h4>
                        <p class="text-gray-500">{{ currentDetailUser.email }}</p>
                      </div>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Rôle</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-purple-100 text-purple-800': currentDetailUser.role === 'admin',
                            'bg-gray-100 text-gray-800': currentDetailUser.role === 'client'
                          }"
                        >
                          {{ getRoleLabel(currentDetailUser.role) }}
                        </span>
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Statut</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-green-100 text-green-800': currentDetailUser.status === 'active',
                            'bg-yellow-100 text-yellow-800': currentDetailUser.status === 'suspended',
                            'bg-red-100 text-red-800': currentDetailUser.status === 'banned'
                          }"
                        >
                          {{ getStatusLabel(currentDetailUser.status) }}
                        </span>
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Type d'authentification</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-blue-100 text-blue-800': currentDetailUser.auth_provider === 'google',
                            'bg-gray-100 text-gray-800': currentDetailUser.auth_provider === 'local'
                          }"
                        >
                          {{ getAuthProviderLabel(currentDetailUser.auth_provider) }}
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
                
                <div>
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                      <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailUser.birth_date, true) }}</p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Date d'inscription</label>
                      <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailUser.created_at) }}</p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Dernière connexion</label>
                      <p class="mt-1 text-sm text-gray-900">
                        {{ currentDetailUser.last_login_at ? formatDate(currentDetailUser.last_login_at) : 'Jamais connecté' }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Email vérifié</label>
                      <p class="mt-1 text-sm text-gray-900">
                        {{ currentDetailUser.email_verified_at ? 'Oui' : 'Non' }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Anonyme par défaut</label>
                      <p class="mt-1 text-sm text-gray-900">
                        {{ currentDetailUser.anonymous_by_default ? 'Oui' : 'Non' }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Statistiques -->
              <div class="border-t pt-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Activité</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ currentDetailUser.publications_count || 0 }}</div>
                    <div class="text-sm text-blue-800">Publications</div>
                  </div>
                  <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ currentDetailUser.comments_count || 0 }}</div>
                    <div class="text-sm text-green-800">Commentaires</div>
                  </div>
                  <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ currentDetailUser.donations_count || 0 }}</div>
                    <div class="text-sm text-purple-800">Dons</div>
                  </div>
                  <div class="bg-red-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-red-600">{{ currentDetailUser.reports_count || 0 }}</div>
                    <div class="text-sm text-red-800">Signalements</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                v-if="!currentDetailUser?.deleted_at && currentDetailUser?.id !== currentUser?.id"
                @click="editUser(currentDetailUser); showDetailModal = false"
                class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm"
              >
                <i class="fas fa-edit mr-2"></i>
                Modifier
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
  users: {
    type: Object,
    required: true
  },
  statistics: {
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
  },
  currentUser: {
    type: Object,
    default: null
  }
})

// État local
const search = ref(props.filters.search || '')
const roleFilter = ref(props.filters.role || '')
const statusFilter = ref(props.filters.status || '')
const authProviderFilter = ref(props.filters.auth_provider || '')
const deletedFilter = ref(props.filters.deleted || '')
const showDeleteModal = ref(false)
const showForceDeleteModal = ref(false)
const showStatusModal = ref(false)
const showRoleModal = ref(false)
const showPasswordModal = ref(false)
const showDetailModal = ref(false)
const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref('success')
const editingUser = ref(null)
const currentDetailUser = ref(null)
const userToDelete = ref(null)
const userToForceDelete = ref(null)
const userToChangeStatus = ref(null)
const userToChangeRole = ref(null)
const userToResetPassword = ref(null)

// Form states
const form = ref({
  pseudo: '',
  email: '',
  role: '',
  status: '',
  birth_date: '',
  anonymous_by_default: false,
  password: '',
  password_confirmation: ''
})

const statusForm = ref({
  status: '',
  reason: ''
})

const roleForm = ref({
  role: ''
})

const passwordForm = ref({
  new_password: '',
  new_password_confirmation: ''
})

// Computed
const paginatedUsers = computed(() => {
  return props.users.data || []
})

const visiblePages = computed(() => {
  const current = props.users.current_page
  const last = props.users.last_page
  const pages = []
  
  for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
    pages.push(i)
  }
  
  return pages
})

// Méthodes
const formatDate = (date, dateOnly = false) => {
  const options = dateOnly 
    ? { year: 'numeric', month: 'long', day: 'numeric' }
    : { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }
  
  return new Date(date).toLocaleDateString('fr-FR', options)
}

const getRoleLabel = (role) => {
  const labels = {
    client: 'Client',
    admin: 'Administrateur'
  }
  return labels[role] || role
}

const getStatusLabel = (status) => {
  const labels = {
    active: 'Actif',
    suspended: 'Suspendu',
    banned: 'Banni'
  }
  return labels[status] || status
}

const getAuthProviderLabel = (provider) => {
  const labels = {
    local: 'Local',
    google: 'Google'
  }
  return labels[provider] || provider
}

const viewUser = (user) => {
  currentDetailUser.value = user
  showDetailModal.value = true
}

const editUser = (user) => {
  editingUser.value = user
  form.value = {
    pseudo: user.pseudo,
    email: user.email,
    role: user.role,
    status: user.status,
    birth_date: user.birth_date,
    anonymous_by_default: user.anonymous_by_default,
    password: '',
    password_confirmation: ''
  }
}

const deleteUser = (id) => {
  userToDelete.value = id
  showDeleteModal.value = true
}

const forceDeleteUser = (id) => {
  userToForceDelete.value = id
  showForceDeleteModal.value = true
}

const restoreUser = (id) => {
  router.post(route('admin.users.restore', id), {}, {
    onSuccess: () => {
      notificationMessage.value = 'Utilisateur restauré avec succès'
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

const changeUserStatus = (user) => {
  userToChangeStatus.value = user
  statusForm.value = {
    status: user.status,
    reason: ''
  }
  showStatusModal.value = true
}

const changeUserRole = (user) => {
  userToChangeRole.value = user
  roleForm.value = {
    role: user.role
  }
  showRoleModal.value = true
}

const resetUserPassword = (user) => {
  userToResetPassword.value = user
  passwordForm.value = {
    new_password: '',
    new_password_confirmation: ''
  }
  showPasswordModal.value = true
}

const confirmDelete = () => {
  if (userToDelete.value) {
    router.post(route('admin.users.destroy', userToDelete.value), {
      _method: 'delete'
    }, {
      onSuccess: () => {
        notificationMessage.value = 'Utilisateur supprimé avec succès'
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
  userToDelete.value = null
}

const confirmForceDelete = () => {
  if (userToForceDelete.value) {
    router.post(route('admin.users.force-delete', userToForceDelete.value), {
      _method: 'delete'
    }, {
      onSuccess: () => {
        notificationMessage.value = 'Utilisateur supprimé définitivement'
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
  showForceDeleteModal.value = false
  userToForceDelete.value = null
}

const confirmStatusChange = () => {
  if (userToChangeStatus.value) {
    router.post(route('admin.users.change-status', userToChangeStatus.value.id), statusForm.value, {
      onSuccess: () => {
        notificationMessage.value = 'Statut modifié avec succès'
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
  userToChangeStatus.value = null
}

const confirmRoleChange = () => {
  if (userToChangeRole.value) {
    router.post(route('admin.users.change-role', userToChangeRole.value.id), roleForm.value, {
      onSuccess: () => {
        notificationMessage.value = 'Rôle modifié avec succès'
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
  showRoleModal.value = false
  userToChangeRole.value = null
}

const confirmPasswordReset = () => {
  if (userToResetPassword.value) {
    router.post(route('admin.users.reset-password', userToResetPassword.value.id), passwordForm.value, {
      onSuccess: () => {
        notificationMessage.value = 'Mot de passe réinitialisé avec succès'
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
  showPasswordModal.value = false
  userToResetPassword.value = null
}

const handleFormSubmit = () => {
  if (editingUser.value) {
    router.post(route('admin.users.update', editingUser.value.id), {
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
        const firstError = Object.values(errors)[0]
        notificationMessage.value = Array.isArray(firstError) ? firstError[0] : firstError || 'Une erreur est survenue'
        notificationType.value = 'error'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      }
    })
  }
}

const cancelForm = () => {
  editingUser.value = null
  form.value = {
    pseudo: '',
    email: '',
    role: '',
    status: '',
    birth_date: '',
    anonymous_by_default: false,
    password: '',
    password_confirmation: ''
  }
}

const changePage = (page) => {
  if (page >= 1 && page <= props.users.last_page) {
    router.get(route('admin.users.index'), {
      page: page,
      search: search.value,
      role: roleFilter.value,
      status: statusFilter.value,
      auth_provider: authProviderFilter.value,
      deleted: deletedFilter.value
    }, {
      preserveState: true,
      preserveScroll: true
    })
  }
}

// Watchers pour les filtres
watch([search, roleFilter, statusFilter, authProviderFilter, deletedFilter], () => {
  router.get(route('admin.users.index'), {
    search: search.value,
    role: roleFilter.value,
    status: statusFilter.value,
    auth_provider: authProviderFilter.value,
    deleted: deletedFilter.value
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['users']
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
