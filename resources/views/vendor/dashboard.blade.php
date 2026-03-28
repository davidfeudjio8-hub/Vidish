<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vidish Vendor | Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .glass-coral { background: rgba(255, 111, 97, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 111, 97, 0.2); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 111, 97, 0.3); border-radius: 10px; }
    </style>
</head>
<body class="bg-[#191919] text-white antialiased font-sans overflow-hidden">

    <div class="fixed inset-0 bg-cover bg-center z-[-2]" style="background-image: url('{{ asset('images/nature-background.jpg') }}');"></div>
    <div class="fixed inset-0 bg-black/60 backdrop-blur-[2px] z-[-1]"></div>

    <div class="flex h-screen p-4 md:p-6" x-data="{ mobileMenu: false, isOpen: {{ $restaurant->is_open ? 'true' : 'false' }} }">
        
        <aside x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false"
            :class="mobileMenu ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:relative z-50 w-64 lg:w-20 lg:hover:w-64 glass-card p-5 flex flex-col transition-all duration-500 ease-in-out h-[calc(100vh-2rem)] md:h-full overflow-hidden mr-0 lg:mr-6 shadow-2xl">
            
            <div class="mb-10 flex items-center h-12 shrink-0">
                <div class="w-10 h-10 bg-coral-500 rounded-xl flex items-center justify-center font-black italic shadow-lg shadow-coral-500/30 text-xl shrink-0 animate-in fade-in zoom-in duration-700">V</div>
                <h2 x-show="open || mobileMenu" x-cloak x-transition.opacity class="text-xl font-black ml-4 tracking-tighter whitespace-nowrap">VIDISH<span class="text-coral-400">.</span>VENDOR</h2>
            </div>

            <nav class="flex-1 space-y-4 w-full">
                <a href="{{ route('vendor.dashboard') }}" class="flex items-center h-12 rounded-xl bg-white text-gray-950 px-3 relative group overflow-hidden">
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
            </nav>

            <div class="space-y-2 pt-4 border-t border-white/10 shrink-0">
                <a href="{{ route('vendor.settings') }}" class="group flex items-center h-12 text-gray-400 hover:text-white px-3 transition-colors">
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

        <main class="flex-1 flex flex-col h-full overflow-hidden">
            
            <header class="flex items-center justify-between h-16 mb-6 px-2 shrink-0">
                <div class="flex items-center">
                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden mr-4 glass-card p-2 w-10 h-10 flex items-center justify-center"><i class="fas fa-bars"></i></button>
                    <div class="hidden md:block text-sm text-gray-400">Accueil / <span class="text-white">Tableau de bord</span></div>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-3 bg-white/5 px-4 py-2 rounded-2xl border border-white/10">
                        <span class="text-[10px] font-black uppercase tracking-widest" :class="isOpen ? 'text-green-400' : 'text-red-400'" x-text="isOpen ? 'Ouvert' : 'Fermé'"></span>
                        <button @click="toggleStatus()" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none" :class="isOpen ? 'bg-coral-500' : 'bg-gray-700'">
                            <span :class="isOpen ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300"></span>
                        </button>
                    </div>

                    <div class="flex items-center space-x-4 border-l border-white/10 pl-6">
                        <i class="fas fa-search text-lg text-gray-400 cursor-pointer hover:text-white transition"></i>
                        <img src="{{ asset('images/avatar.jpg') }}" class="w-10 h-10 rounded-xl border border-white/10 shadow-lg object-cover" alt="Profile">
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar pb-6">
                <div class="flex flex-col xl:flex-row gap-6">
                    
                    <div class="flex-1 space-y-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                            <div>
                                <h1 class="text-3xl font-black tracking-tight leading-none uppercase italic">VIDISH<span class="text-coral-400">.</span>HUB</h1>
                                <p class="text-gray-400 mt-2 text-sm">Gérez vos commandes en temps réel pour <span class="text-white font-bold">{{ $restaurant->name }}</span>.</p>
                            </div>
                            <button class="bg-coral-500 text-white font-black px-5 py-3 rounded-xl shadow-lg shadow-coral-500/30 hover:bg-coral-600 transition flex items-center"><i class="fas fa-plus mr-2"></i> Nouveau Plat</button>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Commandes Prioritaires</h3>
                            
                            @forelse($orders as $order)
                                <div class="glass-card p-6 rounded-3xl border-l-4 border-coral-500 transition hover:bg-white/[0.05]">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 mr-4 shadow-inner">
                                                <i class="fas fa-receipt text-coral-500 text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-black text-lg uppercase tracking-tight">#{{ $order->id }}</h4>
                                                <p class="text-xs text-gray-500 italic">Prévu pour {{ \Carbon\Carbon::parse($order->delivery_time)->format('H:i') }}</p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 bg-coral-500/20 text-coral-400 text-[10px] font-black rounded-full uppercase tracking-widest">{{ $order->status }}</span>
                                    </div>
                                    <div class="text-sm space-y-1 mb-6 text-gray-300">
                                        {{-- Supposons une relation items dans Order --}}
                                        <p>{{ $order->items_summary ?? 'Contenu non spécifié' }}</p>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex-1 h-1 bg-white/5 rounded-full overflow-hidden">
                                            <div class="h-full bg-coral-500 transition-all duration-700" style="width: 50%"></div>
                                        </div>
                                        <button class="text-[10px] font-black uppercase text-coral-400 hover:text-white transition">Détails <i class="fas fa-arrow-right ml-1"></i></button>
                                    </div>
                                </div>
                            @empty
                                <div class="glass-card p-10 rounded-3xl text-center border-dashed border-white/10">
                                    <p class="text-gray-500 font-bold uppercase tracking-widest text-xs">Aucune commande en cours</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="w-full xl:w-[400px] space-y-6">
                        <div class="glass-card p-6 rounded-[2.5rem]">
                            <div class="flex justify-between items-center mb-6">
                                <h4 class="font-black text-sm uppercase tracking-widest text-gray-500">Analytics Vidéos</h4>
                                <span class="text-xs font-black text-coral-400 italic">VUES</span>
                            </div>
                            <div class="h-48 w-full">
                                <canvas id="vendorViewsChart"></canvas>
                            </div>
                            <div class="mt-4 pt-4 border-t border-white/5 flex justify-between items-center">
                                <div>
                                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Total Cumulative</p>
                                    <p class="text-2xl font-black italic">{{ number_format($totalViews, 0, ',', ' ') }}</p>
                                </div>
                                <i class="fas fa-chart-line text-coral-500 text-2xl"></i>
                            </div>
                        </div>

                        <div class="glass-card p-6 rounded-[2.5rem]">
                            <h4 class="font-black text-sm uppercase tracking-widest text-gray-500 mb-6">Derniers Plats</h4>
                            <div class="space-y-4">
                                @foreach($dishes as $dish)
                                    <div class="flex items-center justify-between p-2 hover:bg-white/5 rounded-xl transition">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gray-800 rounded-lg overflow-hidden border border-white/10">
                                                <img src="{{ asset('storage/' . $dish->image_path) }}" class="w-full h-full object-cover">
                                            </div>
                                            <span class="text-xs font-bold">{{ $dish->name }}</span>
                                        </div>
                                        <span class="text-[10px] text-gray-500">{{ $dish->views_count }} vues</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // --- LOGIQUE CHART.JS ---
        const ctx = document.getElementById('vendorViewsChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(255, 111, 97, 0.4)');
        gradient.addColorStop(1, 'rgba(255, 111, 97, 0.01)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                datasets: [{
                    data: @json($chartData),
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#FF6F61',
                    borderWidth: 3,
                    tension: 0.4,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { display: false, beginAtZero: true },
                    x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.2)', font: { size: 9 } } }
                }
            }
        });

        // --- LOGIQUE AJAX POUR LE STATUT ---
        function toggleStatus() {
            const alpine = document.querySelector('[x-data]').__x.$data;
            const newStatus = !alpine.isOpen;

            fetch("{{ route('vendor.status.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ is_open: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                alpine.isOpen = newStatus;
                // Notification optionnelle ici
            })
            .catch(err => console.error('Erreur:', err));
        }
    </script>
</body>
</html>