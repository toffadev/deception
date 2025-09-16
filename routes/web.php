<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Auth\AdminAuthController;


// Route de redirection intelligente pour 'login'
Route::get('/login', function () {
    // Si l'URL précédente contenait 'admin', rediriger vers admin login
    if (str_contains(url()->previous(), '/admin')) {
        return redirect()->route('admin.auth.login');
    }
    // Sinon, rediriger vers client login
    return redirect()->route('auth.login');
})->name('login');

// Routes publiques client
Route::get('/', [App\Http\Controllers\Client\HomeController::class, 'index'])
    ->name('client.home');

Route::get('/solidarity', [App\Http\Controllers\Client\SolidarityController::class, 'index'])
    ->name('client.solidarity');

// Routes AJAX pour la page solidarity
Route::get('/api/solidarity/media', [App\Http\Controllers\Client\SolidarityController::class, 'getMoreMedia'])
    ->name('client.solidarity.media');
Route::get('/api/solidarity/project/{project}', [App\Http\Controllers\Client\SolidarityController::class, 'getProjectDetails'])
    ->name('client.solidarity.project');

/* Route::get('/solidarite', function () {
    return Inertia::render('Solidaire');
})->name('client.solidaire');
 */

Route::get('/testimony', function () {
    return Inertia::render('Testimony');
})->name('client.testimony');

Route::get('/testimony-detail', function () {
    return Inertia::render('TestimonyDetail');
})->name('client.testimony-detail');


Route::get('/publication', [App\Http\Controllers\Client\PublicationController::class, 'index'])
    ->name('client.publication');

Route::get('/publication/{publication:slug}', [App\Http\Controllers\Client\PublicationController::class, 'show'])
    ->name('client.publication.show');

Route::get('/contact', [App\Http\Controllers\Client\ContactController::class, 'index'])
    ->name('client.contact');

Route::post('/contact', [App\Http\Controllers\Client\ContactController::class, 'sendMessage'])
    ->name('client.contact.send');

// Route pour la liste publique des personnes malvoyantes
Route::get('/visually-impaired-people', [App\Http\Controllers\Client\VisuallyImpairedPersonController::class, 'index'])
    ->name('client.visually-impaired-people');

// Route API pour les statistiques des personnes malvoyantes
Route::get('/api/visually-impaired-people/stats', [App\Http\Controllers\Client\VisuallyImpairedPersonController::class, 'getStats'])
    ->name('client.visually-impaired-people.stats');

// Route pour la désinscription des notifications email
Route::get('/unsubscribe/{user}/{token}', [App\Http\Controllers\Auth\ClientAuthController::class, 'unsubscribe'])
    ->name('auth.unsubscribe');

// Routes protégées pour les clients authentifiés
Route::middleware('auth')->group(function () {
    Route::post('/publication', [App\Http\Controllers\Client\PublicationController::class, 'store'])
        ->name('client.publication.store');
    Route::get('/api/tags/suggested', [App\Http\Controllers\Client\PublicationController::class, 'getSuggestedTags'])
        ->name('client.tags.suggested');

    // Routes pour les commentaires - utiliser l'ID pour ces actions
    Route::post('/publication/{publication:id}/comments', [App\Http\Controllers\Client\PublicationController::class, 'storeComment'])
        ->name('client.publication.comments.store');

    // Routes pour les réactions (publications et commentaires)
    Route::post('/reactions/toggle', [App\Http\Controllers\Client\PublicationController::class, 'toggleReaction'])
        ->name('client.reactions.toggle');

    // Routes pour les dons - utiliser l'ID pour ces actions
    Route::post('/publication/{publication:id}/donate', [App\Http\Controllers\Client\PublicationController::class, 'createDonation'])
        ->name('client.publication.donate');
    Route::get('/donation/{donationId}/status', [App\Http\Controllers\Client\PublicationController::class, 'checkPaymentStatus'])
        ->name('client.donation.status');

    // Routes pour les dons solidaires
    Route::post('/solidarity/donate', [App\Http\Controllers\Client\SolidarityController::class, 'createDonation'])
        ->name('client.solidarity.donate');
    Route::get('/solidarity/donation/{donationId}/status', [App\Http\Controllers\Client\SolidarityController::class, 'checkPaymentStatus'])
        ->name('client.solidarity.donation.status');

    // Routes pour les signalements
    Route::post('/report', [App\Http\Controllers\Client\PublicationController::class, 'createReport'])
        ->name('client.report.create');
});

