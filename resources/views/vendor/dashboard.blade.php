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
        [x-cloak] {
            display: none !important;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 111, 97, 0.3);
            border-radius: 10px;
        }

        /* Animation pour les commentaires live */
        .comment-enter {
            transform: translateY(20px);
            opacity: 0;
        }

        .comment-enter-active {
            transform: translateY(0);
            opacity: 1;
            transition: all 0.5s ease-out;
        }

        .comment-exit {
            transform: translateY(0);
            opacity: 1;
        }

        .comment-exit-active {
            transform: translateY(-20px);
            opacity: 0;
            transition: all 0.5s ease-in;
        }
    </style>
</head>

<body class="bg-[#191919] text-white antialiased font-sans overflow-hidden" x-data="{
    mobileMenu: false,
    isOpen: {{ $restaurant->is_open ? 'true' : 'false' }},
    showClipModal: false,
    showDishModal: false
}">

    {{-- Background --}}
    <div class="fixed inset-0 bg-cover bg-center z-[-2]"
        style="background-image: url('{{ asset('images/nature-background.jpg') }}');"></div>
    <div class="fixed inset-0 bg-black/60 backdrop-blur-[2px] z-[-1]"></div>

    <div class="flex h-screen p-4 md:p-6">

        {{-- Sidebar Interactive --}}
        <aside x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false"
            :class="mobileMenu ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:relative z-50 w-64 lg:w-20 lg:hover:w-64 glass-card p-5 flex flex-col transition-all duration-500 ease-in-out h-[calc(100vh-2rem)] md:h-full overflow-hidden mr-0 lg:mr-6 shadow-2xl">

            <div class="mb-10 flex items-center h-12 shrink-0">
                <div
                    class="w-10 h-10 bg-[#FF6F61] rounded-xl flex items-center justify-center font-black italic shadow-lg shadow-coral-500/30 text-xl shrink-0">
                    V</div>
                <h2 x-show="open || mobileMenu" x-cloak x-transition.opacity
                    class="text-xl font-black ml-4 tracking-tighter whitespace-nowrap">VIDISH<span
                        class="text-coral-400">.</span>VENDOR</h2>
            </div>

            <nav class="flex-1 space-y-4 w-full">
                <a href="{{ route('vendor.dashboard') }}"
                    class="flex items-center h-12 rounded-xl bg-white text-gray-950 px-3 relative group overflow-hidden">
                    <div class="w-10 flex justify-center items-center shrink-0 z-10"><i
                            class="fas fa-th-large text-xl"></i></div>
                    <span x-show="open || mobileMenu" x-cloak class="font-bold ml-3 z-10 whitespace-nowrap">Tableau de
                        bord</span>
                </a>

                <a href="{{ route('home') }}"
                    class="group flex items-center h-12 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all px-3">
                    <div class="w-10 flex justify-center items-center shrink-0"><i
                            class="fas fa-play-circle text-xl group-hover:text-coral-400 transition-colors"></i></div>
                    <span x-show="open || mobileMenu" x-cloak class="font-semibold ml-3 whitespace-nowrap">Aller au
                        feed</span>
                </a>

                {{-- Option : Mes Plats (Fixed Link & Syntax) --}}
                <a href="{{ route('vendor.clips') }}"
                    class="group flex items-center h-12 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all px-3">
                    <div class="w-10 flex justify-center items-center shrink-0"><i
                            class="fas fa-utensils text-xl group-hover:text-coral-400 transition-colors"></i></div>
                    <span x-show="open || mobileMenu" x-cloak class="font-semibold ml-3 whitespace-nowrap">Mes plats</span>
                </a>

                {{-- Option : Mes Clips --}}
                <a href="{{ route('vendor.clips') }}"
                    class="group flex items-center h-12 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all px-3">
                    <div class="w-10 flex justify-center items-center shrink-0"><i
                            class="fas fa-film text-xl group-hover:text-coral-400 transition-colors"></i></div>
                    <span x-show="open || mobileMenu" x-cloak class="font-semibold ml-3 whitespace-nowrap">Mes clips</span>
                </a>

                <a href="#"
                    class="group flex items-center h-12 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all px-3 relative">
                    <div class="w-10 flex justify-center items-center shrink-0"><i class="fas fa-envelope text-xl"></i>
                    </div>
                    <span x-show="open || mobileMenu" x-cloak
                        class="font-semibold ml-3 whitespace-nowrap">Messages</span>
                    <span class="absolute top-3 left-8 w-2 h-2 bg-[#FF6F61] rounded-full animate-pulse"></span>
                </a>
            </nav>

            <div class="space-y-2 pt-4 border-t border-white/10 shrink-0">
                <a href="{{ route('vendor.settings') }}"
                    class="group flex items-center h-12 text-gray-400 hover:text-white px-3 transition-colors">
                    <div class="w-10 flex justify-center items-center shrink-0"><i class="fas fa-cog text-xl"></i></div>
                    <span x-show="open || mobileMenu" x-cloak
                        class="font-semibold ml-3 whitespace-nowrap">Paramètres</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="group flex items-center h-12 w-full text-gray-400 hover:text-coral-400 px-3 transition-colors">
                        <div class="w-10 flex justify-center items-center shrink-0"><i
                                class="fas fa-sign-out-alt text-xl"></i></div>
                        <span x-show="open || mobileMenu" x-cloak
                            class="font-semibold ml-3 whitespace-nowrap">Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Content Area --}}
        <main class="flex-1 flex flex-col h-full overflow-hidden">

            <header class="flex items-center justify-between h-16 mb-6 px-2 shrink-0">
                <div class="flex items-center">
                    <button @click="mobileMenu = !mobileMenu"
                        class="lg:hidden mr-4 glass-card p-2 w-10 h-10 flex items-center justify-center"><i
                            class="fas fa-bars"></i></button>
                    <div class="hidden md:block text-sm text-gray-400">Accueil / <span class="text-white">Tableau de
                            bord</span></div>
                </div>

                <div class="flex items-center space-x-6">
                    {{-- Toggle Status --}}
                    <div class="flex items-center space-x-4 bg-white/5 px-4 py-2 rounded-2xl border border-white/10"
                        x-data="{
                            loading: false,
                            async toggle() {
                                this.loading = true;
                                try {
                                    const response = await fetch('{{ route('vendor.status.update') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ is_open: !isOpen })
                                    });
                                    const data = await response.json();
                                    if (data.success) isOpen = !!data.is_open;
                                } catch (e) { console.error('Erreur de mise à jour'); }
                                this.loading = false;
                            }
                        }">

                        <span
                            class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-widest transition-all duration-300"
                            :class="isOpen ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'"
                            x-text="isOpen ? 'Ouvert' : 'Fermé'"></span>

                        <button @click="toggle()" :disabled="loading"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none"
                            :class="isOpen ? 'bg-[#FF6F61]' : 'bg-gray-700'">
                            <span :class="isOpen ? 'translate-x-6' : 'translate-x-1'"
                                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 shadow-sm"></span>
                        </button>
                    </div>

                    <div class="flex items-center space-x-4 border-l border-white/10 pl-6">
                        <a href="{{ route('vendor.settings') }}" class="relative group">
                            <img src="{{ $restaurant->image_path ? asset('storage/' . $restaurant->image_path) : asset('images/default-restaurant.jpg') }}"
                                class="w-10 h-10 rounded-xl border border-white/10 shadow-lg object-cover group-hover:border-coral-400 transition"
                                alt="Profile">
                        </a>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar pb-6">
                <div class="flex flex-col xl:flex-row gap-6">

                    <div class="flex-1 space-y-6">
                        {{-- Actions Rapides --}}
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                            <div>
                                <h1 class="text-3xl font-black tracking-tight leading-none uppercase italic">
                                    VIDISH<span class="text-coral-400">.</span>HUB</h1>
                                <p class="text-gray-400 mt-2 text-sm">Gérez <span
                                        class="text-white font-bold">{{ $restaurant->name }}</span> en temps réel.</p>
                            </div>

                            <div class="flex space-x-3">
                                <button @click="showClipModal = true"
                                    class="bg-white/5 text-white font-black px-5 py-3 rounded-xl border border-white/10 hover:bg-white/10 transition flex items-center">
                                    <i class="fas fa-video mr-2 text-coral-500"></i> Nouveau Clip
                                </button>
                                <button @click="showDishModal = true"
                                    class="bg-[#FF6F61] text-white font-black px-5 py-3 rounded-xl shadow-lg shadow-coral-500/30 hover:bg-coral-600 transition flex items-center">
                                    <i class="fas fa-plus mr-2"></i> Nouveau Plat
                                </button>
                            </div>
                        </div>

                        {{-- Commandes --}}
                        <div class="space-y-4">
                            <h3 class="text-xs font-black text-gray-500 uppercase tracking-[0.2em]">Commandes
                                Prioritaires</h3>
                            @forelse($orders as $order)
                                <div
                                    class="glass-card p-6 rounded-3xl border-l-4 border-coral-500 transition hover:bg-white/[0.05]">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center">
                                            <div
                                                class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 mr-4 shadow-inner">
                                                <i class="fas fa-receipt text-coral-500 text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-black text-lg uppercase tracking-tight">
                                                    #{{ $order->id }}</h4>
                                                <p class="text-xs text-gray-500 italic">Prévu pour
                                                    {{ \Carbon\Carbon::parse($order->delivery_time)->format('H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                        <span
                                            class="px-3 py-1 bg-coral-500/20 text-coral-400 text-[10px] font-black rounded-full uppercase tracking-widest">{{ $order->status }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex-1 h-1 bg-white/5 rounded-full overflow-hidden">
                                            <div class="h-full bg-coral-500 transition-all duration-700"
                                                style="width: 50%"></div>
                                        </div>
                                        <button
                                            class="text-[10px] font-black uppercase text-coral-400 hover:text-white transition">Détails
                                            <i class="fas fa-arrow-right ml-1"></i></button>
                                    </div>
                                </div>
                            @empty
                                <div class="glass-card p-10 rounded-3xl text-center border-dashed border-white/10">
                                    <p class="text-gray-500 font-bold uppercase tracking-widest text-xs">Aucune
                                        commande en cours</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- SECTION COMMENTAIRES LIVE --}}
                        <div class="space-y-4">
                            <h3 class="text-xs font-black text-gray-500 uppercase tracking-[0.2em]">Feedbacks en direct
                            </h3>
                            <div class="glass-card rounded-[2rem] p-6 h-64 relative overflow-hidden flex flex-col justify-end"
                                x-data="{
                                    comments: [],
                                    activeComments: [],
                                    async fetchComments() {
                                        try {
                                            const res = await fetch('/api/vendor/recent-comments');
                                            const data = await res.json();
                                            this.comments = data;
                                            this.startAnimation();
                                        } catch (e) { console.log('API comments non prête'); }
                                    },
                                    startAnimation() {
                                        setInterval(() => {
                                            if (this.comments.length > 0) {
                                                let next = this.comments.shift();
                                                this.activeComments.push(next);
                                                this.comments.push(next);
                                                if (this.activeComments.length > 3) this.activeComments.shift();
                                            }
                                        }, 4000);
                                    }
                                }" x-init="fetchComments()">

                                <div class="space-y-4">
                                    <template x-for="(comment, index) in activeComments" :key="index">
                                        <div x-show="true" x-transition:enter="comment-enter-active"
                                            x-transition:enter-start="comment-enter"
                                            class="flex items-start space-x-3 bg-white/5 p-3 rounded-2xl border border-white/5">
                                            <img :src="comment.user_avatar || '/images/default-avatar.png'"
                                                class="w-8 h-8 rounded-full border border-coral-500/30">
                                            <div>
                                                <p class="text-[10px] font-black text-coral-400 uppercase"
                                                    x-text="comment.user_name"></p>
                                                <p class="text-xs text-gray-200" x-text="comment.content"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div
                                    class="absolute inset-x-0 top-0 h-16 bg-gradient-to-b from-[#191919]/80 to-transparent pointer-events-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Analytics & Plats --}}
                    <div class="w-full xl:w-[400px] space-y-6">
                        <div class="glass-card p-6 rounded-[2.5rem]">
                            <h4 class="font-black text-sm uppercase tracking-widest text-gray-500 mb-6">Analytics</h4>
                            <div class="h-48 w-full">
                                <canvas id="vendorViewsChart"></canvas>
                            </div>
                        </div>

                        <div class="glass-card p-6 rounded-[2.5rem]">
                            <h4 class="font-black text-sm uppercase tracking-widest text-gray-500 mb-6">Derniers Plats
                            </h4>
                            <div class="space-y-4">
                                @foreach ($dishes as $dish)
                                    <div
                                        class="flex items-center justify-between p-2 hover:bg-white/5 rounded-xl transition">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-10 h-10 bg-gray-800 rounded-lg overflow-hidden border border-white/10">
                                                <img src="{{ asset('storage/' . $dish->image_path) }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <span class="text-xs font-bold">{{ $dish->name }}</span>
                                        </div>
                                        <span class="text-[10px] text-gray-500">{{ $dish->views_count ?? 0 }}
                                            vues</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL NOUVEAU PLAT --}}
    <div x-show="showDishModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div @click="showDishModal = false" class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
        <div class="glass-card w-full max-w-lg p-8 rounded-[2.5rem] relative z-10 border-coral-500/20">
            <h2 class="text-2xl font-black uppercase italic mb-6">Ajouter un <span class="text-coral-400">Plat</span>
            </h2>
            <form action="{{ route('vendor.dishes.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4 text-sm">
                @csrf
                <div class="space-y-1">
                    <label class="text-gray-500 font-black uppercase text-[10px] tracking-widest">Nom du plat</label>
                    <input type="text" name="name" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:border-coral-500 outline-none transition"
                        placeholder="ex: Burger Signature">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-gray-500 font-black uppercase text-[10px]">Prix (XAF)</label>
                        <input type="number" name="price" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 outline-none focus:border-coral-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-gray-500 font-black uppercase text-[10px]">Photo</label>
                        <input type="file" name="image"
                            class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-coral-500/10 file:text-coral-400">
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-gray-500 font-black uppercase text-[10px]">Description</label>
                    <textarea name="description" rows="2"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 outline-none focus:border-coral-500"></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-coral-500 py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-coral-500/20 hover:scale-[1.02] transition">Enregistrer
                    le plat</button>
            </form>
        </div>
    </div>

    {{-- MODAL NOUVEAU CLIP --}}
    <div x-show="showClipModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div @click="showClipModal = false" class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
        <div class="glass-card w-full max-w-lg p-8 rounded-[2.5rem] relative z-10 border-coral-500/20">
            <h2 class="text-2xl font-black uppercase italic mb-6">Poster un <span class="text-coral-500">Vidish
                    Clip</span></h2>
            <form action="{{ route('vendor.clips.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <div
                    class="w-full h-48 border-2 border-dashed border-white/10 rounded-3xl flex flex-col items-center justify-center relative group hover:border-coral-500 transition cursor-pointer">
                    <i
                        class="fas fa-cloud-upload-alt text-3xl text-gray-500 group-hover:text-coral-500 transition mb-2"></i>
                    <span class="text-[10px] font-black uppercase text-gray-500 tracking-widest">Choisir la vidéo
                        (.mp4)</span>
                    <input type="file" name="video" required class="absolute inset-0 opacity-0 cursor-pointer">
                </div>
                <div class="space-y-1">
                    <label class="text-gray-500 font-black uppercase text-[10px]">Description accrocheuse</label>
                    <textarea name="caption" rows="2"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 outline-none focus:border-coral-500"
                        placeholder="Une petite phrase pour donner faim..."></textarea>
                </div>
                <div class="space-y-1">
                    <label class="text-gray-500 font-black uppercase text-[10px]">Associer à un plat
                        (Optionnel)</label>
                    <select name="dish_id"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 outline-none focus:border-coral-500 text-sm">
                        <option value="" class="bg-[#191919]">Aucun plat spécifique</option>
                        @foreach ($dishes as $dish)
                            <option value="{{ $dish->id }}" class="bg-[#191919]">{{ $dish->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="w-full bg-white text-black py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-coral-500 hover:text-white transition">Publier
                    maintenant</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('vendorViewsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels ?? ['L', 'M', 'M', 'J', 'V', 'S', 'D']) !!},
                datasets: [{
                    data: {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                    borderColor: '#FF6F61',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(255, 111, 97, 0.1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: 'rgba(255,255,255,0.2)',
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>