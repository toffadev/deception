<template>
    <MainLayout>
        <!-- Hero Section -->
        <SectionHeader
            title="Espace Publications"
            subtitle="Partagez votre histoire, lisez celles des autres et trouvez du réconfort dans notre communauté bienveillante."
            badge="Communauté solidaire"
            badge-icon="fas fa-heart"
            gradient-class="from-red-500 to-pink-500"
            :show-scroll-hint="true"
        >
            <template #actions>
                <a v-if="isAuthenticated" href="#share-form" class="bg-white text-red-500 hover:bg-gray-100 px-8 py-4 rounded-full font-bold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-pen-fancy mr-2"></i> Écrire une publication
                </a>
                <Link v-else href="/auth/login" class="bg-white text-red-500 hover:bg-gray-100 px-8 py-4 rounded-full font-bold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2"></i> Se connecter pour publier
                </Link>
                <a href="#testimonials" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-red-500 px-8 py-4 rounded-full font-bold transition-all duration-300 hover:shadow-xl">
                    <i class="fas fa-book-reader mr-2"></i> Lire les publications
                </a>
            </template>
        </SectionHeader>

        <!-- Flash Messages -->
        <div v-if="flash.success" class="container mx-auto px-4 mt-6">
            <div class="max-w-4xl mx-auto bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ flash.success }}
                </div>
            </div>
        </div>

        <div v-if="flash.error" class="container mx-auto px-4 mt-6">
            <div class="max-w-4xl mx-auto bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ flash.error }}
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <section class="py-12 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                        <div class="w-full md:w-1/3">
                            <div class="relative">
                                <input 
                                    v-model="searchQuery"
                                    @input="applyFilters"
                                    type="text" 
                                    placeholder="Rechercher une publication..." 
                                    class="w-full pl-10 pr-4 py-3 rounded-full border border-gray-300 focus:outline-none focus:border-red-300 search-input transition duration-300"
                                >
                                <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <div class="w-full md:w-2/3">
                            <div class="flex flex-wrap gap-2">
                                <span class="text-gray-600 font-medium mr-2">Filtrer :</span>
                                <button 
                                    v-for="filter in filters" 
                                    :key="filter"
                                    @click="setActiveFilter(filter)"
                                    :class="[
                                        'filter-chip px-4 py-2 rounded-full text-sm font-medium transition-all duration-200',
                                        activeFilter === filter 
                                            ? 'bg-red-500 text-white' 
                                            : 'bg-gray-100 hover:bg-gray-200 text-gray-800'
                                    ]"
                                >
                                    {{ filter }}
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-4 mb-8">
                        <span class="text-gray-600 font-medium">Tags :</span>
                        <button 
                            v-for="tag in availableTags" 
                            :key="tag.id"
                            @click="toggleTag(tag.name)"
                            :class="[
                                'filter-chip px-3 py-1 rounded-full text-xs font-medium transition-all duration-200',
                                selectedTags.includes(tag.name)
                                    ? 'bg-red-100 text-red-600'
                                    : 'bg-gray-100 hover:bg-red-100 hover:text-red-600'
                            ]"
                        >
                            {{ tag.name }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Publications Grid Section -->
        <section id="testimonials" class="py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="title-font text-2xl md:text-3xl font-bold text-gray-800">
                            Publications récentes
                            <span class="text-sm font-normal text-gray-500 ml-2">
                                ({{ publications.total }} résultat{{ publications.total > 1 ? 's' : '' }})
                            </span>
                        </h2>
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-600 text-sm">Trier par :</span>
                            <select 
                                v-model="sortBy"
                                @change="applyFilters"
                                class="bg-white border border-gray-300 rounded-full px-3 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-red-500"
                            >
                                <option value="recent">Plus récents</option>
                                <option value="popular">Plus populaires</option>
                                <option value="commented">Plus commentés</option>
                                <option value="supported">Plus soutenus</option>
                            </select>
                        </div>
                    </div>
                    
                    <div v-if="publications.data.length > 0" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div 
                            v-for="publication in publications.data" 
                            :key="publication.id"
                            class="bg-white rounded-xl shadow-md overflow-hidden testimonial-card cursor-pointer group relative"
                            @click="goToPublication(publication.slug)"
                        >
                            <div :class="getGradientClass(publication.type)" class="h-48 flex items-center justify-center relative">
                                <i class="fas fa-quote-left text-5xl opacity-30" :class="getIconColor(publication.type)"></i>
                                <div class="absolute bottom-4 right-4 flex space-x-1">
                                    <span class="type-badge bg-white bg-opacity-90 backdrop-blur-sm text-xs px-3 py-1.5 rounded-full font-medium shadow-sm" :class="getTypeColor(publication.type)">
                                        {{ getTypeLabel(publication.type) }}
                                    </span>
                                    <span v-if="publication.tags.length > 0" class="type-badge bg-white bg-opacity-90 backdrop-blur-sm text-xs px-3 py-1.5 rounded-full font-medium text-gray-600 shadow-sm">
                                        {{ publication.tags[0].name }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full mr-3 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800">{{ publication.author_name }}</h4>
                                        <p class="text-xs text-gray-500">{{ formatDate(publication.created_at) }}</p>
                                    </div>
                                </div>
                                <h3 class="title-font text-xl font-bold text-gray-800 mb-3">{{ publication.title }}</h3>
                                <div class="testimonial-content text-gray-600 mb-4 line-clamp-3">
                                    {{ publication.excerpt }}
                                </div>
                                <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
                                    <div class="flex space-x-4">
                                        <span class="text-gray-400 text-xs flex items-center">
                                            <i class="far fa-eye mr-1"></i> {{ publication.views_count }}
                                        </span>
                                        <span class="text-gray-400 text-xs flex items-center">
                                            <i class="far fa-comment mr-1"></i> {{ publication.comments_count }}
                                        </span>
                                    </div>
                                    <span v-if="isAuthenticated" class="text-xs bg-gradient-to-r from-red-50 to-pink-50 text-red-500 px-4 py-2 rounded-full cursor-pointer hover:from-red-100 hover:to-pink-100 transition-all duration-300 font-medium shadow-sm hover:shadow-md transform hover:scale-105"
                                          @click.stop="openDonationModal(publication)">
                                        <i class="fas fa-coffee mr-1.5"></i> Offrir un café
                                    </span>
                                    <span v-else class="text-xs bg-gradient-to-r from-gray-50 to-gray-100 text-gray-500 px-4 py-2 rounded-full cursor-pointer hover:from-blue-50 hover:to-blue-100 hover:text-blue-600 transition-all duration-300 font-medium shadow-sm hover:shadow-md transform hover:scale-105"
                                          @click.stop="redirectToLogin"
                                          title="Connectez-vous pour soutenir l'auteur">
                                        <i class="fas fa-sign-in-alt mr-1.5"></i> Se connecter pour offrir
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-16">
                        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-600 mb-2">Aucune publication trouvée</h3>
                        <p class="text-gray-500">Essayez de modifier vos critères de recherche ou d'ajouter une nouvelle publication.</p>
                    </div>
                    
                    <!-- Pagination -->
                    <div v-if="publications.last_page > 1" class="mt-12 flex justify-center">
                        <nav class="flex space-x-2">
                            <Link 
                                v-for="page in paginationLinks" 
                                :key="page.label"
                                :href="page.url"
                                :class="[
                                    'pagination-link px-4 py-2 border rounded-lg transition-all duration-300',
                                    page.active 
                                        ? 'bg-gradient-to-r from-red-500 to-pink-500 text-white border-red-500 shadow-lg' 
                                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 hover:border-red-300 hover:text-red-600'
                                ]"
                                v-html="page.label"
                            />
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <!-- Share Form Section -->
        <section v-if="isAuthenticated" id="share-form" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-8 md:p-12 shadow-lg">
                    <div class="text-center mb-8">
                        <h2 class="title-font text-3xl font-bold text-gray-800 mb-4">Partagez votre histoire</h2>
                        <p class="text-gray-600">Votre publication peut aider d'autres personnes et soutenir notre cause solidaire. Elle sera examinée par notre équipe avant publication.</p>
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                Votre publication sera vérifiée par notre équipe pour s'assurer qu'elle respecte notre charte de bienveillance. Vous serez notifié une fois qu'elle sera publiée.
                            </p>
                        </div>
                    </div>
                    
                    <form @submit.prevent="submitPublication">
                        <div class="mb-6">
                            <label class="block text-gray-700 font-medium mb-2">Publier en tant que :</label>
                            <div class="flex flex-wrap gap-4">
                                <label class="inline-flex items-center">
                                    <input v-model="form.is_anonymous" :value="false" type="radio" class="form-radio text-red-500">
                                    <span class="ml-2">{{ user?.pseudo || 'Utilisateur' }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input v-model="form.is_anonymous" :value="true" type="radio" class="form-radio text-red-500">
                                    <span class="ml-2">Anonyme</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="title" class="block text-gray-700 font-medium mb-2">Titre de votre publication *</label>
                            <input 
                                v-model="form.title"
                                type="text" 
                                id="title" 
                                required 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                placeholder="Un titre accrocheur pour votre publication..."
                            >
                            <span v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</span>
                        </div>
                        
                        <div class="mb-6">
                            <label for="content" class="block text-gray-700 font-medium mb-2">Votre publication *</label>
                            <TinyMCEEditor
                                v-model="form.content"
                                :min-characters="200"
                                :show-word-count="true"
                                @content-change="handleContentChange"
                                :disabled="form.processing || isLoading"
                            />
                            <div v-if="contentStatus.characterCount > 0" class="mt-2 flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-500">Minimum 200 caractères.</span>
                                    <span :class="[
                                        'ml-2 text-sm font-medium',
                                        contentStatus.isValid ? 'text-green-600' : 'text-orange-500'
                                    ]">
                                        {{ contentStatus.characterCount }} / 200
                                    </span>
                                </div>
                                <div v-if="contentStatus.isValid" class="flex items-center text-green-600">
                                    <i class="fas fa-check-circle text-sm mr-1"></i>
                                    <span class="text-xs">Prêt à publier</span>
                                </div>
                            </div>
                            <div v-if="contentStatus.characterCount > 0" class="mt-1 w-full bg-gray-200 rounded-full h-1">
                                <div 
                                    :class="[
                                        'h-1 rounded-full transition-all duration-300',
                                        contentStatus.isValid ? 'bg-green-500' : 'bg-orange-400'
                                    ]"
                                    :style="{ width: Math.min((contentStatus.characterCount / 200) * 100, 100) + '%' }"
                                ></div>
                            </div>
                            <span v-if="form.errors.content" class="text-red-500 text-sm">{{ form.errors.content }}</span>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 font-medium mb-2">Type de contenu :</label>
                            <select 
                                v-model="form.type"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            >
                                <option value="testimony">Témoignage</option>
                               <!--  <option value="poetry">Poème</option> -->
                                <option value="reflection">Réflexion</option>
                            </select>
                            <span v-if="form.errors.type" class="text-red-500 text-sm">{{ form.errors.type }}</span>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 font-medium mb-2">Tags (maximum 3) :</label>
                            <div class="flex flex-wrap gap-2 mb-3">
                                <button 
                                    v-for="tag in suggestedTags" 
                                    :key="tag"
                                    type="button"
                                    @click="toggleFormTag(tag)"
                                    :class="[
                                        'px-3 py-1 rounded-full text-xs font-medium transition-all duration-200',
                                        form.tags.includes(tag)
                                            ? 'bg-red-100 text-red-600'
                                            : 'bg-gray-100 hover:bg-red-100 hover:text-red-600'
                                    ]"
                                >
                                    {{ tag }}
                                </button>
                                <div v-if="suggestedTags.length === 0" class="text-gray-500 text-sm italic py-2">
                                    Aucun tag suggéré disponible pour le moment.
                                </div>
                            </div>
                            <div v-if="form.tags.length > 0" class="flex flex-wrap gap-2">
                                <span class="text-sm text-gray-600">Tags sélectionnés :</span>
                                <span 
                                    v-for="tag in form.tags" 
                                    :key="tag"
                                    class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs"
                                >
                                    {{ tag }}
                                    <button type="button" @click="removeTag(tag)" class="ml-1 text-red-400 hover:text-red-600">×</button>
                                </span>
                            </div>
                            <span v-if="form.errors.tags" class="text-red-500 text-sm">{{ form.errors.tags }}</span>
                        </div>
                        
                        <div class="mb-6">
                            <label class="inline-flex items-center">
                                <input v-model="form.agreeToTerms" type="checkbox" class="form-checkbox text-red-500 rounded" required>
                                <span class="ml-2 text-gray-700">J'ai lu et j'accepte la <a href="#" class="text-red-500 hover:underline">charte de modération</a> et les <a href="#" class="text-red-500 hover:underline">conditions d'utilisation</a></span>
                            </label>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button 
                                type="submit" 
                                :disabled="form.processing || isLoading"
                                class="bg-red-500 hover:bg-red-600 disabled:bg-gray-400 text-white px-8 py-4 rounded-full font-bold transition duration-300 flex-1 relative"
                            >
                                <span v-if="form.processing || isLoading" class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                <span :class="{ 'invisible': form.processing || isLoading }">
                                <i class="fas fa-paper-plane mr-2"></i> 
                                    {{ form.processing || isLoading ? '' : 'Soumettre ma publication' }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Login CTA for guests -->
        <section v-else class="py-16 bg-gradient-to-r from-red-600 to-pink-600">
            <div class="container mx-auto px-4 text-center">
                <h2 class="title-font text-3xl font-bold text-white mb-6">Rejoignez notre communauté</h2>
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">Connectez-vous ou créez un compte pour partager votre histoire et soutenir les autres membres de notre communauté.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <Link href="/auth/login" class="bg-white text-red-500 hover:bg-gray-100 px-8 py-4 rounded-full font-bold text-lg transition duration-300 shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                    </Link>
                    <Link href="/auth/register" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-red-500 px-8 py-4 rounded-full font-bold text-lg transition duration-300">
                        <i class="fas fa-user-plus mr-2"></i> S'inscrire
                    </Link>
                </div>
            </div>
        </section>

        <!-- Guidelines Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 text-2xl mt-1"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="title-font text-xl font-bold text-gray-800 mb-2">Notre charte de bienveillance</h3>
                                <p class="text-gray-700">Cet espace est dédié au partage et au soutien mutuel. Nous modérons activement les contenus pour garantir un environnement sûr et respectueux.</p>
                                <ul class="list-disc list-inside mt-2 text-gray-700 space-y-1">
                                    <li>Respecter l'anonymat et la pudeur de chacun</li>
                                    <li>Éviter tout jugement ou langage blessant</li>
                                    <li>Ne pas divulguer d'informations personnelles</li>
                                    <li>Privilégier l'écoute et l'empathie</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal de validation -->
        <div v-if="showValidationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Validation requise</h3>
                    </div>
                    
                    <p class="text-gray-600 mb-6 leading-relaxed">{{ validationMessage }}</p>
                    
                    <div class="flex justify-end">
                        <button 
                            @click="closeValidationModal"
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200"
                        >
                            Compris
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overlay de chargement -->
        <div v-if="isLoading" class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-40">
            <div class="bg-white rounded-2xl shadow-2xl p-8 mx-4 max-w-sm w-full">
                <LoadingSpinner 
                    message="Publication en cours..."
                    sub-message="Votre publication est en cours de soumission"
                />
            </div>
        </div>

        <!-- Modal de don -->
        <DonationModal 
            :show="showDonationModal"
            :publication="selectedPublication"
            :is-authenticated="isAuthenticated"
            @close="closeDonationModal"
            @donation-completed="() => console.log('Don complété depuis Publication')"
        />

        <!-- Bandeau flottant solidarité malvoyants -->
        <SolidarityMention />
    </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'
import MainLayout from '@/Client/Layouts/MainLayout.vue'
import TinyMCEEditor from '@/Client/Components/TinyMCEEditor.vue'
import DonationModal from '@/Client/Components/DonationModal.vue'
import LoadingSpinner from '@/Client/Components/LoadingSpinner.vue'
import SectionHeader from '@/Client/Components/SectionHeader.vue'
import SolidarityMention from '@/Client/Components/SolidarityMention.vue'
// import TinyMCEEditor from '@/Client/Components/TinyMCEEditorLocal.vue' // Alternative locale

// Props
const props = defineProps({
    publications: Object,
    availableTags: Array,
    suggestedTags: Array,
    filters: Object,
    isAuthenticated: Boolean,
    user: Object,
})

// Page data
const page = usePage()
const flash = computed(() => page.props.flash || {})

// Reactive data
const searchQuery = ref(props.filters?.search || '')
const activeFilter = ref(props.filters?.type || 'Tous')
const sortBy = ref(props.filters?.sort || 'recent')
const selectedTags = ref(props.filters?.tags || [])
const suggestedTags = ref(props.suggestedTags || [])
const showValidationModal = ref(false)
const validationMessage = ref('')
const isLoading = ref(false)
const showDonationModal = ref(false)
const selectedPublication = ref(null)
const contentStatus = ref({
    characterCount: 0,
    isValid: false
})

const filters = ['Tous', 'Témoignages', 'Réflexions']

// Form data avec useForm d'Inertia
const form = useForm({
    title: '',
    content: '',
    type: 'testimony',
    is_anonymous: props.user?.anonymous_by_default ?? false,
    tags: [],
    agreeToTerms: false,
})

// Computed
const paginationLinks = computed(() => {
    return props.publications?.links || []
})

// Methods
const setActiveFilter = (filter) => {
    activeFilter.value = filter
    applyFilters()
}

const toggleTag = (tag) => {
    const index = selectedTags.value.indexOf(tag)
    if (index > -1) {
        selectedTags.value.splice(index, 1)
    } else {
        selectedTags.value.push(tag)
    }
    applyFilters()
}

const toggleFormTag = (tag) => {
    if (form.tags.length >= 3 && !form.tags.includes(tag)) {
        return
    }
    
    const index = form.tags.indexOf(tag)
    if (index > -1) {
        form.tags.splice(index, 1)
    } else {
        form.tags.push(tag)
    }
}

const removeTag = (tag) => {
    const index = form.tags.indexOf(tag)
    if (index > -1) {
        form.tags.splice(index, 1)
    }
}

const applyFilters = () => {
    const params = {
        search: searchQuery.value,
        type: activeFilter.value !== 'Tous' ? activeFilter.value : null,
        sort: sortBy.value,
        tags: selectedTags.value.length > 0 ? selectedTags.value : null,
    }
    
    // Remove null/empty values
    Object.keys(params).forEach(key => {
        if (params[key] === null || params[key] === '' || (Array.isArray(params[key]) && params[key].length === 0)) {
            delete params[key]
        }
    })
    
    router.get('/publication', params, {
        preserveState: true,
        preserveScroll: true,
    })
}

const goToPublication = (slug) => {
    router.visit(`/publication/${slug}`)
}

const openDonationModal = (publication) => {
    selectedPublication.value = publication
    showDonationModal.value = true
}

const closeDonationModal = () => {
    showDonationModal.value = false
    selectedPublication.value = null
}

const redirectToLogin = () => {
    router.visit('/auth/login', {
        data: {
            redirect: window.location.pathname
        }
    })
}

const showValidationError = (message) => {
    validationMessage.value = message
    showValidationModal.value = true
}

const closeValidationModal = () => {
    showValidationModal.value = false
    validationMessage.value = ''
}

const handleContentChange = (data) => {
    contentStatus.value = {
        characterCount: data.characterCount,
        isValid: data.isValid
    }
}

const submitPublication = () => {
    // Validation côté client avec TinyMCE
    if (!contentStatus.value.isValid) {
        showValidationError('Votre publication doit contenir au moins 200 caractères pour être soumise. Vous en avez actuellement ' + contentStatus.value.characterCount + '.')
        return
    }
    
    if (!form.agreeToTerms) {
        showValidationError('Vous devez accepter la charte de modération et les conditions d\'utilisation pour continuer.')
        return
    }

    if (!form.title.trim()) {
        showValidationError('Le titre de votre publication est requis.')
        return
    }

    isLoading.value = true
    
    form.post('/publication', {
        onStart: () => {
            isLoading.value = true
        },
        onSuccess: () => {
            form.reset()
            isLoading.value = false
            // Scroll vers le haut pour voir le message de succès
            window.scrollTo({ top: 0, behavior: 'smooth' })
        },
        onError: (errors) => {
            isLoading.value = false
            console.error('Erreurs de validation:', errors)
            
            // Afficher la première erreur
            const firstError = Object.values(errors)[0]
            if (firstError) {
                showValidationError(Array.isArray(firstError) ? firstError[0] : firstError)
            }
        },
        onFinish: () => {
            isLoading.value = false
        }
    })
}

const getTypeLabel = (type) => {
    const labels = {
        'testimony': 'Témoignage',
        'poetry': 'Poème',
        'reflection': 'Réflexion'
    }
    return labels[type] || type
}

const getGradientClass = (type) => {
    const classes = {
        'testimony': 'bg-gradient-to-r from-pink-100 to-red-100',
        'poetry': 'bg-gradient-to-r from-blue-100 to-indigo-100',
        'reflection': 'bg-gradient-to-r from-purple-100 to-indigo-100'
    }
    return classes[type] || 'bg-gradient-to-r from-gray-100 to-gray-200'
}

const getIconColor = (type) => {
    const colors = {
        'testimony': 'text-red-300',
        'poetry': 'text-blue-300',
        'reflection': 'text-purple-300'
    }
    return colors[type] || 'text-gray-300'
}

const getTypeColor = (type) => {
    const colors = {
        'testimony': 'text-red-500',
        'poetry': 'text-blue-500',
        'reflection': 'text-purple-500'
    }
    return colors[type] || 'text-gray-500'
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    })
}

