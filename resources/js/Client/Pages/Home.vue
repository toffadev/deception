<template>
    <MainLayout>
        <!-- Hero Section -->
        <section class="relative min-h-screen flex items-center justify-center overflow-hidden py-2 md:py-4">
            <!-- Background slideshow -->
            <div class="absolute inset-0">
                <div class="relative w-full h-full">
                    <!-- Slideshow d'images -->
                    <div 
                        v-for="(image, index) in heroImages" 
                        :key="index"
                        :class="[
                            'absolute inset-0 transition-opacity duration-2000 ease-in-out',
                            currentImageIndex === index ? 'opacity-100' : 'opacity-0'
                        ]"
                    >
                        <img 
                            :src="image.url" 
                            :alt="image.alt" 
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                    </div>
                    
                    <!-- Gradient overlay -->
                <div class="absolute inset-0 hero-gradient"></div>
                    
                    <!-- Particules flottantes -->
                    <div class="absolute inset-0 overflow-hidden">
                        <div 
                            v-for="i in 20" 
                            :key="i"
                            class="absolute animate-float"
                            :style="{
                                left: Math.random() * 100 + '%',
                                top: Math.random() * 100 + '%',
                                animationDelay: Math.random() * 4 + 's',
                                animationDuration: (4 + Math.random() * 4) + 's'
                            }"
                        >
                            <div class="w-2 h-2 bg-white opacity-20 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contenu principal -->
            <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto py-2 md:py-4">
                <!-- Badge animé -->
                <div class="inline-flex items-center bg-white bg-opacity-90 backdrop-blur-sm rounded-full px-6 py-2 mb-8 animate-fade-in-up shadow-lg" style="animation-delay: 0.2s">
                    <i class="fas fa-heart text-red-500 mr-2"></i>
                    <span class="text-gray-800 font-medium">Plus de {{ formatNumber(globalStats.total_publications || 150) }} témoignages partagés</span>
                </div>
                
                <!-- Titre principal avec animation -->
                <h1 class="title-font text-2xl sm:text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-4 sm:mb-6 leading-tight animate-fade-in-up" style="animation-delay: 0.4s">
                    <span class="text-white">
                        Exprimez vos émotions,
                    </span>
                    <br>
                    <span class="text-yellow-300">
                        soutenez une cause noble
                    </span>
                </h1>
                
                <!-- Sous-titre avec animation -->
                <p class="text-base sm:text-lg md:text-xl text-white mb-6 sm:mb-8 leading-relaxed animate-fade-in-up opacity-90 px-2" style="animation-delay: 0.6s">
                    Un espace bienveillant pour partager votre histoire et contribuer à aider 
                    <span class="font-semibold text-yellow-200">les malvoyants</span> à travers le monde.
                </p>
                
                <!-- Statistiques rapides -->
                <div class="flex flex-wrap justify-center gap-3 sm:gap-4 mb-6 sm:mb-8 animate-fade-in-up px-2" style="animation-delay: 0.8s">
                    <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-gray-800 shadow-lg">
                        <div class="text-lg sm:text-xl font-bold text-red-600">{{ formatNumber(globalStats.current_year_donations || 25000) }}€</div>
                        <div class="text-xs sm:text-sm text-gray-600">Collectés cette année</div>
                    </div>
                    <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-gray-800 shadow-lg">
                        <div class="text-lg sm:text-xl font-bold text-blue-600">{{ globalStats.people_helped || 0 }}+</div>
                        <div class="text-xs sm:text-sm text-gray-600">Personnes aidées</div>
                    </div>
                    <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl px-3 sm:px-4 py-2 sm:py-3 text-gray-800 shadow-lg">
                        <div class="text-lg sm:text-xl font-bold text-green-600">{{ globalStats.countries_helped || 12 }}</div>
                        <div class="text-xs sm:text-sm text-gray-600">Pays concernés</div>
                    </div>
                </div>
                
                <!-- Boutons d'action avec animations -->
                <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 animate-fade-in-up px-2" style="animation-delay: 1s">
                    <Link href="/publication" 
                          class="group bg-white text-red-500 hover:bg-gray-100 px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-base sm:text-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105">
                        <i class="fas fa-book-open mr-2 group-hover:animate-pulse"></i>
                        Découvrir les publications
                    </Link>
                    <Link href="/solidarity" 
                          class="group bg-transparent border-2 border-white text-white hover:bg-white hover:text-red-500 px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-base sm:text-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105">
                        <i class="fas fa-hands-helping mr-2 group-hover:animate-pulse"></i>
                        Contribuer au projet
                    </Link>
            </div>
            
                <!-- Indicateur de scroll amélioré -->
                <div class="absolute bottom-16 md:bottom-10 left-1/2 transform -translate-x-1/2 animate-fade-in-up" style="animation-delay: 1.2s">
                    <a href="#about" class="text-white hover:text-yellow-200 transition-colors duration-300 group">
                        <div class="flex flex-col items-center">
                            <span class="text-sm mb-2 opacity-80 group-hover:opacity-100">Découvrir</span>
                            <div class="animate-bounce group-hover:animate-pulse">
                                <i class="fas fa-chevron-down text-2xl"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- Indicateurs de slides -->
            <div class="absolute bottom-14 md:bottom-20 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20">
                <button 
                    v-for="(image, index) in heroImages" 
                    :key="index"
                    @click="currentImageIndex = index"
                    :class="[
                        'w-3 h-3 rounded-full transition-all duration-300',
                        currentImageIndex === index 
                            ? 'bg-white scale-125' 
                            : 'bg-white bg-opacity-50 hover:bg-opacity-75'
                    ]"
                ></button>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center mb-12">
                    <h2 class="title-font text-2xl md:text-3xl font-bold text-gray-800 mb-4">Notre Mission</h2>
                    <p class="text-base text-gray-600 mb-6">
                        Von Tränen zu Taten est une plateforme où chaque histoire de déception amoureuse devient une opportunité de soutien. 
                        Nous croyons en la puissance thérapeutique du partage et en la solidarité humaine pour transformer la douleur en espoir.
                    </p>
                    <Link href="/solidarity" 
                          class="inline-block bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-full font-medium transition duration-300">
                        En savoir plus sur notre projet
                    </Link>
                </div>
                
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 p-6 rounded-xl text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-500 mx-auto mb-3">
                            <i class="fas fa-heart text-lg"></i>
                        </div>
                        <h3 class="title-font text-lg font-bold text-gray-800 mb-2">Expression Libre</h3>
                        <p class="text-gray-600 text-sm">
                            Partagez anonymement ou sous pseudonyme vos témoignages, poèmes et réflexions dans un espace bienveillant.
                        </p>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-xl text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-500 mx-auto mb-3">
                            <i class="fas fa-hands-helping text-lg"></i>
                        </div>
                        <h3 class="title-font text-lg font-bold text-gray-800 mb-2">Soutien Mutuel</h3>
                        <p class="text-gray-600 text-sm">
                            Interagissez avec bienveillance grâce aux commentaires et réactions émotionnelles pour créer des liens solides.
                        </p>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-xl text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-500 mx-auto mb-3">
                            <i class="fas fa-globe-americas text-lg"></i>
                        </div>
                        <h3 class="title-font text-lg font-bold text-gray-800 mb-2">Impact Solidaire</h3>
                        <p class="text-gray-600 text-sm">
                            Vos contributions financières soutiennent directement des projets pour les malvoyants, avec une totale transparence.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section - Dynamic with Carousel -->
        <section class="py-16 bg-gray-50 relative overflow-hidden">
            <!-- Éléments décoratifs -->
            <div class="absolute top-0 left-0 w-full h-full opacity-5">
                <div class="absolute top-20 right-20 w-40 h-40 bg-pink-300 rounded-full"></div>
                <div class="absolute bottom-20 left-20 w-32 h-32 bg-red-300 rounded-full"></div>
            </div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-center mb-12">
                    <div class="text-center md:text-left mb-6 md:mb-0">
                        <div class="inline-flex items-center bg-red-100 text-red-600 rounded-full px-4 py-2 mb-4">
                            <i class="fas fa-book-open mr-2"></i>
                            <span class="font-medium">Témoignages</span>
                        </div>
                        <h2 class="title-font text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                            Dernières publications
                        </h2>
                        <p class="text-gray-600 text-sm max-w-md">
                            Découvrez les histoires touchantes partagées par notre communauté
                        </p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Contrôles du carousel -->
                        <div v-if="featuredPublications.length > 3" class="flex space-x-2">
                            <button 
                                @click="previousSlide"
                                class="w-12 h-12 bg-white hover:bg-red-50 border border-gray-200 rounded-full flex items-center justify-center transition-all duration-300 hover:border-red-300 group"
                            >
                                <i class="fas fa-chevron-left text-gray-400 group-hover:text-red-500"></i>
                            </button>
                            <button 
                                @click="nextSlide"
                                class="w-12 h-12 bg-white hover:bg-red-50 border border-gray-200 rounded-full flex items-center justify-center transition-all duration-300 hover:border-red-300 group"
                            >
                                <i class="fas fa-chevron-right text-gray-400 group-hover:text-red-500"></i>
                            </button>
                        </div>
                        
                    <Link href="/publication" 
                              class="group bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-full font-medium flex items-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            Voir toutes les publications 
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                    </Link>
                    </div>
                </div>
                
                <div v-if="featuredPublications.length > 0" class="relative">
                    <!-- Carousel Container -->
                    <div class="overflow-hidden rounded-2xl">
                        <div 
                            class="flex transition-transform duration-500 ease-in-out"
                            :style="{ transform: `translateX(-${currentSlide * (100 / slidesToShow)}%)` }"
                        >
                            <div 
                                v-for="(publication, index) in featuredPublications" 
                         :key="publication.id"
                                class="flex-shrink-0 px-4"
                                :style="{ width: `${100 / slidesToShow}%` }"
                            >
                                <div 
                                    class="bg-white rounded-xl shadow-md overflow-hidden testimonial-card transition-all duration-500 cursor-pointer group hover:shadow-xl"
                                    @click="goToPublication(publication.slug)"
                                    :class="{ 'opacity-75 scale-95': !isSlideActive(index) }"
                                >
                                    <!-- Header coloré avec type -->
                                    <div class="relative h-48 overflow-hidden">
                                        <div :class="getGradientClass(publication.type)" class="h-full flex items-center justify-center relative">
                                            <i class="fas fa-quote-left text-5xl opacity-30 transform group-hover:scale-110 transition-transform duration-500" :class="getIconColor(publication.type)"></i>
                                            
                                            <!-- Badge du type -->
                                            <div class="absolute top-4 right-4">
                                                <span class="bg-white bg-opacity-90 text-xs font-semibold px-3 py-1 rounded-full" :class="getTypeColor(publication.type)">
                                                    {{ getTypeLabel(publication.type) }}
                                                </span>
                        </div>
                                            
                                            <!-- Overlay au hover -->
                                            <div class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Contenu -->
                        <div class="p-6">
                                        <!-- Auteur -->
                            <div class="flex items-center mb-4">
                                            <div class="w-10 h-10 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full mr-3 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ publication.author_name }}</h4>
                                                <p class="text-xs text-gray-500">{{ formatDate(publication.created_at) }}</p>
                                </div>
                            </div>
                                        
                                        <!-- Titre -->
                                        <h3 class="title-font text-xl font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-red-600 transition-colors duration-300">
                                            {{ publication.title }}
                                        </h3>
                                        
                                        <!-- Extrait -->
                                        <p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                                            {{ publication.excerpt }}
                                        </p>
                                        
                                        <!-- Statistiques -->
                                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                            <div class="flex space-x-4 text-sm text-gray-500">
                                                <span class="flex items-center hover:text-red-500 transition-colors">
                                                    <i class="far fa-heart mr-1"></i> 
                                                    {{ publication.reactions_count || 0 }}
                                                </span>
                                                <span class="flex items-center hover:text-blue-500 transition-colors">
                                                    <i class="far fa-comment mr-1"></i> 
                                                    {{ publication.comments_count || 0 }}
                                                </span>
                                            </div>
                                            <span v-if="isAuthenticated" class="bg-green-50 text-green-600 text-sm font-medium px-3 py-1 rounded-full cursor-pointer hover:bg-green-100 transition-colors"
                                                  @click.stop="openDonationModal(publication)">
                                                <i class="fas fa-coffee mr-1"></i> 
                                                Offrir un café
                                            </span>
                                            <span v-else class="bg-gray-50 text-gray-500 text-sm font-medium px-3 py-1 rounded-full cursor-pointer hover:bg-blue-50 hover:text-blue-600 transition-colors"
                                                  @click.stop="redirectToLogin"
                                                  title="Connectez-vous pour soutenir l'auteur">
                                                <i class="fas fa-sign-in-alt mr-1"></i> 
                                                Se connecter pour offrir
                                            </span>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Indicateurs de slides -->
                    <div v-if="featuredPublications.length > slidesToShow" class="flex justify-center mt-8 space-x-2">
                        <button 
                            v-for="(_, index) in Math.ceil(featuredPublications.length / slidesToShow)" 
                            :key="index"
                            @click="goToSlide(index)"
                            :class="[
                                'w-3 h-3 rounded-full transition-all duration-300',
                                currentSlide === index 
                                    ? 'bg-red-500 scale-125' 
                                    : 'bg-gray-300 hover:bg-red-300'
                            ]"
                        ></button>
                    </div>
                </div>

                <!-- Empty state amélioré -->
                <div v-else class="text-center py-20">
                    <div class="w-24 h-24 bg-gradient-to-r from-red-100 to-pink-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-heart text-3xl text-red-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-3">Aucune publication disponible</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">
                        Les premières publications apparaîtront bientôt ici. Soyez le premier à partager votre histoire !
                    </p>
                    <Link 
                        v-if="isAuthenticated" 
                        href="/publication#share-form" 
                        class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-full font-medium transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
                    >
                        <i class="fas fa-pen-fancy mr-2"></i>
                        Partager votre publication
                    </Link>
                    <Link 
                        v-else 
                        href="/auth/register" 
                        class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-full font-medium transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
                    >
                        <i class="fas fa-user-plus mr-2"></i>
                        Rejoindre la communauté
                    </Link>
                </div>
            </div>
        </section>

        <!-- Solidarity Project Section - Dynamic -->
        <section id="stats-section" class="py-16 bg-gradient-to-r from-red-50 to-pink-50 relative overflow-hidden">
            <!-- Éléments décoratifs de fond -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-10 left-10 w-32 h-32 bg-red-500 rounded-full"></div>
                <div class="absolute bottom-10 right-10 w-20 h-20 bg-pink-500 rounded-full"></div>
                <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-yellow-500 rounded-full"></div>
            </div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-4xl mx-auto text-center mb-16">
                    <div class="inline-flex items-center bg-red-100 text-red-600 rounded-full px-4 py-2 mb-6 animate-fade-in-up">
                        <i class="fas fa-heart mr-2"></i>
                        <span class="font-medium">Impact solidaire</span>
                    </div>
                    <h2 class="title-font text-2xl md:text-3xl font-bold text-gray-800 mb-4 animate-fade-in-up" style="animation-delay: 0.2s">
                        Notre Projet Solidaire
                    </h2>
                    <p class="text-base text-gray-600 animate-fade-in-up" style="animation-delay: 0.4s">
                        Chaque témoignage, chaque interaction et chaque don contribue à soutenir des projets pour les malvoyants et les personnes aveugles au Benin (Ouest du continent africain limitrophe au Togo). 
                        Nous garantissons une transparence totale sur l'utilisation des fonds.
                    </p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-6xl mx-auto animate-fade-in-up" style="animation-delay: 0.6s">
                    <div class="md:flex">
                        <div class="md:w-1/2 p-8 lg:p-12">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-chart-line text-white text-xl"></i>
                                </div>
                                <h3 class="title-font text-2xl font-bold text-gray-800">Nos réalisations</h3>
                            </div>
                            
                            <!-- Barre de progression améliorée -->
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="font-medium text-gray-700">Objectif annuel</span>
                                    <span class="font-bold text-red-500 text-lg">{{ formatCurrency(globalStats.annual_goal) }}</span>
                                </div>
                                <div class="donation-progress rounded-full relative overflow-hidden">
                                    <div 
                                        class="donation-progress-bar rounded-full transition-all duration-1000 ease-out" 
                                        :style="`width: ${isInView ? globalStats.goal_progress : 0}%`"
                                    ></div>
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-30 animate-pulse"></div>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-sm text-gray-500">
                                    {{ formatCurrency(globalStats.current_year_donations) }} collectés
                                    </span>
                                    <span class="text-sm font-medium text-red-600">
                                        {{ Math.round(globalStats.goal_progress || 0) }}%
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Statistiques avec compteurs animés -->
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="bg-gradient-to-br from-red-50 to-pink-50 p-6 rounded-xl text-center hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-project-diagram text-red-500"></i>
                                </div>
                                    <div class="text-red-500 text-3xl font-bold mb-2">
                                        <AnimatedCounter 
                                            :target="globalStats.completed_projects || 15" 
                                            :is-in-view="isInView"
                                        />
                                </div>
                                    <div class="text-gray-600 text-sm font-medium">Projets financés</div>
                                </div>
                                
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl text-center hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-users text-blue-500"></i>
                                    </div>
                                    <div class="text-blue-500 text-3xl font-bold mb-2">
                                        <AnimatedCounter 
                                            :target="globalStats.people_helped || 500" 
                                            :is-in-view="isInView"
                                            suffix="+"
                                        />
                                    </div>
                                    <div class="text-gray-600 text-sm font-medium">Personnes aidées</div>
                                </div>
                                
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-xl text-center hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-percentage text-green-500"></i>
                                    </div>
                                    <div class="text-green-500 text-3xl font-bold mb-2">
                                        <AnimatedCounter 
                                            :target="globalStats.efficiency_rate || 92" 
                                            :is-in-view="isInView"
                                            suffix="%"
                                        />
                                    </div>
                                    <div class="text-gray-600 text-sm font-medium">Fonds alloués</div>
                                </div>
                                
                                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-6 rounded-xl text-center hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-globe text-yellow-500"></i>
                                    </div>
                                    <div class="text-yellow-500 text-3xl font-bold mb-2">
                                        <AnimatedCounter 
                                            :target="globalStats.countries_helped || 12" 
                                            :is-in-view="isInView"
                                        />
                                    </div>
                                    <div class="text-gray-600 text-sm font-medium">Pays concernés</div>
                                </div>
                            </div>
                            
                            <Link href="/solidarity" 
                                  class="group block w-full bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white text-center py-4 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                                <i class="fas fa-arrow-right mr-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                                Voir le détail des projets
                            </Link>
                        </div>
                        
                        <!-- Galerie de médias améliorée -->
                        <div class="md:w-1/2 bg-gradient-to-br from-gray-50 to-gray-100 p-6">
                            <div class="h-full flex flex-col">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-images text-white text-sm"></i>
                                    </div>
                                    <h4 class="font-semibold text-gray-800">Galerie projets</h4>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 flex-grow">
                                <div v-for="(media, index) in projectMedia.slice(0, 3)" 
                                     :key="media.id"
                                         class="relative h-32 bg-gray-300 rounded-xl overflow-hidden group cursor-pointer hover:shadow-lg transition-all duration-300"
                                         :class="index === 0 ? 'col-span-2 h-40' : ''"
                                    >
                                    <img v-if="media.type === 'image'" 
                                         :src="media.file_url" 
                                         :alt="media.alt_text || media.title"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div v-else 
                                         class="w-full h-full flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-500">
                                            <i class="fas fa-play text-white text-3xl group-hover:scale-125 transition-transform duration-300"></i>
                                        </div>
                                        
                                                                                <!-- Overlay au hover -->
                                        <div class="absolute inset-0  bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                            <i class="fas fa-expand text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                                        </div>
                                </div>
                                
                                <!-- Show "+N more" if we have more than 3 media -->
                                <div v-if="projectMedia.length > 3"
                                         class="relative h-32 bg-gray-300 rounded-xl overflow-hidden group cursor-pointer hover:shadow-lg transition-all duration-300">
                                    <div v-if="projectMedia[3]" class="absolute inset-0">
                                        <img v-if="projectMedia[3].type === 'image'" 
                                             :src="projectMedia[3].file_url" 
                                             :alt="projectMedia[3].alt_text"
                                             class="w-full h-full object-cover">
                                        <div v-else 
                                             class="w-full h-full bg-gradient-to-r from-purple-500 to-pink-500"></div>
                                    </div>
                                                                                <div class="absolute inset-0  bg-opacity-60 flex items-center justify-center">
                                            <div class="text-center text-white">
                                                <div class="text-2xl font-bold">+{{ projectMedia.length - 3 }}</div>
                                                <div class="text-xs">Plus de photos</div>
                                            </div>
                                        </div>
                                </div>
                                
                                <!-- Fallback images if no media -->
                                    <template v-if="projectMedia.length === 0">
                                        <div v-for="n in 4" :key="`fallback-${n}`"
                                             class="relative h-32 bg-gray-300 rounded-xl overflow-hidden group"
                                             :class="n === 1 ? 'col-span-2 h-40' : ''"
                                        >
                                    <img :src="`https://images.unsplash.com/photo-${n === 1 ? '1515378791036-0648a3ef77b2' : n === 2 ? '1503676260728-1c00da094a0d' : n === 3 ? '1529333166437-7750a6dd5a70' : '1521791055366-0d553872125f'}?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80`" 
                                                 alt="Projet solidarité" 
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                    </template>
                                </div>
                                
                                <Link href="/solidarity" 
                                      class="mt-4 text-center text-sm text-gray-600 hover:text-red-500 transition-colors duration-300">
                                    <i class="fas fa-images mr-1"></i>
                                    Voir toute la galerie
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How to Contribute Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center mb-12">
                    <h2 class="title-font text-2xl md:text-3xl font-bold text-gray-800 mb-4">Comment contribuer ?</h2>
                    <p class="text-base text-gray-600">Plusieurs façons de soutenir notre communauté et nos actions solidaires.</p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 hover:border-red-300 transition duration-300">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-500 mx-auto mb-4">
                            <i class="fas fa-coffee text-lg"></i>
                        </div>
                        <h3 class="title-font text-xl font-bold text-center text-gray-800 mb-3">Offrir un café</h3>
                        <p class="text-gray-600 text-center text-sm mb-4">
                            Soutenez directement les auteurs qui partagent leurs témoignages. 
                            100% de votre contribution va à l'auteur de votre choix.
                        </p>
                        <div class="flex justify-center space-x-2">
                            <button class="bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-full text-sm hover:bg-gray-100 transition duration-300">€2</button>
                            <button class="bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-full text-sm hover:bg-gray-100 transition duration-300">€5</button>
                            <button class="bg-red-500 text-white px-3 py-2 rounded-full text-sm hover:bg-red-600 transition duration-300">€10</button>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 hover:border-red-300 transition duration-300">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-500 mx-auto mb-4">
                            <i class="fas fa-hand-holding-heart text-lg"></i>
                        </div>
                        <h3 class="title-font text-xl font-bold text-center text-gray-800 mb-3">Faire un don</h3>
                        <p class="text-gray-600 text-center text-sm mb-4">
                            Contribuez à nos projets solidaires pour les malvoyants. 
                            Chaque euro compte et nous publions des bilans financiers détaillés.
                        </p>
                        <div class="flex justify-center space-x-2">
                            <button class="bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-full text-sm hover:bg-gray-100 transition duration-300">€10</button>
                            <button class="bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-full text-sm hover:bg-gray-100 transition duration-300">€20</button>
                            <button class="bg-red-500 text-white px-3 py-2 rounded-full text-sm hover:bg-red-600 transition duration-300">€50</button>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-12">
                    <Link href="/solidarity" 
                          class="inline-block bg-red-500 hover:bg-red-600 text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 shadow-lg">
                        Je soutiens maintenant
                    </Link>
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
                                        href="/solidarity" 
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

        <!-- Partners Section - Modern Compact Design -->
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
                    <h2 class="title-font text-3xl md:text-4xl font-bold bg-gradient-to-r from-gray-800 via-blue-600 to-indigo-600 bg-clip-text text-transparent mb-6">
                        Nos Partenaires de Confiance
                    </h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        Des organisations prestigieuses qui croient en notre mission et nous accompagnent dans notre impact solidaire.
                    </p>
                </div>

                <!-- Partners Grid - Compact Layout -->
                <div class="max-w-6xl mx-auto">
                    <!-- All Partners in a Single Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
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
                    
                    <!-- Trust Indicators -->
                    <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-8 border border-white/20 shadow-lg mb-8">
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Pourquoi nous faire confiance ?</h3>
                            <p class="text-gray-600">Des partenariats solides pour un impact durable</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                <div class="text-center">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl p-8 text-white shadow-xl max-w-4xl mx-auto">
                        <h3 class="text-2xl font-bold mb-4">Rejoignez notre réseau de partenaires</h3>
                        <p class="text-blue-100 mb-6 max-w-2xl mx-auto">
                            Ensemble, nous pouvons créer un impact plus grand et transformer durablement la vie des personnes malvoyantes.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                            <Link href="/contact" 
                                  class="inline-flex items-center bg-white text-blue-600 hover:bg-gray-50 px-8 py-3 rounded-full font-semibold transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-envelope mr-2"></i>
                                Devenir partenaire
                            </Link>
                            <Link href="/solidarity" 
                                  class="inline-flex items-center border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-full font-semibold transition duration-300">
                                <i class="fas fa-eye mr-2"></i>
                                Voir nos projets
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- User Testimonials Section -->
        <section class="py-20 bg-gradient-to-r from-red-500 to-pink-500">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center mb-16">
                    <h2 class="title-font text-3xl md:text-4xl font-bold text-white mb-6">Ce qu'ils disent de nous</h2>
                    <p class="text-lg text-red-100">Des témoignages de notre communauté et des bénéficiaires de nos actions.</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white bg-opacity-90 rounded-xl p-8 shadow-lg">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gray-200 rounded-full mr-4 overflow-hidden">
                                <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="User" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Sophie L.</h4>
                                <p class="text-sm text-red-500">Membre depuis 2022</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">
                            "Grâce à cette plateforme, j'ai pu exprimer ma douleur et recevoir un soutien incroyable. 
                            Savoir que mon témoignage aide aussi des malvoyants donne un sens à mon expérience."
                        </p>
                    </div>
                    
                    <div class="bg-white bg-opacity-90 rounded-xl p-8 shadow-lg">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gray-200 rounded-full mr-4 overflow-hidden">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Jean P.</h4>
                                <p class="text-sm text-red-500">Bénéficiaire</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">
                            "Le matériel adapté financé par cette communauté a changé ma vie de malvoyant. 
                            Je peux maintenant lire seul grâce à leur soutien."
                        </p>
                    </div>
                    
                    <div class="bg-white bg-opacity-90 rounded-xl p-8 shadow-lg">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gray-200 rounded-full mr-4 overflow-hidden">
                                <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="User" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Émilie T.</h4>
                                <p class="text-sm text-red-500">Auteure soutenue</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">
                            "Les cafés offerts par les lecteurs m'ont permis de consacrer plus de temps à l'écriture 
                            tout en soutenant une noble cause. Une belle synergie!"
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter Section Interactive -->
        <section class="py-16 bg-white relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, rgba(239, 68, 68, 0.3) 0%, transparent 50%), radial-gradient(circle at 80% 50%, rgba(236, 72, 153, 0.3) 0%, transparent 50%);"></div>
            </div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-4xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center bg-blue-100 text-blue-600 rounded-full px-4 py-2 mb-6">
                            <i class="fas fa-envelope mr-2"></i>
                            <span class="font-medium">Newsletter</span>
                        </div>
                        <h2 class="title-font text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                            Restez connecté(e) à notre mission
                        </h2>
                        <p class="text-base text-gray-600 max-w-2xl mx-auto">
                            Recevez des témoignages inspirants, des nouvelles de nos projets solidaires 
                            et des invitations à nos événements communautaires.
                        </p>
                    </div>
                    
                    <!-- Newsletter Card -->
                    <div class="bg-gradient-to-r from-red-50 via-pink-50 to-purple-50 rounded-2xl p-6 md:p-8 shadow-xl relative overflow-hidden">
                        <!-- Decorative elements -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-200 to-pink-200 rounded-full -translate-y-16 translate-x-16 opacity-20"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-purple-200 to-pink-200 rounded-full translate-y-12 -translate-x-12 opacity-20"></div>
                        
                        <div class="relative z-10">
                            <!-- Benefits Grid -->
                            <div class="grid md:grid-cols-3 gap-4 mb-6">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-white bg-opacity-60 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                                        <i class="fas fa-heart text-red-500 text-lg"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-800 mb-1 text-sm">Témoignages exclusifs</h3>
                                    <p class="text-xs text-gray-600">Histoires inspirantes en avant-première</p>
                                </div>
                                
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-white bg-opacity-60 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                                        <i class="fas fa-chart-line text-blue-500 text-lg"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-800 mb-1 text-sm">Impact transparent</h3>
                                    <p class="text-xs text-gray-600">Rapports détaillés de nos actions</p>
                                </div>
                                
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-white bg-opacity-60 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                                        <i class="fas fa-calendar-alt text-purple-500 text-lg"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-800 mb-1 text-sm">Événements privilégiés</h3>
                                    <p class="text-xs text-gray-600">Invitations à nos événements</p>
                                </div>
                            </div>
                            
                            <!-- Newsletter Form -->
                            <form class="space-y-4" @submit.prevent="subscribeNewsletter">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="flex-grow relative">
                                        <input 
                                            v-model="newsletterEmail" 
                               type="email" 
                                            placeholder="Votre adresse email" 
                               required
                                            :class="[
                                                'w-full px-6 py-4 rounded-xl border-2 transition-all duration-300 focus:outline-none',
                                                newsletterStatus === 'error' 
                                                    ? 'border-red-300 focus:border-red-500 bg-red-50' 
                                                    : newsletterStatus === 'success'
                                                    ? 'border-green-300 focus:border-green-500 bg-green-50'
                                                    : 'border-gray-200 focus:border-red-500 bg-white'
                                            ]"
                                        >
                                        <div v-if="newsletterStatus === 'success'" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        </div>
                                        <div v-if="newsletterStatus === 'error'" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                            <i class="fas fa-exclamation-circle text-red-500"></i>
                                        </div>
                                    </div>
                                    <button 
                                        type="submit" 
                                        :disabled="newsletterLoading || !newsletterEmail"
                                        class="group bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 disabled:from-gray-400 disabled:to-gray-400 text-white px-8 py-4 rounded-xl font-bold transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1 disabled:hover:translate-y-0 disabled:hover:shadow-lg whitespace-nowrap"
                                    >
                                        <span v-if="newsletterLoading" class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Inscription...
                                        </span>
                                        <span v-else class="flex items-center">
                                            <i class="fas fa-paper-plane mr-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                                            S'abonner
                                        </span>
                        </button>
                                </div>
                                
                                <!-- Status Messages -->
                                <Transition
                                    enter-active-class="transition ease-out duration-300"
                                    enter-from-class="opacity-0 transform translate-y-2"
                                    enter-to-class="opacity-100 transform translate-y-0"
                                    leave-active-class="transition ease-in duration-200"
                                    leave-from-class="opacity-100 transform translate-y-0"
                                    leave-to-class="opacity-0 transform translate-y-2"
                                >
                                    <div v-if="newsletterMessage" :class="[
                                        'text-center p-4 rounded-lg',
                                        newsletterStatus === 'success' 
                                            ? 'bg-green-100 text-green-800 border border-green-200' 
                                            : 'bg-red-100 text-red-800 border border-red-200'
                                    ]">
                                        <i :class="[
                                            'mr-2',
                                            newsletterStatus === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'
                                        ]"></i>
                                        {{ newsletterMessage }}
                                    </div>
                                </Transition>
                    </form>
                    
                            <!-- Privacy Notice -->
                            <div class="flex items-center justify-center mt-6 text-sm text-gray-600">
                                <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                <span>Nous respectons votre vie privée. Désabonnement en un clic.</span>
                            </div>
                            
                            <!-- Social Proof -->
                            <div class="flex items-center justify-center mt-4 space-x-4 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-users mr-2 text-blue-500"></i>
                                    <span>{{ formatNumber(globalStats.newsletter_subscribers || 1250) }}+ abonnés</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-star mr-2 text-yellow-500"></i>
                                    <span>4.9/5 satisfaction</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-gradient-to-r from-red-600 to-pink-600">
            <div class="container mx-auto px-4 text-center">
                <h2 class="title-font text-3xl font-bold text-white mb-6">Prêt à partager votre histoire ?</h2>
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                    Rejoignez notre communauté bienveillante et transformez votre expérience en soutien pour les autres.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <Link v-if="isAuthenticated" 
                          href="/publication#share-form" 
                          class="bg-red-500 hover:bg-red-600 text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 shadow-lg">
                        Partager une publication
                    </Link>
                    <Link v-else 
                          href="/auth/register" 
                          class="bg-red-500 hover:bg-red-600 text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 shadow-lg">
                        Partager une publication
                    </Link>
                    <Link href="/solidarity" 
                          class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-red-600 px-8 py-4 rounded-full font-bold text-lg transition duration-300">
                        Découvrir comment ça marche
                    </Link>
                </div>
            </div>
        </section>
        
        <!-- Scroll to Top Button -->
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 scale-75 translate-y-4"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-75 translate-y-4"
        >
            <button
                v-if="showScrollTop"
                @click="scrollToTop"
                class="fixed bottom-8 right-8 w-14 h-14 bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center z-50 group hover:scale-110"
                aria-label="Retour en haut"
            >
                <i class="fas fa-chevron-up text-lg group-hover:-translate-y-1 transition-transform duration-300"></i>
            </button>
        </Transition>

        <!-- Modal de don -->
        <DonationModal 
            :show="showDonationModal"
            :publication="selectedPublication"
            :is-authenticated="isAuthenticated"
            @close="closeDonationModal"
            @donation-completed="() => console.log('Don complété depuis Home')"
        />
    </MainLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Client/Layouts/MainLayout.vue';
