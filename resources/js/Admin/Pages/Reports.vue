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

    <!-- Modal de traitement individuel -->
    <Teleport to="body">
      <div v-if="showProcessModal" class="fixed inset-0 z-[9998] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showProcessModal = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[9999]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Traiter le signalement
              </h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Statut</label>
                  <select
                    v-model="processForm.status"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                    required
                  >
                    <option value="reviewed">Examiné</option>
                    <option value="resolved">Résolu</option>
                    <option value="dismissed">Rejeté</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Notes de modération</label>
                  <textarea
                    v-model="processForm.moderator_notes"
                    rows="3"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                    placeholder="Ajoutez vos notes de modération..."
                  ></textarea>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Action sur le contenu</label>
                  <select
                    v-model="processForm.action_on_content"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                  >
                    <option value="none">Aucune action</option>
                    <option value="moderate">Modérer</option>
                    <option value="hide">Masquer</option>
                    <option value="delete">Supprimer</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Action sur l'utilisateur</label>
                  <select
                    v-model="processForm.action_on_user"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                  >
                    <option value="none">Aucune action</option>
                    <option value="warn">Avertir</option>
                    <option value="suspend">Suspendre</option>
                    <option value="ban">Bannir</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                @click="confirmProcess"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm"
              >
                Traiter
              </button>
              <button
                type="button"
                @click="showProcessModal = false"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Modal de traitement en lot -->
    <Teleport to="body">
      <div v-if="showBulkModal" class="fixed inset-0 z-[9998] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showBulkModal = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[9999]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Traitement en lot ({{ selectedReports.length }} signalements)
              </h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Action</label>
                  <select
                    v-model="bulkForm.action"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                    required
                  >
                    <option value="mark_reviewed">Marquer comme examiné</option>
                    <option value="resolve">Résoudre</option>
                    <option value="dismiss">Rejeter</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Notes de modération (optionnel)</label>
                  <textarea
                    v-model="bulkForm.moderator_notes"
                    rows="3"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                    placeholder="Notes communes pour tous les signalements..."
                  ></textarea>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                @click="confirmBulkProcess"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm"
              >
                Traiter
              </button>
              <button
                type="button"
                @click="showBulkModal = false"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Liste des signalements -->
    <div>
      <!-- Actions Bar -->
      <div class="mb-6">
        <!-- Barre de recherche et actions -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
          <div class="relative w-full lg:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-search text-gray-400"></i>
            </div>
            <input 
              type="text" 
              v-model="search" 
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm" 
              placeholder="Rechercher un signalement..."
            >
          </div>
          
          <div class="flex justify-end">
            <button 
              v-if="selectedReports.length > 0"
              @click="showBulkModal = true"
              class="flex items-center space-x-2 px-4 py-2 bg-orange-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 whitespace-nowrap"
            >
              <i class="fas fa-cogs"></i>
              <span>Traiter ({{ selectedReports.length }})</span>
            </button>
          </div>
        </div>
        
        <!-- Filtres -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3">
          <div class="relative">
            <select
              v-model="statusFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous les statuts</option>
              <option v-for="(label, value) in filterData.statuses" :key="value" :value="value">
                {{ label }}
              </option>
            </select>
          </div>

          <div class="relative">
            <select
              v-model="reasonFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Toutes les raisons</option>
              <option v-for="(label, value) in filterData.reasons" :key="value" :value="value">
                {{ label }}
              </option>
            </select>
          </div>

          <div class="relative">
            <select
              v-model="typeFilter"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
            >
              <option value="">Tous les types</option>
              <option v-for="(label, value) in filterData.reportable_types" :key="value" :value="value">
                {{ label }}
              </option>
            </select>
          </div>

          <div class="relative">
            <input
              type="date"
              v-model="dateFromFilter"
              class="block w-full pl-3 pr-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
              placeholder="Date de début"
            >
          </div>

          <div class="relative">
            <input
              type="date"
              v-model="dateToFilter"
              class="block w-full pl-3 pr-3 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
              placeholder="Date de fin"
            >
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="mb-6 grid grid-cols-1 md:grid-cols-6 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Total</p>
              <h3 class="text-2xl font-bold text-dark">{{ statistics.total_reports }}</h3>
            </div>
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
              <i class="fas fa-flag text-xl"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">En attente</p>
              <h3 class="text-2xl font-bold text-dark">{{ statistics.pending_reports }}</h3>
            </div>
            <div class="p-3 rounded-full bg-orange-100 text-orange-600">
              <i class="fas fa-clock text-xl"></i>
            </div>
          </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Examinés</p>
              <h3 class="text-2xl font-bold text-dark">{{ statistics.reviewed_reports }}</h3>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
              <i class="fas fa-eye text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Résolus</p>
              <h3 class="text-2xl font-bold text-dark">{{ statistics.resolved_reports }}</h3>
            </div>
            <div class="p-3 rounded-full bg-green-100 text-green-600">
              <i class="fas fa-check text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Rejetés</p>
              <h3 class="text-2xl font-bold text-dark">{{ statistics.dismissed_reports }}</h3>
            </div>
            <div class="p-3 rounded-full bg-red-100 text-red-600">
              <i class="fas fa-times text-xl"></i>
            </div>
          </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500">Aujourd'hui</p>
              <h3 class="text-2xl font-bold text-dark">{{ statistics.today_reports }}</h3>
            </div>
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
              <i class="fas fa-calendar-day text-xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left">
                <input
                  type="checkbox"
                  @change="toggleSelectAll"
                  :checked="isAllSelected"
                  :indeterminate="isPartiallySelected"
                  class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary"
                >
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Signalement
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Rapporteur
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Objet signalé
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Raison
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Statut
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="report in paginatedReports" :key="report.id" :class="report.status === 'pending' ? 'bg-orange-50' : ''">
              <td class="px-6 py-4">
                <input
                  type="checkbox"
                  :value="report.id"
                  v-model="selectedReports"
                  class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary"
                >
              </td>
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">
                  #{{ report.id }}
                  <span v-if="isPriority(report)" class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                    PRIORITÉ
                  </span>
                </div>
                <div class="text-sm text-gray-500 truncate max-w-xs">
                  {{ report.description || 'Aucune description' }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ report.reporter?.pseudo || 'Utilisateur supprimé' }}</div>
                <div class="text-sm text-gray-500">{{ report.reporter?.email || '' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ getReportableTypeLabel(report.reportable_type) }}</div>
                <div class="text-sm text-gray-500">{{ getReportableTitle(report) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                  {{ getReasonLabel(report.reason) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-orange-100 text-orange-800': report.status === 'pending',
                    'bg-yellow-100 text-yellow-800': report.status === 'reviewed',
                    'bg-green-100 text-green-800': report.status === 'resolved',
                    'bg-red-100 text-red-800': report.status === 'dismissed'
                  }"
                >
                  {{ getStatusLabel(report.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ formatDate(report.created_at) }}</div>
                <div class="text-sm text-gray-500" v-if="report.reviewed_at">
                  Traité {{ formatDate(report.reviewed_at) }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click="viewReport(report)" 
                    class="text-blue-600 hover:text-blue-900"
                    title="Voir détails"
                  >
                    <i class="fas fa-eye"></i>
                  </button>
                  <button 
                    v-if="report.status === 'pending'"
                    @click="processReport(report)" 
                    class="text-green-600 hover:text-green-900"
                    title="Traiter"
                  >
                    <i class="fas fa-gavel"></i>
                  </button>
                  <button 
                    @click="togglePriority(report)" 
                    :class="isPriority(report) ? 'text-red-600 hover:text-red-900' : 'text-gray-600 hover:text-gray-900'"
                    :title="isPriority(report) ? 'Retirer la priorité' : 'Marquer comme prioritaire'"
                  >
                    <i class="fas fa-star"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        </div>

        <!-- Pagination -->
        <div v-if="reports.data && reports.data.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              @click="changePage(reports.current_page - 1)"
              :disabled="reports.current_page <= 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Précédent
            </button>
            <button
              @click="changePage(reports.current_page + 1)"
              :disabled="reports.current_page >= reports.last_page"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Suivant
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Affichage de
                <span class="font-medium">{{ reports.total }}</span>
                résultats
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  @click="changePage(reports.current_page - 1)"
                  :disabled="reports.current_page <= 1"
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
                    page === reports.current_page
                      ? 'z-10 bg-primary border-primary text-white'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                  ]"
                >
                  {{ page }}
                </button>
                <button
                  @click="changePage(reports.current_page + 1)"
                  :disabled="reports.current_page >= reports.last_page"
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
                  Signalement #{{ currentDetailReport?.id }}
                </h3>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-500">
                  <i class="fas fa-times"></i>
                </button>
              </div>
              
              <div v-if="currentDetailReport" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Rapporteur</label>
                      <p class="mt-1 text-sm text-gray-900">
                        {{ currentDetailReport.reporter?.pseudo || 'Utilisateur supprimé' }}
                        <span class="text-gray-500">({{ currentDetailReport.reporter?.email || '' }})</span>
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Raison</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                          {{ getReasonLabel(currentDetailReport.reason) }}
                        </span>
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Statut</label>
                      <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-orange-100 text-orange-800': currentDetailReport.status === 'pending',
                            'bg-yellow-100 text-yellow-800': currentDetailReport.status === 'reviewed',
                            'bg-green-100 text-green-800': currentDetailReport.status === 'resolved',
                            'bg-red-100 text-red-800': currentDetailReport.status === 'dismissed'
                          }"
                        >
                          {{ getStatusLabel(currentDetailReport.status) }}
                        </span>
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Objet signalé</label>
                      <p class="mt-1 text-sm text-gray-900">
                        {{ getReportableTypeLabel(currentDetailReport.reportable_type) }}
                        <br>
                        <span class="text-gray-500">{{ getReportableTitle(currentDetailReport) }}</span>
                      </p>
                    </div>
                  </div>
                </div>
                
                <div>
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Date de signalement</label>
                      <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailReport.created_at) }}</p>
                    </div>
                    <div v-if="currentDetailReport.reviewed_at">
                      <label class="block text-sm font-medium text-gray-700">Date de traitement</label>
                      <p class="mt-1 text-sm text-gray-900">{{ formatDate(currentDetailReport.reviewed_at) }}</p>
                    </div>
                    <div v-if="currentDetailReport.reviewer">
                      <label class="block text-sm font-medium text-gray-700">Traité par</label>
                      <p class="mt-1 text-sm text-gray-900">{{ currentDetailReport.reviewer.pseudo }}</p>
                    </div>
                    <div v-if="isPriority(currentDetailReport)">
                      <label class="block text-sm font-medium text-gray-700">Priorité</label>
                      <p class="mt-1">
                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                          PRIORITÉ
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Description -->
              <div v-if="currentDetailReport?.description" class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <div class="bg-gray-50 p-4 rounded-md">
                  <p class="text-gray-900 whitespace-pre-wrap">{{ currentDetailReport.description }}</p>
                </div>
              </div>

              <!-- Notes de modération -->
              <div v-if="currentDetailReport?.moderator_notes" class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes de modération</label>
                <div class="bg-blue-50 p-4 rounded-md">
                  <p class="text-blue-900 whitespace-pre-wrap">{{ currentDetailReport.moderator_notes }}</p>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                v-if="currentDetailReport?.status === 'pending'"
                @click="processReport(currentDetailReport); showDetailModal = false"
                class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm"
              >
                <i class="fas fa-gavel mr-2"></i>
                Traiter
              </button>
              <button
                @click="togglePriority(currentDetailReport)"
                :class="[
                  'ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:text-sm',
                  isPriority(currentDetailReport) 
                    ? 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500' 
                    : 'bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500'
                ]"
              >
                <i class="fas fa-star mr-2"></i>
                {{ isPriority(currentDetailReport) ? 'Retirer priorité' : 'Marquer prioritaire' }}
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
  reports: {
    type: Object,
    required: true
  },
  statistics: {
    type: Object,
    required: true
  },
  filterData: {
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
const statusFilter = ref(props.filters.status || '')
const reasonFilter = ref(props.filters.reason || '')
const typeFilter = ref(props.filters.reportable_type || '')
const dateFromFilter = ref(props.filters.date_from || '')
const dateToFilter = ref(props.filters.date_to || '')
const showProcessModal = ref(false)
const showBulkModal = ref(false)
const showDetailModal = ref(false)
const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref('success')
const currentDetailReport = ref(null)
const reportToProcess = ref(null)
const selectedReports = ref([])

// Form states
const processForm = ref({
  status: 'reviewed',
  moderator_notes: '',
  action_on_content: 'none',
  action_on_user: 'none'
})

const bulkForm = ref({
  action: 'mark_reviewed',
  moderator_notes: ''
})

// Computed
const paginatedReports = computed(() => {
  return props.reports.data || []
})

const visiblePages = computed(() => {
  const current = props.reports.current_page
  const last = props.reports.last_page
  const pages = []
  
  for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
    pages.push(i)
  }
  
  return pages
})

