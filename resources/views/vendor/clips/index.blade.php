<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vidish - Mes Clips</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-black text-white antialiased">
    <div class="max-w-6xl mx-auto p-6">
        <div class="flex justify-between items-end mb-10 border-b border-white/10 pb-6">
            <div>
                <h1 class="text-4xl font-black tracking-tighter uppercase italic">Vidish <span class="text-orange-500">Clips</span></h1>
                <p class="text-gray-400 text-sm">Gérez vos vidéos promotionnelles</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @forelse($clips as $clip)
            <div class="relative aspect-[9/16] rounded-[1.5rem] overflow-hidden bg-zinc-900 group border border-white/5">
                <video src="{{ asset('storage/' . $clip->video_url) }}" 
                       class="w-full h-full object-cover opacity-60"
                       muted onmouseenter="this.play()" onmouseleave="this.pause(); this.currentTime = 0;">
                </video>

                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black via-black/60 to-transparent">
                    <p class="text-[11px] font-medium leading-tight mb-3 line-clamp-2">{{ $clip->description }}</p>
                    <div class="flex items-center justify-between text-[10px] font-bold">
                        <span><i class="fa-solid fa-play mr-1"></i> {{ number_format($clip->views_count) }}</span>
                        <span><i class="fa-solid fa-heart mr-1 text-red-500"></i> {{ number_format($clip->likes_count) }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-32 text-center border-2 border-dashed border-white/10 rounded-[3rem]">
                <p class="text-gray-500 font-bold uppercase tracking-widest">Aucun clip publié</p>
            </div>
            @endforelse
        </div>
    </div>
</body>
</html>