import AnimatedCounter from '@/Client/Components/AnimatedCounter.vue';
import DonationModal from '@/Client/Components/DonationModal.vue';

// Props reçues du contrôleur
const props = defineProps({
    featuredPublications: Array,
    solidarityProjects: Object,
    partners: Array,
    globalStats: Object,
    projectMedia: Array,
    recentDonations: Array,
    visuallyImpairedStats: Object,
    isAuthenticated: Boolean,
    user: Object,
    error: String
});

// État réactif pour la newsletter
const newsletterEmail = ref('');
const newsletterLoading = ref(false);
const newsletterStatus = ref(''); // 'success', 'error', ''
const newsletterMessage = ref('');

// État pour le slideshow et les animations
const currentImageIndex = ref(0);
const isInView = ref(false);

// État pour le carousel des publications
const currentSlide = ref(0);
const slidesToShow = ref(3); // Nombre de slides visibles

// État pour le bouton scroll to top
const showScrollTop = ref(false);

// État pour le modal de don
const showDonationModal = ref(false);
const selectedPublication = ref(null);

// Images pour le slideshow du hero
const heroImages = ref([
    {
        url: 'https://images.unsplash.com/photo-1492106087820-71f1a00d2b11?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
        alt: 'Cœur brisé et solidarité'
    },
    {
        url: 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
        alt: 'Entraide et soutien communautaire'
    },
    {
        url: 'https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
        alt: 'Aide aux malvoyants'
    },
    {
        url: 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
        alt: 'Compassion et partage'
    }
]);

