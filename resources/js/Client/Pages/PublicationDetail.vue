<template>
    <MainLayout>
        <!-- Breadcrumb -->
        <div class="container mx-auto px-4 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <Link href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-500">
                            <i class="fas fa-home mr-2"></i>
                            Accueil
                        </Link>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                            <Link href="/publication" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-500 md:ml-2">Publications</Link>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ getTypeLabel(publication.type) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Testimonial Detail Section -->
        <section class="py-8">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <!-- Navigation buttons -->
                    <div class="flex justify-between mb-8">
                        <Link href="/publication" class="navigation-btn flex items-center text-red-500 hover:text-red-600 font-medium transition-all duration-200 hover:-translate-x-1">
                            <i class="fas fa-arrow-left mr-2"></i> Retour aux publications
                        </Link>
                        <div class="flex space-x-4">
                            <button @click="sharePublication" class="navigation-btn flex items-center text-red-500 hover:text-red-600 font-medium transition-all duration-200">
                                <i class="fas fa-share-alt mr-2"></i> Partager
                            </button>
                        </div>
                    </div>
                    
                    <!-- Main publication card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 publication-main-card">
                        <div :class="getGradientClass(publication.type)" class="h-64 flex items-center justify-center relative">
                            <i class="fas fa-quote-left text-7xl opacity-20" :class="getIconColor(publication.type)"></i>
                            <div class="absolute bottom-4 right-4 flex space-x-2">
                                <span class="publication-badge bg-white bg-opacity-90 backdrop-blur-sm text-sm px-4 py-2 rounded-full font-semibold shadow-md" :class="getTypeColor(publication.type)">
                                    {{ getTypeLabel(publication.type) }}
                                </span>
                                <span 
                                    v-for="tag in publication.tags.slice(0, 2)" 
                                    :key="tag.id"
                                    class="publication-badge bg-white bg-opacity-90 backdrop-blur-sm text-sm px-4 py-2 rounded-full font-semibold text-gray-600 shadow-md"
                                >
                                    {{ tag.name }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gray-200 rounded-full mr-4 overflow-hidden flex items-center justify-center">
                                        <img v-if="publication.user?.avatar" :src="publication.user.avatar" :alt="publication.author_name" class="w-full h-full object-cover">
                                        <i v-else class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800">{{ publication.author_name }}</h4>
                                        <p class="text-sm text-gray-500">Publié le {{ formatDate(publication.created_at) }}</p>
                                        <div class="flex items-center text-xs text-gray-400 mt-1">
                                            <i class="far fa-eye mr-1"></i>
                                            <span class="mr-3">{{ publication.views_count }} vues</span>
                                            <i class="far fa-comment mr-1"></i>
                                            <span>{{ publication.comments_count }} commentaires</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <button @click="sharePublication" class="share-report-btn text-gray-500 hover:text-red-500 transition-all" title="Partager">
                                        <i class="fas fa-share-alt text-xl"></i>
                                    </button>
                                    <button v-if="isAuthenticated" @click="reportPublication" class="share-report-btn text-gray-500 hover:text-red-500 transition-all" title="Signaler">
                                        <i class="far fa-flag text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <h1 class="title-font text-3xl font-bold text-gray-800 mb-6">{{ publication.title }}</h1>
                            
                            <div class="publication-content text-gray-700 mb-8" v-html="publication.content">
                            </div>
                            
                            <!-- Quick reactions -->
                            <div class="flex flex-wrap items-center justify-between mb-6 pt-6 border-t border-gray-100">
                                <div class="flex flex-wrap gap-2 mb-4 sm:mb-0">
                                    <button 
                                        v-for="(emoji, type) in reactionEmojis" 
                                        :key="type"
                                        @click="toggleReaction(type)"
                                        :class="[
                                            'emoji-reaction flex items-center px-4 py-2 bg-gray-100 rounded-full transition-all duration-200 hover:scale-110',
                                            getReactionHoverColor(type),
                                            userReactions.includes(type) ? getReactionActiveColor(type) : ''
                                        ]"
                                    >
                                        <span class="text-2xl mr-2">{{ emoji }}</span>
                                        <span class="text-sm">{{ getReactionLabel(type) }}</span>
                                        <span class="ml-2 text-gray-500 text-sm">({{ reactionStats[type] || 0 }})</span>
                                    </button>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">
                                        {{ publication.donations_amount }}€ collectés
                                    </span>
                                </div>
                            </div>

                            <!-- Bouton de don discret -->
                            <DonationButton 
                                :publication="publication" 
                                :is-authenticated="isAuthenticated"
                                @donation-completed="onDonationCompleted"
                            />
                        </div>
                    </div>
                    
                    <!-- Comments section -->
                    <div class="comment-section bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="p-6 md:p-8">
                            <h2 class="title-font text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="far fa-comments text-red-500 mr-3"></i> Commentaires ({{ publication.comments_count }})
                            </h2>
                            
                            <!-- Composant de commentaires threadés -->
                            <CommentThread 
                                :comments="publication.comments"
                                :publication-id="publication.id"
                                :is-authenticated="isAuthenticated"
                                :user="user"
                                :has-more-comments="hasMoreComments"
                                @comment-added="onCommentAdded"
                                @load-more="loadMoreComments"
                            />
                        </div>
                    </div>
                    
                    <!-- Related publications -->
                    <div v-if="relatedPublications.length > 0" class="related-publications mt-12">
                        <h2 class="title-font text-2xl font-bold text-gray-800 mb-6">Autres publications qui pourraient vous intéresser</h2>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div 
                                v-for="related in relatedPublications" 
                                :key="related.id"
                                class="related-publication-card bg-white rounded-xl shadow-sm overflow-hidden cursor-pointer"
                                @click="goToPublication(related.slug)"
                            >
                                <div :class="getGradientClass(related.type)" class="h-40 flex items-center justify-center">
                                    <i class="fas fa-quote-left text-4xl opacity-20" :class="getIconColor(related.type)"></i>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center mb-3">
                                        <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 overflow-hidden flex items-center justify-center">
                                            <i class="fas fa-user text-gray-400 text-xs"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm">{{ related.author_name }}</h4>
                                            <p class="text-xs text-gray-500">{{ formatRelativeTime(related.created_at) }}</p>
                                        </div>
                                    </div>
                                    <h3 class="title-font text-lg font-bold text-gray-800 mb-2">{{ related.title }}</h3>
                                    <p class="text-gray-600 text-sm line-clamp-2">{{ related.excerpt }}</p>
                                    <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-100">
                                        <div class="flex flex-wrap gap-1">
                                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100" :class="getTypeColor(related.type)">
                                                {{ getTypeLabel(related.type) }}
                                            </span>
                                            <span 
                                                v-for="tag in related.tags.slice(0, 2)" 
                                                :key="tag.id"
                                                class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600"
                                            >
                                                {{ tag.name }}
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                                            <span><i class="far fa-eye mr-1"></i>{{ related.views_count }}</span>
                                            <span><i class="far fa-comment mr-1"></i>{{ related.comments_count }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 text-center">
                            <Link href="/publication" class="inline-block bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-full font-medium transition duration-300">
                                <i class="fas fa-book-open mr-2"></i> Voir toutes les publications
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-gradient-to-r from-red-600 to-pink-600">
            <div class="container mx-auto px-4 text-center">
                <h2 class="title-font text-3xl font-bold text-white mb-6">Vous aussi, partagez votre histoire</h2>
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">Votre publication peut aider d'autres personnes et vous permettre de libérer vos émotions.</p>
                <Link href="/publication" class="bg-white text-red-500 hover:bg-gray-100 px-8 py-4 rounded-full font-bold text-lg transition duration-300 shadow-lg inline-flex items-center">
                    <i class="fas fa-pen-fancy mr-2"></i> Écrire une publication
                </Link>
            </div>
        </section>

        <!-- Modal de succès de paiement -->
        <div v-if="showPaymentSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
                <div class="p-8 text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-heart text-green-500 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Merci pour votre générosité !</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Votre don a été transmis avec succès à <strong>{{ publication.author_name }}</strong>. 
                        Votre soutien l'encouragera à continuer d'écrire et de partager ses émotions.
                    </p>
                    <button 
                        @click="closePaymentSuccessModal"
                        class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-200"
                    >
                        Continuer la lecture
                    </button>
                </div>
            </div>
        </div>

        <!-- Bandeau flottant solidarité malvoyants -->
        <SolidarityMention />

        <!-- Modal de signalement -->
        <ReportModal 
            :show="showReportModal"
            :reportable-type="'App\\Models\\Publication'"
            :reportable-id="publication.id"
            @close="showReportModal = false"
            @reported="onReported"
        />
    </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import MainLayout from '@/Client/Layouts/MainLayout.vue'
import CommentThread from '@/Client/Components/CommentThread.vue'
import DonationButton from '@/Client/Components/DonationButton.vue'
import SolidarityMention from '@/Client/Components/SolidarityMention.vue'
import ReportModal from '@/Client/Components/ReportModal.vue'

// Props du backend
const props = defineProps({
    publication: Object,
    reactionStats: Object,
    userReactions: Array,
    relatedPublications: Array,
    isAuthenticated: Boolean,
    user: Object
})

// État local
const hasMoreComments = ref(false) // Sera géré par le backend plus tard
const showPaymentSuccessModal = ref(false)
const showReportModal = ref(false)

// Réactions disponibles
const reactionEmojis = {
    'heart': '❤️',
    'cry': '😢', 
    'pray': '🙏',
    'thank_you': '🙏',
    'understand': '💯',
    'courage': '💪'
}

const reactionLabels = {
    'heart': 'J\'aime',
    'cry': 'Tristesse',
    'pray': 'Prière',
    'thank_you': 'Merci',
    'understand': 'Je comprends',
    'courage': 'Courage'
}

// Méthodes
const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' }
    return new Date(dateString).toLocaleDateString('fr-FR', options)
}

