<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vidish - Mes Plats</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-orange-600 uppercase">Vidish</h1>
                <p class="text-gray-500 font-medium">Gestion de mes Plats</p>
            </div>
            <a href="{{ route('vendor.dishes.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg transition-all">
                + Ajouter un Plat
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($plats as $plat)
            <div class="bg-white rounded-[2rem] border border-gray-100 p-5 shadow-sm hover:shadow-xl transition-all group">
                <div class="relative aspect-square rounded-[1.5rem] overflow-hidden mb-5">
                    <img src="{{ asset('storage/' . $plat->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur px-4 py-2 rounded-xl text-orange-600 font-black shadow-sm">
                        {{ number_format($plat->price, 0, '', ' ') }} FCFA
                    </div>
                </div>

                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="font-bold text-xl leading-tight mb-1">{{ $plat->name }}</h3>
                        <div class="flex items-center gap-3 text-xs text-gray-400 font-medium">
                            <span><i class="fa-solid fa-fire text-orange-400"></i> {{ $plat->orders_count }} vendus</span>
                        </div>
                    </div>
                    <a href="{{ route('vendor.dishes.edit', $plat->id) }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:bg-orange-100 hover:text-orange-600 transition">
                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 flex flex-col items-center opacity-30 text-center">
                <i class="fa-solid fa-utensils text-6xl mb-4"></i>
                <p class="text-xl font-bold">Aucun plat enregistré</p>
            </div>
            @endforelse
        </div>
    </div>
</body>
</html>