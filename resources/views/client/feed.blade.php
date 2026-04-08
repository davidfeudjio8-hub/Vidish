<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vidish | Discovery Feed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: { coral: '#FF5A5F', darkBg: '#0f1115' } } }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .snap-y { scroll-snap-type: y mandatory; }
        .snap-start { scroll-snap-align: start; }
    </style>
</head>
<body class="bg-black text-white antialiased flex h-screen overflow-hidden" 
      x-data="{ 
        commentOpen: false, 
        currentVideoId: null,
        comments: [],
        newComment: '',
        searchQuery: '',
        suggestions: [],
        geoEnabled: true,

        async fetchTags() {
            if (this.searchQuery.startsWith('#') && this.searchQuery.length > 1) {
                let q = this.searchQuery.substring(1);
                let response = await fetch(`/api/tags/search?q=${q}`);
                this.suggestions = await response.json();
            } else { this.suggestions = []; }
        },

        async toggleLike(id) {
            let res = await fetch(`/video/${id}/like`, { 
                method: 'POST', 
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'} 
            });
            let data = await res.json();
            document.getElementById('like-count-'+id).innerText = data.count;
        },

        async loadComments(id) {
            this.currentVideoId = id;
            this.commentOpen = true;
            let res = await fetch(`/video/${id}/comments`);
            this.comments = await res.json();
        },

        async postComment() {
            if(!this.newComment) return;
            let res = await fetch(`/video/${this.currentVideoId}/comment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({content: this.newComment})
            });
            let data = await res.json();
            this.comments.unshift(data); // Ajoute le commentaire en haut de liste
            this.newComment = '';
        }
      }">

    <aside class="w-20 lg:w-64 border-r border-white/5 bg-darkBg flex flex-col z-50 h-full">
        <div class="p-6">
            <h1 class="text-xl font-black italic uppercase tracking-tighter text-white hidden lg:block">VIDISH<span class="text-coral">.</span></h1>
            <div class="lg:hidden text-coral text-2xl font-black italic">V.</div>
        </div>

        <nav class="flex-1 px-3 space-y-2">
            <form action="{{ route('video.explore') }}" method="GET" class="relative px-2 mb-4">
                <div class="bg-white/5 rounded-xl p-3 flex items-center hover:bg-white/10 transition-all focus-within:ring-1 focus-within:ring-coral">
                    <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                    <input type="text" name="search" x-model="searchQuery" @input="fetchTags()" placeholder="Rechercher #..." 
                           class="bg-transparent border-none outline-none text-xs ml-3 hidden lg:block w-full text-white">
                </div>
                <div x-show="suggestions.length > 0" x-cloak class="absolute left-0 right-0 mt-2 bg-darkBg border border-white/10 rounded-xl overflow-hidden shadow-2xl z-[100]">
                    <template x-for="tag in suggestions" :key="tag.name">
                        <button type="submit" @click="searchQuery = '#' + tag.name" class="w-full text-left px-4 py-3 text-[10px] font-black uppercase hover:bg-coral transition-all">
                            <span class="text-coral mr-1">#</span><span x-text="tag.name"></span>
                        </button>
                    </template>
                </div>
            </form>

            <a href="{{ route('video.feed') }}" class="flex items-center space-x-4 px-4 py-3 rounded-xl bg-white/5 text-white">
                <i class="fa-solid fa-house"></i>
                <span class="hidden lg:block text-xs font-bold uppercase">Home</span>
            </a>
            <a href="{{ route('video.explore') }}" class="flex items-center space-x-4 px-4 py-3 rounded-xl text-gray-500 hover:text-white">
                <i class="fa-solid fa-compass"></i>
                <span class="hidden lg:block text-xs font-bold uppercase">Explore</span>
            </a>
            
            @if(Auth::user()->role === 'client')
            <a href="{{ route('client.orders') }}" class="flex items-center space-x-4 px-4 py-3 rounded-xl text-gray-500 hover:text-white">
                <i class="fa-solid fa-utensils"></i>
                <span class="hidden lg:block text-xs font-bold uppercase">Mes Commandes</span>
            </a>
            @endif

            <div class="px-4 py-3 flex items-center justify-between cursor-pointer group" @click="geoEnabled = !geoEnabled">
                <div class="flex items-center space-x-4">
                    <i class="fa-solid fa-location-dot transition-colors" :class="geoEnabled ? 'text-coral' : 'text-gray-500'"></i>
                    <span class="hidden lg:block text-[10px] font-black uppercase">Géo-feed</span>
                </div>
                <div class="w-8 h-4 rounded-full relative transition-colors" :class="geoEnabled ? 'bg-coral' : 'bg-white/10'">
                    <div class="absolute top-1 left-1 w-2 h-2 bg-white rounded-full transition-all" :class="geoEnabled ? 'translate-x-4' : ''"></div>
                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-white/5">
            <div class="flex items-center space-x-3 p-2 rounded-2xl hover:bg-white/5 cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-coral flex items-center justify-center font-bold text-sm uppercase">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="hidden lg:block overflow-hidden">
                    <p class="text-[10px] font-black uppercase tracking-widest leading-none truncate">{{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>
    </aside>

    <main class="flex-1 h-screen overflow-y-scroll snap-y no-scrollbar relative">
        @foreach($clips as $clip)
        <section class="h-screen w-full snap-start relative flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-black">
                <video src="{{ asset('storage/' . $clip->video_path) }}" class="w-full h-full object-cover blur-3xl opacity-30"></video>
            </div>

            <div class="h-[92vh] aspect-[9/16] relative bg-black shadow-2xl lg:rounded-[2.5rem] overflow-hidden border border-white/5 z-10">
                <video src="{{ asset('storage/' . $clip->video_path) }}" class="w-full h-full object-cover" loop autoplay muted playsinline></video>

                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent p-8 flex flex-col justify-end">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 rounded-full border-2 border-coral bg-gray-800 flex items-center justify-center font-bold uppercase">
                            {{ substr($clip->restaurant->name ?? 'V', 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-black italic uppercase tracking-tighter text-lg leading-none">
                                {{ $clip->restaurant->name ?? 'Vidish Kitchen' }}
                            </h3>
                            <p class="text-xs text-coral font-bold mt-1 tracking-widest uppercase">
                                #{{ $clip->dish->name ?? 'Spécialité' }}
                            </p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-300 line-clamp-2 mb-6">{{ $clip->description }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($clip->tags as $tag)
                        <span class="text-[8px] bg-white/10 px-2 py-1 rounded-md uppercase font-black tracking-widest text-coral">
                            #{{ $tag->name }}
                        </span>
                        @endforeach
                    </div>
                </div>

                <div class="absolute right-4 bottom-24 flex flex-col space-y-6 z-20">
                    <button @click="toggleLike({{ $clip->id }})" class="flex flex-col items-center group">
                        <div class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center group-hover:bg-coral transition-all">
                            <i class="fa-solid fa-heart text-lg"></i>
                        </div>
                        <span id="like-count-{{ $clip->id }}" class="text-[10px] font-black mt-2">
                            {{ $clip->likes->count() }}
                        </span>
                    </button>
                    <button @click="loadComments({{ $clip->id }})" class="flex flex-col items-center group">
                        <div class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center group-hover:bg-white/20 transition-all">
                            <i class="fa-solid fa-comment text-lg"></i>
                        </div>
                        <span class="text-[10px] font-black mt-2">Voir</span>
                    </button>
                </div>
            </div>
        </section>
        @endforeach

        <div x-show="commentOpen" x-cloak 
             @click.away="commentOpen = false"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             class="fixed top-0 right-0 h-full w-full lg:w-[400px] bg-darkBg z-[100] border-l border-white/5 p-8 flex flex-col shadow-2xl">
            
            <div class="flex justify-between items-center mb-8">
                <h4 class="text-xl font-black italic uppercase tracking-tighter">Commentaires</h4>
                <button @click="commentOpen = false" class="text-gray-500 hover:text-white transition-colors">✕</button>
            </div>

            <div class="flex-1 overflow-y-auto no-scrollbar space-y-4 pr-2">
                <template x-for="comment in comments" :key="comment.id">
                    <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                        <p class="text-coral font-black text-[10px] uppercase tracking-widest" x-text="comment.user_name || 'Utilisateur'"></p>
                        <p class="text-sm text-gray-300 mt-1" x-text="comment.content"></p>
                    </div>
                </template>
                <div x-show="comments.length === 0" class="text-center py-10 text-gray-600 uppercase font-black text-xs italic">
                    Aucun avis pour le moment
                </div>
            </div>

            <div class="mt-6 flex space-x-2">
                <input type="text" x-model="newComment" @keyup.enter="postComment()" placeholder="Laisse un avis..." 
                       class="flex-1 bg-white/5 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-coral text-sm transition-all">
                <button @click="postComment()" class="bg-coral px-6 rounded-2xl font-black italic uppercase text-xs hover:scale-105 active:scale-95 transition-all">
                    OK
                </button>
            </div>
        </div>
    </main>
</body>
</html>