const formatRelativeTime = (dateString) => {
    const now = new Date()
    const date = new Date(dateString)
    const diffTime = Math.abs(now - date)
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    
    if (diffDays === 1) return 'Il y a 1 jour'
    if (diffDays < 7) return `Il y a ${diffDays} jours`
    if (diffDays < 14) return 'Il y a 1 semaine'
    if (diffDays < 30) return `Il y a ${Math.floor(diffDays / 7)} semaines`
    return `Il y a ${Math.floor(diffDays / 30)} mois`
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

const getReactionLabel = (type) => {
    return reactionLabels[type] || type
}

const getReactionHoverColor = (type) => {
    const colors = {
        'heart': 'hover:bg-red-50 hover:text-red-500',
        'cry': 'hover:bg-blue-50 hover:text-blue-500',
        'pray': 'hover:bg-yellow-50 hover:text-yellow-500',
        'thank_you': 'hover:bg-green-50 hover:text-green-500',
        'understand': 'hover:bg-indigo-50 hover:text-indigo-500',
        'courage': 'hover:bg-orange-50 hover:text-orange-500'
    }
    return colors[type] || 'hover:bg-gray-50 hover:text-gray-500'
}

const getReactionActiveColor = (type) => {
    const colors = {
        'heart': 'bg-red-50 text-red-500',
        'cry': 'bg-blue-50 text-blue-500',
        'pray': 'bg-yellow-50 text-yellow-500',
        'thank_you': 'bg-green-50 text-green-500',
        'understand': 'bg-indigo-50 text-indigo-500',
        'courage': 'bg-orange-50 text-orange-500'
    }
    return colors[type] || 'bg-gray-50 text-gray-500'
}

const sharePublication = () => {
    if (navigator.share) {
        navigator.share({
            title: props.publication.title,
            text: 'Découvrez cette publication sur notre plateforme',
            url: window.location.href
        })
    } else {
        // Fallback: copier l'URL dans le presse-papiers
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Lien copié dans le presse-papiers!')
        })
    }
}

