<template>
    <div class="space-y-6">
        <!-- Formulaire d'ajout de commentaire principal -->
        <div v-if="!isReply && isAuthenticated" class="mb-8 bg-gray-50 p-6 rounded-lg">
            <h3 class="font-bold text-gray-800 mb-4">Ajouter un commentaire</h3>
            <form @submit.prevent="submitComment">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Publier en tant que :</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="inline-flex items-center">
                            <input v-model="commentForm.is_anonymous" :value="false" type="radio" class="form-radio text-red-500">
                            <span class="ml-2">{{ user?.pseudo || 'Utilisateur' }}</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input v-model="commentForm.is_anonymous" :value="true" type="radio" class="form-radio text-red-500">
                            <span class="ml-2">Anonyme</span>
                        </label>
                    </div>
                </div>
                <textarea 
                    v-model="commentForm.content"
                    rows="4" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent mb-4" 
                    placeholder="Votre commentaire..."
                    required
                    minlength="10"
                    maxlength="1000"
                ></textarea>
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i> Soyez bienveillant et respectueux
                    </div>
                    <button 
                        type="submit"
                        :disabled="!commentForm.content.trim() || commentForm.processing"
                        class="bg-red-500 hover:bg-red-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-6 py-2 rounded-full font-medium transition duration-300"
                    >
                        <span v-if="commentForm.processing">
                            <svg class="animate-spin h-4 w-4 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Publication...
                        </span>
                        <span v-else>Publier</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des commentaires -->
        <div class="space-y-6">
            <div 
                v-for="comment in comments" 
                :key="comment.id"
                class="comment-box bg-gray-50 p-6 rounded-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-md"
            >
                <div class="flex items-start mb-4">
                    <div class="w-10 h-10 bg-gray-200 rounded-full mr-4 overflow-hidden flex items-center justify-center">
                        <img v-if="comment.user?.avatar" :src="comment.user.avatar" :alt="comment.author_name" class="w-full h-full object-cover">
                        <i v-else class="fas fa-user text-gray-400"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-800">{{ comment.author_name }}</h4>
                                <p class="text-xs text-gray-500">{{ formatRelativeTime(comment.created_at) }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <!-- Boutons de réaction pour le commentaire -->
                                <button 
                                    v-for="(emoji, reactionType) in reactionEmojis" 
                                    :key="reactionType"
                                    @click="toggleCommentReaction(comment, reactionType)"
                                    :class="[
                                        'text-sm px-2 py-1 rounded-full transition-all duration-200',
                                        comment.user_reactions?.includes(reactionType) 
                                            ? 'bg-red-100 text-red-600' 
                                            : 'bg-gray-100 hover:bg-red-100 hover:text-red-600'
                                    ]"
                                    :title="getReactionLabel(reactionType)"
                                >
                                    {{ emoji }}
                                    <span v-if="comment.reaction_counts?.[reactionType] > 0" class="ml-1">
                                        {{ comment.reaction_counts[reactionType] }}
                                    </span>
                                </button>
                            </div>
                        </div>
                        <p class="text-gray-700 mt-2">{{ comment.content }}</p>
                        <div class="flex items-center mt-3 text-sm text-gray-500">
                            <button 
                                v-if="isAuthenticated"
                                @click="toggleReply(comment.id)"
                                class="flex items-center hover:text-gray-700 transition-colors duration-200"
                            >
                                <i class="fas fa-reply mr-1"></i> Répondre
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Réponses -->
                <div v-if="comment.replies && comment.replies.length > 0" class="pl-14 mt-4 space-y-4">
                    <div 
                        v-for="reply in comment.replies" 
                        :key="reply.id"
                        class="bg-white p-4 rounded-lg border border-gray-200"
                    >
                        <div class="flex items-start mb-2">
                            <div class="w-8 h-8 bg-gray-200 rounded-full mr-3 overflow-hidden flex items-center justify-center">
                                <img v-if="reply.user?.avatar" :src="reply.user.avatar" :alt="reply.author_name" class="w-full h-full object-cover">
                                <i v-else class="fas fa-user text-gray-400"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-sm">{{ reply.author_name }}</h4>
                                        <p class="text-xs text-gray-500">{{ formatRelativeTime(reply.created_at) }}</p>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <!-- Boutons de réaction pour les réponses -->
                                        <button 
                                            v-for="(emoji, reactionType) in reactionEmojis" 
                                            :key="reactionType"
                                            @click="toggleCommentReaction(reply, reactionType)"
                                            :class="[
                                                'text-xs px-1 py-1 rounded transition-all duration-200',
                                                reply.user_reactions?.includes(reactionType) 
                                                    ? 'bg-red-100 text-red-600' 
                                                    : 'bg-gray-100 hover:bg-red-100 hover:text-red-600'
                                            ]"
                                            :title="getReactionLabel(reactionType)"
                                        >
                                            {{ emoji }}
                                            <span v-if="reply.reaction_counts?.[reactionType] > 0" class="ml-1">
                                                {{ reply.reaction_counts[reactionType] }}
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-gray-700 mt-1 text-sm">{{ reply.content }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Formulaire de réponse -->
                <div v-if="showReplyForm === comment.id && isAuthenticated" class="pl-14 mt-4">
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <form @submit.prevent="submitReply(comment.id)">
                            <div class="mb-3">
                                <label class="block text-gray-700 font-medium mb-2 text-sm">Répondre en tant que :</label>
                                <div class="flex flex-wrap gap-3">
                                    <label class="inline-flex items-center text-sm">
                                        <input v-model="replyForm.is_anonymous" :value="false" type="radio" class="form-radio text-red-500">
                                        <span class="ml-1">{{ user?.pseudo || 'Utilisateur' }}</span>
                                    </label>
                                    <label class="inline-flex items-center text-sm">
                                        <input v-model="replyForm.is_anonymous" :value="true" type="radio" class="form-radio text-red-500">
                                        <span class="ml-1">Anonyme</span>
                                    </label>
                                </div>
                            </div>
                            <textarea 
                                v-model="replyForm.content"
                                rows="3" 
                                class="w-full px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-transparent mb-3" 
                                placeholder="Votre réponse..."
                                required
                                minlength="10"
                                maxlength="1000"
                            ></textarea>
                            <div class="flex justify-end space-x-2">
                                <button 
                                    type="button"
                                    @click="cancelReply"
                                    class="text-gray-500 hover:text-gray-700 px-3 py-1 text-sm transition-colors duration-200"
                                >
                                    Annuler
                                </button>
                                <button 
                                    type="submit"
                                    :disabled="!replyForm.content.trim() || replyForm.processing"
                                    class="bg-red-500 hover:bg-red-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-4 py-1 rounded text-sm transition duration-300"
                                >
                                    <span v-if="replyForm.processing">
                                        <svg class="animate-spin h-3 w-3 inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                    Répondre
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bouton charger plus -->
        <div v-if="hasMoreComments" class="mt-8 text-center">
            <button 
                @click="$emit('load-more')"
                class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-full font-medium transition duration-300"
            >
                <i class="fas fa-arrow-down mr-2"></i> Charger plus de commentaires
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    comments: Array,
    publicationId: Number,
    isAuthenticated: Boolean,
    user: Object,
    hasMoreComments: Boolean,
    isReply: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['comment-added', 'load-more'])

