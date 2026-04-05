<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vidish | Mes Clips</title>
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
                        vidishPurple: '#8B5CF6'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-darkBg text-white antialiased flex min-h-screen" 
    x-data="{ 
        showOverlay: false, 
        editMode: false,
        actionUrl: '',
        tagSearch: '',
        selectedTags: [],
        dbTags: {{ json_encode($allTags) }},
        
        formData: { id: '', title: '', dish_id: '', description: '' },

        addTag(tag) {
            let cleanTag = tag.replace('#', '').trim();
            if(cleanTag && !this.selectedTags.includes(cleanTag)) {
                this.selectedTags.push(cleanTag);
            }
            this.tagSearch = '';
        },
        removeTag(tag) {
            this.selectedTags = this.selectedTags.filter(t => t !== tag);
        },
        get suggestions() {
            if (this.tagSearch === '') return [];
            return this.dbTags.filter(t => t.toLowerCase().includes(this.tagSearch.toLowerCase()) && !this.selectedTags.includes(t));
        },
        openAdd() {
            this.editMode = false;
            this.selectedTags = [];
            this.formData = { id: '', title: '', dish_id: '', description: '' };
            this.actionUrl = '{{ route('vendor.clips.store') }}';
            this.showOverlay = true;
        },
        openEdit(clip) {
            this.editMode = true;
            this.selectedTags = clip.tags ? clip.tags.map(t => t.name) : []; 
            this.formData = { 
                id: clip.id, 
                title: clip.title || '', 
                dish_id: clip.dish_id || '', 
                description: clip.description 
            };
            this.actionUrl = '/vendor/clips/' + clip.id;
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
            <a href="{{ route('vendor.dashboard') }}" class="flex items-center space-x-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-white/5 hover:text-white transition-all group">
                <i class="fa-solid fa-chart-pie text-lg"></i>
                <span class="text-xs font-black uppercase tracking-widest">Dashboard</span>
            </a>
            <a href="#" class="flex items-center space-x-4 px-6 py-4 rounded-2xl bg-coral text-white shadow-lg shadow-coral/20 transition-all">
                <i class="fa-solid fa-clapperboard text-lg"></i>
                <span class="text-xs font-black uppercase tracking-widest">Mes Clips</span>
            </a>
            <a href="{{ route('vendor.plats') }}" class="flex items-center space-x-4 px-6 py-4 rounded-2xl text-gray-500 hover:bg-white/5 hover:text-white transition-all group">
                <i class="fa-solid fa-utensils text-lg"></i>
                <span class="text-xs font-black uppercase tracking-widest">Mes Plats</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-64 p-8 md:p-12">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h1 class="text-4xl font-black tracking-tighter uppercase italic text-white">
                        Mes <span class="text-coral">Clips</span>
                    </h1>
                    <p class="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em] mt-2">
                        Vidéos promotionnelles du Restaurant
                    </p>
                </div>
                <button @click="openAdd()" class="bg-white/5 border border-white/10 px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-coral hover:border-coral transition-all">
                    + Nouveau Clip
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($clips as $clip)
                <div class="bg-white/[0.03] rounded-[2.5rem] border border-white/5 p-6 transition-all duration-500 group hover:bg-white/[0.05] hover:border-coral/30">
                    <div class="relative aspect-[9/16] rounded-[2rem] overflow-hidden mb-6 shadow-2xl">
                        <video src="{{ asset('storage/' . $clip->video_path) }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition duration-700" muted onmouseenter="this.play()" onmouseleave="this.pause(); this.currentTime = 0;"></video>
                        
                        <div class="absolute top-4 right-4 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click="openEdit({{ $clip->load('tags') }})" class="bg-black/50 backdrop-blur-md p-3 rounded-full hover:bg-coral transition-colors">
                                <i class="fa-solid fa-pen-to-square text-xs text-white"></i>
                            </button>
                            
                            <form action="{{ route('vendor.clips.destroy', $clip->id) }}" method="POST" onsubmit="return confirm('Es-tu sûr de vouloir supprimer ce clip définitivement ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-black/50 backdrop-blur-md p-3 rounded-full hover:bg-red-600 transition-colors">
                                    <i class="fa-solid fa-trash-can text-xs text-white"></i>
                                </button>
                            </form>
                        </div>

                        <div class="absolute bottom-4 left-4">
                            <span class="text-[7px] font-black uppercase tracking-widest px-3 py-1 rounded-full backdrop-blur-md {{ $clip->dish_id ? 'bg-white/20 text-white' : 'bg-coral/80 text-white' }}">
                                {{ $clip->dish_id ? 'Plat : ' . $clip->dish->name : 'Clip Général' }}
                            </span>
                        </div>
                    </div>
                    <div class="px-2">
                        <h3 class="font-black text-sm uppercase italic tracking-tighter text-white mb-1 line-clamp-1">
                            {{ $clip->title ?? 'Sans titre' }}
                        </h3>
                        <div class="flex flex-wrap gap-1">
                            @foreach($clip->tags as $tag)
                                <span class="text-[8px] font-bold text-vidishPurple bg-vidishPurple/10 px-2 py-0.5 rounded-full">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-32 text-center border-2 border-dashed border-white/5 rounded-[3rem]">
                    <p class="text-gray-500 font-black uppercase tracking-widest text-xs">Aucune vidéo publiée...</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    <div x-show="showOverlay" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-darkBg/95 backdrop-blur-xl" x-transition.opacity>
        <div @click.away="showOverlay = false" class="w-full max-w-2xl bg-[#16191e] border border-white/10 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
            
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-black italic uppercase tracking-tighter">
                    <span x-text="editMode ? 'Modifier' : 'Nouveau'"></span> <span class="text-coral">Clip</span>
                </h2>
                <button @click="showOverlay = false" class="text-gray-500 hover:text-white transition-colors text-2xl">✕</button>
            </div>

            <form :action="actionUrl" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="text-[10px] text-gray-500 uppercase font-black tracking-widest ml-4 mb-2 block">Nom du Clip</label>
                        <input type="text" name="title" x-model="formData.title" required placeholder="Ex: Ambiance du Samedi Soir" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-coral font-bold text-white text-sm transition-all">
                    </div>

                    <div class="col-span-2">
                        <label class="text-[10px] text-gray-500 uppercase font-black tracking-widest ml-4 mb-2 block">Associer à (Optionnel)</label>
                        <select name="dish_id" x-model="formData.dish_id" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-coral font-bold text-white text-sm">
                            <option value="">-- Clip Général (Aucun plat spécifique) --</option>
                            @php $restaurant = Auth::user()->restaurant; @endphp
                            @if($restaurant)
                                @foreach ($restaurant->dishes as $dish)
                                    <option value="{{ $dish->id }}">Plat : {{ $dish->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="text-[10px] text-gray-500 uppercase font-black tracking-widest ml-4 mb-2 block">Tags (#)</label>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <template x-for="tag in selectedTags" :key="tag">
                                <span class="bg-vidishPurple text-white px-3 py-1 rounded-full text-[10px] font-black uppercase flex items-center shadow-lg shadow-vidishPurple/20">
                                    #<span x-text="tag"></span>
                                    <button type="button" @click="removeTag(tag)" class="ml-2 hover:text-white/50">✕</button>
                                </span>
                            </template>
                        </div>
                        <div class="relative">
                            <input type="text" x-model="tagSearch" @keydown.enter.prevent="if(!suggestions.length && tagSearch.length > 0) addTag(tagSearch)" 
                                placeholder="Ajouter un tag..." 
                                class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-vidishPurple text-white text-sm font-bold">
                            <div x-show="tagSearch.length > 0" class="absolute z-10 left-0 right-0 mt-2 bg-[#1c2128] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
                                <template x-for="sug in suggestions" :key="sug">
                                    <button type="button" @click="addTag(sug)" class="w-full text-left px-6 py-3 text-xs font-bold hover:bg-vidishPurple hover:text-white transition-colors border-b border-white/5 uppercase">
                                        <i class="fa-solid fa-hashtag mr-2 text-vidishPurple"></i> <span x-text="sug"></span>
                                    </button>
                                </template>
                                <button type="button" x-show="tagSearch.length > 0 && !dbTags.includes(tagSearch)" @click="addTag(tagSearch)" 
                                    class="w-full text-left px-6 py-4 text-[10px] font-black text-vidishPurple bg-vidishPurple/5 hover:bg-vidishPurple hover:text-white transition-all uppercase tracking-widest">
                                    + Créer le tag "#<span x-text="tagSearch"></span>"
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="tags" :value="selectedTags.join(',')">
                    </div>

                    <div class="col-span-2">
                        <label class="text-[10px] text-gray-500 uppercase font-black tracking-widest ml-4 mb-2 block">Changer la Vidéo (Optionnel)</label>
                        <input type="file" name="video" :required="!editMode" accept="video/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-coral/20 file:text-white file:font-black">
                    </div>

                    <div class="col-span-2">
                        <label class="text-[10px] text-gray-500 uppercase font-black tracking-widest ml-4 mb-2 block">Légende</label>
                        <textarea name="description" x-model="formData.description" rows="3" required placeholder="Décrivez l'ambiance ou le plat..." class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-coral text-sm text-white font-bold"></textarea>
                    </div>
                </div>

                <button type="submit" class="w-full bg-coral py-5 rounded-[2rem] font-black uppercase tracking-widest text-[11px] shadow-2xl hover:scale-[1.02] transition-transform active:scale-95">
                    <span x-text="editMode ? 'Mettre à jour' : 'Publier'"></span>
                </button>
            </form>
        </div>
    </div>

    <p class="fixed bottom-8 left-8 text-[8px] text-gray-700 uppercase tracking-[0.4em] font-bold rotate-180 [writing-mode:vertical-lr]">
        Vidish Engineering &copy; 2026
    </p>

</body>
</html>