// Interval pour le slideshow
let slideshowInterval = null;

// Méthodes utilitaires
const formatNumber = (number) => {
    if (!number || isNaN(number)) return '0';
    return new Intl.NumberFormat('fr-FR').format(number);
};

// Gestion du slideshow
const startSlideshow = () => {
    slideshowInterval = setInterval(() => {
        currentImageIndex.value = (currentImageIndex.value + 1) % heroImages.value.length;
    }, 5000); // Change toutes les 5 secondes
};

const stopSlideshow = () => {
    if (slideshowInterval) {
        clearInterval(slideshowInterval);
        slideshowInterval = null;
    }
};

// Intersection Observer pour les animations
const setupIntersectionObserver = () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting && entry.target.id === 'stats-section') {
                isInView.value = true;
            }
        });
    }, {
        threshold: 0.3 // Déclenche quand 30% de l'élément est visible
    });

    // Observer la section des statistiques
    const statsSection = document.getElementById('stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    }
};

// Fonctions pour le carousel des publications
const nextSlide = () => {
    const maxSlides = Math.ceil(props.featuredPublications.length / slidesToShow.value);
    currentSlide.value = (currentSlide.value + 1) % maxSlides;
};

const previousSlide = () => {
    const maxSlides = Math.ceil(props.featuredPublications.length / slidesToShow.value);
    currentSlide.value = currentSlide.value === 0 ? maxSlides - 1 : currentSlide.value - 1;
};

