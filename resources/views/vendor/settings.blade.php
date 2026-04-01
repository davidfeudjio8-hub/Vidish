<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vidish | Vendor Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        .light .glass-card {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-darkBg dark:bg-darkBg light:bg-slate-50 transition-colors duration-300">

    <div class="min-h-screen flex flex-col items-center justify-center p-4 md:p-8">
        
        <div class="w-full max-w-2xl mb-8 flex justify-between items-end px-4">
            <div>
                <h1 class="text-3xl font-black italic uppercase tracking-tighter text-white dark:text-white light:text-slate-900">
                    VIDISH<span class="text-coral">.</span>
                </h1>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.3em]">Merchant Settings</p>
            </div>
            <button onclick="document.documentElement.classList.toggle('dark')" class="p-3 rounded-2xl bg-white/5 hover:bg-coral/20 transition-all border border-white/5">
                <svg class="w-5 h-5 text-coral" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
        </div>

        <div class="w-full max-w-2xl glass-card rounded-[40px] shadow-2xl overflow-hidden relative">
            
            <a href="{{ route('vendor.dashboard') }}" 
               class="absolute top-8 right-8 p-2.5 rounded-full bg-white/5 hover:bg-red-500/20 text-gray-500 hover:text-red-500 transition-all duration-300 group z-20">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
            
            <div class="p-8 md:p-12 border-b border-white/5">
                <h2 class="text-2xl font-black italic uppercase tracking-tight text-white dark:text-white light:text-slate-900 pr-12">
                    Edit your <span class="text-coral">Kitchen</span>
                </h2>
            </div>

            <form action="{{ route('vendor.settings.update') }}" method="POST" class="p-8 md:p-12 space-y-8">
                @csrf
                @method('PUT')

                <div class="space-y-3">
                    <label class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-black ml-2">Restaurant Name</label>
                    <input type="text" name="name" value="{{ old('name', $restaurant->name) }}" required
                           class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white outline-none focus:border-coral transition-all">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-black ml-2">Physical Address</label>
                    <input type="text" name="address" value="{{ old('address', $restaurant->address) }}" required
                           class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white outline-none focus:border-coral transition-all">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-black ml-2">Description</label>
                    <textarea name="description" rows="4" 
                              class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white outline-none focus:border-coral transition-all resize-none">{{ old('description', $restaurant->description) }}</textarea>
                </div>

                <div class="flex items-center justify-between p-6 bg-white/[0.02] rounded-3xl border border-white/5">
                    <div>
                        <span class="block text-xs font-black uppercase tracking-widest text-white">Delivery Ready</span>
                        <span class="text-[10px] text-gray-500 uppercase">Available for orders</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="has_delivery" value="1" class="sr-only peer" {{ $restaurant->has_delivery ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-white/10 rounded-full peer peer-checked:bg-coral transition-all after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-gray-400 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:bg-white shadow-inner"></div>
                    </label>
                </div>

                <button type="submit" class="w-full py-5 bg-coral text-white font-black uppercase tracking-[0.3em] text-xs rounded-2xl shadow-lg hover:scale-[1.02] active:scale-95 transition-all">
                    Save Changes
                </button>
            </form>
        </div>

        <p class="mt-10 text-[9px] text-gray-600 uppercase tracking-[0.5em] font-bold">Vidish Engineering &copy; 2026</p>
    </div>

</body>
</html>