const reportPublication = () => {
    if (!props.isAuthenticated) {
        router.visit('/auth/login')
        return
    }
    showReportModal.value = true
}

const onReported = (data) => {
    console.log('Publication signalée avec succès:', data)
    // Optionnel : afficher un message de confirmation ou actualiser la page
}

const toggleReaction = async (type) => {
    if (!props.isAuthenticated) {
        router.visit('/auth/login')
        return
    }
    
    try {
        const response = await fetch('/reactions/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                reactable_type: 'App\\Models\\Publication',
                reactable_id: props.publication.id,
                type: type
            })
        })
        
        const data = await response.json()
        
        if (data.success) {
            // Mettre à jour les réactions localement
            if (data.reacted) {
                if (!props.userReactions.includes(type)) {
                    props.userReactions.push(type)
                }
            } else {
                const index = props.userReactions.indexOf(type)
                if (index > -1) {
                    props.userReactions.splice(index, 1)
                }
            }
            
            // Mettre à jour les statistiques
            Object.keys(data.reaction_stats).forEach(reactionType => {
                props.reactionStats[reactionType] = data.reaction_stats[reactionType]
            })
        }
    } catch (error) {
        console.error('Erreur lors du toggle de réaction:', error)
    }
}

const onCommentAdded = (newComment) => {
    // Ajouter le nouveau commentaire à la liste
    props.publication.comments.unshift(newComment)
    // Incrémenter le compteur de commentaires
    props.publication.comments_count++
}