const goToSlide = (index) => {
    currentSlide.value = index;
};

const isSlideActive = (index) => {
    const startIndex = currentSlide.value * slidesToShow.value;
    const endIndex = startIndex + slidesToShow.value;
    return index >= startIndex && index < endIndex;
};

// Fonctions utilitaires pour les publications
const getTypeLabel = (type) => {
    const labels = {
        'testimony': 'Témoignage',
        'poetry': 'Poème',
        'reflection': 'Réflexion'
    };
    return labels[type] || type;
};

const getGradientClass = (type) => {
    const classes = {
        'testimony': 'bg-gradient-to-br from-pink-100 to-red-100',
        'poetry': 'bg-gradient-to-br from-blue-100 to-indigo-100',
        'reflection': 'bg-gradient-to-br from-purple-100 to-indigo-100'
    };
    return classes[type] || 'bg-gradient-to-br from-gray-100 to-gray-200';
};

const getIconColor = (type) => {
    const colors = {
        'testimony': 'text-red-300',
        'poetry': 'text-blue-300',
        'reflection': 'text-purple-300'
    };
    return colors[type] || 'text-gray-300';
};

const getTypeColor = (type) => {
    const colors = {
        'testimony': 'text-red-500',
        'poetry': 'text-blue-500',
        'reflection': 'text-purple-500'
    };
    return colors[type] || 'text-gray-500';
};

