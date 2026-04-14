<?php

namespace App\Livewire;

use App\Models\Video;
use function Livewire\Volt\{state, layout, computed};

layout('layouts.guest');

state(['search' => '']);

$clips = computed(function () {
    return Video::with(['restaurant', 'dish', 'tags'])
        ->when($this->search, function ($query) {
            $searchTerm = $this->search;
            if (str_starts_with($searchTerm, '#')) {
                $tag = ltrim($searchTerm, '#');
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('name', 'like', "%{$tag}%");
                });
            } else {
                $query
                    ->whereHas('restaurant', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('dish', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            }
        })
        ->latest()
        ->get();
});

?>

<div
    class="fixed inset-0 bg-[#090a0c] text-white antialiased flex flex-col md:flex-row overflow-hidden font-sans z-[9999]">
    <style>
        :root {
            --coral: #FF5A5F;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .bg-coral {
            background-color: var(--coral) !important;
        }

        .text-coral {
            color: var(--coral) !important;
        }

        body {
            overflow: hidden !important;
            margin: 0 !important;
            background-color: #090a0c !important;
            -webkit-tap-highlight-color: transparent;
        }

        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>

    {{-- SIDEBAR (Desktop) --}}
    <aside class="hidden md:flex w-20 lg:w-64 border-r border-white/5 bg-[#0f1115] flex-col z-50 h-full">
        <div class="p-6">
            <h1 class="text-xl font-black italic uppercase tracking-tighter text-white hidden lg:block">VIDISH<span
                    class="text-coral">.</span></h1>
            <div class="lg:hidden text-coral text-2xl font-black italic text-center">V.</div>
        </div>

        <nav class="flex-1 px-3 space-y-2">
            <a href="{{ route('video.feed') }}"
                class="flex items-center space-x-4 px-4 py-3 rounded-xl text-gray-500 hover:text-white hover:bg-white/5 transition-all group">
                <i class="fa-solid fa-house group-hover:text-coral"></i>
                <span class="hidden lg:block text-xs font-bold uppercase tracking-widest">Home</span>
            </a>
            <a href="{{ route('video.explore') }}"
                class="flex items-center space-x-4 px-4 py-3 rounded-xl bg-white/5 text-white border border-white/5">
                <i class="fa-solid fa-compass text-coral"></i>
                <span class="hidden lg:block text-xs font-bold uppercase tracking-widest text-coral">Explore</span>
            </a>
        </nav>

        <div class="p-4 border-t border-white/5">
            <div class="flex items-center space-x-3 p-2 rounded-2xl">
                <div
                    class="w-10 h-10 rounded-full bg-coral flex items-center justify-center font-bold text-sm uppercase shadow-lg shadow-coral/20">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="hidden lg:block overflow-hidden">
                    <p class="text-[10px] font-black uppercase tracking-widest leading-none truncate">
                        {{ Auth::user()->name ?? 'Guest' }}</p>
                    <p class="text-[8px] text-gray-500 uppercase mt-1">Mon Compte</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 h-full overflow-y-auto no-scrollbar relative bg-[#090a0c] pb-32 md:pb-0">
        <div class="max-w-7xl mx-auto p-4 md:p-12">

            {{-- HEADER --}}
            <header class="flex flex-col gap-6 mb-8 md:mb-16">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-5xl md:text-8xl font-black italic uppercase tracking-tighter leading-none m-0">
                            Explore<span class="text-coral">.</span>
                        </h2>
                        <p
                            class="text-gray-500 font-black uppercase text-[8px] md:text-[10px] mt-2 tracking-[0.3em] italic opacity-60">
                            Les pépites à proximité, exploration geolocaliser
                        </p>
                    </div>
                </div>

                <div class="relative group w-full">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-600 group-focus-within:text-coral transition-colors"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Un plat, un resto, un #tag..."
                        class="w-full bg-white/5 border border-white/10 rounded-2xl pl-14 pr-6 py-4 outline-none focus:border-coral/40 focus:bg-white/[0.08] transition-all text-sm text-white placeholder-gray-700 shadow-xl">
                </div>
            </header>

            {{-- GRID --}}
            @if ($this->clips->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                    @foreach ($this->clips as $clip)
                        <div
                            class="group relative aspect-[9/16] bg-black rounded-[2.5rem] overflow-hidden border border-white/5 md:hover:border-coral/50 transition-all duration-500 shadow-2xl">
                            <video src="{{ asset('storage/' . $clip->video_path) }}"
                                class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-all duration-700"
                                @if (!Str::contains(request()->header('User-Agent'), ['Mobile', 'Android', 'iPhone'])) onmouseover="this.play()" onmouseout="this.pause(); this.currentTime = 0;" @endif
                            </video>

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent p-6 flex flex-col justify-end">
                                <div
                                    class="md:transform md:translate-y-4 md:group-hover:translate-y-0 transition-transform duration-500">
                                    <span
                                        class="text-coral font-black text-[9px] uppercase tracking-[0.2em]">#{{ $clip->dish->name ?? 'Gourmet' }}</span>
                                    <h3 class="font-black italic text-xl uppercase tracking-tighter text-white mb-4">
                                        {{ $clip->restaurant->name ?? 'Vidish Kitchen' }}
                                    </h3>

                                    <div
                                        class="flex items-center justify-between md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-500">
                                        <span
                                            class="text-[8px] text-gray-400 font-bold uppercase tracking-widest flex items-center">
                                            <i class="fa-solid fa-location-dot mr-1.5 text-coral"></i> 1.2 KM
                                        </span>
                                        <a href="{{ route('video.feed') }}"
                                            class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center hover:bg-coral transition-all border border-white/10 shadow-xl">
                                            <i class="fa-solid fa-play text-[10px] ml-0.5"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    class="h-[40vh] flex flex-col items-center justify-center border-2 border-dashed border-white/5 rounded-[3rem] bg-white/[0.01]">
                    <i class="fa-solid fa-utensils text-white/5 text-4xl mb-4"></i>
                    <p class="text-gray-600 font-black uppercase text-[10px] italic">Aucune pépite trouvée</p>
                    <button wire:click="$set('search', '')"
                        class="mt-4 text-coral text-[10px] font-black uppercase tracking-widest hover:underline">Voir
                        tout</button>
                </div>
            @endif
        </div>
    </main>

    {{-- BOTTOM NAVIGATION (Mobile - Design Pilule) --}}
    <div class="md:hidden fixed bottom-6 left-1/2 -translate-x-1/2 w-[90%] max-w-[400px] z-[10000]">
        <nav
            class="bg-[#16181d]/90 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] p-2 flex justify-between items-center shadow-[0_20px_50px_rgba(0,0,0,0.5)] safe-bottom">

            {{-- Home avec Pilule --}}
            <a href="{{ route('video.feed') }}" class="flex-1 flex justify-center py-3 group">
                <div class="flex items-center justify-center bg-coral px-6 py-2 rounded-full shadow-lg shadow-coral/20">
                    <i class="fa-solid fa-house text-white text-sm"></i>
                    <span class="ml-2 text-[10px] font-black uppercase tracking-tighter text-white">Home</span>
                </div>
            </a>

            {{-- Explore (Icone simple si pas actif, ou tu peux inverser) --}}
            <a href="{{ route('video.explore') }}" class="flex-1 flex justify-center py-3">
                <i class="fa-solid fa-compass text-coral text-xl"></i>
            </a>

            {{-- Profil avec Pilule --}}
            <a href="#" class="flex-1 flex justify-center py-3">
                <div class="flex items-center justify-center bg-coral px-6 py-2 rounded-full shadow-lg shadow-coral/20">
                    <i class="fa-solid fa-user text-white text-sm"></i>
                    <span class="ml-2 text-[10px] font-black uppercase tracking-tighter text-white">Moi</span>
                </div>
            </a>

        </nav>
    </div>
</div>
