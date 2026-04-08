<?php

namespace App\Livewire;

use App\Models\Video;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
// Ajout de "layout" dans l'import Volt
use function Livewire\Volt\{state, mount, on, layout};

// On force l'utilisation du layout "guest" qui est généralement vide de toute nav bar
layout('layouts.guest');

state([
    'clips' => [],
    'commentOpen' => false,
    'currentVideoId' => null,
    'newComment' => '',
    'searchQuery' => '',
    'suggestions' => [],
    'geoEnabled' => true,
]);

mount(function () {
    $this->loadClips();
});

// Action : Charger les vidéos
$loadClips = function () {
    $query = Video::with(['restaurant', 'dish', 'tags', 'likes', 'comments.user']);
    
    if (!empty($this->searchQuery) && str_starts_with($this->searchQuery, '#')) {
        $tagName = ltrim($this->searchQuery, '#');
        $query->whereHas('tags', function($q) use ($tagName) {
            $q->where('name', 'like', "%{$tagName}%");
        });
    }

    $this->clips = $query->latest()->get();
};

// Action : Recherche de tags (Suggestions)
$fetchTags = function () {
    if (str_starts_with($this->searchQuery, '#') && strlen($this->searchQuery) > 1) {
        $q = ltrim($this->searchQuery, '#');
        $this->suggestions = Tag::where('name', 'like', "%{$q}%")->limit(5)->get()->toArray();
    } else {
        $this->suggestions = [];
    }
};

// Action : Like/Unlike
$toggleLike = function ($videoId) {
    $like = Like::where('user_id', Auth::id())->where('video_id', $videoId)->first();

    if ($like) {
        $like->delete();
    } else {
        Like::create([
            'user_id' => Auth::id(),
            'video_id' => $videoId
        ]);
    }
    $this->loadClips(); 
};

// Action : Ouvrir les commentaires
$openComments = function ($videoId) {
    $this->currentVideoId = $videoId;
    $this->commentOpen = true;
};

// Action : Poster un commentaire
$postComment = function () {
    if (!$this->newComment || !$this->currentVideoId) return;

    Comment::create([
        'user_id' => Auth::id(),
        'video_id' => $this->currentVideoId,
        'content' => $this->newComment
    ]);

    $this->newComment = '';
    $this->loadClips();
};

?>