// Ajustement responsive du nombre de slides
const updateSlidesToShow = () => {
    if (window.innerWidth < 768) {
        slidesToShow.value = 1;
    } else if (window.innerWidth < 1024) {
        slidesToShow.value = 2;
    } else {
        slidesToShow.value = 3;
    }
};

// Fonction pour remonter en haut de page
const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

// Gestion du scroll pour le bouton scroll-to-top
const handlePageScroll = () => {
    showScrollTop.value = window.scrollY > 300;
};

// Lifecycle hooks
onMounted(() => {
    startSlideshow();
    setupIntersectionObserver();
    updateSlidesToShow();
    window.addEventListener('resize', updateSlidesToShow);
    window.addEventListener('scroll', handlePageScroll);
});

onUnmounted(() => {
    stopSlideshow();
    window.removeEventListener('resize', updateSlidesToShow);
    window.removeEventListener('scroll', handlePageScroll);
});

// Méthodes
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

const formatCurrency = (amount, currency = 'EUR') => {
    if (!amount || isNaN(amount)) return '0 €';
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: currency
    }).format(amount);
};

const goToPublication = (slug) => {
    router.visit(`/publication/${slug}`);
};

const openDonationModal = (publication) => {
    selectedPublication.value = publication;
    showDonationModal.value = true;
};

