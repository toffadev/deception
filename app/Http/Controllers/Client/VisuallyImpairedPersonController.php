<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\VisuallyImpairedPerson;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class VisuallyImpairedPersonController extends Controller
{
    /**
     * Afficher la liste publique des personnes malvoyantes
     */
    public function index(Request $request)
    {
        try {
            $query = VisuallyImpairedPerson::query()
                ->active() // Seulement les personnes actives
                ->orderBy('sort_order', 'asc')
                ->orderBy('created_at', 'desc');

            // Filtres optionnels
            if ($request->filled('sexe')) {
                $query->where('sexe', $request->sexe);
            }

            if ($request->filled('type_voyance')) {
                $query->where('type_voyance', $request->type_voyance);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('lieu_residence', 'like', "%{$search}%");
                });
            }

            $people = $query->paginate(12);

            // Transformer les données pour la vue publique
            $people->getCollection()->transform(function ($person) {
                return [
                    'id' => $person->id,
                    'nom_complet' => $person->nom_complet,
                    'sexe' => $person->sexe,
                    'sexe_libelle' => $person->sexe_libelle,
                    'age' => $person->age,
                    'lieu_residence' => $person->lieu_residence,
                    'type_voyance' => $person->type_voyance,
                    'type_voyance_libelle' => $person->type_voyance_libelle,
                    'traitement_en_cours' => $person->traitement_en_cours,
                    'photo_url' => $person->photo_url,
                ];
            });

            // Statistiques générales
            $stats = [
                'total_personnes' => VisuallyImpairedPerson::active()->count(),
                'hommes' => VisuallyImpairedPerson::active()->bySexe('M')->count(),
                'femmes' => VisuallyImpairedPerson::active()->bySexe('F')->count(),
                'en_traitement' => VisuallyImpairedPerson::active()->where('traitement_en_cours', true)->count(),
                'types_voyance' => VisuallyImpairedPerson::active()
                    ->whereNotNull('type_voyance')
                    ->groupBy('type_voyance')
                    ->selectRaw('type_voyance, COUNT(*) as count')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [VisuallyImpairedPerson::TYPES_VOYANCE[$item->type_voyance] ?? $item->type_voyance => $item->count];
                    })
            ];

            return Inertia::render('VisuallyImpairedPeople', [
                'people' => $people,
                'stats' => $stats,
                'sexes' => VisuallyImpairedPerson::SEXES,
                'typesVoyance' => VisuallyImpairedPerson::TYPES_VOYANCE,
                'filters' => $request->only(['sexe', 'type_voyance', 'search']),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage de la liste des personnes malvoyantes:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du chargement des données.');
        }
    }

    /**
     * Afficher les statistiques générales pour l'encart dans Solidarity
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_personnes' => VisuallyImpairedPerson::active()->count(),
                'hommes' => VisuallyImpairedPerson::active()->bySexe('M')->count(),
                'femmes' => VisuallyImpairedPerson::active()->bySexe('F')->count(),
                'en_traitement' => VisuallyImpairedPerson::active()->where('traitement_en_cours', true)->count(),
                'derniere_mise_a_jour' => VisuallyImpairedPerson::active()->latest('updated_at')->first()?->updated_at,
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques:', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }
}
