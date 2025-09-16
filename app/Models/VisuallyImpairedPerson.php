<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VisuallyImpairedPerson extends Model
{
    use HasFactory;

    protected $table = 'visually_impaired_people';

    protected $fillable = [
        'nom',
        'prenom',
        'sexe',
        'age',
        'lieu_residence',
        'telephone',
        'type_voyance',
        'traitement_en_cours',
        'photo_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'traitement_en_cours' => 'boolean',
        'is_active' => 'boolean',
        'age' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Constantes pour les types de sexe
     */
    const SEXES = [
        'M' => 'Masculin',
        'F' => 'Féminin',
    ];

    /**
     * Constantes pour les types de voyance
     */
    const TYPES_VOYANCE = [
        'cécité_totale' => 'Cécité totale',
        'cécité_partielle' => 'Cécité partielle',
        'malvoyance' => 'Malvoyance',
        'vision_tubulaire' => 'Vision tubulaire',
        'autre' => 'Autre',
    ];

    /**
     * Accesseur pour l'URL de la photo
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path) {
            return Storage::disk('public')->url($this->photo_path);
        }
        return null;
    }

    /**
     * Accesseur pour le nom complet
     */
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Accesseur pour le libellé du sexe
     */
    public function getSexeLibelleAttribute()
    {
        return self::SEXES[$this->sexe] ?? $this->sexe;
    }

    /**
     * Accesseur pour le libellé du type de voyance
     */
    public function getTypeVoyanceLibelleAttribute()
    {
        return self::TYPES_VOYANCE[$this->type_voyance] ?? $this->type_voyance;
    }

    /**
     * Scope pour les personnes actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les personnes inactives
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope pour filtrer par sexe
     */
    public function scopeBySexe($query, $sexe)
    {
        return $query->where('sexe', $sexe);
    }

    /**
     * Scope pour filtrer par type de voyance
     */
    public function scopeByTypeVoyance($query, $type)
    {
        return $query->where('type_voyance', $type);
    }

    /**
     * Scope pour recherche
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
                ->orWhere('prenom', 'like', "%{$search}%")
                ->orWhere('telephone', 'like', "%{$search}%")
                ->orWhere('lieu_residence', 'like', "%{$search}%");
        });
    }
}
