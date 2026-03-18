<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vidish Vendor | Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-coral {
            background: rgba(255, 111, 97, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 111, 97, 0.2);
        }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 111, 97, 0.3); border-radius: 10px; }
    </style>
</head>
<body class="bg-[#0A0A0A] text-white antialiased font-sans overflow-hidden">

    <div class="fixed inset-0 bg-cover bg-center z-[-2]" style="background-image: url('{{ asset('images/nature-background.jpg') }}');"></div>
    <div class="fixed inset-0 bg-black/60 backdrop-blur-[2px] z-[-1]"></div>

    <div class="flex h-screen p-4 md:p-6" x-data="{ mobileMenu: false }">
        
        <aside 
            x-data="{ open: false }" 
            @mouseenter="open = true" 
            @mouseleave="open = false"
            :class="mobileMenu ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:relative z-50 w-64 lg:w-20 lg:hover:w-64 glass-card p-5 flex flex-col transition-all duration-500 ease-in-out h-[calc(100vh-2rem)] md:h-full overflow-hidden mr-0 lg:mr-6 shadow-2xl">
            
            <div class="mb-10 flex items-center h-12 shrink-0">
                <div class="w-10 h-10 bg-coral-500 rounded-xl flex items-center justify-center font-black italic shadow-lg shadow-coral-500/30 text-xl shrink-0 animate-in fade-in zoom-in duration-700">
                    V
                </div>
                <h2 x-show="open || mobileMenu" x-cloak x-transition.opacity
                    class="text-xl font-black ml-4 tracking-tighter whitespace-nowrap">
                    VIDISH<span class="text-coral-400">.</span>VENDOR
                </h2>
            </div>

            <nav class="flex-1 space-y-4 w-full">
                <a href="#" class="flex items-center h-12 rounded-xl bg-white text-gray-950 px-3 relative group overflow-hidden">
                    <div class="w-10 flex justify-center items-center shrink-0 z-10"><i class="fas fa-th-large text-xl"></i></div>
                    <span x-show="open || mobileMenu" x-cloak class="font-bold ml-3 z-10 whitespace-nowrap">Tableau de bord</span>
                </a>

                <a href="#" class="group flex items-center h-12 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all px-3">
                    <div class="w-10 flex justify-center items-center shrink-0"><i class="fas fa-utensils text-xl group-hover:text-coral-400 transition-colors"></i></div>
                    <span x-show="open || mobileMenu" x-cloak class="font-semibold ml-3 whitespace-nowrap">Mes Plats</span>
                </a>

                <a href="#" class="group flex items-center h-12 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all px-3 relative">
                    <div class="w-10 flex justify-center items-center shrink-0"><i class="fas fa-envelope text-xl"></i></div>
                    <span x-show="open || mobileMenu" x-cloak class="font-semibold ml-3 whitespace-nowrap">Messages</span>
                    <span class="absolute top-3 left-8 w-2 h-2 bg-coral-500 rounded-full animate-pulse"></span>
                </a>

                <a href="#" class="group flex items-center h-12 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all px-3">
                    <div class="w-10 flex justify-center items-center shrink-0"><i class="fas fa-chart-bar text-xl group-hover:text-coral-400 transition-colors"></i></div>
                    <span x-show="open || mobileMenu" x-cloak class="font-semibold ml-3 whitespace-nowrap">Analyses</span>
                </a>
            </nav>

            <div class="space-y-2 pt-4 border-t border-white/10 shrink-0">
                <a href="{{ route('profile.edit') }}" class="group flex items-center h-12 text-gray-400 hover:text-white px-3 transition-colors">
                    <div class="w-10 flex justify-center items-center shrink-0"><i class="fas fa-cog text-xl"></i></div>
                    <span x-show="open || mobileMenu" x-cloak class="font-semibold ml-3 whitespace-nowrap">Paramètres</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="group flex items-center h-12 w-full text-gray-400 hover:text-coral-400 px-3 transition-colors">
                        <div class="w-10 flex justify-center items-center shrink-0"><i class="fas fa-sign-out-alt text-xl"></i></div>
                        <span x-show="open || mobileMenu" x-cloak class="font-semibold ml-3 whitespace-nowrap">Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <div x-show="mobileMenu" @click="mobileMenu = false" x-cloak
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"></div>

        <main class="flex-1 flex flex-col h-full overflow-hidden">
            
            <header class="flex items-center justify-between h-16 mb-6 px-2 shrink-0">
                <div class="flex items-center">
                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden mr-4 glass-card p-2 w-10 h-10 flex items-center justify-center">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="hidden md:block text-sm text-gray-400">Accueil / <span class="text-white">Tableau de bord</span></div>
                </div>
                
                <div class="flex items-center space-x-4 md:space-x-6">
                    <span class="hidden sm:block text-sm text-gray-300">Mer. 20 Sep <i class="fas fa-chevron-down text-xs ml-1 text-coral-500"></i></span>
                    
                    <div class="flex items-center space-x-3 md:space-x-4 border-l border-white/10 pl-4 md:pl-6">
                        <i class="fas fa-search text-lg text-gray-400 cursor-pointer hover:text-white transition"></i>
                        <div class="relative cursor-pointer group">
                            <i class="fas fa-bell text-lg text-gray-400 group-hover:text-white transition"></i>
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-coral-500 rounded-full border border-[#0A0A0A]"></span>
                        </div>
                        <img src="{{ asset('images/avatar.jpg') }}" 
                             class="w-10 h-10 rounded-xl border border-white/10 shadow-lg object-cover" 
                             alt="{{ Auth::user()->name }}">
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar pb-6">
                <div class="flex flex-col xl:flex-row gap-6">
                    
                    <div class="flex-1 space-y-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                            <div>
                                <h1 class="text-3xl font-black tracking-tight leading-none">Gérez votre restaurant simplement <span class="text-coral-400">!</span></h1>
                                <p class="text-gray-400 mt-2 text-sm">Bienvenue sur Vidish Vendor. Suivez vos commandes et vos vidéos en quelques clics.</p>
                            </div>
                            <button class="bg-coral-500 text-white font-black px-5 py-3 rounded-xl shadow-lg shadow-coral-500/30 hover:bg-coral-600 transition flex items-center whitespace-nowrap">
                                <i class="fas fa-plus mr-2"></i> Nouveau Plat
                            </button>
                        </div>

                        <div class="flex items-center space-x-2 text-xs font-bold overflow-x-auto pb-2 no-scrollbar">
                            <span class="flex items-center px-4 py-2 rounded-full bg-white/10 border border-white/10 cursor-pointer">
                                <i class="fas fa-circle text-coral-500 text-[8px] mr-2 animate-pulse"></i> À faire <i class="fas fa-chevron-down text-[10px] ml-2 text-gray-500"></i>
                            </span>
                            <span class="px-4 py-2 rounded-full bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10">Cuisine</span>
                            <span class="px-4 py-2 rounded-full bg-white/5 border border-white/10 cursor-pointer hover:bg-white/10">Livré</span>
                        </div>

                        <div class="space-y-4">
                            <div class="glass-coral p-5 rounded-3xl relative overflow-hidden group">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-bold text-lg">Menu du Jour</h4>
                                        <p class="text-xs text-gray-400">20 Sep, 12h-14h • Yaoundé</p>
                                    </div>
                                    <i class="fas fa-edit text-gray-400 group-hover:text-coral-400 transition cursor-pointer"></i>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex -space-x-3">
                                        <div class="w-10 h-10 rounded-full border-2 border-[#1a1a1a] bg-gray-800"></div>
                                        <div class="w-10 h-10 rounded-full border-2 border-[#1a1a1a] bg-gray-700"></div>
                                        <div class="w-10 h-10 rounded-full border-2 border-[#1a1a1a] bg-coral-500/20 flex items-center justify-center text-[10px] font-bold text-coral-400">+2 plats</div>
                                    </div>
                                    <span class="text-xs font-black text-coral-400 tracking-widest uppercase">Actif</span>
                                </div>
                            </div>

                            <div class="glass-card p-6 rounded-3xl">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 mr-4 shadow-inner">
                                            <i class="fas fa-utensils text-coral-500 text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-lg uppercase tracking-tight">Commande #1234</h4>
                                            <p class="text-xs text-gray-500">Livraison demandée pour 19h30</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-3 text-gray-500">
                                        <i class="fas fa-bell hover:text-white cursor-pointer"></i>
                                        <i class="fas fa-ellipsis-v hover:text-white cursor-pointer"></i>
                                    </div>
                                </div>
                                <div class="text-sm space-y-1 mb-6">
                                    <p><span class="text-gray-500 font-medium">Plats :</span> <span class="text-white">2x Burger Signature, 1x Coca-Cola</span></p>
                                    <p><span class="text-gray-500 font-medium">Statut :</span> <span class="text-coral-400 font-bold">En préparation</span></p>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex-1 h-1.5 bg-white/5 rounded-full overflow-hidden border border-white/5">
                                        <div class="h-full bg-coral-500 shadow-[0_0_12px_rgba(255,111,97,0.5)] transition-all duration-1000" style="width: 70%"></div>
                                    </div>
                                    <span class="text-xs font-black text-coral-400">70% PRÊT</span>
                                </div>
                            </div>

                            <div class="border-2 border-dashed border-white/10 p-5 rounded-2xl flex items-center justify-center text-gray-500 font-bold hover:border-coral-500/40 hover:text-gray-300 transition cursor-pointer group">
                                <i class="fas fa-plus mr-3 group-hover:rotate-90 transition-transform"></i> Ajouter une commande manuelle
                            </div>
                        </div>
                    </div>

                    <div class="w-full xl:w-[400px] space-y-6">
                        <div class="glass-coral p-6 rounded-[2.5rem] relative overflow-hidden group">
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-coral-500/10 blur-3xl group-hover:bg-coral-500/20 transition-all"></div>
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-black text-sm uppercase tracking-widest text-coral-400">Notes Cuisine</h4>
                                <i class="fas fa-sticky-note text-gray-500"></i>
                            </div>
                            <div class="space-y-3 text-sm text-gray-300 leading-relaxed italic">
                                <p>"Rupture de stock sur la Sauce Signature. Utiliser la Sauce Classic."</p>
                            </div>
                            <div class="mt-6 pt-4 border-t border-coral-500/10 flex justify-between items-center text-[10px] font-bold uppercase tracking-tighter">
                                <span class="text-gray-500">Il y a 20 min</span>
                                <span class="text-coral-400 flex items-center"><i class="fas fa-check-circle mr-1"></i> Lu par l'équipe</span>
                            </div>
                        </div>

                        <div class="glass-card p-6 rounded-[2.5rem] flex flex-col min-h-[250px]">
                            <h4 class="font-black text-sm uppercase tracking-widest text-gray-500 mb-6 text-center">Contenu Vidéo</h4>
                            <div class="flex-1 flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mb-4 border border-white/10 group cursor-pointer hover:border-coral-500/50 transition">
                                    <i class="fas fa-play text-coral-500 text-xl group-hover:scale-110 transition"></i>
                                </div>
                                <p class="text-xs text-gray-500 px-6">Améliorez votre visibilité en ajoutant des vidéos de vos plats.</p>
                            </div>
                            <button class="mt-6 w-full py-3 rounded-2xl bg-coral-500/10 text-coral-400 font-black text-xs uppercase tracking-widest border border-coral-500/20 hover:bg-coral-500 hover:text-white transition">
                                <i class="fas fa-plus mr-2"></i> Ajouter une vidéo
                            </button>
                        </div>

                        <div class="glass-card p-6 rounded-[2.5rem]">
                            <div class="flex justify-between items-center mb-6">
                                <h4 class="font-black text-sm uppercase tracking-widest text-gray-500">Vues Vidéos</h4>
                                <span class="text-xs font-black text-coral-400">TOTAL</span>
                            </div>
                            <div class="text-4xl font-black text-center mb-6 tracking-tighter italic">45.2k</div>
                            <div class="flex items-end justify-between h-12 gap-1 px-4">
                                @foreach([40, 60, 30, 90, 100, 70, 50] as $h)
                                    <div class="flex-1 bg-coral-500/20 rounded-t-sm transition-all hover:bg-coral-500 cursor-help relative group" style="height: {{ $h }}%">
                                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[8px] bg-coral-500 px-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                            {{ $h }}%
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>