const closeDonationModal = () => {
    showDonationModal.value = false;
    selectedPublication.value = null;
};

const redirectToLogin = () => {
    router.visit('/auth/login', {
        data: {
            redirect: window.location.pathname
        }
    });
};

const subscribeNewsletter = async () => {
    if (!newsletterEmail.value.trim()) return;
    
    // Reset des messages précédents
    newsletterStatus.value = '';
    newsletterMessage.value = '';
    newsletterLoading.value = true;
    
    try {
        // Validation côté client
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(newsletterEmail.value)) {
            throw new Error('Veuillez entrer une adresse email valide.');
        }
        
        // Simulation d'inscription newsletter avec API
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        // Simulation d'une réponse réussie
        const success = Math.random() > 0.1; // 90% de chance de succès pour la démo
        
        if (success) {
            newsletterStatus.value = 'success';
            newsletterMessage.value = '🎉 Merci ! Vous êtes maintenant abonné(e) à notre newsletter. Vérifiez votre boîte email pour confirmer votre inscription.';
            
            // Reset du formulaire après succès
            setTimeout(() => {
                newsletterEmail.value = '';
                newsletterStatus.value = '';
                newsletterMessage.value = '';
            }, 5000);
        } else {
            throw new Error('Cette adresse email est déjà inscrite à notre newsletter.');
        }
        
    } catch (error) {
        newsletterStatus.value = 'error';
        newsletterMessage.value = error.message || 'Une erreur est survenue. Veuillez réessayer plus tard.';
        
        // Auto-clear error message
        setTimeout(() => {
            newsletterStatus.value = '';
            newsletterMessage.value = '';
        }, 5000);
        
        console.error('Erreur lors de l\'inscription à la newsletter:', error);
    } finally {
        newsletterLoading.value = false;
    }
};