// État local
const showReplyForm = ref(null)

const commentForm = reactive({
    content: '',
    is_anonymous: props.user?.anonymous_by_default ?? false,
    processing: false
})

const replyForm = reactive({
    content: '',
    is_anonymous: props.user?.anonymous_by_default ?? false,
    processing: false
})

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

const getReactionLabel = (type) => {
    return reactionLabels[type] || type
}

const submitComment = async () => {
    if (!commentForm.content.trim()) return
    
    commentForm.processing = true
    
    try {
        const response = await fetch(`/publication/${props.publicationId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                content: commentForm.content.trim(),
                is_anonymous: commentForm.is_anonymous
            })
        })
        
        const data = await response.json()
        
        if (data.success) {
            // Ajouter le commentaire à la liste
            emit('comment-added', data.comment)
            
            // Reset du formulaire
            commentForm.content = ''
            
            // Afficher un message de succès
            // Vous pouvez utiliser une notification toast ici
            console.log(data.message)
        } else {
            console.error('Erreur:', data.message)
        }
    } catch (error) {
        console.error('Erreur lors de l\'ajout du commentaire:', error)
    } finally {
        commentForm.processing = false
    }
}

const toggleReply = (commentId) => {
    showReplyForm.value = showReplyForm.value === commentId ? null : commentId
    replyForm.content = ''
}

const cancelReply = () => {
    showReplyForm.value = null
    replyForm.content = ''
}

const submitReply = async (parentId) => {
    if (!replyForm.content.trim()) return
    
    replyForm.processing = true
    
    try {
        const response = await fetch(`/publication/${props.publicationId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                content: replyForm.content.trim(),
                parent_id: parentId,
                is_anonymous: replyForm.is_anonymous
            })
        })
        
        const data = await response.json()
        
        if (data.success) {
            // Ajouter la réponse au commentaire parent
            const parentComment = props.comments.find(c => c.id === parentId)
            if (parentComment) {
                if (!parentComment.replies) {
                    parentComment.replies = []
                }
                parentComment.replies.push(data.comment)
            }
            
            // Reset du formulaire
            cancelReply()
            
            console.log(data.message)
        } else {
            console.error('Erreur:', data.message)
        }
    } catch (error) {
        console.error('Erreur lors de l\'ajout de la réponse:', error)
    } finally {
        replyForm.processing = false
    }
}

const toggleCommentReaction = async (comment, reactionType) => {
    if (!props.isAuthenticated) {
        // Rediriger vers la page de connexion
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
                reactable_type: 'App\\Models\\Comment',
                reactable_id: comment.id,
                type: reactionType
            })
        })
        
        const data = await response.json()
        
        if (data.success) {
            // Mettre à jour les réactions du commentaire
            if (!comment.reaction_counts) comment.reaction_counts = {}
            if (!comment.user_reactions) comment.user_reactions = []
            
            // Mettre à jour les statistiques
            Object.keys(data.reaction_stats).forEach(type => {
                comment.reaction_counts[type] = data.reaction_stats[type]
            })
            
            // Mettre à jour les réactions de l'utilisateur
            if (data.reacted) {
                if (!comment.user_reactions.includes(reactionType)) {
                    comment.user_reactions.push(reactionType)
                }
            } else {
                const index = comment.user_reactions.indexOf(reactionType)
                if (index > -1) {
                    comment.user_reactions.splice(index, 1)
                }
            }
        }
    } catch (error) {
        console.error('Erreur lors du toggle de réaction:', error)
    }
}
</script>

<style scoped>
.comment-box {
    transition: all 0.3s ease;
}

.comment-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}
</style>