// Webhook Stripe (sans middleware auth)
Route::post('/stripe/webhook', [App\Http\Controllers\Client\PublicationController::class, 'stripeWebhook'])
    ->name('stripe.webhook');




// Routes par défaut pour Laravel (sans préfixe)
Route::get('/email/verify/{id}/{hash}', [ClientAuthController::class, 'verifyEmail'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

// Routes de mot de passe par défaut pour Laravel Password Reset
Route::get('/password/reset/{token}', [ClientAuthController::class, 'showResetPasswordForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/password/reset', [ClientAuthController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');

Route::post('/password/email', [ClientAuthController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

// Routes d'authentification CLIENT
Route::prefix('auth')->name('auth.')->group(function () {
    // Routes accessibles seulement si NON authentifié
    Route::middleware('guest')->group(function () {
        // Inscription
        Route::get('register', [ClientAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [ClientAuthController::class, 'register'])->name('register.store');

        // Connexion
        Route::get('login', [ClientAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [ClientAuthController::class, 'login'])->name('login.store');

        // Mot de passe oublié
        Route::get('forgot-password', [ClientAuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('forgot-password', [ClientAuthController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('reset-password/{token}', [ClientAuthController::class, 'showResetPasswordForm'])->name('password.reset');
        Route::post('reset-password', [ClientAuthController::class, 'resetPassword'])->name('password.update');

        // Authentification Google
        Route::get('google', [ClientAuthController::class, 'redirectToGoogle'])->name('google');
        Route::get('google/callback', [ClientAuthController::class, 'handleGoogleCallback'])->name('google.callback');
    });

    // Routes accessibles seulement si authentifié
    Route::middleware('auth')->group(function () {
        Route::post('logout', [ClientAuthController::class, 'logout'])->name('logout');
        Route::get('verify-email', [ClientAuthController::class, 'showVerifyEmailForm'])->name('verification.notice');
        Route::post('email/verification-notification', [ClientAuthController::class, 'sendVerificationEmail'])->name('verification.send');
        Route::get('verify-email/{id}/{hash}', [ClientAuthController::class, 'verifyEmail'])
            ->middleware('signed')->name('verification.verify');
    });
});

// Routes d'authentification ADMIN
Route::prefix('admin/auth')->name('admin.auth.')->group(function () {
    // Routes accessibles seulement si NON authentifié OU pas admin
    Route::middleware(['guest:web'])->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.store');
        Route::get('forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('forgot-password', [AdminAuthController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('reset-password/{token}', [AdminAuthController::class, 'showResetPasswordForm'])->name('password.reset');
        Route::post('reset-password', [AdminAuthController::class, 'resetPassword'])->name('password.update');
    });

    // Déconnexion admin
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

// Routes admin protégées
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Home'); // Page Dashboard.vue dans Admin/
    })->name('admin.home');
});

// Routes admin pour les publications (protégées)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Routes resource standard (utilisant l'ID, pas le slug)
    Route::get('publications', [PublicationController::class, 'index'])->name('publications.index');
    Route::get('publications/create', [PublicationController::class, 'create'])->name('publications.create');
    Route::post('publications', [PublicationController::class, 'store'])->name('publications.store');
    Route::get('publications/{publication:id}/edit', [PublicationController::class, 'edit'])->name('publications.edit');
    Route::put('publications/{publication:id}', [PublicationController::class, 'update'])->name('publications.update');
    Route::delete('publications/{publication:id}', [PublicationController::class, 'destroy'])->name('publications.destroy');

    // Routes supplémentaires
    Route::post('publications/{id}/restore', [PublicationController::class, 'restore'])->name('publications.restore');
    Route::delete('publications/{id}/force-delete', [PublicationController::class, 'forceDelete'])->name('publications.force-delete');
    Route::post('publications/{publication:id}/moderate', [PublicationController::class, 'moderate'])->name('publications.moderate');

    // Routes pour les utilisateurs
    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::post('users/{id}/restore', [App\Http\Controllers\Admin\UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [App\Http\Controllers\Admin\UserController::class, 'forceDelete'])->name('users.force-delete');
    Route::post('users/{user}/change-status', [App\Http\Controllers\Admin\UserController::class, 'changeStatus'])->name('users.change-status');
    Route::post('users/{user}/change-role', [App\Http\Controllers\Admin\UserController::class, 'changeRole'])->name('users.change-role');
    Route::post('users/{user}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::get('users-statistics', [App\Http\Controllers\Admin\UserController::class, 'statistics'])->name('users.statistics');

    // Routes pour les signalements
    Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/{report}', [App\Http\Controllers\Admin\ReportController::class, 'show'])->name('reports.show');
    Route::post('reports/{report}/process', [App\Http\Controllers\Admin\ReportController::class, 'process'])->name('reports.process');
    Route::post('reports/bulk-process', [App\Http\Controllers\Admin\ReportController::class, 'bulkProcess'])->name('reports.bulk-process');
    Route::post('reports/{report}/toggle-priority', [App\Http\Controllers\Admin\ReportController::class, 'togglePriority'])->name('reports.toggle-priority');
    Route::get('reports-statistics', [App\Http\Controllers\Admin\ReportController::class, 'statistics'])->name('reports.statistics');

    // Routes pour les médias de projet
    Route::get('project-media', [App\Http\Controllers\Admin\ProjectMediaController::class, 'index'])->name('project-media.index');
    Route::get('project-media/create', [App\Http\Controllers\Admin\ProjectMediaController::class, 'create'])->name('project-media.create');
    Route::post('project-media', [App\Http\Controllers\Admin\ProjectMediaController::class, 'store'])->name('project-media.store');
    Route::get('project-media/{projectMedia}/edit', [App\Http\Controllers\Admin\ProjectMediaController::class, 'edit'])->name('project-media.edit');
    Route::put('project-media/{projectMedia}', [App\Http\Controllers\Admin\ProjectMediaController::class, 'update'])->name('project-media.update');
    Route::delete('project-media/{projectMedia}', [App\Http\Controllers\Admin\ProjectMediaController::class, 'destroy'])->name('project-media.destroy');
    Route::post('project-media/update-order', [App\Http\Controllers\Admin\ProjectMediaController::class, 'updateOrder'])->name('project-media.update-order');

    // Routes pour les tags
    Route::get('tags', [App\Http\Controllers\Admin\TagController::class, 'index'])->name('tags.index');
    Route::post('tags', [App\Http\Controllers\Admin\TagController::class, 'store'])->name('tags.store');
    Route::put('tags/{tag}', [App\Http\Controllers\Admin\TagController::class, 'update'])->name('tags.update');
    Route::delete('tags/{tag}', [App\Http\Controllers\Admin\TagController::class, 'destroy'])->name('tags.destroy');
    Route::post('tags/bulk-destroy', [App\Http\Controllers\Admin\TagController::class, 'bulkDestroy'])->name('tags.bulk-destroy');
    Route::post('tags/{tag}/toggle-suggested', [App\Http\Controllers\Admin\TagController::class, 'toggleSuggested'])->name('tags.toggle-suggested');
    Route::post('tags/recalculate-usage', [App\Http\Controllers\Admin\TagController::class, 'recalculateUsage'])->name('tags.recalculate-usage');
    Route::post('tags/cleanup', [App\Http\Controllers\Admin\TagController::class, 'cleanup'])->name('tags.cleanup');
    Route::get('tags/suggestions', [App\Http\Controllers\Admin\TagController::class, 'suggestions'])->name('tags.suggestions');

    // Routes pour les partenaires
    Route::get('partners', [App\Http\Controllers\Admin\PartnerController::class, 'index'])->name('partners.index');
    Route::get('partners/create', [App\Http\Controllers\Admin\PartnerController::class, 'create'])->name('partners.create');
    Route::post('partners', [App\Http\Controllers\Admin\PartnerController::class, 'store'])->name('partners.store');
    Route::get('partners/{partner}/edit', [App\Http\Controllers\Admin\PartnerController::class, 'edit'])->name('partners.edit');
    Route::put('partners/{partner}', [App\Http\Controllers\Admin\PartnerController::class, 'update'])->name('partners.update');
    Route::delete('partners/{partner}', [App\Http\Controllers\Admin\PartnerController::class, 'destroy'])->name('partners.destroy');
    Route::post('partners/{partner}/toggle-status', [App\Http\Controllers\Admin\PartnerController::class, 'toggleStatus'])->name('partners.toggle-status');
    Route::post('partners/reorder', [App\Http\Controllers\Admin\PartnerController::class, 'reorder'])->name('partners.reorder');

    // Routes pour les projets de solidarité
    Route::get('solidarity-projects', [App\Http\Controllers\Admin\SolidarityProjectController::class, 'index'])->name('solidarity-projects.index');
    Route::get('solidarity-projects/create', [App\Http\Controllers\Admin\SolidarityProjectController::class, 'create'])->name('solidarity-projects.create');
    Route::post('solidarity-projects', [App\Http\Controllers\Admin\SolidarityProjectController::class, 'store'])->name('solidarity-projects.store');
    Route::get('solidarity-projects/{solidarityProject}', [App\Http\Controllers\Admin\SolidarityProjectController::class, 'show'])->name('solidarity-projects.show');
    Route::get('solidarity-projects/{solidarityProject}/edit', [App\Http\Controllers\Admin\SolidarityProjectController::class, 'edit'])->name('solidarity-projects.edit');
    Route::put('solidarity-projects/{solidarityProject}', [App\Http\Controllers\Admin\SolidarityProjectController::class, 'update'])->name('solidarity-projects.update');
    Route::delete('solidarity-projects/{solidarityProject}', [App\Http\Controllers\Admin\SolidarityProjectController::class, 'destroy'])->name('solidarity-projects.destroy');
    Route::post('solidarity-projects/{solidarityProject}/toggle-featured', [App\Http\Controllers\Admin\SolidarityProjectController::class, 'toggleFeatured'])->name('solidarity-projects.toggle-featured');
    Route::post('solidarity-projects/{solidarityProject}/update-status', [App\Http\Controllers\Admin\SolidarityProjectController::class, 'updateStatus'])->name('solidarity-projects.update-status');
    Route::post('solidarity-projects/{solidarityProject}/recalculate-amount', [App\Http\Controllers\Admin\SolidarityProjectController::class, 'recalculateAmount'])->name('solidarity-projects.recalculate-amount');
    Route::get('solidarity-projects-statistics', [App\Http\Controllers\Admin\SolidarityProjectController::class, 'statistics'])->name('solidarity-projects.statistics');

    // Routes pour les personnes malvoyantes
    Route::get('visually-impaired', [App\Http\Controllers\Admin\VisuallyImpairedPersonController::class, 'index'])->name('visually-impaired.index');
    Route::get('visually-impaired/create', [App\Http\Controllers\Admin\VisuallyImpairedPersonController::class, 'create'])->name('visually-impaired.create');
    Route::post('visually-impaired', [App\Http\Controllers\Admin\VisuallyImpairedPersonController::class, 'store'])->name('visually-impaired.store');
    Route::get('visually-impaired/{visuallyImpaired}/edit', [App\Http\Controllers\Admin\VisuallyImpairedPersonController::class, 'edit'])->name('visually-impaired.edit');
    Route::put('visually-impaired/{visuallyImpaired}', [App\Http\Controllers\Admin\VisuallyImpairedPersonController::class, 'update'])->name('visually-impaired.update');
    Route::delete('visually-impaired/{visuallyImpaired}', [App\Http\Controllers\Admin\VisuallyImpairedPersonController::class, 'destroy'])->name('visually-impaired.destroy');
    Route::post('visually-impaired/{visuallyImpaired}/toggle-status', [App\Http\Controllers\Admin\VisuallyImpairedPersonController::class, 'toggleStatus'])->name('visually-impaired.toggle-status');
    Route::post('visually-impaired/reorder', [App\Http\Controllers\Admin\VisuallyImpairedPersonController::class, 'reorder'])->name('visually-impaired.reorder');
});
