<template>
    <MainLayout>
        <!-- Bouton flottant solidarité malvoyants -->
        <SolidarityDonationButton 
            :is-authenticated="isAuthenticated"
            @donation-completed="onDonationCompleted"
        />

        <!-- Hero Section - Présentation du Projet -->
        <section class="relative py-12 bg-gradient-to-r from-red-600 to-pink-600 text-white overflow-hidden">
            <div class="absolute inset-0 hero-pattern opacity-10"></div>
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-4xl mx-auto text-center">
                    <!-- Logo ONG Malvoyants mis en valeur -->
                    <div class="mb-6 flex justify-center">
                        <div class="bg-white bg-opacity-95 backdrop-blur-sm rounded-full p-4 shadow-lg border-4 border-white border-opacity-50">
                            <img src="/assets/images/logo4.png" alt="Logo ONG Solidarité Malvoyants" class="w-16 h-16 object-contain">
                        </div>
                    </div>
                    
                    <h1 class="title-font text-4xl md:text-5xl font-bold mb-4 leading-tight">
                        Gemeinsames Licht
                    </h1>
                    <h2 class="text-xl md:text-2xl font-light mb-6 opacity-90">
                        Transformer la douleur en espoir pour les malvoyants du Bénin
                    </h2>
                    <p class="text-lg md:text-xl mb-8 leading-relaxed opacity-95">
                        Nous croyons que chaque cœur brisé peut devenir une source de lumière pour ceux qui vivent dans l'obscurité. 
                        Notre mission : convertir vos témoignages de douleur en actions concrètes pour soutenir les personnes malvoyantes ou aveugles au Bénin.
                    </p>
                    
                    <div class="grid md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-4 border border-white border-opacity-30 shadow-lg">
                            <div class="text-3xl font-bold mb-1 text-red-600">{{ globalStats.people_helped }}+</div>
                            <div class="text-sm font-medium text-gray-700">Personnes aidées</div>
                        </div>
                        <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-4 border border-white border-opacity-30 shadow-lg">
                            <div class="text-3xl font-bold mb-1 text-red-600">{{ formatCurrency(globalStats.total_donations) }}</div>
                            <div class="text-sm font-medium text-gray-700">Fonds collectés</div>
                        </div>
                        <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-4 border border-white border-opacity-30 shadow-lg">
                            <div class="text-3xl font-bold mb-1 text-red-600">{{ globalStats.completed_projects }}</div>
                            <div class="text-sm font-medium text-gray-700">Projets réalisés</div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row items-center justify-center space-y-4 md:space-y-0 md:space-x-6">
                        <button @click="openDonationModal" class="bg-white text-red-600 px-6 py-3 rounded-full font-bold text-base hover:bg-gray-100 transition duration-300 shadow-lg">
                            <i class="fas fa-heart mr-2"></i>
                            Faire un don maintenant
                        </button>
                        <a href="#projects" class="border-2 border-white text-white px-6 py-3 rounded-full font-bold text-base hover:bg-white hover:text-red-600 transition duration-300">
                            <i class="fas fa-eye mr-2"></i>
                            Découvrir nos actions
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission Section - Notre Engagement -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16">
                        <!-- Logo ONG dans la section mission -->
                        <div class="mb-6 flex justify-center">
                            <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-3 shadow-md border border-red-100">
                                <img src="/assets/images/logo4.png" alt="Logo ONG Solidarité Malvoyants" class="w-12 h-12 object-contain">
                            </div>
                        </div>
                        
                        <h2 class="title-font text-4xl font-bold text-gray-800 mb-4">Notre Mission au Service des Malvoyants</h2>
                        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                            Gemeinsames Licht unit la communauté béninoise et internationale autour d'un objectif commun : 
                            offrir autonomie, dignité et opportunités aux personnes malvoyantes ou aveugles du Bénin.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
                        <div>
                            <h3 class="title-font text-2xl font-bold text-gray-800 mb-6">Notre Vision</h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                Au Bénin, plus de 200,000 personnes vivent avec une déficience visuelle. Beaucoup d'entre elles 
                                font face à l'exclusion sociale, au manque d'opportunités économiques et aux préjugés. 
                                Nous croyons en un Bénin où chaque personne malvoyante peut réaliser son plein potentiel.
                            </p>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-red-500 mr-3 mt-1"></i>
                                    <span>Briser les barrières de l'exclusion sociale</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-red-500 mr-3 mt-1"></i>
                                    <span>Créer des opportunités d'emploi adaptées</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-red-500 mr-3 mt-1"></i>
                                    <span>Sensibiliser les communautés locales</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-red-500 mr-3 mt-1"></i>
                                    <span>Favoriser l'autonomie et l'indépendance</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="relative">
                            <div class="bg-gradient-to-br from-red-100 to-pink-100 rounded-2xl p-8 shadow-lg">
                                <div class="text-center mb-6">
                                    <i class="fas fa-hands-helping text-red-500 text-6xl mb-4"></i>
                                    <h4 class="title-font text-xl font-bold text-gray-800">Comment ça marche ?</h4>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4">1</div>
                                        <span class="text-gray-700">Vous partagez votre témoignage</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4">2</div>
                                        <span class="text-gray-700">Chaque interaction génère des fonds</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4">3</div>
                                        <span class="text-gray-700">Nous transformons en aide concrète</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4">4</div>
                                        <span class="text-gray-700">Impact direct au Bénin</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="md:flex">
                            <div class="md:w-1/2 p-8">
                                <h3 class="title-font text-2xl font-bold text-gray-800 mb-4">Pourquoi les Malvoyants ?</h3>
                                <p class="text-gray-600 mb-6">
                                    Notre choix de soutenir spécifiquement les personnes malvoyantes ou aveugles naît d'une réalité : 
                                    au Bénin, elles font partie des groupes les plus marginalisés, avec un taux de chômage 
                                    de plus de 80% et un accès limité aux services de base.
                                </p>
                                <div class="space-y-4">
                                    <div class="bg-red-50 border-l-4 border-red-500 p-4">
                                        <h4 class="font-bold text-gray-800 mb-2">Impact Social</h4>
                                        <p class="text-gray-600 text-sm">Chaque personne malvoyante que nous aidons devient un modèle pour sa communauté, brisant les préjugés et inspirant l'inclusion.</p>
                                    </div>
                                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                                        <h4 class="font-bold text-gray-800 mb-2">Impact Économique</h4>
                                        <p class="text-gray-600 text-sm">Nos formations créent de vrais emplois, permettant aux bénéficiaires de subvenir à leurs besoins et à ceux de leur famille.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="md:w-1/2 bg-gradient-to-br from-gray-100 to-gray-200 p-8 flex items-center">
                                <div class="w-full">
                                    <div class="text-center mb-6">
                                        <h4 class="title-font text-xl font-bold text-gray-800 mb-4">Nos Domaines d'Action</h4>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-white p-4 rounded-lg text-center shadow-sm">
                                            <i class="fas fa-graduation-cap text-blue-500 text-2xl mb-2"></i>
                                            <div class="text-sm font-semibold text-gray-800">Formation</div>
                                        </div>
                                        <div class="bg-white p-4 rounded-lg text-center shadow-sm">
                                            <i class="fas fa-briefcase text-green-500 text-2xl mb-2"></i>
                                            <div class="text-sm font-semibold text-gray-800">Emploi</div>
                                        </div>
                                        <div class="bg-white p-4 rounded-lg text-center shadow-sm">
                                            <i class="fas fa-home text-purple-500 text-2xl mb-2"></i>
                                            <div class="text-sm font-semibold text-gray-800">Logement</div>
                                        </div>
                                        <div class="bg-white p-4 rounded-lg text-center shadow-sm">
                                            <i class="fas fa-heart text-red-500 text-2xl mb-2"></i>
                                            <div class="text-sm font-semibold text-gray-800">Santé</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section - Nos Actions Spécifiques -->
        <section id="projects" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="title-font text-4xl font-bold text-gray-800 mb-4">Nos Actions au Bénin</h2>
                        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                            Découvrez comment nous adaptons nos interventions aux besoins spécifiques des malvoyants béninois
                        </p>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8">
                        <div v-for="(project, index) in featuredProjects" :key="project.id" 
                             class="feature-card bg-gradient-to-br rounded-xl p-8"
                             :class="{
                                'from-blue-50 to-blue-100': index === 0,
                                'from-green-50 to-green-100': index === 1,
                                'from-purple-50 to-purple-100': index === 2
                             }">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto"
                                 :class="{
                                    'bg-blue-500': index === 0,
                                    'bg-green-500': index === 1,
                                    'bg-purple-500': index === 2
                                 }">
                                <i class="fas fa-heart text-white text-2xl"></i>
                            </div>
                            <h3 class="title-font text-xl font-bold text-gray-800 mb-4 text-center">{{ project.title }}</h3>
                            <p class="text-gray-600 mb-6 text-center">
                                {{ safeSubstring(project.description, 150) }}
                            </p>
                            <div class="text-white p-4 rounded-lg text-center"
                                 :class="{
                                    'bg-blue-500': index === 0,
                                    'bg-green-500': index === 1,
                                    'bg-purple-500': index === 2
                                 }">
                                <div class="text-2xl font-bold">{{ Math.round(project.progress_percentage) }}%</div>
                                <div class="text-sm">{{ formatCurrency(project.current_amount) }} / {{ formatCurrency(project.target_amount) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Nos bénéficiaires Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl overflow-hidden shadow-lg border border-blue-100">
                        <div class="lg:flex">
                            <div class="lg:w-2/3 p-8 lg:p-12">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-users text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h2 class="title-font text-2xl lg:text-3xl font-bold text-gray-800">Nos bénéficiaires</h2>
                                        <p class="text-blue-600 font-medium">Personnes malvoyantes ou aveugles accompagnées</p>
                                    </div>
                                </div>
                                
                                <p class="text-lg text-gray-700 mb-6 leading-relaxed">
                                    Découvrez les visages de l'espoir : notre communauté soutient actuellement des personnes malvoyantes ou aveugles 
                                    courageuses au Bénin. Chaque profil raconte une histoire de résilience et de détermination.
                                </p>

                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8" v-if="visuallyImpairedStats">
                                    <div class="text-center">
                                        <div class="text-2xl lg:text-3xl font-bold text-blue-600 mb-1">{{ visuallyImpairedStats.total_personnes || 0 }}</div>
                                        <div class="text-sm text-gray-600">Personnes</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl lg:text-3xl font-bold text-green-600 mb-1">{{ visuallyImpairedStats.hommes || 0 }}</div>
                                        <div class="text-sm text-gray-600">Hommes</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl lg:text-3xl font-bold text-pink-600 mb-1">{{ visuallyImpairedStats.femmes || 0 }}</div>
                                        <div class="text-sm text-gray-600">Femmes</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl lg:text-3xl font-bold text-yellow-600 mb-1">{{ visuallyImpairedStats.en_traitement || 0 }}</div>
                                        <div class="text-sm text-gray-600">En traitement</div>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-4">
                                    <Link 
                                        href="/visually-impaired-people" 
                                        class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
                                    >
                                        <i class="fas fa-eye mr-2"></i>
                                        Voir tous nos bénéficiaires
                                    </Link>
                                    <Link 
                                        href="#solidarity-projects" 
                                        class="inline-flex items-center justify-center bg-transparent border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-6 py-3 rounded-full font-medium transition-all duration-300"
                                    >
                                        <i class="fas fa-heart mr-2"></i>
                                        Nos projets solidaires
                                    </Link>
                                </div>
                            </div>
                            
                            <div class="lg:w-1/3 bg-gradient-to-br from-blue-600 to-indigo-600 p-8 lg:p-12 text-white">
                                <div class="text-center">
                                    <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fas fa-heart text-3xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold mb-4">Chaque histoire compte</h3>
                                    <p class="text-blue-100 mb-6 text-sm leading-relaxed">
                                        Nos bénéficiaires ne sont pas des statistiques, mais des personnes avec des rêves, 
                                        des espoirs et une détermination inspirante.
                                    </p>
                                    <div class="bg-white bg-opacity-10 rounded-lg p-4">
                                        <p class="text-xs text-blue-100 mb-2">Dernière mise à jour</p>
                                        <p class="font-medium" v-if="visuallyImpairedStats?.derniere_mise_a_jour">
                                            {{ formatDate(visuallyImpairedStats.derniere_mise_a_jour) }}
                                        </p>
                                        <p class="font-medium" v-else>
                                            Récemment
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Solidarity Project Section -->
        <section id="solidarity-projects" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center mb-12">
                    <h2 class="title-font text-3xl font-bold text-gray-800 mb-4">Comment les témoignages soutiennent notre projet solidaire</h2>
                    <p class="text-lg text-gray-600">Chaque interaction avec les témoignages contribue à notre mission d'aide aux personnes malvoyantes. Voici l'impact concret de votre engagement.</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-4xl mx-auto">
                    <div class="md:flex">
                        <div class="md:w-1/2 p-8">
                            <h3 class="title-font text-2xl font-bold text-gray-800 mb-4">Notre mission</h3>
                            <p class="text-gray-600 mb-6">Gemeinsames Licht transforme la douleur émotionnelle en soutien concret pour les personnes malvoyantes. Nous croyons que chaque histoire partagée peut créer un impact positif dans la vie de ceux qui vivent avec un handicap visuel.</p>
                            
                            <h4 class="font-bold text-gray-800 mb-3">Le lien entre cœurs brisés et cœurs ouverts</h4>
                            <p class="text-gray-600 mb-6">Nous croyons que la douleur émotionnelle peut se transformer en force positive. Chaque témoignage partagé ici génère des fonds pour :</p>
                            <ul class="list-disc pl-5 text-gray-600 mb-6 space-y-2">
                                <li>Financer des livres audio et matériel adapté</li>
                                <li>Organiser des ateliers d'autonomie</li>
                                <li>Former des chiens guides d'aveugle</li>
                            </ul>
                            <p class="text-gray-600">Votre histoire devient ainsi un acte concret de solidarité.</p>
                            
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                                <p class="text-gray-700"><span class="font-bold">Impact de ce témoignage :</span> 42 commentaires × 0,50€ = 21€ collectés pour notre projet "Livres audio pour malvoyants".</p>
                            </div>
                            
                            <a href="#" class="inline-block bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-full font-medium transition duration-300">En savoir plus sur notre projet</a>
                        </div>
                        
                        <div class="md:w-1/2 bg-gray-100 p-8 flex items-center">
                            <div class="w-full">
                                <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                    <h4 class="font-bold text-gray-800 mb-4">Projets financés par des témoignages comme celui-ci</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <div class="flex justify-between mb-1">
                                                <span class="text-gray-700">Livres audio (85%)</span>
                                                <span class="font-bold text-red-500">127/150 produits</span>
                                            </div>
                                            <div class="donation-progress rounded-full">
                                                <div class="donation-progress-bar rounded-full" style="width: 85%"></div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">1 livre audio = 12 témoignages partagés</p>
                                        </div>
                                        
                                        <div>
                                            <div class="flex justify-between mb-1">
                                                <span class="text-gray-700">Formation chiens guides</span>
                                                <span class="font-bold text-red-500">62%</span>
                                            </div>
                                            <div class="donation-progress rounded-full">
                                                <div class="donation-progress-bar rounded-full" style="width: 62%"></div>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <div class="flex justify-between mb-1">
                                                <span class="text-gray-700">Ateliers d'autonomie</span>
                                                <span class="font-bold text-red-500">45%</span>
                                            </div>
                                            <div class="donation-progress rounded-full">
                                                <div class="donation-progress-bar rounded-full" style="width: 45%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-6 rounded-lg shadow-sm">
                                    <h4 class="font-bold text-gray-800 mb-4">Suivi en temps réel</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                                            <div class="text-red-500 text-2xl font-bold mb-1">21€</div>
                                            <div class="text-gray-600 text-sm">Générés par ce témoignage</div>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                                            <div class="text-red-500 text-2xl font-bold mb-1">3</div>
                                            <div class="text-gray-600 text-sm">Personnes aidées</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Galerie Média Professionnelle -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <!-- En-tête de section -->
                    <div class="text-center mb-12">
                        <h2 class="title-font text-4xl font-bold text-gray-800 mb-4">
                            Impact Visuel de Nos Actions
                        </h2>
                        <p class="text-lg text-gray-600 max-w-3xl mx-auto mb-8">
                            Découvrez à travers images et vidéos comment vos contributions transforment concrètement la vie des personnes malvoyantes ou aveugles au Bénin.
                        </p>
                        
                        <!-- Filtres de type média -->
                        <div class="inline-flex bg-gray-100 rounded-lg p-1">
                            <button @click="switchGalleryType('images')" 
                                    :class="galleryType === 'images' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-600 hover:text-red-500'"
                                    class="flex items-center px-4 py-2 rounded-md transition-all duration-200 font-medium">
                                <i class="fas fa-images mr-2"></i>
                                Images
                            </button>
                            <button @click="switchGalleryType('videos')" 
                                    :class="galleryType === 'videos' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-600 hover:text-red-500'"
                                    class="flex items-center px-4 py-2 rounded-md transition-all duration-200 font-medium">
                                <i class="fas fa-video mr-2"></i>
                                Vidéos
                            </button>
                        </div>
                    </div>

                    <!-- Grille de médias -->
                    <div v-if="filteredMedia.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="(media, index) in filteredMedia.slice(0, 6)" :key="media.id"
                             class="group cursor-pointer transform hover:scale-105 transition-all duration-300"
                             @click="openMediaModal(media)">
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                
                                <!-- Container image/vidéo -->
                                <div class="h-48 bg-gray-100 overflow-hidden relative">
                                    <!-- Image -->
                                    <img v-if="media.type === 'image' && media.file_url"
                                         :src="media.file_url"
                                         :alt="media.alt_text || media.title"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                         @load="console.log('✅ Image chargée:', media.file_url)"
                                         @error="console.log('❌ Erreur image:', media.file_url)">
                                    
                                    <!-- Fallback si image ne charge pas -->
                                    <div v-else-if="media.type === 'image'"
                                         class="w-full h-full bg-red-100 flex items-center justify-center border-2 border-red-200">
                                        <div class="text-center text-red-600">
                                            <i class="fas fa-exclamation-triangle text-3xl mb-2"></i>
                                            <p class="text-sm">Image non trouvée</p>
                                            <p class="text-xs">{{ media.file_url }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Vidéo placeholder -->
                                    <div v-else-if="media.type === 'video'"
                                         class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                        <div class="text-center text-white">
                                            <i class="fas fa-play text-4xl mb-2"></i>
                                            <p class="text-sm font-medium">Cliquer pour voir</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Bouton hover seulement -->
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="transform scale-75 group-hover:scale-100 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                            <div class="bg-white shadow-lg rounded-full p-3">
                                                <i class="fas fa-expand text-gray-800 text-lg"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Badge type -->
                                    <div class="absolute top-3 right-3">
                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                            <i :class="media.type === 'image' ? 'fas fa-image' : 'fas fa-video'" class="mr-1"></i>
                                            {{ media.type === 'image' ? 'Photo' : 'Vidéo' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Informations du média -->
                                <div class="p-4">
                                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-2">
                                        {{ media.title || media.project_title || 'Média sans titre' }}
                                    </h3>
                                    <p v-if="media.description" class="text-gray-600 text-sm line-clamp-2 mb-3">
                                        {{ media.description }}
                                    </p>
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <span v-if="media.project_title" class="flex items-center">
                                            <i class="fas fa-heart text-red-400 mr-1"></i>
                                            {{ media.project_title }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            Voir
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Message si aucun média -->
                    <div v-else class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                            <i class="fas fa-images text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun média disponible</h3>
                        <p class="text-gray-500 max-w-md mx-auto">
                            Nous travaillons à documenter nos actions. Les médias seront bientôt disponibles.
                        </p>
                    </div>
                    
                    <!-- Bouton voir plus -->
                    <div v-if="filteredMedia.length > 6" class="text-center mt-12">
                        <button class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-full font-medium transition-colors duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Voir toute la galerie ({{ filteredMedia.length }} médias)
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Beneficiaries Testimonials Section -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center mb-12">
                    <h2 class="title-font text-3xl font-bold text-gray-800 mb-4">Témoignages des bénéficiaires</h2>
                    <p class="text-lg text-gray-600">Découvrez comment notre projet solidaire change concrètement la vie des personnes malvoyantes.</p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="h-48 bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center relative">
                            <i class="fas fa-quote-left text-5xl text-blue-300 opacity-50"></i>
                            <div class="absolute bottom-4 left-4 bg-white bg-opacity-80 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-volume-up mr-1 text-blue-500"></i> Écouter
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 mr-3">
                                    <i class="fas fa-user text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Kodjo A.</h4>
                                    <p class="text-sm text-gray-500">Bénéficiaire depuis 2022 - Cotonou</p>
                                </div>
                            </div>
                            <p class="text-gray-600 italic">"Grâce aux formations financées par vos témoignages, j'ai pu apprendre le massage thérapeutique. Aujourd'hui, j'ai mon propre salon et je nourris ma famille avec dignité !"</p>
                            <div class="mt-4 text-sm text-gray-500">
                                <p>1 témoignage partagé = 1 formation offerte</p>
                                <p>42 commentaires = 1 heure d'accompagnement</p>
                            </div>
                            <div class="mt-4">
                                <div class="audio-player flex items-center p-3">
                                    <button class="bg-blue-500 text-white rounded-full w-10 h-10 flex items-center justify-center mr-3">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <div class="flex-grow">
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>0:00</span>
                                            <span>2:45</span>
                                        </div>
                                        <div class="bg-gray-200 rounded-full h-1">
                                            <div class="bg-blue-500 h-1 rounded-full" style="width: 30%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="h-48 bg-gradient-to-r from-purple-100 to-pink-100 flex items-center justify-center relative">
                            <i class="fas fa-quote-left text-5xl text-purple-300 opacity-50"></i>
                            <div class="absolute bottom-4 left-4 bg-white bg-opacity-80 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-video mr-1 text-purple-500"></i> Regarder
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-500 mr-3">
                                    <i class="fas fa-user text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Adjoa M.</h4>
                                    <p class="text-sm text-gray-500">Bénéficiaire depuis 2021 - Porto-Novo</p>
                                </div>
                            </div>
                            <p class="text-gray-600 italic">"Les ateliers d'orientation financés par vos contributions m'ont permis de me déplacer en ville avec confiance. Je peux maintenant aller au marché seule !"</p>
                            <div class="mt-4">
                                <button class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-play mr-2"></i> Voir le témoignage vidéo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Financial Reports Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto mb-12">
                    <div class="flex border-b border-gray-200">
                        <button class="px-6 py-3 tab-active">Bilans financiers</button>
                        <button class="px-6 py-3 text-gray-600 hover:text-red-500">Projets financés</button>
                        <button class="px-6 py-3 text-gray-600 hover:text-red-500">Suivi des dons</button>
                    </div>
                </div>
                
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-center mb-4">
                                <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-lg p-2 shadow-sm border border-red-100 mr-4">
                                    <img src="/assets/images/logo4.png" alt="Logo ONG Solidarité Malvoyants" class="w-8 h-8 object-contain">
                                </div>
                                <div>
                                    <h3 class="title-font text-2xl font-bold text-gray-800 mb-1">Transparence financière</h3>
                                    <p class="text-gray-600">Découvrez comment chaque euro est utilisé pour soutenir nos actions au Bénin.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div v-if="latestFinancialReport" class="p-6">
                            <h4 class="font-bold text-gray-800 mb-4">{{ latestFinancialReport.title }}</h4>
                            
                            <div class="grid md:grid-cols-2 gap-8 mb-8">
                                <div>
                                    <div class="h-64 flex items-center justify-center">
                                        <div class="w-64 h-64 relative">
                                            <!-- Pie chart placeholder - would be replaced with actual chart in production -->
                                            <div class="absolute inset-0 rounded-full border-8 border-red-500"></div>
                                            <div class="absolute inset-0 rounded-full border-8 border-blue-500" style="clip-path: polygon(50% 50%, 50% 0, 0 0, 0 50%);"></div>
                                            <div class="absolute inset-0 rounded-full border-8 border-yellow-500" style="clip-path: polygon(50% 50%, 0 50%, 0 100%, 50% 100%);"></div>
                                            <div class="absolute inset-0 rounded-full border-8 border-green-500" style="clip-path: polygon(50% 50%, 50% 100%, 100% 100%, 100% 50%);"></div>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="bg-white rounded-full w-32 h-32 flex items-center justify-center shadow-sm">
                                                    <span class="text-xl font-bold text-gray-700">{{ formatCurrency(latestFinancialReport.total_donations) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="space-y-4">
                                        <div v-for="(item, index) in latestFinancialReport.breakdown" :key="index" class="flex items-center">
                                            <div class="w-4 h-4 rounded-full mr-3"
                                                 :class="{
                                                    'bg-red-500': index % 4 === 0,
                                                    'bg-blue-500': index % 4 === 1, 
                                                    'bg-yellow-500': index % 4 === 2,
                                                    'bg-green-500': index % 4 === 3
                                                 }"></div>
                                            <div class="flex-grow">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-700">{{ item.category }}</span>
                                                    <span class="font-bold">{{ Math.round((item.amount / latestFinancialReport.total_expenses) * 100) }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Taux d'efficacité -->
                                        <div class="mt-6 p-4 bg-green-50 rounded-lg border border-green-200">
                                            <div class="flex justify-between items-center">
                                                <span class="text-green-800 font-medium">Taux d'efficacité</span>
                                                <span class="text-green-600 font-bold text-lg">{{ Math.round(latestFinancialReport.efficiency_rate) }}%</span>
                                            </div>
                                            <p class="text-green-600 text-sm mt-1">{{ Math.round(latestFinancialReport.efficiency_rate) }}% des dons vont directement aux bénéficiaires</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-8">
                                        <h5 class="font-bold text-gray-800 mb-2">Télécharger les rapports complets</h5>
                                        <div class="flex space-x-3">
                                            <button class="flex items-center text-gray-700 hover:text-red-500">
                                                <i class="fas fa-file-pdf mr-2"></i> PDF
                                            </button>
                                            <button class="flex items-center text-gray-700 hover:text-red-500">
                                                <i class="fas fa-file-excel mr-2"></i> Excel
                                            </button>
                                            <button class="flex items-center text-gray-700 hover:text-red-500">
                                                <i class="fas fa-file-alt mr-2"></i> Texte
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full financial-table">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projet</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dépensé</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avancement</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Formations professionnelles</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">€15,000</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">€12,750</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-24 h-2 bg-gray-200 rounded-full mr-2">
                                                        <div class="h-2 rounded-full bg-green-500" style="width: 85%"></div>
                                                    </div>
                                                    <span class="text-sm text-gray-600">85%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Cannes blanches</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">€10,000</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">€7,200</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-24 h-2 bg-gray-200 rounded-full mr-2">
                                                        <div class="h-2 rounded-full bg-blue-500" style="width: 72%"></div>
                                                    </div>
                                                    <span class="text-sm text-gray-600">72%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Ateliers mobilité</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">€8,000</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">€4,800</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-24 h-2 bg-gray-200 rounded-full mr-2">
                                                        <div class="h-2 rounded-full bg-yellow-500" style="width: 60%"></div>
                                                    </div>
                                                    <span class="text-sm text-gray-600">60%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Micro-crédits solidaires</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">€12,000</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">€6,000</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-24 h-2 bg-gray-200 rounded-full mr-2">
                                                        <div class="h-2 rounded-full bg-red-500" style="width: 50%"></div>
                                                    </div>
                                                    <span class="text-sm text-gray-600">50%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Message si aucun rapport financier -->
                        <div v-else class="p-12 text-center">
                            <i class="fas fa-chart-pie text-gray-400 text-6xl mb-4"></i>
                            <h4 class="text-lg font-bold text-gray-600 mb-2">Aucun rapport financier disponible</h4>
                            <p class="text-gray-500">Nous travaillons sur la préparation de nos rapports financiers. Ils seront bientôt disponibles.</p>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <!-- <p class="text-gray-600 mb-4">Vous souhaitez plus de détails sur nos finances ?</p> -->
                        <a href="#" class="inline-block bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-full font-medium transition duration-300">Contactez notre équipe</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Partners Section - Professional Design -->
        <section v-if="partners.length > 0" class="py-20 bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-10 right-10 w-32 h-32 bg-gradient-to-br from-blue-200 to-indigo-200 rounded-full opacity-20 animate-pulse"></div>
                <div class="absolute bottom-10 left-10 w-24 h-24 bg-gradient-to-br from-indigo-200 to-purple-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
            </div>
            
            <div class="container mx-auto px-4 relative z-10">
                <!-- Section Header -->
                <div class="max-w-5xl mx-auto text-center mb-16">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl shadow-lg mb-6">
                        <i class="fas fa-handshake text-white text-2xl"></i>
                    </div>
                    <h2 class="title-font text-4xl md:text-5xl font-bold bg-gradient-to-r from-gray-800 via-blue-600 to-indigo-600 bg-clip-text text-transparent mb-6">
                        Nos Partenaires de Confiance
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        Des organisations prestigieuses qui croient en notre mission et nous accompagnent dans notre lutte contre l'exclusion des personnes malvoyantes ou aveugles au Bénin.
                    </p>
                </div>

                <!-- Partners Grid - Compact Layout -->
                <div class="max-w-7xl mx-auto">
                    <!-- All Partners in a Single Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div v-for="(partner, index) in partners" 
                             :key="partner.id"
                             class="partner-card group cursor-pointer bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                             @click="partner.website_url ? window.open(partner.website_url, '_blank') : null">
                            <div class="flex flex-col items-center text-center">
                                <!-- Logo container avec couleur dynamique selon le type -->
                                <div class="w-16 h-16 rounded-xl flex items-center justify-center mb-3 group-hover:scale-105 transition-transform duration-300"
                                     :class="{
                                        'bg-gradient-to-br from-yellow-100 to-yellow-200': partner.category === 'mecenas',
                                        'bg-gradient-to-br from-green-100 to-green-200': partner.category === 'association',
                                        'bg-gradient-to-br from-purple-100 to-purple-200': partner.category === 'expert',
                                        'bg-gradient-to-br from-blue-100 to-blue-200': !partner.category || (partner.category !== 'mecenas' && partner.category !== 'association' && partner.category !== 'expert')
                                     }">
                                    <img v-if="partner.logo_url" 
                                         :src="partner.logo_url" 
                                         :alt="partner.name"
                                         class="max-w-12 max-h-12 object-contain partner-logo">
                                    <i v-else 
                                       :class="{
                                          'fas fa-crown text-yellow-500': partner.category === 'mecenas',
                                          'fas fa-users text-green-500': partner.category === 'association',
                                          'fas fa-user-tie text-purple-500': partner.category === 'expert',
                                          'fas fa-handshake text-blue-500': !partner.category || (partner.category !== 'mecenas' && partner.category !== 'association' && partner.category !== 'expert')
                                       }" 
                                       class="text-xl"></i>
                                </div>
                                
                                <!-- Nom du partenaire -->
                                <h4 class="font-semibold text-gray-800 text-sm mb-1 line-clamp-2">{{ partner.name }}</h4>
                                
                                <!-- Badge de catégorie (optionnel) -->
                                <div v-if="partner.category" class="text-xs px-2 py-1 rounded-full"
                                     :class="{
                                        'bg-yellow-100 text-yellow-700': partner.category === 'mecenas',
                                        'bg-green-100 text-green-700': partner.category === 'association',
                                        'bg-purple-100 text-purple-700': partner.category === 'expert'
                                     }">
                                    {{
                                        partner.category === 'mecenas' ? 'Mécène' :
                                        partner.category === 'association' ? 'Association' :
                                        partner.category === 'expert' ? 'Expert' :
                                        'Partenaire'
                                    }}
                                </div>
                                <div v-else class="text-xs text-gray-500">Partenaire</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Message si aucun partenaire -->
                    <div v-if="partners.length === 0" class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                            <i class="fas fa-handshake text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun partenaire pour le moment</h3>
                        <p class="text-gray-500 max-w-md mx-auto">
                            Nous travaillons à développer notre réseau de partenaires. Contactez-nous si vous souhaitez nous rejoindre.
                        </p>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="mt-16 bg-white/70 backdrop-blur-sm rounded-2xl p-8 border border-white/20 shadow-lg">
                        <div class="text-center mb-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Pourquoi nous faire confiance ?</h3>
                            <p class="text-gray-600">Des partenariats solides pour un impact durable</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-certificate text-white"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800 mb-2">Transparence</h4>
                                <p class="text-sm text-gray-600">Rapports financiers publics et suivi en temps réel</p>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800 mb-2">Collaboration</h4>
                                <p class="text-sm text-gray-600">Travail en réseau avec des experts reconnus</p>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800 mb-2">Impact</h4>
                                <p class="text-sm text-gray-600">Résultats mesurables et témoignages vérifiés</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Call to Action -->
                <div class="text-center mt-16">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl p-8 text-white shadow-xl">
                        <h3 class="text-2xl font-bold mb-4">Rejoignez notre réseau de partenaires</h3>
                        <p class="text-blue-100 mb-6 max-w-2xl mx-auto">
                            Ensemble, nous pouvons créer un impact plus grand et transformer durablement la vie des personnes malvoyantes ou aveugles au Bénin.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                            <Link href="/contact" 
                                  class="inline-flex items-center bg-white text-blue-600 hover:bg-gray-50 px-8 py-3 rounded-full font-semibold transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-envelope mr-2"></i>
                                Devenir partenaire
                            </Link>
                            <a href="#projects" 
                               class="inline-flex items-center border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-full font-semibold transition duration-300">
                                <i class="fas fa-eye mr-2"></i>
                                Voir nos projets
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Success Stories Section -->
        <section class="py-16 bg-gradient-to-r from-red-50 to-pink-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center mb-12">
                    <h2 class="title-font text-3xl font-bold text-gray-800 mb-4">Nos réalisations concrètes au Bénin</h2>
                    <p class="text-lg text-gray-600">Découvrez comment vos contributions transforment réellement des vies dans les communautés béninoises.</p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <div v-for="(project, index) in solidarityProjects.slice(0, 4)" :key="project.id" 
                         class="bg-white rounded-xl shadow-lg overflow-hidden">
                        
                        <!-- Image du projet ou placeholder -->
                        <div class="h-48 relative overflow-hidden">
                            <img v-if="project.featured_image_url" 
                                 :src="project.featured_image_url" 
                                 :alt="project.title"
                                 class="w-full h-full object-cover">
                            <div v-else 
                                 class="h-full flex items-center justify-center bg-gradient-to-r"
                                 :class="{
                                    'from-purple-500 to-pink-500': index % 4 === 0,
                                    'from-blue-500 to-teal-400': index % 4 === 1,
                                    'from-yellow-500 to-orange-500': index % 4 === 2,
                                    'from-green-500 to-emerald-400': index % 4 === 3
                                 }">
                                <i class="fas fa-heart text-white text-6xl opacity-50"></i>
                            </div>
                            
                            <!-- Progress overlay -->
                            <div class="absolute top-4 right-4 bg-white bg-opacity-90 px-3 py-1 rounded-full">
                                <span class="text-sm font-bold text-gray-800">{{ Math.round(project.progress_percentage) }}%</span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="title-font text-xl font-bold text-gray-800 mb-3">{{ project.title }}</h3>
                            <p class="text-gray-600 mb-4">{{ safeSubstring(project.description, 120) }}</p>
                            
                            <!-- Informations du projet -->
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Objectif:</span>
                                    <span class="font-medium text-gray-800">{{ formatCurrency(project.target_amount) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Collecté:</span>
                                    <span class="font-medium text-green-600">{{ formatCurrency(project.current_amount) }}</span>
                                </div>
                            </div>
                            
                            <!-- Barre de progression -->
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full transition-all duration-500"
                                     :style="`width: ${Math.min(project.progress_percentage, 100)}%`"></div>
                            </div>
                            
                            <!-- Informations bénéficiaires -->
                            <div v-if="project.beneficiaries_info" class="flex items-center text-gray-500">
                                <i class="fas fa-users mr-2"></i>
                                <span class="text-sm">
                                    {{ project.beneficiaries_info.count || 'Plusieurs' }} bénéficiaires
                                    <template v-if="project.beneficiaries_info.location">
                                        • {{ project.beneficiaries_info.location }}
                                    </template>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Message si pas de projets -->
                    <div v-if="solidarityProjects.length === 0" class="col-span-full text-center py-12">
                        <i class="fas fa-heart text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">Aucun projet de solidarité actif pour le moment</p>
                    </div>
                </div>
                
                <div class="text-center mt-12">
                    <div class="flex flex-col items-center">
                        <div class="mb-4">
                            <img src="/assets/images/logo4.png" alt="Logo ONG Solidarité Malvoyants" class="w-8 h-8 object-contain opacity-70">
                        </div>
                        <a href="#" class="inline-block bg-red-500 hover:bg-red-600 text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 shadow-lg">Voir plus de réalisations</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal pour les médias -->
        <div v-if="selectedMedia" 
             class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
             @click="closeMediaModal()">
            <div class="max-w-4xl w-full bg-white rounded-lg overflow-hidden" @click.stop>
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-bold text-gray-800">{{ selectedMedia.title || selectedMedia.project_title }}</h3>
                    <button @click="closeMediaModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-4">
                    <div v-if="selectedMedia.type === 'image'" class="text-center">
                        <img :src="selectedMedia.file_url" 
                             :alt="selectedMedia.alt_text || selectedMedia.title"
                             class="max-w-full max-h-96 mx-auto rounded">
                    </div>
                    <div v-else-if="selectedMedia.type === 'video'" class="text-center">
                        <video :src="selectedMedia.file_url" 
                               controls 
                               class="max-w-full max-h-96 mx-auto rounded">
                            Votre navigateur ne supporte pas les vidéos.
                        </video>
                    </div>
                    <div v-if="selectedMedia.description" class="mt-4">
                        <p class="text-gray-600">{{ selectedMedia.description }}</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal de don solidaire -->
        <SolidarityDonationModal 
            :show="showDonationModal"
            :is-authenticated="isAuthenticated"
            @close="closeDonationModal"
            @donation-completed="onDonationCompleted"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/Client/Layouts/MainLayout.vue';
import SolidarityDonationButton from '@/Client/Components/SolidarityDonationButton.vue';
import SolidarityDonationModal from '@/Client/Components/SolidarityDonationModal.vue';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// Props reçues du contrôleur
const props = defineProps({
    solidarityProjects: Array,
    projectMedia: Array, 
    partners: Array,
    latestFinancialReport: Object,
    allFinancialReports: Array,
    globalStats: Object,
    visuallyImpairedStats: Object,
    isAuthenticated: Boolean,
    user: Object
});

// État réactif pour la galerie média
const galleryType = ref('images');
const selectedMedia = ref(null);

// État pour le modal de don solidaire
const showDonationModal = ref(false);

// Données calculées
const filteredMedia = computed(() => {
    if (galleryType.value === 'images') {
        return props.projectMedia.filter(media => media.type === 'image');
    } else if (galleryType.value === 'videos') {
        return props.projectMedia.filter(media => media.type === 'video');
    }
    return props.projectMedia;
});

const featuredProjects = computed(() => {
    return props.solidarityProjects.slice(0, 3);
});

const partnersByCategory = computed(() => {
    const categories = {
        mecenas: [],
        association: [],
        expert: []
    };
    
    if (props.partners && Array.isArray(props.partners)) {
        props.partners.forEach(partner => {
            if (categories[partner.category]) {
                categories[partner.category].push(partner);
            }
        });
    }
    
    return categories;
});

// Méthodes
const formatCurrency = (amount, currency = 'EUR') => {
    if (!amount || isNaN(amount)) return '0 €';
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: currency
    }).format(amount);
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const safeSubstring = (text, length = 150) => {
    if (!text || typeof text !== 'string') return '';
    return text.length > length ? text.substring(0, length) + '...' : text;
};

const switchGalleryType = (type) => {
    galleryType.value = type;
};

const openMediaModal = (media) => {
    selectedMedia.value = media;
};

// Méthodes pour le modal de don solidaire
const openDonationModal = () => {
    showDonationModal.value = true;
};

const closeDonationModal = () => {
    showDonationModal.value = false;
};

const closeMediaModal = () => {
    selectedMedia.value = null;
};

// Gestion des dons solidaires
const onDonationCompleted = (donationData) => {
    console.log('Don solidaire complété:', donationData);
    // Mettre à jour les statistiques globales si nécessaire
    props.globalStats.total_donations += donationData.amount;
};
</script>

<style scoped>
.title-font {
    font-family: 'Playfair Display', serif;
}

.hero-gradient {
    background: linear-gradient(135deg, rgba(253,29,29,0.9) 0%, rgba(252,176,69,0.9) 100%);
}

.hero-pattern {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='m36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.donation-progress {
    height: 8px;
    background-color: #e5e7eb;
}

.donation-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #f87171, #ef4444);
    transition: width 0.5s ease;
}

.partner-logo {
    filter: grayscale(100%);
    opacity: 0.7;
    transition: all 0.3s ease;
}

.partner-logo:hover {
    filter: grayscale(0);
    opacity: 1;
}

.audio-player {
    background: rgba(0,0,0,0.05);
    border-radius: 50px;
}

.tab-active {
    border-bottom: 3px solid #ef4444;
    color: #ef4444;
    font-weight: 600;
}

.financial-table tr:nth-child(even) {
    background-color: #f9fafb;
}

.financial-table tr:hover {
    background-color: #f3f4f6;
}



.impact-number {
    font-size: 3rem;
    font-weight: 700;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.feature-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.feature-card:hover {
    border-color: #ef4444;
    transform: translateY(-5px);
}

/* Styles pour la section partenaires professionnelle */
.partner-card {
    position: relative;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.partner-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.partner-card-featured {
    position: relative;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    border: 2px solid transparent;
}

.partner-card-featured:hover {
    border-color: #3b82f6;
    box-shadow: 0 25px 50px rgba(59, 130, 246, 0.15);
}

.partner-logo {
    filter: grayscale(10%);
    transition: all 0.4s ease;
    object-fit: contain;
}

.partner-card:hover .partner-logo {
    filter: grayscale(0%) brightness(1.1) contrast(1.1);
}

/* Animations d'entrée séquentielles */
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

@keyframes slideInScale {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.partner-card {
    animation: slideInScale 0.6s ease-out forwards;
    opacity: 0;
}

.partner-card-featured {
    animation: slideInScale 0.8s ease-out forwards;
    opacity: 0;
}

.partner-card:nth-child(1) { animation-delay: 0.1s; }
.partner-card:nth-child(2) { animation-delay: 0.2s; }
.partner-card:nth-child(3) { animation-delay: 0.3s; }
.partner-card:nth-child(4) { animation-delay: 0.4s; }
.partner-card:nth-child(5) { animation-delay: 0.5s; }
.partner-card:nth-child(6) { animation-delay: 0.6s; }
.partner-card:nth-child(7) { animation-delay: 0.7s; }
.partner-card:nth-child(8) { animation-delay: 0.8s; }

.partner-card-featured:nth-child(1) { animation-delay: 0.2s; }
.partner-card-featured:nth-child(2) { animation-delay: 0.4s; }
.partner-card-featured:nth-child(3) { animation-delay: 0.6s; }

/* Effet de brillance subtile sur les cartes */
.partner-card::before,
.partner-card-featured::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.6s ease;
    z-index: 1;
    pointer-events: none;
}

.partner-card:hover::before,
.partner-card-featured:hover::before {
    left: 100%;
}

/* Effet de glow sur hover pour les cartes featured */
.partner-card-featured:hover {
    box-shadow: 
        0 25px 50px rgba(59, 130, 246, 0.15),
        0 0 0 1px rgba(59, 130, 246, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

/* Responsive adaptations */
@media (max-width: 768px) {
    .partner-card {
        transform: none;
    }
    
    .partner-card:hover {
        transform: translateY(-3px);
    }
    
    .partner-card-featured:hover {
        transform: translateY(-3px);
    }
}

/* Trust indicators animations */
@keyframes pulseGlow {
    0%, 100% {
        box-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
    }
    50% {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.6);
    }
}

.trust-indicator {
    animation: pulseGlow 3s ease-in-out infinite;
}

.trust-indicator:nth-child(2) {
    animation-delay: 1s;
}

.trust-indicator:nth-child(3) {
    animation-delay: 2s;
}

/* Classes utilitaires pour line-clamp */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>