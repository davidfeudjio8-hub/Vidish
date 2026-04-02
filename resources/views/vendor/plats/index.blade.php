<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vidish | Mes Plats</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-darkBg text-white antialiased flex min-h-screen" x-data="{
    showOverlay: false,
    editMode: false,
    actionUrl: '',
    formData: { name: '', price: '', description: '', id: '' },
    openAdd() {
        this.editMode = false;
        this.formData = { name: '', price: '', description: '', id: '' };
        this.actionUrl = '{{ route('vendor.dishes.store') }}';
        this.showOverlay = true;
    },
    openEdit(plat) {
        this.editMode = true;
        this.formData = {
            id: plat.id,
            name: plat.name,
            price: plat.price,
            description: plat.description || ''
        };
        this.actionUrl = '/vendor/dishes/' + plat.id;
        this.showOverlay = true;
    }
}">

    <aside class="w-64 border-r border-white/5 flex flex-col fixed h-full bg-darkBg z-50">
        <div class="p-8">
            <h1 class="text-2xl font-black italic uppercase tracking-tighter text-white">
                VIDISH<span class="text-coral">.</span>
            </h1>
        </div>

        <nav class="flex-1 px-4 space-y-2">
            <a href="{{ route('vendor.dashboard') }}"
                class="flex items-center space-x-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-white/5 hover:text-white transition-all group">
                <i class="fa-solid fa-chart-pie text-lg"></i>
                <span class="text-xs font-black uppercase tracking-widest">Dashboard</span>
            </a>
            <a href="{{ route('vendor.clips') }}"
                class="flex items-center space-x-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-white/5 hover:text-white transition-all group">
                <i class="fa-solid fa-clapperboard text-lg"></i>
                <span class="text-xs font-black uppercase tracking-widest">Mes Clips</span>
            </a>
            <a href="{{ route('vendor.plats') }}"
                class="flex items-center space-x-4 px-6 py-4 rounded-2xl bg-coral text-white shadow-lg shadow-coral/20 transition-all">
                <i class="fa-solid fa-utensils text-lg"></i>
                <span class="text-xs font-black uppercase tracking-widest">Mes Plats</span>
            </a>
            <a href="{{ route('vendor.settings') }}"
                class="flex items-center space-x-4 px-6 py-4 rounded-2xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i class="fa-solid fa-gears text-lg"></i>
                <span class="text-xs font-black uppercase tracking-widest">Ma Cuisine</span>
            </a>
        </nav>

        <div class="p-8 border-t border-white/5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center space-x-4 px-6 py-4 text-gray-500 hover:text-red-500 transition-all w-full text-left">
                    <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                    <span class="text-xs font-black uppercase tracking-widest">Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-8 md:p-12">
        <div class="max-w-7xl mx-auto">

            <div class="flex justify-between items-end mb-12">
                <div>
                    <h1 class="text-4xl font-black tracking-tighter uppercase italic text-white">
                        Mes <span class="text-coral">Plats</span>
                    </h1>
                    <p class="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em] mt-2">
                        Gérer le Menu du Restaurant
                    </p>
                </div>

                <button @click="openAdd()"
                    class="bg-white/5 border border-white/10 px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-coral hover:border-coral transition-all shadow-xl shadow-black/20">
                    + Ajouter un Plat
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($plats as $plat)
                    <div
                        class="bg-white/[0.03] rounded-[2.5rem] border border-white/5 p-6 transition-all duration-500 group hover:bg-white/[0.05] hover:border-coral/30">
                        <div class="relative aspect-square rounded-[2rem] overflow-hidden mb-6 shadow-2xl">
                            <img src="{{ asset('storage/' . $plat->image_path) }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-80 group-hover:opacity-100">
                            <div
                                class="absolute bottom-4 left-4 bg-darkBg/80 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10">
                                <span class="text-coral font-black text-sm tracking-tight">
                                    {{ number_format($plat->price, 0, '', ' ') }} <span class="text-[10px]">FCFA</span>
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between items-start px-2">
                            <div class="flex-1">
                                <h3
                                    class="font-black text-lg uppercase italic tracking-tighter text-white mb-1 leading-none">
                                    {{ $plat->name }}</h3>
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-500 italic">
                                    <i class="fa-solid fa-fire-flame-curved text-coral mr-1"></i>
                                    {{ $plat->orders_count ?? 0 }} Commandes
                                </span>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <button type="button"
                                    @click="openEdit({ 
                                    id: {{ $plat->id }}, 
                                    name: '{{ addslashes($plat->name) }}', 
                                    price: {{ $plat->price }}, 
                                    description: '{{ addslashes($plat->description) }}' 
                                    })"
                                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-gray-400 hover:bg-coral hover:text-white transition-all shadow-lg">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>

                                <form action="{{ route('vendor.dishes.destroy', $plat->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment retirer ce plat du menu ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-gray-400 hover:bg-red-500 hover:text-white transition-all shadow-lg">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-32 text-center border-2 border-dashed border-white/5 rounded-[3rem] bg-white/[0.01]">
                        <p class="text-gray-500 font-black uppercase tracking-widest text-xs">Cuisine vide...</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <div x-show="showOverlay" x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-darkBg/95 backdrop-blur-xl"
        x-transition.opacity>
        <div @click.away="showOverlay = false"
            class="w-full max-w-2xl bg-[#16191e] border border-white/10 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">

            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-black italic uppercase tracking-tighter text-white">
                    <span x-text="editMode ? 'Modifier' : 'Nouveau'"></span> <span class="text-coral">Plat</span>
                </h2>
                <button @click="showOverlay = false"
                    class="text-gray-500 hover:text-white transition-colors text-2xl">✕</button>
            </div>

            <form :action="actionUrl" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label
                            class="text-[10px] text-gray-500 uppercase font-black tracking-widest ml-4 mb-2 block">Nom
                            du Plat</label>
                        <input type="text" name="name" x-model="formData.name" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-coral transition-all font-bold text-white">
                    </div>
                    <div>
                        <label
                            class="text-[10px] text-gray-500 uppercase font-black tracking-widest ml-4 mb-2 block">Prix
                            (FCFA)</label>
                        <input type="number" name="price" x-model="formData.price" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-coral transition-all font-bold text-white">
                    </div>
                    <div>
                        <label
                            class="text-[10px] text-gray-500 uppercase font-black tracking-widest ml-4 mb-2 block">Image</label>
                        <input type="file" name="image" :required="!editMode"
                            class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-coral/20 file:text-white file:font-black">
                        <p x-show="editMode" class="text-[9px] text-gray-500 mt-1 ml-4 italic">Laissez vide pour
                            conserver l'image actuelle.</p>
                    </div>
                    <div class="col-span-2">
                        <label
                            class="text-[10px] text-gray-500 uppercase font-black tracking-widest ml-4 mb-2 block">Description</label>
                        <textarea name="description" x-model="formData.description" rows="3"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-coral transition-all font-bold text-white"></textarea>
                    </div>
                    <template x-if="!editMode">
                        <div class="col-span-2">
                            <label
                                class="text-[10px] text-gray-500 uppercase font-black tracking-widest ml-4 mb-2 block italic text-coral">Clip
                                Vidéo (Optionnel)</label>
                            <input type="file" name="video" accept="video/*"
                                class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-white/10 file:text-white">
                        </div>
                    </template>
                </div>

                <button type="submit"
                    class="w-full bg-coral py-5 rounded-[2rem] font-black uppercase tracking-widest text-[11px] shadow-2xl hover:scale-[1.02] transition-transform text-white">
                    <span x-text="editMode ? 'Mettre à jour le plat' : 'Enregistrer dans le menu'"></span>
                </button>
            </form>
        </div>
    </div>

    <p
        class="fixed bottom-8 left-8 text-[8px] text-gray-700 uppercase tracking-[0.4em] font-bold rotate-180 [writing-mode:vertical-lr]">
        Vidish Engineering &copy; 2026
    </p>

</body>

</html>