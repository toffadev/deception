<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SolidarityProject;
use App\Models\Partner;
use App\Models\FinancialReport;
use App\Models\ProjectMedia;
use App\Models\Donation;
use App\Models\VisuallyImpairedPerson;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SolidarityController extends Controller
{
    /**
     * Afficher la page de solidarité avec toutes les données dynamiques
     */
    public function index()
    {
        // 1. Récupérer les projets de solidarité actifs avec leurs médias
        $solidarityProjects = SolidarityProject::with(['media' => function ($query) {
            $query->ordered();
        }])
            ->active()
            ->featured()
            ->orderBy('created_at', 'desc')
            ->limit(6)
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
            });

        // 2. Récupérer toutes les galeries média pour la section galerie
        $allProjectMedia = ProjectMedia::with('solidarityProject')
            ->whereHas('solidarityProject', function ($query) {
                $query->active();
            })
            ->ordered()
            ->limit(12)
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

        // 3. Récupérer les partenaires actifs
        $partners = Partner::active()
            ->ordered()
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



        // 4. Récupérer le dernier rapport financier publié
        $latestFinancialReport = FinancialReport::published()
            ->orderBy('period_end', 'desc')
            ->first();

        $financialReportData = null;
        if ($latestFinancialReport) {
            $financialReportData = [
                'id' => $latestFinancialReport->id,
                'title' => $latestFinancialReport->title,
                'description' => $latestFinancialReport->description,
                'period_type' => $latestFinancialReport->period_type,
                'period_start' => $latestFinancialReport->period_start,
                'period_end' => $latestFinancialReport->period_end,
                'total_donations' => $latestFinancialReport->total_donations,
                'total_expenses' => $latestFinancialReport->total_expenses,
                'administrative_costs' => $latestFinancialReport->administrative_costs,
                'net_amount' => $latestFinancialReport->net_amount,
                'efficiency_rate' => $latestFinancialReport->efficiency_rate,
                'breakdown' => $latestFinancialReport->breakdown,
                'report_file_url' => $latestFinancialReport->report_file_path ? Storage::url($latestFinancialReport->report_file_path) : null,
                'published_at' => $latestFinancialReport->published_at,
            ];
        }

        // 5. Récupérer tous les rapports financiers publiés pour la section historique
        $allFinancialReports = FinancialReport::published()
            ->orderBy('period_end', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'title' => $report->title,
                    'period_type' => $report->period_type,
                    'period_start' => $report->period_start,
                    'period_end' => $report->period_end,
                    'total_donations' => $report->total_donations,
                    'total_expenses' => $report->total_expenses,
                    'efficiency_rate' => $report->efficiency_rate,
                    'report_file_url' => $report->report_file_path ? Storage::url($report->report_file_path) : null,
                ];
            });

        // 6. Calculs statistiques globaux
        $totalDonations = SolidarityProject::sum('current_amount');
        $activeProjectsCount = SolidarityProject::active()->count();
        $completedProjectsCount = SolidarityProject::whereColumn('current_amount', '>=', 'target_amount')->count();
        $beneficiariesCount = SolidarityProject::active()
            ->get()
            ->sum(function ($project) {
                $beneficiaries = $project->beneficiaries_info;
                return is_array($beneficiaries) && isset($beneficiaries['count']) ? $beneficiaries['count'] : 0;
            });

        $globalStats = [
            'total_donations' => $totalDonations,
            'people_helped' => $beneficiariesCount > 0 ? $beneficiariesCount : 0, // Fallback pour l'affichage
            'active_projects' => $activeProjectsCount,
            'completed_projects' => $completedProjectsCount,
        ];

        // 7. Statistiques des personnes malvoyantes pour l'encart
        $visuallyImpairedStats = [
            'total_personnes' => VisuallyImpairedPerson::active()->count(),
            'hommes' => VisuallyImpairedPerson::active()->bySexe('M')->count(),
            'femmes' => VisuallyImpairedPerson::active()->bySexe('F')->count(),
            'en_traitement' => VisuallyImpairedPerson::active()->where('traitement_en_cours', true)->count(),
            'derniere_mise_a_jour' => VisuallyImpairedPerson::active()->latest('updated_at')->first()?->updated_at,
        ];

        return Inertia::render('Solidarity', [
            'solidarityProjects' => $solidarityProjects,
            'projectMedia' => $allProjectMedia,
            'partners' => $partners,
            'latestFinancialReport' => $financialReportData,
            'allFinancialReports' => $allFinancialReports,
            'globalStats' => $globalStats,
            'visuallyImpairedStats' => $visuallyImpairedStats,
            'isAuthenticated' => Auth::check(),
            'user' => Auth::user() ? [
                'id' => Auth::user()->id,
                'pseudo' => Auth::user()->pseudo
            ] : null,
        ]);
    }

    /**
     * Récupérer plus de médias pour la galerie (pagination AJAX)
     */
    public function getMoreMedia(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 12);
        $type = $request->get('type', null); // 'image' ou 'video'

        $query = ProjectMedia::with('solidarityProject')
            ->whereHas('solidarityProject', function ($q) {
                $q->active();
            })
            ->ordered()
            ->skip($offset)
            ->take($limit);

        if ($type) {
            $query->where('type', $type);
        }

        $media = $query->get()->map(function ($media) {
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

        return response()->json([
            'media' => $media,
            'hasMore' => $media->count() === $limit
        ]);
    }

    /**
     * Récupérer les détails d'un projet spécifique
     */
    public function getProjectDetails(SolidarityProject $project)
    {
        $project->load(['media' => function ($query) {
            $query->ordered();
        }]);

        return response()->json([
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
            'status' => $project->status,
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
        ]);
    }

    /**
     * Créer un don solidaire via Stripe
     */
    public function createDonation(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:500',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean',
            'project_id' => 'nullable|exists:solidarity_projects,id'
        ]);

        try {
            // Configuration Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            DB::beginTransaction();

            // Créer l'enregistrement de don solidaire
            $donation = Donation::create([
                'user_id' => Auth::id(),
                'type' => 'platform', // Don pour la plateforme/projets solidaires
                'publication_id' => null, // Pas de publication associée
                'amount' => $validated['amount'],
                'currency' => 'EUR',
                'frequency' => 'one_time',
                'status' => 'pending',
                'is_anonymous' => $validated['is_anonymous'] ?? false,
                'message' => $validated['message'] ?? null,
            ]);

            // Déterminer la description selon le projet
            $description = 'Don solidaire pour les malvoyants';
            if (isset($validated['project_id']) && $validated['project_id']) {
                $project = SolidarityProject::find($validated['project_id']);
                if ($project) {
                    $description = 'Don pour le projet: ' . $project->title;
                }
            }

            // Créer une session Stripe Checkout
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $description,
                            'description' => 'Soutien aux projets solidaires pour les malvoyants au Bénin',
                        ],
                        'unit_amount' => $validated['amount'] * 100, // Convertir en centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url("/solidarity?payment_success=true&donation_id={$donation->id}"),
                'cancel_url' => url("/solidarity?payment_cancelled=true"),
                'metadata' => [
                    'donation_id' => $donation->id,
                    'project_id' => $validated['project_id'] ?? '',
                    'user_id' => Auth::id(),
                    'type' => 'solidarity'
                ]
            ]);

            $donation->update(['stripe_payment_intent_id' => $checkoutSession->id]);

            DB::commit();

            return response()->json([
                'success' => true,
                'donation_id' => $donation->id,
                'checkout_url' => $checkoutSession->url,
                'session_id' => $checkoutSession->id,
                'message' => 'Session de paiement solidaire créée avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du don solidaire:', [
                'user_id' => Auth::id(),
                'amount' => $validated['amount'] ?? null,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création du don solidaire.'
            ], 500);
        }
    }

    /**
     * Vérifier le statut d'un don solidaire après paiement Stripe
     */
    public function checkPaymentStatus(Request $request, $donationId)
    {
        try {
            $donation = Donation::findOrFail($donationId);

            // Vérifier que c'est bien un don solidaire
            if ($donation->type !== 'platform') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce don n\'est pas un don solidaire'
                ], 400);
            }

            // Vérifier le statut via Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $session = \Stripe\Checkout\Session::retrieve($donation->stripe_payment_intent_id);

            if ($session->payment_status === 'paid') {
                // Marquer le don comme complété
                $donation->update([
                    'status' => 'completed',
                    'processed_at' => now()
                ]);

                Log::info('Don solidaire confirmé:', [
                    'donation_id' => $donation->id,
                    'amount' => $donation->amount,
                    'user_id' => $donation->user_id
                ]);

                return response()->json([
                    'success' => true,
                    'status' => 'completed',
                    'donation' => [
                        'id' => $donation->id,
                        'amount' => $donation->amount,
                        'currency' => $donation->currency,
                        'message' => $donation->message,
                        'is_anonymous' => $donation->is_anonymous,
                        'processed_at' => $donation->processed_at
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'status' => $session->payment_status,
                    'message' => 'Paiement non confirmé'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification du don solidaire:', [
                'donation_id' => $donationId,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification du paiement'
            ], 500);
        }
    }
}
