<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vidish - Setup Your Restaurant</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'concrete-black': '#121212',
                        'vidish-coral': '#FF7F50',
                    }
                }
            }
        }
    </script>
    <style>
        body { background: #121212; transition: all 0.4s ease; }
        .glass-card {
            backdrop-filter: blur(20px);
            background: rgba(18, 18, 18, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .dot { transition: all 0.3s ease-in-out; }
        input:checked~.dot { transform: translateX(100%); background-color: #FF7F50; }
        input:checked~.block { background-color: rgba(255, 127, 80, 0.2); }
    </style>
</head>

<body class="text-white antialiased flex items-center justify-center p-4 min-h-screen">

    <div class="w-full max-w-xl">
        <div class="mb-10 flex justify-between items-center">
            <h1 class="text-3xl font-black italic">VIDISH<span class="text-vidish-coral">.</span></h1>
        </div>

        <div class="glass-card rounded-[2.5rem] p-10 shadow-2xl">
            <header class="mb-10">
                <h2 class="text-3xl font-black mb-2">Nouveau <span class="text-vidish-coral">Restau.</span></h2>
                <p class="text-gray-400 text-sm">Configure tes options de vente et ta position.</p>
            </header>

            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500 text-red-500 p-4 rounded-2xl mb-6 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('restaurants.store') }}" method="POST">
                @csrf

                <input type="hidden" name="latitude" id="lat">
                <input type="hidden" name="longitude" id="lng">

                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Nom de l'enseigne</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                            class="w-full bg-white/5 border-none rounded-2xl px-6 py-4 text-white placeholder-gray-600 focus:ring-2 focus:ring-vidish-coral/50 outline-none"
                            placeholder="ex: Vidish Food Truck">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Localisation (Quartier)</label>
                        <input type="text" name="address" id="address_input" value="{{ old('address') }}"
                            class="w-full bg-white/5 border-none rounded-2xl px-6 py-4 text-white placeholder-gray-600 focus:ring-2 focus:ring-vidish-coral/50 outline-none mb-3"
                            placeholder="ex: Bastos, Odza, Akwa...">
                        
                        <div class="flex gap-3">
                            <button type="button" onclick="getLocation()" id="geoBtn"
                                class="flex-1 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl py-3 px-4 text-xs font-bold transition-all flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-vidish-coral" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span id="geoText">Préciser par GPS (Optionnel)</span>
                            </button>
                        </div>
                        <p id="geoStatus" class="text-[10px] mt-2 text-gray-500 italic"></p>
                    </div>

                    <div class="flex items-center justify-between bg-white/5 p-5 rounded-2xl border border-white/5">
                        <div>
                            <span class="block text-sm font-bold">Service de Livraison</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider">Est-ce que vous livrez ?</span>
                        </div>
                        <label for="has_delivery" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" id="has_delivery" name="has_delivery" class="sr-only" value="1" checked>
                                <div class="block bg-gray-700 w-14 h-8 rounded-full"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                            </div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Description</label>
                        <textarea name="description" rows="2"
                            class="w-full bg-white/5 border-none rounded-2xl px-6 py-4 text-white placeholder-gray-600 focus:ring-2 focus:ring-vidish-coral/50 outline-none resize-none"
                            placeholder="Spécialités, horaires...">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-vidish-coral hover:brightness-110 text-white font-black py-5 rounded-2xl shadow-xl shadow-vidish-coral/20 transform active:scale-95 transition-all uppercase tracking-widest text-sm">
                        Ouvrir mon Restaurant
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function getLocation() {
            const btn = document.getElementById('geoBtn');
            const status = document.getElementById('geoStatus');
            const text = document.getElementById('geoText');
            const addressInput = document.getElementById('address_input');

            if (navigator.geolocation) {
                status.innerText = "Accès GPS...";
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        document.getElementById('lat').value = position.coords.latitude;
                        document.getElementById('lng').value = position.coords.longitude;
                        
                        btn.classList.add('border-vidish-coral');
                        text.innerText = "GPS Activé ✓";
                        status.innerText = "Position capturée précisément.";
                        
                        // Si l'utilisateur n'a rien écrit dans le quartier, on peut l'aider
                        if (addressInput.value === "") {
                            addressInput.placeholder = "Position GPS capturée (vous pouvez préciser le quartier)";
                        }
                    },
                    (error) => {
                        status.innerText = "Erreur GPS : " + error.message;
                        status.classList.add('text-red-400');
                    }
                );
            } else {
                status.innerText = "Navigateur non compatible GPS.";
            }
        }
    </script>
</body>
</html>