// Les tags sont maintenant chargés directement depuis le serveur via props
// Plus besoin de fonction de chargement asynchrone

onMounted(() => {
    console.log('Tags suggérés disponibles:', suggestedTags.value)
})
</script>

<style scoped>
.testimonial-card {
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    position: relative;
    overflow: hidden;
}

.testimonial-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.6s;
    z-index: 1;
}

.testimonial-card:hover::before {
    left: 100%;
}

.testimonial-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.5),
        0 0 20px rgba(239, 68, 68, 0.1);
}

.testimonial-card .group:hover .testimonial-content {
    color: #374151;
}

.filter-chip {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
}

.filter-chip:hover {
    transform: scale(1.05) translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.2);
}

.filter-chip:active {
    transform: scale(0.98);
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.6;
    height: calc(1.6em * 3);
}

.search-input {
    transition: all 0.3s ease;
}

.search-input:focus {
    box-shadow: 
        0 0 0 4px rgba(239, 68, 68, 0.1),
        0 4px 12px rgba(0, 0, 0, 0.05);
    transform: translateY(-1px);
}

.title-font {
    font-family: 'Playfair Display', serif;
}

/* Animation pour les cartes au chargement */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.testimonial-card {
    animation: fadeInUp 0.6s ease-out forwards;
}

.testimonial-card:nth-child(1) { animation-delay: 0.1s; }
.testimonial-card:nth-child(2) { animation-delay: 0.2s; }
.testimonial-card:nth-child(3) { animation-delay: 0.3s; }
.testimonial-card:nth-child(4) { animation-delay: 0.4s; }
.testimonial-card:nth-child(5) { animation-delay: 0.5s; }
.testimonial-card:nth-child(6) { animation-delay: 0.6s; }

/* Effet de brillance sur les badges */
.type-badge {
    position: relative;
    overflow: hidden;
}

.type-badge::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.5s;
}

.group:hover .type-badge::after {
    left: 100%;
}

/* Amélioration des boutons de tri */
select {
    transition: all 0.3s ease;
}

select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    border-color: #ef4444;
}

/* Pagination améliorée */
.pagination-link {
    transition: all 0.3s ease;
}

.pagination-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>