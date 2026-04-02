<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vidish | Paramètres du Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        coral: '#FF5A5F',
                        darkBg: '#0f1115',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .glass-card { 
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-darkBg dark:bg-darkBg transition-colors duration-300">

    <div class="min-h-screen flex flex-col items-center justify-center p-4 md:p-8">
        
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 -translate-y-4"
             class="fixed top-8 z-50 w-full max-w-md px-4">
            <div class="bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-xl p-4 rounded-2xl flex items-center justify-between shadow-2xl">
                <div class="flex items-center space-x-3">
                    <div class="bg-emerald-500 p-1.5 rounded-full text-white">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm font-bold text-emerald-500 uppercase tracking-widest">Modifications enregistrées avec succès</p>
                </div>
                <button @click="show = false" class="text-emerald-500/50 hover:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        @endif

        <div class="w-full max-w-2xl mb-8 flex justify-between items-end px-4">
            <div>
                <h1 class="text-3xl font-black italic uppercase tracking-tighter text-white">
                    VIDISH<span class="text-coral">.</span>
                </h1>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.3em]">Paramètres Marchand</p>
            </div>
            <button onclick="document.documentElement.classList.toggle('dark')" class="p-3 rounded-2xl bg-white/5 hover:bg-coral/20 transition-all border border-white/5 text-coral">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
        </div>

        <div class="w-full max-w-2xl glass-card rounded-[40px] shadow-2xl overflow-hidden relative" x-data="{ photoPreview: null }">
            
            <a href="{{ route('vendor.dashboard') }}" 
               class="absolute top-8 right-8 p-2.5 rounded-full bg-white/5 hover:bg-red-500/20 text-gray-500 hover:text-red-500 transition-all duration-300 group z-20">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
            
            <div class="p-8 md:p-12 border-b border-white/5">
                <h2 class="text-2xl font-black italic uppercase tracking-tight text-white pr-12">
                    Éditer votre <span class="text-coral">Cuisine</span>
                </h2>
            </div>

            <form action="{{ route('vendor.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-12 space-y-8">
                @csrf
                @method('PUT')

                <div class="flex flex-col items-center space-y-4 pb-4">
                    <div class="relative group">
                        <div class="w-32 h-32 rounded-[32px] overflow-hidden border-2 border-white/10 group-hover:border-coral transition-all duration-300 shadow-2xl bg-white/5">
                            <template x-if="!photoPreview">
                                <img src="{{ $restaurant->image_path ? asset('storage/' . $restaurant->image_path) : asset('images/default-restaurant.jpg') }}"
                                     class="w-full h-full object-cover">
                            </template>
                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="w-full h-full object-cover">
                            </template>
                        </div>
                        
                        <label for="logo_path" class="absolute inset-0 flex items-center justify-center bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-[32px]">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </label>
                    </div>
                    
                    <input type="file" name="logo_path" id="logo_path" class="hidden" accept="image/*"
                           @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result }; reader.readAsDataURL(file); }">
                    
                    <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Logo du Restaurant</p>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-black ml-2">Nom du Restaurant</label>
                    <input type="text" name="name" value="{{ old('name', $restaurant->name) }}" 
                           placeholder="Ex: Le Gourmet de Yaoundé" required
                           class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white outline-none focus:border-coral transition-all">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-black ml-2">Adresse Physique</label>
                    <input type="text" name="address" value="{{ old('address', $restaurant->address) }}" 
                           placeholder="Ex: Rue 1234, Bastos, Yaoundé" required
                           class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white outline-none focus:border-coral transition-all">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-black ml-2">Description de votre établissement</label>
                    <textarea name="description" rows="4" placeholder="Décrivez vos spécialités, votre ambiance..."
                              class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white outline-none focus:border-coral transition-all resize-none">{{ old('description', $restaurant->description) }}</textarea>
                </div>

                <div class="flex items-center justify-between p-6 bg-white/[0.02] rounded-3xl border border-white/5">
                    <div>
                        <span class="block text-xs font-black uppercase tracking-widest text-white">Livraison Disponible</span>
                        <span class="text-[10px] text-gray-500 uppercase">Prêt pour les commandes à domicile</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="has_delivery" value="1" class="sr-only peer" {{ $restaurant->has_delivery ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-white/10 rounded-full peer peer-checked:bg-coral transition-all after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-gray-400 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:bg-white shadow-inner"></div>
                    </label>
                </div>

                <button type="submit" class="w-full py-5 bg-coral text-white font-black uppercase tracking-[0.3em] text-xs rounded-2xl shadow-lg hover:scale-[1.02] active:scale-95 transition-all">
                    Enregistrer les Modifications
                </button>
            </form>
            
        </div>

        <p class="mt-10 text-[9px] text-gray-600 uppercase tracking-[0.5em] font-bold">Vidish Engineering &copy; 2026</p>
    </div>

</body>
</html>