const onDonationCompleted = (donationData) => {
    // Mettre à jour le montant total des dons
    props.publication.donations_amount += donationData.amount
    console.log('Don complété:', donationData)
}

const loadMoreComments = () => {
    // Implémenter le chargement de commentaires supplémentaires
    console.log('Chargement de plus de commentaires...')
}

const goToPublication = (slug) => {
    router.visit(`/publication/${slug}`)
}

// Vérifier si l'utilisateur revient de Stripe
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search)
    
    if (urlParams.get('payment_success') === 'true') {
        const donationId = urlParams.get('donation_id')
        // Vérifier que le donationId existe vraiment avant de vérifier le statut
        if (donationId && donationId !== 'null' && donationId !== '') {
            checkPaymentStatus(donationId)
        }
        
        // Nettoyer l'URL
        const newUrl = window.location.pathname
        window.history.replaceState({}, document.title, newUrl)
    }
    
    if (urlParams.get('payment_cancelled') === 'true') {
        // Afficher un message d'annulation si nécessaire
        console.log('Paiement annulé par l\'utilisateur')
        
        // Nettoyer l'URL
        const newUrl = window.location.pathname
        window.history.replaceState({}, document.title, newUrl)
    }
})

const checkPaymentStatus = async (donationId) => {
    try {
        const response = await fetch(`/donation/${donationId}/status`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        
        const data = await response.json()
        
        if (data.success && data.status === 'completed') {
            showPaymentSuccessModal.value = true
            // Mettre à jour le montant des dons dans l'interface
            props.publication.donations_amount += data.donation.amount
        }
    } catch (error) {
        console.error('Erreur lors de la vérification du paiement:', error)
    }
}

const closePaymentSuccessModal = () => {
    showPaymentSuccessModal.value = false
}
</script>

<style scoped>
.title-font {
    font-family: 'Playfair Display', serif;
}

.publication-main-card {
    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    position: relative;
    overflow: hidden;
}

.publication-main-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.8s;
    z-index: 1;
}

.publication-main-card:hover::before {
    left: 100%;
}

.publication-badge {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.publication-badge::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    transition: left 0.6s;
}

.publication-badge:hover::after {
    left: 100%;
}

.emoji-reaction {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
}

.emoji-reaction:hover {
    transform: scale(1.15) translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.emoji-reaction:active {
    transform: scale(1.05);
}

.filter-chip {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.filter-chip:hover {
    transform: scale(1.05) translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.2);
}

.comment-box {
    transition: all 0.4s ease;
    background: linear-gradient(145deg, #ffffff 0%, #f9fafb 100%);
}

.comment-box:hover {
    transform: translateY(-3px);
    box-shadow: 
        0 20px 25px -5px rgba(0, 0, 0, 0.1), 
        0 10px 10px -5px rgba(0, 0, 0, 0.04),
        0 0 0 1px rgba(255, 255, 255, 0.5);
}

.donation-option:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.15);
}

.navigation-btn {
    transition: all 0.3s ease;
}

.navigation-btn:hover {
    transform: translateX(-3px);
    text-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
}

.publication-content {
    line-height: 1.8;
    text-align: justify;
}

.publication-content p {
    margin-bottom: 1.5rem;
    transition: color 0.3s ease;
}

.publication-content p:last-child {
    margin-bottom: 0;
}

.publication-content:hover p {
    color: #374151;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.6;
}

/* Animation d'entrée pour la carte principale */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.publication-main-card {
    animation: slideInUp 0.8s ease-out;
}

/* Amélioration des boutons de partage et signalement */
.share-report-btn {
    transition: all 0.3s ease;
}

.share-report-btn:hover {
    transform: scale(1.1) rotate(5deg);
    color: #ef4444 !important;
}

/* Amélioration des cartes de publications liées */
.related-publication-card {
    transition: all 0.4s ease;
    background: linear-gradient(145deg, #ffffff 0%, #f9fafb 100%);
}

.related-publication-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 
        0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(255, 255, 255, 0.5),
        0 0 15px rgba(239, 68, 68, 0.1);
}

/* Effet de focus amélioré pour l'accessibilité */
button:focus,
a:focus {
    outline: 2px solid #ef4444;
    outline-offset: 2px;
}

/* Animation de chargement des éléments */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.comment-section,
.related-publications {
    animation: fadeIn 1s ease-out 0.3s both;
}
</style>