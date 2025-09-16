<template>
    <MainLayout>
        <!-- Hero Section -->
        <SectionHeader
            title="Nos bénéficiaires"
            subtitle="Découvrez les personnes malvoyantes ou aveugles que notre communauté soutient au Bénin. Chaque profil représente une histoire et un espoir."
            badge="Solidarité"
            badge-icon="fas fa-heart"
            gradient-class="from-blue-600 to-indigo-600"
            :show-scroll-hint="true"
        >
            <template #actions>
                <Link href="/solidarity" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-4 rounded-full font-bold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la solidarité
                </Link>
                <a href="#statistics" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-4 rounded-full font-bold transition-all duration-300 hover:shadow-xl">
                    <i class="fas fa-chart-bar mr-2"></i> Voir les statistiques
                </a>
            </template>
        </SectionHeader>

        <!-- Statistiques Section -->
        <section id="statistics" class="py-12 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-8">
                        <h2 class="title-font text-3xl font-bold text-gray-800 mb-4">Statistiques de nos bénéficiaires</h2>
                        <p class="text-lg text-gray-600">Un aperçu de la communauté que nous soutenons</p>
                    </div>
                    
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-all duration-300">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-2xl text-blue-600"></i>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-800 mb-2">{{ stats.total_personnes }}</h3>
                            <p class="text-gray-600">Personnes accompagnées</p>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-all duration-300">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-male text-2xl text-green-600"></i>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-800 mb-2">{{ stats.hommes }}</h3>
                            <p class="text-gray-600">Hommes</p>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-all duration-300">
                            <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-female text-2xl text-pink-600"></i>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-800 mb-2">{{ stats.femmes }}</h3>
                            <p class="text-gray-600">Femmes</p>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-all duration-300">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-stethoscope text-2xl text-yellow-600"></i>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-800 mb-2">{{ stats.en_traitement }}</h3>
                            <p class="text-gray-600">En traitement</p>
                        </div>
                    </div>

                    <!-- Répartition par type de voyance -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">Répartition par type de déficience visuelle</h3>
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div v-for="(count, type) in stats.types_voyance" :key="type" class="bg-gray-50 rounded-lg p-4 text-center">
                                <h4 class="font-semibold text-gray-800 mb-2">{{ type }}</h4>
                                <span class="text-2xl font-bold text-blue-600">{{ count }}</span>
                                <span class="text-sm text-gray-500 block">personne{{ count > 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Filters Section -->
        <section class="py-8 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                        <div class="w-full md:w-1/3">
                            <div class="relative">
                                <input 
                                    v-model="searchQuery"
                                    @input="applyFilters"
                                    type="text" 
                                    placeholder="Rechercher par nom, prénom ou lieu..." 
                                    class="w-full pl-10 pr-4 py-3 rounded-full border border-gray-300 focus:outline-none focus:border-blue-300 search-input transition duration-300"
                                >
                                <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <div class="w-full md:w-2/3">
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2">
                                    <label class="text-gray-600 font-medium">Sexe :</label>
                                    <select 
                                        v-model="selectedSexe"
                                        @change="applyFilters"
                                        class="bg-white border border-gray-300 rounded-full px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                    >
                                        <option value="">Tous</option>
                                        <option v-for="(label, value) in sexes" :key="value" :value="value">{{ label }}</option>
                                    </select>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <label class="text-gray-600 font-medium">Type de déficience :</label>
                                    <select 
                                        v-model="selectedTypeVoyance"
                                        @change="applyFilters"
                                        class="bg-white border border-gray-300 rounded-full px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                    >
                                        <option value="">Tous</option>
                                        <option v-for="(label, value) in typesVoyance" :key="value" :value="value">{{ label }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- People Grid Section -->
        <section class="py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="title-font text-2xl md:text-3xl font-bold text-gray-800">
                            Nos bénéficiaires
                            <span class="text-sm font-normal text-gray-500 ml-2">
                                ({{ people.total }} personne{{ people.total > 1 ? 's' : '' }})
                            </span>
                        </h2>
                    </div>
                    
                    <div v-if="people.data.length > 0" class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <div 
                            v-for="person in people.data" 
                            :key="person.id"
                            class="bg-white rounded-xl shadow-md overflow-hidden person-card group relative"
                        >
                            <div class="relative">
                                <div v-if="person.photo_url" class="h-48 overflow-hidden">
                                    <img :src="person.photo_url" :alt="person.nom_complet" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                                <div v-else class="h-48 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-user text-6xl text-blue-300"></i>
                                </div>
                                
                                <!-- Badges -->
                                <div class="absolute top-4 right-4 flex flex-col space-y-2">
                                    <span class="bg-white bg-opacity-90 backdrop-blur-sm text-xs px-3 py-1.5 rounded-full font-medium shadow-sm" :class="person.sexe === 'M' ? 'text-blue-600' : 'text-pink-600'">
                                        {{ person.sexe_libelle }}
                                    </span>
                                    <span v-if="person.traitement_en_cours" class="bg-green-500 bg-opacity-90 backdrop-blur-sm text-white text-xs px-3 py-1.5 rounded-full font-medium shadow-sm">
                                        En traitement
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <h3 class="title-font text-xl font-bold text-gray-800 mb-2">{{ person.nom_complet }}</h3>
                                
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div v-if="person.age" class="flex items-center">
                                        <i class="fas fa-birthday-cake w-4 text-gray-400 mr-2"></i>
                                        <span>{{ person.age }} ans</span>
                                    </div>
                                    
                                    <div v-if="person.lieu_residence" class="flex items-center">
                                        <i class="fas fa-map-marker-alt w-4 text-gray-400 mr-2"></i>
                                        <span>{{ person.lieu_residence }}</span>
                                    </div>
                                    
                                    <div v-if="person.type_voyance_libelle" class="flex items-center">
                                        <i class="fas fa-eye w-4 text-gray-400 mr-2"></i>
                                        <span>{{ person.type_voyance_libelle }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-16">
                        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-600 mb-2">Aucune personne trouvée</h3>
                        <p class="text-gray-500">Essayez de modifier vos critères de recherche.</p>
                    </div>
                    
                    <!-- Pagination -->
                    <div v-if="people.last_page > 1" class="mt-12 flex justify-center">
                        <nav class="flex space-x-2">
                            <Link 
                                v-for="page in paginationLinks" 
                                :key="page.label"
                                :href="page.url"
                                :class="[
                                    'pagination-link px-4 py-2 border rounded-lg transition-all duration-300',
                                    page.active 
                                        ? 'bg-gradient-to-r from-blue-500 to-indigo-500 text-white border-blue-500 shadow-lg' 
                                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 hover:border-blue-300 hover:text-blue-600'
                                ]"
                                v-html="page.label"
                            />
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-600">
            <div class="container mx-auto px-4 text-center">
                <h2 class="title-font text-3xl font-bold text-white mb-6">Soutenez notre mission</h2>
                <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                    Chaque don nous aide à améliorer la vie de ces personnes courageuses. 
                    Votre générosité peut faire la différence dans leur quotidien.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <button @click="openDonationModal" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-4 rounded-full font-bold text-lg transition duration-300 shadow-lg">
                        <i class="fas fa-hand-holding-heart mr-2"></i> Faire un don
                    </button>
                    <Link href="/publication" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-4 rounded-full font-bold text-lg transition duration-300">
                        <i class="fas fa-pen-fancy mr-2"></i> Partager votre histoire
                    </Link>
                </div>
            </div>
        </section>

        <!-- Bandeau flottant solidarité malvoyants -->
        <SolidarityMention />

        <!-- Modal de don solidaire -->
        <SolidarityDonationModal 
            :show="showDonationModal"
            :is-authenticated="true"
            @close="closeDonationModal"
            @donation-completed="onDonationCompleted"
        />
    </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import MainLayout from '@/Client/Layouts/MainLayout.vue'
import SectionHeader from '@/Client/Components/SectionHeader.vue'
import SolidarityMention from '@/Client/Components/SolidarityMention.vue'
import SolidarityDonationModal from '@/Client/Components/SolidarityDonationModal.vue'

// Props
const props = defineProps({
    people: Object,
    stats: Object,
    sexes: Object,
    typesVoyance: Object,
    filters: Object,
})

// Reactive data
const searchQuery = ref(props.filters?.search || '')
const selectedSexe = ref(props.filters?.sexe || '')
const selectedTypeVoyance = ref(props.filters?.type_voyance || '')
const showDonationModal = ref(false)

// Computed
const paginationLinks = computed(() => {
    return props.people?.links || []
})

// Methods
const applyFilters = () => {
    const params = {
        search: searchQuery.value,
        sexe: selectedSexe.value,
        type_voyance: selectedTypeVoyance.value,
    }
    
    // Remove null/empty values
    Object.keys(params).forEach(key => {
        if (params[key] === null || params[key] === '') {
            delete params[key]
        }
    })
    
    router.get('/visually-impaired-people', params, {
        preserveState: true,
        preserveScroll: true,
    })
}

// Méthodes pour le modal de don solidaire
const openDonationModal = () => {
    showDonationModal.value = true
}

const closeDonationModal = () => {
    showDonationModal.value = false
}

const onDonationCompleted = (donationData) => {
    // Traiter la completion du don si nécessaire
    console.log('Don solidaire complété:', donationData)
}
</script>

<style scoped>
.person-card {
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    position: relative;
    overflow: hidden;
}

.person-card::before {
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

.person-card:hover::before {
    left: 100%;
}

.person-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.5),
        0 0 20px rgba(59, 130, 246, 0.1);
}

.search-input {
    transition: all 0.3s ease;
}

.search-input:focus {
    box-shadow: 
        0 0 0 4px rgba(59, 130, 246, 0.1),
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

.person-card {
    animation: fadeInUp 0.6s ease-out forwards;
}

.person-card:nth-child(1) { animation-delay: 0.1s; }
.person-card:nth-child(2) { animation-delay: 0.2s; }
.person-card:nth-child(3) { animation-delay: 0.3s; }
.person-card:nth-child(4) { animation-delay: 0.4s; }
.person-card:nth-child(5) { animation-delay: 0.5s; }
.person-card:nth-child(6) { animation-delay: 0.6s; }
.person-card:nth-child(7) { animation-delay: 0.7s; }
.person-card:nth-child(8) { animation-delay: 0.8s; }

/* Pagination améliorée */
.pagination-link {
    transition: all 0.3s ease;
}

.pagination-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>