{{-- Utilisation de fixed inset-0 pour s'assurer que le design prend TOUT l'écran --}}
<div class="fixed inset-0 bg-black text-white antialiased flex overflow-hidden font-sans z-[9999]">
    {{-- Configuration Tailwind & Styles --}}
    <style>
        :root { --coral: #FF5A5F; --darkBg: #0f1115; }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .snap-y { scroll-snap-type: y mandatory; }
        .snap-start { scroll-snap-align: start; }
        .text-coral { color: var(--coral); }
        .bg-coral { background-color: var(--coral); }
        .border-coral { border-color: var(--coral); }
        /* Reset pour éviter tout scroll parasite du body Laravel */
        body { overflow: hidden !important; margin: 0 !important; }
    </style>

    {{-- Sidebar Responsive --}}
    <aside class="w-20 lg:w-64 border-r border-white/5 bg-[#0f1115] flex flex-col z-50 h-full transition-all">
        <div class="p-6">
            <h1 class="text-xl font-black italic uppercase tracking-tighter text-white hidden lg:block">VIDISH<span class="text-coral">.</span></h1>
            <div class="lg:hidden text-coral text-2xl font-black italic text-center">V.</div>
        </div>

        <nav class="flex-1 px-3 space-y-2">
            {{-- Recherche --}}
            <div class="relative px-2 mb-4">
                <div class="bg-white/5 rounded-xl p-3 flex items-center hover:bg-white/10 transition-all focus-within:ring-1 focus-within:ring-coral">
                    <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                    <input type="text" 
                           wire:model.live.debounce.300ms="searchQuery" 
                           wire:input="fetchTags"
                           placeholder="Rechercher #..." 
                           class="bg-transparent border-none outline-none text-xs ml-3 hidden lg:block w-full text-white placeholder-gray-600">
                </div>
                
                @if(count($suggestions) > 0)
                <div class="absolute left-0 right-0 mt-2 bg-[#0f1115] border border-white/10 rounded-xl overflow-hidden shadow-2xl z-[100]">
                    @foreach($suggestions as $tag)
                    <button wire:click="$set('searchQuery', '#{{ $tag['name'] }}'); $set('suggestions', []); loadClips()" 
                            class="w-full text-left px-4 py-3 text-[10px] font-black uppercase hover:bg-coral transition-all">
                        <span class="text-white/50 mr-1">#</span>{{ $tag['name'] }}
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <a href="{{ route('video.feed') }}" class="flex items-center space-x-4 px-4 py-3 rounded-xl bg-white/5 text-white group">
                <i class="fa-solid fa-house text-coral"></i>
                <span class="hidden lg:block text-xs font-bold uppercase tracking-widest">Home</span>
            </a>
            
            <a href="{{ route('video.explore') }}" class="flex items-center space-x-4 px-4 py-3 rounded-xl text-gray-500 hover:text-white hover:bg-white/5 transition-all">
                <i class="fa-solid fa-compass"></i>
                <span class="hidden lg:block text-xs font-bold uppercase tracking-widest">Explore</span>
            </a>

            <div class="px-4 py-3 flex items-center justify-between cursor-pointer group" wire:click="$toggle('geoEnabled')">
                <div class="flex items-center space-x-4">
                    <i class="fa-solid fa-location-dot transition-colors {{ $geoEnabled ? 'text-coral' : 'text-gray-500' }}"></i>
                    <span class="hidden lg:block text-[10px] font-black uppercase tracking-widest {{ $geoEnabled ? 'text-white' : 'text-gray-500' }}">Géo-feed</span>
                </div>
                <div class="hidden lg:block w-8 h-4 rounded-full relative transition-colors {{ $geoEnabled ? 'bg-coral' : 'bg-white/10' }}">
                    <div class="absolute top-1 left-1 w-2 h-2 bg-white rounded-full transition-all {{ $geoEnabled ? 'translate-x-4' : '' }}"></div>
                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-white/5">
            <div class="flex items-center space-x-3 p-2 rounded-2xl hover:bg-white/5 cursor-pointer transition-all">
                <div class="w-10 h-10 rounded-full bg-coral flex items-center justify-center font-bold text-sm uppercase shadow-lg shadow-coral/20">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="hidden lg:block overflow-hidden">
                    <p class="text-[10px] font-black uppercase tracking-widest leading-none truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[8px] text-gray-500 uppercase mt-1">Mon Compte</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Discovery Feed --}}
    <main class="flex-1 h-full overflow-y-scroll snap-y no-scrollbar relative bg-[#090a0c]">
        @forelse($clips as $clip)
        <section class="h-full w-full snap-start relative flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0">
                <video src="{{ asset('storage/' . $clip->video_path) }}" class="w-full h-full object-cover blur-[80px] opacity-20"></video>
            </div>

            <div class="h-full lg:h-[92vh] w-full lg:aspect-[9/16] relative bg-black shadow-[0_0_50px_rgba(0,0,0,0.5)] lg:rounded-[2.5rem] overflow-hidden border border-white/5 z-10 transition-all">
                <video src="{{ asset('storage/' . $clip->video_path) }}" 
                       class="w-full h-full object-cover" 
                       loop autoplay muted playsinline></video>

                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-transparent to-transparent p-6 lg:p-10 flex flex-col justify-end">
                    <div class="flex items-center space-x-4 mb-5 group cursor-pointer">
                        <div class="w-14 h-14 rounded-full border-2 border-coral bg-[#1a1c21] flex items-center justify-center font-bold uppercase text-xl shadow-lg shadow-coral/20 transform group-hover:scale-110 transition-all">
                            {{ substr($clip->restaurant->name ?? 'V', 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-black italic uppercase tracking-tighter text-xl leading-none flex items-center">
                                {{ $clip->restaurant->name ?? 'Vidish Kitchen' }}
                                <i class="fa-solid fa-circle-check text-blue-400 text-[10px] ml-2"></i>
                            </h3>
                            <p class="text-xs text-coral font-black mt-2 tracking-[0.2em] uppercase">
                                {{ $clip->dish->name ?? 'Spécialité' }}
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-300 line-clamp-2 mb-6 max-w-[85%] font-medium leading-relaxed">
                        {{ $clip->description }}
                    </p>

                    <div class="flex flex-wrap gap-2">
                        @foreach($clip->tags as $tag)
                        <span class="text-[9px] bg-coral/10 border border-coral/20 px-3 py-1.5 rounded-full uppercase font-black tracking-widest text-coral backdrop-blur-md">
                            #{{ $tag->name }}
                        </span>
                        @endforeach
                    </div>
                </div>

                <div class="absolute right-4 lg:right-6 bottom-24 flex flex-col space-y-7 z-20">
                    <button wire:click="toggleLike({{ $clip->id }})" class="flex flex-col items-center group">
                        @php $hasLiked = $clip->likes->where('user_id', Auth::id())->first(); @endphp
                        <div class="w-14 h-14 rounded-full backdrop-blur-xl flex items-center justify-center transition-all duration-300 transform group-active:scale-90 {{ $hasLiked ? 'bg-coral text-white shadow-lg shadow-coral/40' : 'bg-white/10 text-white hover:bg-coral/40' }}">
                            <i class="fa-solid fa-heart text-xl"></i>
                        </div>
                        <span class="text-[10px] font-black mt-2 tracking-widest uppercase">{{ $clip->likes->count() }}</span>
                    </button>

                    <button wire:click="openComments({{ $clip->id }})" class="flex flex-col items-center group">
                        <div class="w-14 h-14 rounded-full bg-white/5 backdrop-blur-xl border border-white/10 flex items-center justify-center hover:bg-white/20 transition-all transform group-active:scale-90">
                            <i class="fa-solid fa-comment-dots text-xl"></i>
                        </div>
                        <span class="text-[10px] font-black mt-2 tracking-widest uppercase">Avis</span>
                    </button>

                    <button class="flex flex-col items-center group">
                        <div class="w-14 h-14 rounded-full bg-white/5 backdrop-blur-xl border border-white/10 flex items-center justify-center hover:bg-white/20 transition-all">
                            <i class="fa-solid fa-share text-lg"></i>
                        </div>
                        <span class="text-[10px] font-black mt-2 tracking-widest uppercase">Partager</span>
                    </button>
                </div>
            </div>
        </section>
        @empty
        <div class="h-full w-full flex flex-col items-center justify-center text-gray-600 space-y-4 bg-darkBg">
            <i class="fa-solid fa-utensils text-4xl text-white/5"></i>
            <p class="uppercase font-black italic tracking-widest text-sm">Aucune pépite trouvée ici.</p>
        </div>
        @endforelse
    </main>

    {{-- Comment Modal --}}
    <div x-show="$wire.commentOpen" x-cloak 
         class="fixed top-0 right-0 h-full w-full lg:w-[450px] bg-[#0f1115] z-[100] border-l border-white/10 flex flex-col shadow-[0_0_100px_rgba(0,0,0,0.8)]"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full">
        
        <div class="p-8 border-b border-white/5 flex justify-between items-center bg-[#13151b]">
            <div>
                <h4 class="text-2xl font-black italic uppercase tracking-tighter">Avis Clients<span class="text-coral">.</span></h4>
                <p class="text-[9px] text-gray-500 font-black uppercase tracking-[0.3em] mt-1">Partage ton expérience</p>
            </div>
            <button @click="$wire.commentOpen = false" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-coral transition-all">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto no-scrollbar p-6 space-y-4">
            @php $currentVideo = $clips->find($currentVideoId); @endphp
            @if($currentVideo && $currentVideo->comments->count() > 0)
                @foreach($currentVideo->comments->sortByDesc('created_at') as $comment)
                    <div class="bg-white/[0.03] p-5 rounded-[1.5rem] border border-white/5 hover:border-coral/30 transition-all">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-6 h-6 rounded-full bg-coral/20 text-coral flex items-center justify-center text-[10px] font-black">
                                {{ substr($comment->user->name, 0, 1) }}
                            </div>
                            <span class="text-coral font-black text-[11px] uppercase tracking-widest">{{ $comment->user->name }}</span>
                            <span class="text-[8px] text-gray-600 uppercase font-bold">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-300 leading-relaxed font-medium">{{ $comment->content }}</p>
                    </div>
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center h-full opacity-20">
                    <i class="fa-solid fa-comments text-6xl mb-4"></i>
                    <p class="uppercase font-black text-xs italic tracking-widest text-center">Sois le premier à donner ton avis !</p>
                </div>
            @endif
        </div>

        <div class="p-8 bg-[#13151b] border-t border-white/5">
            <div class="relative flex items-center gap-3">
                <input type="text" 
                       wire:model="newComment" 
                       wire:keydown.enter="postComment" 
                       placeholder="Laisse un avis gourmand..." 
                       class="flex-1 bg-white/5 border border-white/10 rounded-2xl px-6 py-5 outline-none focus:border-coral/50 focus:bg-white/[0.07] text-sm transition-all text-white placeholder-gray-600 shadow-inner">
                
                <button wire:click="postComment" 
                        class="bg-coral h-[58px] px-8 rounded-2xl font-black italic uppercase text-xs hover:brightness-110 active:scale-95 transition-all shadow-lg shadow-coral/20">
                    Poster
                </button>
            </div>
        </div>
    </div>
</div>