// Log des erreurs si présentes
if (props.error) {
    console.error('Erreur de chargement de la page d\'accueil:', props.error);
}
</script>

<style scoped>
.title-font {
    font-family: 'Playfair Display', serif;
}

.hero-gradient {
    background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(239,68,68,0.6) 100%);
}

.testimonial-card {
    transition: all 0.3s ease;
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.donation-progress {
    height: 12px;
    background-color: #e5e7eb;
    position: relative;
}

.donation-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #f87171, #ef4444, #dc2626);
    transition: width 1s ease-out;
    position: relative;
}

/* Styles pour la section partenaires moderne */
.partner-card {
    position: relative;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.partner-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
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

.partner-card:nth-child(1) { animation-delay: 0.1s; }
.partner-card:nth-child(2) { animation-delay: 0.2s; }
.partner-card:nth-child(3) { animation-delay: 0.3s; }
.partner-card:nth-child(4) { animation-delay: 0.4s; }
.partner-card:nth-child(5) { animation-delay: 0.5s; }
.partner-card:nth-child(6) { animation-delay: 0.6s; }
.partner-card:nth-child(7) { animation-delay: 0.7s; }
.partner-card:nth-child(8) { animation-delay: 0.8s; }

/* Effet de brillance subtile sur les cartes */
.partner-card::before {
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

.partner-card:hover::before {
    left: 100%;
}

/* Responsive adaptations */
@media (max-width: 768px) {
    .partner-card {
        transform: none;
    }
    
    .partner-card:hover {
        transform: translateY(-3px);
    }
}

/* Animations personnalisées */
@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

@keyframes pulse-glow {
    0%, 100% {
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
    }
    50% {
        box-shadow: 0 0 30px rgba(239, 68, 68, 0.6);
    }
}

.animate-fade-in-up {
    opacity: 0;
    animation: fadeInUp 0.8s ease-out forwards;
}

.animate-float {
    animation: float ease-in-out infinite;
}

.animate-pulse-glow {
    animation: pulse-glow 2s ease-in-out infinite;
}

/* Animations pour les éléments au scroll */
.stats-card {
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

/* Animation de chargement pour les compteurs */
.counter-loading {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* Améliorations des transitions */
.group:hover .group-hover\:animate-pulse {
    animation: pulse 1s infinite;
}

.hover-lift {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Responsive pour les animations */
@media (prefers-reduced-motion: reduce) {
    .animate-fade-in-up,
    .animate-float,
    .animate-pulse-glow {
        animation: none;
    }
    
    .stats-card:hover,
    .testimonial-card:hover,
    .hover-lift:hover {
        transform: none;
    }
}

/* Dégradés améliorés */
.gradient-text {
    background: linear-gradient(135deg, #ef4444, #ec4899, #f59e0b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Effets de glassmorphism */
.glass-effect {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Classes utilitaires pour le text truncation */
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

/* Carousel styles */
.carousel-transition {
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hover effects pour le carousel */
.carousel-slide:not(.active-slide) {
    transition: all 0.3s ease;
}

.carousel-slide:not(.active-slide):hover {
    transform: scale(1.02);
}
</style>