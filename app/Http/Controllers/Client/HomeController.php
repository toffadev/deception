<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\SolidarityProject;
use App\Models\Partner;
use App\Models\Donation;
use App\Models\User;
use App\Models\ProjectMedia;
use App\Models\VisuallyImpairedPerson;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil avec toutes les données dynamiques
     */
    public function index()
    {
        try {
            // Cache des données pour optimiser les performances (10 minutes)
            $homeData = Cache::remember('home_page_data', 600, function () {
                return [
                    'featuredPublications' => $this->getFeaturedPublications(),
                    'solidarityProjects' => $this->getSolidarityProjectsPreview(),
                    'partners' => $this->getPartners(),
                    'globalStats' => $this->getGlobalStats(),
                    'projectMedia' => $this->getProjectMediaPreview(),
                    'recentDonations' => $this->getRecentDonations(),
                    'visuallyImpairedStats' => $this->getVisuallyImpairedStats(),
                ];
            });

            return Inertia::render('Home', [
                'featuredPublications' => $homeData['featuredPublications'],
                'solidarityProjects' => $homeData['solidarityProjects'],
                'partners' => $homeData['partners'],
                'globalStats' => $homeData['globalStats'],
                'projectMedia' => $homeData['projectMedia'],
                'recentDonations' => $homeData['recentDonations'],
                'visuallyImpairedStats' => $homeData['visuallyImpairedStats'],
                'isAuthenticated' => Auth::check(),
                'user' => Auth::user() ? [
                    'id' => Auth::user()->id,
                    'pseudo' => Auth::user()->pseudo,
                    'avatar' => Auth::user()->avatar,
                ] : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de la page d\'accueil:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Retourner des données par défaut en cas d'erreur
            return Inertia::render('Home', [
                'featuredPublications' => [],
                'solidarityProjects' => [],
                'partners' => [],
                'globalStats' => $this->getDefaultStats(),
                'projectMedia' => [],
                'recentDonations' => [],
                'isAuthenticated' => Auth::check(),
                'user' => Auth::user() ? [
                    'id' => Auth::user()->id,
                    'pseudo' => Auth::user()->pseudo,
                    'avatar' => Auth::user()->avatar,
                ] : null,
                'error' => 'Une erreur est survenue lors du chargement des données.'
            ]);
        }
    }

    /**
     * Obtenir les publications mises en avant pour la page d'accueil
     */
    private function getFeaturedPublications()
    {
        try {
            return Publication::with(['user', 'tags'])
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function ($publication) {
                    // S'assurer que le slug existe
                    if (empty($publication->slug)) {
                        $publication->slug = $publication->generateSlug();
                        $publication->save();
                    }

                    return [
                        'id' => $publication->id,
                        'title' => $publication->title,
                        'slug' => $publication->slug,
                        'type' => $publication->type,
                        'excerpt' => substr(strip_tags(html_entity_decode($publication->content, ENT_QUOTES | ENT_HTML5, 'UTF-8')), 0, 150) . '...',
                        'author_name' => $publication->getAuthorNameAttribute(),
                        'created_at' => $publication->created_at,
                        'views_count' => $publication->views_count,
                        'comments_count' => $publication->comments_count,
                        'donations_amount' => $publication->donations_amount,
                        'reactions_count' => $publication->reactions_count,
                        'tags' => $publication->tags->map(function ($tag) {
                            return [
                                'id' => $tag->id,
                                'name' => $tag->name,
                                'color' => $tag->color ?? '#3B82F6'
                            ];
                        })->toArray(),
                        'gradient_class' => $this->getGradientClass($publication->type),
                        'icon_color' => $this->getIconColor($publication->type),
                        'type_color' => $this->getTypeColor($publication->type),
                        'type_label' => $this->getTypeLabel($publication->type),
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des publications mises en avant:', [
                'message' => $e->getMessage()
            ]);
            return collect([]);
        }
    }

    /**
     * Obtenir un aperçu des projets de solidarité
     */
    private function getSolidarityProjectsPreview()
    {
        try {
            return SolidarityProject::with(['media' => function ($query) {
                $query->ordered()->limit(4);
            }])
                ->active()
                ->featured()
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->get()
                ->map(function ($project) {
                    return [
                        'id' => $project->id,
                        'title' => $project->title,
                        'description' => $project->description,
                        'target_amount' => $project->target_amount,
                        'current_amount' => $project->current_amount,
                        'currency' => $project->currency,
                        'progress_percentage' => $project->progress_percentage,
                        'remaining_amount' => $project->remaining_amount,
                        'is_completed' => $project->isCompleted(),
                        'featured_image_url' => $project->featured_image_path ? Storage::url($project->featured_image_path) : null,
                        'beneficiaries_info' => $project->beneficiaries_info,
                        'impact_description' => $project->impact_description,
                        'start_date' => $project->start_date,
                        'end_date' => $project->end_date,
                        'media' => $project->media->map(function ($media) {
                            return [
                                'id' => $media->id,
                                'type' => $media->type,
                                'file_url' => Storage::url($media->file_path),
                                'title' => $media->title,
                                'description' => $media->description,
                                'alt_text' => $media->alt_text,
                            ];
                        })
                    ];
                })->first();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des projets solidaires:', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Obtenir les partenaires pour la page d'accueil
     */
    private function getPartners()
    {
        try {
            return Partner::active()
                ->ordered()
                ->limit(8)
                ->get()
                ->map(function ($partner) {
                    return [
                        'id' => $partner->id,
                        'name' => $partner->name,
                        'description' => $partner->description,
                        'logo_url' => $partner->logo_path ? Storage::url($partner->logo_path) : null,
                        'website_url' => $partner->website_url,
                        'category' => $partner->category,
                        'category_label' => $partner->category_label,
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des partenaires:', [
                'message' => $e->getMessage()
            ]);
            return collect([]);
        }
    }

    /**
     * Obtenir les statistiques globales de la plateforme
     */
    private function getGlobalStats()
    {
        try {
            // Publications
            $totalPublications = Publication::where('status', 'published')->count();
            $totalViews = Publication::where('status', 'published')->sum('views_count');
            $totalComments = Publication::where('status', 'published')->sum('comments_count');

            // Utilisateurs
            $totalUsers = User::where('role', 'client')->count();

            // Dons et projets solidaires
            $totalDonations = Donation::where('status', 'completed')->sum('amount');
            $activeProjects = SolidarityProject::active()->count();
            $completedProjects = SolidarityProject::whereColumn('current_amount', '>=', 'target_amount')->count();

            // Calcul des bénéficiaires aidés
            $beneficiariesCount = SolidarityProject::active()
                ->get()
                ->sum(function ($project) {
                    $beneficiaries = $project->beneficiaries_info;
                    return is_array($beneficiaries) && isset($beneficiaries['count']) ? $beneficiaries['count'] : 0;
                });

            // Objectif annuel et pourcentage
            $annualGoal = 75000; // 75,000€ objectif annuel
            $currentYear = now()->year;
            $currentYearDonations = Donation::where('status', 'completed')
                ->whereYear('created_at', $currentYear)
                ->sum('amount');

            $goalProgress = $annualGoal > 0 ? min(100, ($currentYearDonations / $annualGoal) * 100) : 0;

            return [
                'total_publications' => $totalPublications,
                'total_views' => $totalViews,
                'total_comments' => $totalComments,
                'total_users' => $totalUsers,
                'total_donations' => $totalDonations,
                'active_projects' => $activeProjects,
                'completed_projects' => $completedProjects,
                'people_helped' => $beneficiariesCount > 0 ? $beneficiariesCount : 0, // Fallback
                'annual_goal' => $annualGoal,
                'current_year_donations' => $currentYearDonations,
                'goal_progress' => $goalProgress,
                'efficiency_rate' => 85, // Taux d'efficacité (85% des fonds vont aux bénéficiaires)
                'countries_helped' => 5, // Nombre de pays aidés
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors du calcul des statistiques globales:', [
                'message' => $e->getMessage()
            ]);
            return $this->getDefaultStats();
        }
    }

    /**
     * Obtenir un aperçu des médias de projet
     */
    private function getProjectMediaPreview()
    {
        try {
            return ProjectMedia::with('solidarityProject')
                ->whereHas('solidarityProject', function ($query) {
                    $query->active();
                })
                ->ordered()
                ->limit(4)
                ->get()
                ->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'type' => $media->type,
                        'file_url' => Storage::url($media->file_path),
                        'title' => $media->title,
                        'description' => $media->description,
                        'alt_text' => $media->alt_text,
                        'project_title' => $media->solidarityProject->title,
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des médias de projet:', [
                'message' => $e->getMessage()
            ]);
            return collect([]);
        }
    }

    /**
     * Obtenir les dons récents pour afficher l'activité
     */
    private function getRecentDonations()
    {
        try {
            return Donation::with(['user', 'publication'])
                ->where('status', 'completed')
                ->where('is_anonymous', false)
                ->orderBy('processed_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($donation) {
                    return [
                        'id' => $donation->id,
                        'amount' => $donation->amount,
                        'currency' => $donation->currency,
                        'donor_name' => $donation->getDonorNameAttribute(),
                        'type' => $donation->type,
                        'processed_at' => $donation->processed_at,
                        'publication_title' => $donation->publication ? $donation->publication->title : null,
                        'message' => $donation->message,
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des dons récents:', [
                'message' => $e->getMessage()
            ]);
            return collect([]);
        }
    }

    /**
     * Statistiques par défaut en cas d'erreur
     */
    private function getDefaultStats()
    {
        return [
            'total_publications' => 0,
            'total_views' => 0,
            'total_comments' => 0,
            'total_users' => 0,
            'total_donations' => 0,
            'active_projects' => 0,
            'completed_projects' => 0,
            'people_helped' => 50,
            'annual_goal' => 75000,
            'current_year_donations' => 0,
            'goal_progress' => 0,
            'efficiency_rate' => 85,
            'countries_helped' => 15,
        ];
    }

    /**
     * Obtenir la classe CSS de gradient selon le type de publication
     */
    private function getGradientClass($type)
    {
        $classes = [
            'testimony' => 'bg-gradient-to-r from-pink-100 to-red-100',
            'poetry' => 'bg-gradient-to-r from-purple-100 to-blue-100',
            'reflection' => 'bg-gradient-to-r from-yellow-100 to-orange-100'
        ];
        return $classes[$type] ?? 'bg-gradient-to-r from-gray-100 to-gray-200';
    }

    /**
     * Obtenir la couleur d'icône selon le type de publication
     */
    private function getIconColor($type)
    {
        $colors = [
            'testimony' => 'text-red-300',
            'poetry' => 'text-blue-300',
            'reflection' => 'text-orange-300'
        ];
        return $colors[$type] ?? 'text-gray-300';
    }

    /**
     * Obtenir la couleur de type selon le type de publication
     */
    private function getTypeColor($type)
    {
        $colors = [
            'testimony' => 'text-red-500',
            'poetry' => 'text-blue-500',
            'reflection' => 'text-orange-500'
        ];
        return $colors[$type] ?? 'text-gray-500';
    }

    /**
     * Obtenir le libellé du type de publication
     */
    private function getTypeLabel($type)
    {
        $labels = [
            'testimony' => 'Témoignage',
            'poetry' => 'Poème',
            'reflection' => 'Réflexion'
        ];
        return $labels[$type] ?? $type;
    }

    /**
     * Obtenir les statistiques des personnes malvoyantes
     */
    private function getVisuallyImpairedStats()
    {
        try {
            return [
                'total_personnes' => VisuallyImpairedPerson::active()->count(),
                'hommes' => VisuallyImpairedPerson::active()->bySexe('M')->count(),
                'femmes' => VisuallyImpairedPerson::active()->bySexe('F')->count(),
                'en_traitement' => VisuallyImpairedPerson::active()->where('traitement_en_cours', true)->count(),
                'derniere_mise_a_jour' => VisuallyImpairedPerson::active()->latest('updated_at')->first()?->updated_at,
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques malvoyants sur Home:', [
                'message' => $e->getMessage()
            ]);
            return [
                'total_personnes' => 0,
                'hommes' => 0,
                'femmes' => 0,
                'en_traitement' => 0,
                'derniere_mise_a_jour' => null,
            ];
        }
    }

    /**
     * Vider le cache de la page d'accueil (pour l'administration)
     */
    public function clearCache()
    {
        Cache::forget('home_page_data');
        return response()->json(['success' => true, 'message' => 'Cache de la page d\'accueil vidé']);
    }
}
