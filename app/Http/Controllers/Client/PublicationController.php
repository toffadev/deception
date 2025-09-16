<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\Tag;
use App\Models\Donation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PublicationController extends Controller
{
    /**
     * Afficher la liste des publications publiques avec filtres
     */
    public function index(Request $request)
    {
        try {
            Log::info('PublicationController@index appelé', [
                'request_params' => $request->all(),
                'user_authenticated' => Auth::check()
            ]);

            $query = Publication::with(['user', 'tags'])
                ->where('status', 'published')
                ->orderBy('created_at', 'desc');

            // Filtres
            if ($request->filled('type') && $request->type !== 'Tous') {
                $typeMap = [
                    'Témoignages' => 'testimony',
                    'Poèmes' => 'poetry',
                    'Réflexions' => 'reflection'
                ];
                if (isset($typeMap[$request->type])) {
                    $query->where('type', $typeMap[$request->type]);
                }
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            }

            if ($request->filled('tags') && is_array($request->tags)) {
                $query->whereHas('tags', function ($q) use ($request) {
                    $q->whereIn('name', $request->tags);
                });
            }

            // Tri
            switch ($request->get('sort', 'recent')) {
                case 'popular':
                    $query->orderBy('views_count', 'desc');
                    break;
                case 'commented':
                    $query->orderBy('comments_count', 'desc');
                    break;
                case 'supported':
                    $query->orderBy('donations_amount', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            $publications = $query->paginate(12);

            // Transformer les données des publications
            $publications->getCollection()->transform(function ($publication) {
                // S'assurer que le slug existe
                if (empty($publication->slug)) {
                    $publication->slug = $publication->generateSlug();
                    $publication->save();
                }

                // Créer manuellement un array propre
                $data = [
                    'id' => $publication->id,
                    'title' => $publication->title,
                    'slug' => $publication->slug,
                    'type' => $publication->type,
                    'is_anonymous' => $publication->is_anonymous,
                    'status' => $publication->status,
                    'views_count' => $publication->views_count,
                    'comments_count' => $publication->comments_count,
                    'donations_amount' => $publication->donations_amount,
                    'created_at' => $publication->created_at,
                    'updated_at' => $publication->updated_at,
                ];

                // Nettoyer et ajouter le contenu
                $cleanContent = preg_replace('/[^\x20-\x7E\x{00A0}-\x{FFFF}]/u', '', $publication->content ?? '');
                $data['content'] = $cleanContent;

                // Créer l'excerpt en décodant d'abord les entités HTML puis en retirant les tags
                $decodedContent = html_entity_decode($cleanContent, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $data['excerpt'] = substr(strip_tags($decodedContent), 0, 200) . '...';

                // Ajouter l'auteur
                $data['author_name'] = $publication->getAuthorNameAttribute();

                // Tags simplifiés
                $data['tags'] = $publication->tags->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                        'color' => $tag->color ?? '#3B82F6'
                    ];
                })->toArray();

                return $data;
            });

            // Tags disponibles pour les filtres
            $availableTags = Tag::where('is_suggested', true)
                ->orWhere('usage_count', '>', 0)
                ->orderBy('usage_count', 'desc')
                ->limit(20)
                ->get();

            // Tags suggérés pour le formulaire
            $suggestedTags = Tag::where('is_suggested', true)
                ->orWhere('usage_count', '>', 5)
                ->orderBy('usage_count', 'desc')
                ->limit(15)
                ->pluck('name')
                ->toArray();

            Log::info('Données prêtes pour la vue', [
                'publications_count' => $publications->total(),
                'available_tags_count' => $availableTags->count(),
                'suggested_tags_count' => count($suggestedTags)
            ]);

            // Debug: Vérifier les données avant sérialisation
            try {
                $data = [
                    'publications' => $publications,
                    'availableTags' => $availableTags,
                    'suggestedTags' => $suggestedTags,
                    'filters' => $request->only(['type', 'search', 'tags', 'sort']),
                    'isAuthenticated' => Auth::check(),
                    'user' => Auth::user() ? [
                        'id' => Auth::user()->id,
                        'pseudo' => Auth::user()->pseudo,
                        'anonymous_by_default' => Auth::user()->anonymous_by_default
                    ] : null,
                ];

                // Test de sérialisation JSON
                $jsonTest = json_encode($data);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Erreur de sérialisation JSON:', [
                        'error' => json_last_error_msg(),
                        'publications_sample' => $publications->take(1)->toArray()
                    ]);
                }

                Log::info('Test sérialisation JSON réussi');
            } catch (\Exception $e) {
                Log::error('Erreur lors du test de sérialisation:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            return Inertia::render('Publication', [
                'publications' => $publications,
                'availableTags' => $availableTags,
                'suggestedTags' => $suggestedTags,
                'filters' => $request->only(['type', 'search', 'tags', 'sort']),
                'isAuthenticated' => Auth::check(),
                'user' => Auth::user() ? [
                    'id' => Auth::user()->id,
                    'pseudo' => Auth::user()->pseudo,
                    'anonymous_by_default' => Auth::user()->anonymous_by_default
                ] : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur dans PublicationController@index:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine()
            ]);

            // Retourner une page d'erreur ou des données par défaut
            return Inertia::render('Publication', [
                'publications' => ['data' => [], 'total' => 0, 'links' => []],
                'availableTags' => collect([]),
                'suggestedTags' => [],
                'filters' => [],
                'isAuthenticated' => Auth::check(),
                'user' => Auth::user() ? [
                    'id' => Auth::user()->id,
                    'pseudo' => Auth::user()->pseudo,
                    'anonymous_by_default' => Auth::user()->anonymous_by_default
                ] : null,
                'error' => 'Une erreur est survenue lors du chargement des publications.'
            ]);
        }
    }

    /**
     * Afficher une publication spécifique
     */
    public function show(Publication $publication)
    {
        try {
            // Vérifier que la publication est publiée
            if ($publication->status !== 'published') {
                abort(404);
            }

            // Charger toutes les relations nécessaires avec commentaires threadés
            $publication->load([
                'user',
                'tags',
                'comments' => function ($query) {
                    $query->with([
                        'user:id,pseudo,avatar',
                        'replies.user:id,pseudo,avatar',
                        'reactions' => function ($q) {
                            $q->select('reactable_id', 'reactable_type', 'type', 'user_id');
                        }
                    ])
                        ->topLevel()
                        ->published()
                        ->orderBy('created_at', 'desc');
                },
                'reactions' => function ($query) {
                    $query->select('reactable_id', 'reactable_type', 'type', 'user_id');
                },
                'donations' => function ($query) {
                    $query->completed()->latest()->limit(5);
                }
            ]);

            // Incrémenter le nombre de vues
            $publication->incrementViews();

            // Ajouter le nom de l'auteur
            $publication->author_name = $publication->getAuthorNameAttribute();

            // Formater les commentaires avec les réactions
            $this->formatCommentsWithReactions($publication->comments);

            // Statistiques des réactions par type
            $reactionStats = $this->getReactionStats($publication);

            // Réactions de l'utilisateur connecté
            $userReactions = [];
            if (Auth::check()) {
                $userReactions = $publication->reactions()
                    ->where('user_id', Auth::id())
                    ->pluck('type')
                    ->toArray();
            }

            // Publications similaires
            $relatedPublications = $this->getRelatedPublications($publication);

            return Inertia::render('PublicationDetail', [
                'publication' => $publication,
                'reactionStats' => $reactionStats,
                'userReactions' => $userReactions,
                'relatedPublications' => $relatedPublications,
                'isAuthenticated' => Auth::check(),
                'user' => Auth::user() ? [
                    'id' => Auth::user()->id,
                    'pseudo' => Auth::user()->pseudo,
                    'avatar' => Auth::user()->avatar,
                    'anonymous_by_default' => Auth::user()->anonymous_by_default
                ] : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de la publication:', [
                'publication_id' => $publication->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            abort(500, 'Une erreur est survenue lors du chargement de la publication.');
        }
    }

    /**
     * Stocker une nouvelle publication (pour les clients connectés)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:200',
            'type' => 'required|in:testimony,reflection,poetry',
            'is_anonymous' => 'boolean',
            'tags' => 'array|max:3',
            'tags.*' => 'string|max:50',
        ], [
            'title.required' => 'Le titre est requis.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'content.required' => 'Le contenu est requis.',
            'content.min' => 'Le contenu doit contenir au moins 200 caractères.',
            'type.required' => 'Le type de publication est requis.',
            'type.in' => 'Le type de publication doit être : témoignage, réflexion ou poésie.',
            'tags.max' => 'Vous ne pouvez sélectionner que 3 tags maximum.',
        ]);

        try {
            DB::beginTransaction();

            // Créer la publication avec le statut 'draft' pour modération
            $publication = Publication::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'content' => $validated['content'],
                'type' => $validated['type'],
                'is_anonymous' => $validated['is_anonymous'] ?? Auth::user()->anonymous_by_default ?? false,
                'status' => 'draft', // Les publications clients vont en modération
                'auto_tags' => $this->generateAutoTags($validated['content'], $validated['type']),
            ]);

            // Gérer les tags
            if (!empty($validated['tags'])) {
                $tagIds = [];
                foreach ($validated['tags'] as $tagName) {
                    $tag = Tag::firstOrCreate(
                        ['name' => trim($tagName)],
                        ['color' => '#3B82F6', 'is_suggested' => false]
                    );
                    $tag->incrementUsage();
                    $tagIds[] = $tag->id;
                }
                $publication->tags()->attach($tagIds);
            }

            DB::commit();

            return redirect()->back()->with(
                'success',
                'Votre publication a été soumise avec succès ! Elle sera examinée par notre équipe et publiée sous peu si elle respecte notre charte de bienveillance.'
            );
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la création de la publication client:', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la soumission de votre publication. Veuillez réessayer.'])
                ->withInput();
        }
    }

    /**
     * Récupérer les tags suggérés pour l'autocomplétion
     */
    public function getSuggestedTags()
    {
        try {
            $tags = Tag::where('is_suggested', true)
                ->orWhere('usage_count', '>', 5)
                ->orderBy('usage_count', 'desc')
                ->limit(30)
                ->pluck('name')
                ->toArray();

            return response()->json($tags);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des tags suggérés:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([]);
        }
    }

    /**
     * Générer des tags automatiques
     */
    private function generateAutoTags(string $content, string $type): array
    {
        $tags = [];

        // Ajouter le type comme tag
        $typeLabels = [
            'testimony' => 'témoignage',
            'reflection' => 'réflexion',
            'poetry' => 'poésie'
        ];
        $tags[] = $typeLabels[$type] ?? $type;

        // Mots-clés émotionnels et thématiques
        $keywords = [
            'rupture' => ['rupture', 'séparation', 'fin', 'cassé'],
            'douleur' => ['douleur', 'souffrance', 'mal', 'peine'],
            'espoir' => ['espoir', 'avenir', 'demain', 'lumière'],
            'amour' => ['amour', 'aimer', 'coeur', 'sentiment'],
            'solitude' => ['seul', 'solitude', 'isolé', 'abandonné'],
            'reconstruction' => ['reconstruction', 'guérison', 'renaître', 'nouveau'],
            'trahison' => ['trahison', 'mensonge', 'trompé', 'infidélité']
        ];

        $contentLower = strtolower($content);
        foreach ($keywords as $tag => $words) {
            foreach ($words as $word) {
                if (strpos($contentLower, $word) !== false) {
                    $tags[] = $tag;
                    break;
                }
            }
        }

        return array_slice(array_unique($tags), 0, 5);
    }

    /**
     * Formater les commentaires avec leurs réactions
     */
    private function formatCommentsWithReactions($comments)
    {
        foreach ($comments as $comment) {
            // Ajouter le nom d'auteur
            $comment->author_name = $comment->getAuthorNameAttribute();

            // Compter les réactions par type
            $reactionCounts = $comment->reactions->groupBy('type')->map->count();
            $comment->reaction_counts = $reactionCounts;

            // Réactions de l'utilisateur connecté
            if (Auth::check()) {
                $comment->user_reactions = $comment->reactions
                    ->where('user_id', Auth::id())
                    ->pluck('type')
                    ->toArray();
            } else {
                $comment->user_reactions = [];
            }

            // Formater les réponses récursivement
            if ($comment->replies) {
                $this->formatCommentsWithReactions($comment->replies);
            }
        }
    }

    /**
     * Obtenir les statistiques des réactions
     */
    private function getReactionStats(Publication $publication)
    {
        $stats = $publication->reactions->groupBy('type')->map->count();

        // Assurer que tous les types de réactions sont présents
        $allTypes = ['heart', 'cry', 'pray', 'thank_you', 'understand', 'courage'];
        $result = [];

        foreach ($allTypes as $type) {
            $result[$type] = $stats->get($type, 0);
        }

        return $result;
    }

    /**
     * Obtenir les publications similaires
     */
    private function getRelatedPublications(Publication $publication)
    {
        try {
            return Publication::with(['user', 'tags'])
                ->where('status', 'published')
                ->where('id', '!=', $publication->id)
                ->where(function ($query) use ($publication) {
                    // Publications du même type
                    $query->where('type', $publication->type)
                        // Ou avec des tags similaires
                        ->orWhereHas('tags', function ($q) use ($publication) {
                            $tagIds = $publication->tags->pluck('id');
                            if ($tagIds->isNotEmpty()) {
                                $q->whereIn('tags.id', $tagIds);
                            }
                        });
                })
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get()
                ->map(function ($pub) {
                    return [
                        'id' => $pub->id,
                        'slug' => $pub->slug,
                        'title' => $pub->title,
                        'excerpt' => substr(strip_tags(html_entity_decode($pub->content, ENT_QUOTES | ENT_HTML5, 'UTF-8')), 0, 150) . '...',
                        'type' => $pub->type,
                        'author_name' => $pub->getAuthorNameAttribute(),
                        'created_at' => $pub->created_at,
                        'views_count' => $pub->views_count,
                        'comments_count' => $pub->comments_count,
                        'tags' => $pub->tags->map(function ($tag) {
                            return [
                                'id' => $tag->id,
                                'name' => $tag->name,
                                'color' => $tag->color ?? '#3B82F6'
                            ];
                        })
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des publications similaires:', [
                'publication_id' => $publication->id,
                'message' => $e->getMessage()
            ]);
            return collect([]);
        }
    }

    /**
     * Ajouter un commentaire
     */
    public function storeComment(Request $request, Publication $publication)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:10|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
            'is_anonymous' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $comment = $publication->comments()->create([
                'user_id' => Auth::id(),
                'content' => $validated['content'],
                'parent_id' => $validated['parent_id'] ?? null,
                'is_anonymous' => $validated['is_anonymous'] ?? Auth::user()->anonymous_by_default ?? false,
                'status' => 'published' // Modération possible selon les règles
            ]);

            // Incrémenter le compteur de commentaires de la publication
            $publication->increment('comments_count');

            // Charger les relations pour la réponse
            $comment->load(['user:id,pseudo,avatar']);
            $comment->author_name = $comment->getAuthorNameAttribute();
            $comment->reaction_counts = [];
            $comment->user_reactions = [];

            DB::commit();

            return response()->json([
                'success' => true,
                'comment' => $comment,
                'message' => 'Commentaire ajouté avec succès!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'ajout du commentaire:', [
                'publication_id' => $publication->id,
                'user_id' => Auth::id(),
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'ajout du commentaire.'
            ], 500);
        }
    }

    /**
     * Toggle une réaction sur une publication ou un commentaire
     */
    public function toggleReaction(Request $request)
    {
        $validated = $request->validate([
            'reactable_type' => 'required|in:App\\Models\\Publication,App\\Models\\Comment',
            'reactable_id' => 'required|integer',
            'type' => 'required|in:heart,cry,pray,thank_you,understand,courage'
        ]);

        try {
            $reactableClass = $validated['reactable_type'];
            $reactable = $reactableClass::findOrFail($validated['reactable_id']);

            // Vérifier si l'utilisateur a déjà cette réaction
            $existingReaction = $reactable->reactions()
                ->where('user_id', Auth::id())
                ->where('type', $validated['type'])
                ->first();

            if ($existingReaction) {
                // Supprimer la réaction
                $existingReaction->delete();
                $reacted = false;
            } else {
                // Ajouter la réaction
                $reactable->reactions()->create([
                    'user_id' => Auth::id(),
                    'type' => $validated['type']
                ]);
                $reacted = true;
            }

            // Mettre à jour le compteur pour les publications
            if ($reactable instanceof Publication) {
                $reactable->update(['reactions_count' => $reactable->reactions()->count()]);
            }

            // Retourner les nouvelles statistiques
            $reactionStats = $reactable->reactions->groupBy('type')->map->count();

            return response()->json([
                'success' => true,
                'reacted' => $reacted,
                'reaction_stats' => $reactionStats,
                'message' => $reacted ? 'Réaction ajoutée!' : 'Réaction supprimée!'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du toggle de réaction:', [
                'user_id' => Auth::id(),
                'reactable_type' => $validated['reactable_type'] ?? null,
                'reactable_id' => $validated['reactable_id'] ?? null,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue.'
            ], 500);
        }
    }

    /**
     * Créer un don via Stripe
     */
    public function createDonation(Request $request, Publication $publication)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:500',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean'
        ]);

        try {
            // Configuration Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            DB::beginTransaction();

            // Créer l'enregistrement de don
            $donation = $publication->donations()->create([
                'user_id' => Auth::id(),
                'type' => 'blind_support', // Don pour soutenir l'auteur
                'amount' => $validated['amount'],
                'currency' => 'EUR',
                'frequency' => 'one_time',
                'status' => 'pending',
                'is_anonymous' => $validated['is_anonymous'] ?? false,
                'message' => $validated['message'] ?? null,
            ]);

            // Créer une session Stripe Checkout
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Don pour: ' . $publication->title,
                            'description' => 'Soutien à l\'auteur ' . $publication->getAuthorNameAttribute(),
                        ],
                        'unit_amount' => $validated['amount'] * 100, // Convertir en centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url("/publication/{$publication->slug}?payment_success=true&donation_id={$donation->id}"),
                'cancel_url' => url("/publication/{$publication->slug}?payment_cancelled=true"),
                'metadata' => [
                    'donation_id' => $donation->id,
                    'publication_id' => $publication->id,
                    'user_id' => Auth::id(),
                    'publication_title' => $publication->title
                ]
            ]);

            $donation->update(['stripe_payment_intent_id' => $checkoutSession->id]);

            DB::commit();

            return response()->json([
                'success' => true,
                'donation_id' => $donation->id,
                'checkout_url' => $checkoutSession->url,
                'session_id' => $checkoutSession->id,
                'message' => 'Session de paiement créée avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du don:', [
                'publication_id' => $publication->id,
                'user_id' => Auth::id(),
                'amount' => $validated['amount'] ?? null,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création du don.'
            ], 500);
        }
    }

    /**
     * Vérifier le statut d'un paiement après retour de Stripe
     */
    public function checkPaymentStatus(Request $request, $donationId)
    {
        try {
            $donation = Donation::findOrFail($donationId);

            // Vérifier le statut via Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $session = \Stripe\Checkout\Session::retrieve($donation->stripe_payment_intent_id);

            if ($session->payment_status === 'paid') {
                // Marquer le don comme complété
                $donation->update([
                    'status' => 'completed',
                    'processed_at' => now()
                ]);

                // Mettre à jour le montant total de la publication
                $donation->publication->increment('donations_amount', $donation->amount);

                return response()->json([
                    'success' => true,
                    'status' => 'completed',
                    'donation' => $donation->load('publication', 'user')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'status' => $session->payment_status,
                    'message' => 'Paiement non confirmé'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification du paiement:', [
                'donation_id' => $donationId,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification du paiement'
            ], 500);
        }
    }

    /**
     * Webhook Stripe pour confirmation automatique des paiements
     */
    public function stripeWebhook(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook.secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Webhook Stripe - Payload invalide:', ['error' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Webhook Stripe - Signature invalide:', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        // Gérer les événements Stripe
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            // Trouver le don correspondant
            $donation = Donation::where('stripe_payment_intent_id', $session->id)->first();

            if ($donation && $donation->status === 'pending') {
                DB::beginTransaction();
                try {
                    // Marquer le don comme complété
                    $donation->update([
                        'status' => 'completed',
                        'processed_at' => now()
                    ]);

                    // Mettre à jour le montant total de la publication
                    $donation->publication->increment('donations_amount', $donation->amount);

                    DB::commit();

                    Log::info('Don confirmé via webhook:', [
                        'donation_id' => $donation->id,
                        'amount' => $donation->amount,
                        'publication_id' => $donation->publication_id
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Erreur lors de la confirmation du don via webhook:', [
                        'donation_id' => $donation->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return response('Webhook reçu', 200);
    }

    /**
     * Créer un signalement
     */
    public function createReport(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => 'required|string|in:App\\Models\\Publication,App\\Models\\Comment',
            'reportable_id' => 'required|integer',
            'reason' => 'required|string|in:inappropriate_content,harassment,spam,violence,hate_speech,misinformation,other',
            'description' => 'required|string|max:1000'
        ]);

        try {
            // Vérifier que l'objet à signaler existe
            $reportableClass = $validated['reportable_type'];
            $reportable = $reportableClass::find($validated['reportable_id']);

            if (!$reportable) {
                return response()->json([
                    'success' => false,
                    'message' => 'L\'élément à signaler n\'existe pas.'
                ], 404);
            }

            // Vérifier si l'utilisateur n'a pas déjà signalé cet élément
            $existingReport = \App\Models\Report::where([
                'reporter_id' => Auth::id(),
                'reportable_type' => $validated['reportable_type'],
                'reportable_id' => $validated['reportable_id']
            ])->first();

            if ($existingReport) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous avez déjà signalé cet élément.'
                ], 409);
            }

            // Créer le signalement
            $report = \App\Models\Report::create([
                'reporter_id' => Auth::id(),
                'reportable_type' => $validated['reportable_type'],
                'reportable_id' => $validated['reportable_id'],
                'reason' => $validated['reason'],
                'description' => $validated['description'],
                'status' => 'pending'
            ]);

            Log::info('Nouveau signalement créé:', [
                'report_id' => $report->id,
                'reporter_id' => Auth::id(),
                'reportable_type' => $validated['reportable_type'],
                'reportable_id' => $validated['reportable_id'],
                'reason' => $validated['reason']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Votre signalement a été enregistré avec succès. Notre équipe de modération l\'examinera dans les plus brefs délais.',
                'report_id' => $report->id
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du signalement:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enregistrement de votre signalement. Veuillez réessayer.'
            ], 500);
        }
    }
}