const isAllSelected = computed(() => {
  return paginatedReports.value.length > 0 && selectedReports.value.length === paginatedReports.value.length
})

const isPartiallySelected = computed(() => {
  return selectedReports.value.length > 0 && selectedReports.value.length < paginatedReports.value.length
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

const getStatusLabel = (status) => {
  return props.filterData.statuses[status] || status
}

const getReasonLabel = (reason) => {
  return props.filterData.reasons[reason] || reason
}

const getReportableTypeLabel = (type) => {
  return props.filterData.reportable_types[type] || type
}

const getReportableTitle = (report) => {
  if (!report.reportable) return 'Contenu supprimé'
  
  if (report.reportable_type === 'App\\Models\\Publication') {
    return report.reportable.title || 'Publication sans titre'
  } else if (report.reportable_type === 'App\\Models\\Comment') {
    return 'Commentaire: ' + (report.reportable.content?.substring(0, 50) || 'Contenu supprimé') + '...'
  } else if (report.reportable_type === 'App\\Models\\User') {
    return 'Utilisateur: ' + (report.reportable.pseudo || 'Utilisateur supprimé')
  }
  
  return 'Contenu inconnu'
}

const isPriority = (report) => {
  return report.moderator_notes && report.moderator_notes.includes('[PRIORITÉ]')
}

const viewReport = (report) => {
  currentDetailReport.value = report
  showDetailModal.value = true
}

const processReport = (report) => {
  reportToProcess.value = report
  processForm.value = {
    status: 'reviewed',
    moderator_notes: '',
    action_on_content: 'none',
    action_on_user: 'none'
  }
  showProcessModal.value = true
}

const toggleSelectAll = () => {
  if (isAllSelected.value) {
    selectedReports.value = []
  } else {
    selectedReports.value = paginatedReports.value.map(report => report.id)
  }
}

const togglePriority = (report) => {
  router.post(route('admin.reports.toggle-priority', report.id), {}, {
    onSuccess: () => {
      notificationMessage.value = 'Priorité modifiée avec succès'
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

const confirmProcess = () => {
  if (reportToProcess.value) {
    router.post(route('admin.reports.process', reportToProcess.value.id), processForm.value, {
      onSuccess: () => {
        notificationMessage.value = 'Signalement traité avec succès'
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
  showProcessModal.value = false
  reportToProcess.value = null
}

const confirmBulkProcess = () => {
  if (selectedReports.value.length > 0) {
    router.post(route('admin.reports.bulk-process'), {
      report_ids: selectedReports.value,
      ...bulkForm.value
    }, {
      onSuccess: () => {
        notificationMessage.value = 'Signalements traités en lot avec succès'
        notificationType.value = 'success'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
        selectedReports.value = []
      },
      onError: (errors) => {
        notificationMessage.value = Object.values(errors)[0] || 'Une erreur est survenue'
        notificationType.value = 'error'
        showNotification.value = true
        setTimeout(() => showNotification.value = false, 3000)
      }
    })
  }
  showBulkModal.value = false
}

const changePage = (page) => {
  if (page >= 1 && page <= props.reports.last_page) {
    router.get(route('admin.reports.index'), {
      page: page,
      search: search.value,
      status: statusFilter.value,
      reason: reasonFilter.value,
      reportable_type: typeFilter.value,
      date_from: dateFromFilter.value,
      date_to: dateToFilter.value
    }, {
      preserveState: true,
      preserveScroll: true
    })
  }
}

// Watchers pour les filtres
watch([search, statusFilter, reasonFilter, typeFilter, dateFromFilter, dateToFilter], () => {
  router.get(route('admin.reports.index'), {
    search: search.value,
    status: statusFilter.value,
    reason: reasonFilter.value,
    reportable_type: typeFilter.value,
    date_from: dateFromFilter.value,
    date_to: dateToFilter.value
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['reports']
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
