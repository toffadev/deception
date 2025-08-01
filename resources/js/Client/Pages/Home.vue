<template>
    <MainLayout>
          <!-- Hero Section -->
        <section class="relative h-screen flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1492106087820-71f1a00d2b11?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                alt="Heartbreak and solidarity" class="w-full h-full object-cover">
            <div class="absolute inset-0 hero-gradient"></div>
            </div>
            
            <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="title-font text-4xl md:text-6xl font-bold text-white mb-6">Exprimez vos émotions, soutenez une cause noble</h1>
            <p class="text-xl text-white mb-10">Un espace bienveillant pour partager votre histoire et contribuer à aider les malvoyants à travers le monde.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#" class="bg-white text-red-500 hover:bg-gray-100 px-8 py-4 rounded-full font-bold text-lg transition duration-300 shadow-lg">Découvrir les témoignages</a>
                <a href="#" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-red-500 px-8 py-4 rounded-full font-bold text-lg transition duration-300 shadow-lg">Contribuer au projet</a>
            </div>
            </div>
            
            <div class="absolute bottom-10 left-0 right-0 flex justify-center">
            <a href="#about" class="text-white animate-bounce">
                <i class="fas fa-chevron-down text-3xl"></i>
            </a>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-20 bg-white">
            <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <h2 class="title-font text-3xl md:text-4xl font-bold text-gray-800 mb-6">Notre Mission</h2>
                <p class="text-lg text-gray-600 mb-8">Solidarité Cœur Brisé est une plateforme où chaque histoire de déception amoureuse devient une opportunité de soutien. Nous croyons en la puissance thérapeutique du partage et en la solidarité humaine pour transformer la douleur en espoir.</p>
                <a href="#" class="inline-block bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-full font-medium transition duration-300">En savoir plus sur notre projet</a>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-xl text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center text-red-500 mx-auto mb-4">
                    <i class="fas fa-heart text-2xl"></i>
                </div>
                <h3 class="title-font text-xl font-bold text-gray-800 mb-3">Expression Libre</h3>
                <p class="text-gray-600">Partagez anonymement ou sous pseudonyme vos témoignages, poèmes et réflexions dans un espace bienveillant.</p>
                </div>
                
                <div class="bg-gray-50 p-8 rounded-xl text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center text-red-500 mx-auto mb-4">
                    <i class="fas fa-hands-helping text-2xl"></i>
                </div>
                <h3 class="title-font text-xl font-bold text-gray-800 mb-3">Soutien Mutuel</h3>
                <p class="text-gray-600">Interagissez avec bienveillance grâce aux commentaires et réactions émotionnelles pour créer des liens solides.</p>
                </div>
                
                <div class="bg-gray-50 p-8 rounded-xl text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center text-red-500 mx-auto mb-4">
                    <i class="fas fa-globe-americas text-2xl"></i>
                </div>
                <h3 class="title-font text-xl font-bold text-gray-800 mb-3">Impact Solidaire</h3>
                <p class="text-gray-600">Vos contributions financières soutiennent directement des projets pour les malvoyants, avec une totale transparence.</p>
                </div>
            </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-12">
                <h2 class="title-font text-3xl font-bold text-gray-800">Derniers témoignages</h2>
                <a href="#" class="text-red-500 hover:text-red-600 font-medium flex items-center">
                Voir tous les témoignages <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-md overflow-hidden testimonial-card transition duration-300">
                <div class="h-48 bg-gradient-to-r from-pink-100 to-red-100 flex items-center justify-center">
                    <i class="fas fa-quote-left text-5xl text-red-300 opacity-50"></i>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gray-200 rounded-full mr-3"></div>
                    <div>
                        <h4 class="font-bold text-gray-800">Anonyme</h4>
                        <p class="text-sm text-gray-500">Il y a 2 jours</p>
                    </div>
                    </div>
                    <h3 class="title-font text-xl font-bold text-gray-800 mb-3">Le jour où j'ai compris</h3>
                    <p class="text-gray-600 mb-4">"Après cinq ans ensemble, il est parti sans un mot. Ce forum m'a aidé à réaliser que je méritais mieux..."</p>
                    <div class="flex justify-between text-gray-500">
                    <span><i class="far fa-heart mr-1"></i> 24</span>
                    <span><i class="far fa-comment mr-1"></i> 8</span>
                    <span><i class="fas fa-coffee mr-1"></i> 3</span>
                    </div>
                </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden testimonial-card transition duration-300">
                <div class="h-48 bg-gradient-to-r from-purple-100 to-blue-100 flex items-center justify-center">
                    <i class="fas fa-quote-left text-5xl text-blue-300 opacity-50"></i>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gray-200 rounded-full mr-3"></div>
                    <div>
                        <h4 class="font-bold text-gray-800">Marine92</h4>
                        <p class="text-sm text-gray-500">Il y a 5 jours</p>
                    </div>
                    </div>
                    <h3 class="title-font text-xl font-bold text-gray-800 mb-3">Poème pour toi</h3>
                    <p class="text-gray-600 mb-4">"Tes promesses étaient des feuilles d'automne, belles mais promises à disparaître. Aujourd'hui, j'apprends à aimer..."</p>
                    <div class="flex justify-between text-gray-500">
                    <span><i class="far fa-heart mr-1"></i> 42</span>
                    <span><i class="far fa-comment mr-1"></i> 15</span>
                    <span><i class="fas fa-coffee mr-1"></i> 7</span>
                    </div>
                </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-md overflow-hidden testimonial-card transition duration-300">
                <div class="h-48 bg-gradient-to-r from-yellow-100 to-orange-100 flex items-center justify-center">
                    <i class="fas fa-quote-left text-5xl text-orange-300 opacity-50"></i>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gray-200 rounded-full mr-3"></div>
                    <div>
                        <h4 class="font-bold text-gray-800">Thomas</h4>
                        <p class="text-sm text-gray-500">Il y a 1 semaine</p>
                    </div>
                    </div>
                    <h3 class="title-font text-xl font-bold text-gray-800 mb-3">Rebondir après la trahison</h3>
                    <p class="text-gray-600 mb-4">"Découvrir son infidélité a été un coup dur, mais grâce aux témoignages ici, j'ai retrouvé confiance en..."</p>
                    <div class="flex justify-between text-gray-500">
                    <span><i class="far fa-heart mr-1"></i> 37</span>
                    <span><i class="far fa-comment mr-1"></i> 12</span>
                    <span><i class="fas fa-coffee mr-1"></i> 5</span>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </section>

        <!-- Solidarity Project Section -->
        <section class="py-20 bg-gradient-to-r from-red-50 to-pink-50">
            <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <h2 class="title-font text-3xl md:text-4xl font-bold text-gray-800 mb-6">Notre Projet Solidaire</h2>
                <p class="text-lg text-gray-600">Chaque témoignage, chaque interaction et chaque don contribue à soutenir des projets pour les malvoyants à travers le monde. Nous garantissons une transparence totale sur l'utilisation des fonds.</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-4xl mx-auto">
                <div class="md:flex">
                <div class="md:w-1/2 p-8">
                    <h3 class="title-font text-2xl font-bold text-gray-800 mb-4">Nos réalisations</h3>
                    <div class="mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium text-gray-700">Objectif annuel</span>
                        <span class="font-bold text-red-500">€75,000</span>
                    </div>
                    <div class="donation-progress rounded-full">
                        <div class="donation-progress-bar rounded-full" style="width: 65%"></div>
                    </div>
                    <div class="text-right mt-1 text-sm text-gray-500">€48,750 collectés</div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <div class="text-red-500 text-3xl font-bold mb-2">12</div>
                        <div class="text-gray-600 text-sm">Projets financés</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <div class="text-red-500 text-3xl font-bold mb-2">3,200+</div>
                        <div class="text-gray-600 text-sm">Personnes aidées</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <div class="text-red-500 text-3xl font-bold mb-2">85%</div>
                        <div class="text-gray-600 text-sm">Fonds alloués</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <div class="text-red-500 text-3xl font-bold mb-2">15</div>
                        <div class="text-gray-600 text-sm">Pays concernés</div>
                    </div>
                    </div>
                    
                    <a href="#" class="block w-full bg-red-500 hover:bg-red-600 text-white text-center py-3 rounded-lg font-medium transition duration-300">Voir le détail des projets</a>
                </div>
                
                <div class="md:w-1/2 bg-gray-100 p-4">
                    <div class="grid grid-cols-2 gap-2">
                    <div class="h-40 bg-gray-300 rounded-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                            alt="Projet solidarité" class="w-full h-full object-cover">
                    </div>
                    <div class="h-40 bg-gray-300 rounded-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1522&q=80" 
                            alt="Projet solidarité" class="w-full h-full object-cover">
                    </div>
                    <div class="h-40 bg-gray-300 rounded-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80" 
                            alt="Projet solidarité" class="w-full h-full object-cover">
                    </div>
                    <div class="h-40 bg-gray-300 rounded-lg overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1521791055366-0d553872125f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80" 
                            alt="Projet solidarité" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <span class="text-white font-bold">+12</span>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </section>

        <!-- How to Contribute Section -->
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <h2 class="title-font text-3xl md:text-4xl font-bold text-gray-800 mb-6">Comment contribuer ?</h2>
                <p class="text-lg text-gray-600">Plusieurs façons de soutenir notre communauté et nos actions solidaires.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="bg-gray-50 rounded-xl p-8 border border-gray-200 hover:border-red-300 transition duration-300">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center text-red-500 mx-auto mb-6">
                    <i class="fas fa-coffee text-2xl"></i>
                </div>
                <h3 class="title-font text-2xl font-bold text-center text-gray-800 mb-4">Offrir un café</h3>
                <p class="text-gray-600 text-center mb-6">Soutenez directement les auteurs qui partagent leurs témoignages. 100% de votre contribution va à l'auteur de votre choix.</p>
                <div class="flex justify-center space-x-3">
                    <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-100 transition duration-300">€2</button>
                    <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-100 transition duration-300">€5</button>
                    <button class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition duration-300">€10</button>
                </div>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-8 border border-gray-200 hover:border-red-300 transition duration-300">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center text-red-500 mx-auto mb-6">
                    <i class="fas fa-hand-holding-heart text-2xl"></i>
                </div>
                <h3 class="title-font text-2xl font-bold text-center text-gray-800 mb-4">Faire un don</h3>
                <p class="text-gray-600 text-center mb-6">Contribuez à nos projets solidaires pour les malvoyants. Chaque euro compte et nous publions des bilans financiers détaillés.</p>
                <div class="flex justify-center space-x-3">
                    <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-100 transition duration-300">€10</button>
                    <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-100 transition duration-300">€20</button>
                    <button class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition duration-300">€50</button>
                </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="#" class="inline-block bg-red-500 hover:bg-red-600 text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 shadow-lg">Je soutiens maintenant</a>
            </div>
            </div>
        </section>

        <!-- Partners Section -->
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <h2 class="title-font text-3xl md:text-4xl font-bold text-gray-800 mb-6">Nos Partenaires</h2>
                <p class="text-lg text-gray-600">Des organisations engagées à nos côtés pour créer un impact positif.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-6xl mx-auto">
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-center h-24">
                <img src="https://via.placeholder.com/150x60?text=Association+A" alt="Association A" class="partner-logo max-h-12">
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-center h-24">
                <img src="https://via.placeholder.com/150x60?text=Fondation+B" alt="Fondation B" class="partner-logo max-h-12">
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-center h-24">
                <img src="https://via.placeholder.com/150x60?text=Entreprise+C" alt="Entreprise C" class="partner-logo max-h-12">
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-center h-24">
                <img src="https://via.placeholder.com/150x60?text=ONG+D" alt="ONG D" class="partner-logo max-h-12">
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-center h-24">
                <img src="https://via.placeholder.com/150x60?text=Expert+E" alt="Expert E" class="partner-logo max-h-12">
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-center h-24">
                <img src="https://via.placeholder.com/150x60?text=Mécène+F" alt="Mécène F" class="partner-logo max-h-12">
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-center h-24">
                <img src="https://via.placeholder.com/150x60?text=Association+G" alt="Association G" class="partner-logo max-h-12">
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-center h-24">
                <img src="https://via.placeholder.com/150x60?text=Partenaire+H" alt="Partenaire H" class="partner-logo max-h-12">
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="#" class="inline-block border border-red-500 text-red-500 hover:bg-red-50 px-6 py-3 rounded-full font-medium transition duration-300">Devenir partenaire</a>
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
                <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl p-8 border border-white border-opacity-20">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-white rounded-full mr-4 overflow-hidden">
                    <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="User" class="w-full h-full object-cover">
                    </div>
                    <div>
                    <h4 class="font-bold text-white">Sophie L.</h4>
                    <p class="text-sm text-red-100">Membre depuis 2022</p>
                    </div>
                </div>
                <p class="text-white italic">"Grâce à cette plateforme, j'ai pu exprimer ma douleur et recevoir un soutien incroyable. Savoir que mon témoignage aide aussi des malvoyants donne un sens à mon expérience."</p>
                </div>
                
                <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl p-8 border border-white border-opacity-20">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-white rounded-full mr-4 overflow-hidden">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-full h-full object-cover">
                    </div>
                    <div>
                    <h4 class="font-bold text-white">Jean P.</h4>
                    <p class="text-sm text-red-100">Bénéficiaire</p>
                    </div>
                </div>
                <p class="text-white italic">"Le matériel adapté financé par cette communauté a changé ma vie de malvoyant. Je peux maintenant lire seul grâce à leur soutien."</p>
                </div>
                
                <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl p-8 border border-white border-opacity-20">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-white rounded-full mr-4 overflow-hidden">
                    <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="User" class="w-full h-full object-cover">
                    </div>
                    <div>
                    <h4 class="font-bold text-white">Émilie T.</h4>
                    <p class="text-sm text-red-100">Auteure soutenue</p>
                    </div>
                </div>
                <p class="text-white italic">"Les cafés offerts par les lecteurs m'ont permis de consacrer plus de temps à l'écriture tout en soutenant une noble cause. Une belle synergie!"</p>
                </div>
            </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-8 md:p-12 shadow-lg">
                <div class="text-center mb-8">
                <h2 class="title-font text-3xl font-bold text-gray-800 mb-4">Restez informé(e) de nos actions</h2>
                <p class="text-gray-600">Abonnez-vous à notre newsletter pour recevoir des témoignages inspirants, des nouvelles de nos projets solidaires et des invitations à nos événements.</p>
                </div>
                
                <form class="flex flex-col sm:flex-row gap-4">
                <input type="email" placeholder="Votre email" class="flex-grow px-6 py-4 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-8 py-4 rounded-full font-bold transition duration-300 whitespace-nowrap">S'abonner</button>
                </form>
                
                <p class="text-center text-sm text-gray-500 mt-4">Nous respectons votre vie privée. Vous pouvez vous désabonner à tout moment.</p>
            </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-gradient-to-r from-red-600 to-pink-600">
            <div class="container mx-auto px-4 text-center">
            <h2 class="title-font text-3xl font-bold text-white mb-6">Prêt à partager votre histoire ?</h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">Rejoignez notre communauté bienveillante et transformez votre expérience en soutien pour les autres.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#" class="bg-red-500 hover:bg-red-600 text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 shadow-lg">Partager un témoignage</a>
                <a href="#" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-red-600 px-8 py-4 rounded-full font-bold text-lg transition duration-300">Découvrir comment ça marche</a>
            </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/Client/Layouts/MainLayout.vue';
</script>