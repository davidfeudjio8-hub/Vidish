<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vidish | Stop Guessing. Start Watching.</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'concrete-black': '#121212',
                        'vidish-coral': '#FF5A5F',
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --charcoal-glass: rgba(20, 22, 26, 0.85);
            --coral: #FF5A5F;
            --coral-glow: rgba(255, 90, 95, 0.4);
        }

        body {
            background: linear-gradient(rgba(18, 18, 18, 0.8), rgba(18, 18, 18, 0.8)),
                url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2070');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            font-family: 'Inter', sans-serif;
        }

        .glass-section {
            background: var(--charcoal-glass);
            backdrop-filter: blur(30px) saturate(160%);
            -webkit-backdrop-filter: blur(30px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .btn-coral {
            background-color: var(--coral) !important;
            color: white !important;
            box-shadow: 0 10px 20px var(--coral-glow);
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-weight: 900;
            letter-spacing: 0.1em;
        }

        .btn-coral:hover {
            transform: scale(1.05);
            filter: brightness(1.1);
        }

        .carousel-container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 2rem;
            padding: 2rem;
            scrollbar-width: none;
        }

        .carousel-container::-webkit-scrollbar {
            display: none;
        }

        .app-screenshot {
            flex: 0 0 280px;
            scroll-snap-align: center;
            transform: perspective(1000px) rotateY(-5deg);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 24px;
            border: 2px solid transparent;
        }

        @media (min-width: 768px) {
            .app-screenshot {
                flex: 0 0 350px;
                transform: perspective(1000px) rotateY(-15deg);
            }
        }

        .app-screenshot:hover {
            transform: perspective(1000px) rotateY(0deg) scale(1.05);
            filter: saturate(1.2) brightness(1.1);
            border-color: var(--coral);
            box-shadow: 0 0 30px var(--coral-glow);
        }

        [x-cloak] {
            display: none !important;
        }

        .footer-concrete {
            background: #1A1C1E;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="antialiased text-white font-sans" x-data="{
    openAuth: {{ $errors->any() || request()->query('auth_trigger') ? 'true' : 'false' }},
    authMode: '{{ $errors->has('name') || old('role') || request()->query('auth_trigger') === 'signup' ? 'signup' : (request()->query('auth_trigger') === 'login' ? 'login' : 'login') }}',
    authRole: '{{ old('role', 'client') }}',
    profileDropdown: false
}">

    <nav class="fixed top-4 md:top-6 left-1/2 -translate-x-1/2 w-[95%] max-w-6xl z-50 glass-section rounded-2xl md:rounded-3xl px-4 md:px-8 py-3 md:py-4 flex justify-between items-center border-white/10">
        <a href="{{ url('/') }}" class="flex items-center gap-2 md:gap-3 group">
            <div class="w-7 h-7 md:w-8 md:h-8 btn-coral rounded-lg flex items-center justify-center font-black italic shadow-lg shadow-coral/30 text-sm md:text-base transition-transform group-hover:scale-110">
                V</div>
            <span class="font-bold tracking-tighter uppercase text-base md:text-lg">Vidish</span>
        </a>

        <div class="flex gap-4 md:gap-6 items-center">
            @guest
                <button @click="openAuth = true; authMode = 'login'"
                    class="text-xs md:text-sm text-gray-400 hover:text-white transition-colors">Sign In</button>
                <button @click="openAuth = true; authMode = 'signup'; authRole = 'client'"
                    class="px-4 md:px-6 py-2 md:py-2.5 bg-coral text-white text-[10px] md:text-xs font-black rounded-full uppercase tracking-widest shadow-lg shadow-coral/30 hover:bg-[#FF7075] transition-all">Join</button>
            @endguest

            @auth
                <div class="relative" @click.away="profileDropdown = false">
                    <button @click="profileDropdown = !profileDropdown"
                        class="flex items-center gap-3 p-1 rounded-full hover:bg-white/5 transition-colors focus:outline-none">
                        <div class="relative">
                            <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center border border-white/20">
                                <span class="text-[11px] font-black text-coral">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                            </div>
                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-[#1a1a1a] rounded-full">
                            </div>
                        </div>
                        <div class="hidden md:flex flex-col text-left">
                            <span class="text-[11px] font-black uppercase tracking-wider text-white leading-none">{{ Auth::user()->name }}</span>
                            <span class="text-[9px] text-gray-500 uppercase tracking-tighter mt-1">{{ Auth::user()->role === 'restaurateur' ? 'Chef Account' : 'Diner Account' }}</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600 transition-transform"
                            :class="profileDropdown ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="profileDropdown" x-cloak x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                        class="absolute right-0 mt-3 w-56 glass-section rounded-2xl border border-white/10 shadow-xl z-50 overflow-hidden">

                        <div class="p-3 border-b border-white/5">
                            <span class="text-[10px] text-gray-500 uppercase tracking-widest block mb-1">My Area</span>
                            @if (Auth::user()->role === 'restaurateur')
                                <a href="{{ route('vendor.dashboard') }}"
                                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-vidish-coral/10 group transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-vidish-coral" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zM9 19h6m-6 0l6-6m0 0v6m0 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2z" />
                                    </svg>
                                    <span class="text-xs font-bold text-white group-hover:text-vidish-coral">Vendor Dashboard</span>
                                </a>
                            @else
                                <a href="{{ route('video.feed') }}"
                                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-vidish-coral/10 group transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-vidish-coral" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-xs font-bold text-white group-hover:text-vidish-coral">Go to Feed</span>
                                </a>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 p-4 text-xs font-bold text-gray-500 hover:text-white hover:bg-red-500/10 transition-colors text-left">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </nav>

    <main class="pt-36 pb-24 px-6 flex flex-col items-center gap-16">

        <section class="w-full max-w-5xl glass-section rounded-[30px] md:rounded-[50px] p-8 md:p-24 text-center relative overflow-hidden">
            <h2 class="text-[10px] font-black text-coral uppercase tracking-[0.3em] md:tracking-[0.5em] mb-4 md:mb-6">
                Discovery Redefined</h2>
            <h1 class="text-3xl sm:text-5xl md:text-7xl font-black mb-6 md:mb-8 leading-tight tracking-tighter">STOP
                <span class="italic text-transparent" style="-webkit-text-stroke: 1px white;">GUESSING.</span><br
                    class="hidden md:block">START <span class="text-coral">WATCHING.</span>
            </h1>
            <p class="text-gray-400 text-sm md:text-xl max-w-2xl mx-auto leading-relaxed font-light mb-8 md:mb-10">The
                first video-first platform where every dish is an experience.</p>

            @auth
                @if (Auth::user()->role === 'restaurateur')
                    <a href="{{ route('vendor.dashboard') }}"
                        class="px-8 md:px-10 py-3 md:py-4 btn-coral text-white font-black rounded-full uppercase text-[10px] md:text-xs tracking-[0.2em] md:tracking-[0.3em] shadow-xl shadow-coral/20 hover:scale-105 transition-transform inline-block">Go
                        to My Dashboard</a>
                @else
                    <a href="{{ route('video.feed') }}"
                        class="px-8 md:px-10 py-3 md:py-4 btn-coral text-white font-black rounded-full uppercase text-[10px] md:text-xs tracking-[0.2em] md:tracking-[0.3em] shadow-xl shadow-coral/20 hover:scale-105 transition-transform inline-block">Start
                        Exploring Feed</a>
                @endif
            @else
                <button @click="openAuth = true; authMode = 'signup'"
                    class="px-8 md:px-10 py-3 md:py-4 btn-coral text-white font-black rounded-full uppercase text-[10px] md:text-xs tracking-[0.2em] md:tracking-[0.3em] shadow-xl shadow-coral/20 hover:scale-105 transition-transform">Get
                    Early Access</button>
            @endauth
        </section>

        <section class="w-full max-w-6xl glass-section rounded-[50px] overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-12 md:p-20 flex flex-col justify-center">
                    <span class="text-[#FF5A5F] text-xs font-black uppercase tracking-[0.4em] mb-4">The Mission</span>
                    <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-6 italic">Why <span
                            class="text-coral">Vidish?</span></h2>
                    <p class="text-gray-400 text-lg leading-relaxed font-light mb-8">Tired of bland photos? Vidish
                        brings masterpieces to life. We believe food is an experience—one that should be watched, seen,
                        and craved before it's ordered.</p>
                    <div>
                        @auth
                            @if (Auth::user()->role === 'restaurateur')
                                <a href="{{ route('vendor.dashboard') }}"
                                    class="btn-coral px-8 py-3 rounded-full font-bold uppercase text-xs tracking-widest inline-block">Manage
                                    My Kitchen</a>
                            @else
                                <a href="{{ route('video.feed') }}"
                                    class="btn-coral px-8 py-3 rounded-full font-bold uppercase text-xs tracking-widest inline-block">Order
                                    Now</a>
                            @endif
                        @else
                            <button @click="openAuth = true; authMode = 'signup'"
                                class="btn-coral px-8 py-3 rounded-full font-bold uppercase text-xs tracking-widest">Join
                                the Revolution</button>
                        @endauth
                    </div>
                </div>
                <div class="w-full md:w-1/2 p-6 md:p-10">
                    <div class="w-full h-[400px] md:h-full min-h-[400px] rounded-[35px] overflow-hidden border border-white/10 relative group">
                        <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=1974"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            alt="Vidish Experience">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="w-full max-w-6xl px-4">
            <div class="glass-section rounded-[40px] overflow-hidden shadow-2xl">
                <div class="p-8 md:p-10 border-b border-white/5 bg-white/5">
                    <h2 class="text-2xl md:text-3xl font-black italic uppercase tracking-tighter text-white">
                        The <span class="text-coral">Vidish</span> Experience
                    </h2>
                    <p class="text-gray-500 text-xs uppercase tracking-[0.2em] mt-2 font-bold">Compare your advantages
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full glass-table border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02]">
                                <th class="text-left py-6 px-8 text-coral font-black uppercase tracking-widest text-[10px]">Features</th>
                                <th class="text-center py-6 px-8 text-coral font-black uppercase tracking-widest text-[10px]">Diner</th>
                                <th class="text-center py-6 px-8 text-coral font-black uppercase tracking-widest text-[10px]">Chef</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.03]">
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-6 px-8 font-medium">Immersive Video Feed</td>
                                <td class="text-center py-6 px-8"><span class="check-coral text-lg">✓</span></td>
                                <td class="text-center py-6 px-8"><span class="check-coral text-lg">✓</span></td>
                            </tr>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-6 px-8 font-medium">Geolocation Discovery</td>
                                <td class="text-center py-6 px-8"><span class="check-coral text-lg">✓</span></td>
                                <td class="text-center py-6 px-8"><span class="cross-gray opacity-30">✕</span></td>
                            </tr>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-6 px-8 font-medium">Smart Dashboard Access</td>
                                <td class="text-center py-6 px-8"><span class="cross-gray opacity-30">✕</span></td>
                                <td class="text-center py-6 px-8"><span class="check-coral text-lg">✓</span></td>
                            </tr>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-6 px-8 font-medium">Content Creation Tools</td>
                                <td class="text-center py-6 px-8"><span class="cross-gray opacity-30">✕</span></td>
                                <td class="text-center py-6 px-8"><span class="check-coral text-lg">✓</span></td>
                            </tr>
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-6 px-8 font-medium">Early Adopter Badge</td>
                                <td class="text-center py-6 px-8"><span class="check-coral text-lg">✓</span></td>
                                <td class="text-center py-6 px-8"><span class="check-coral text-lg">✓</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-white/[0.02] text-center">
                    <p class="text-[9px] text-gray-600 uppercase tracking-[0.3em]">Built for the future of food discovery</p>
                </div>
            </div>
        </section>

        <section class="w-full max-w-6xl grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">
            @unless (Auth::check() && Auth::user()->role === 'restaurateur')
                <div class="glass-section rounded-[30px] md:rounded-[45px] p-8 md:p-12 flex flex-col items-center text-center">
                    <h3 class="text-2xl md:text-3xl font-bold mb-2 md:mb-4 text-white">I'm a Diner</h3>
                    <p class="text-gray-400 text-xs md:text-sm mb-6 md:mb-10 leading-relaxed">Swipe through the best kitchens in the city.</p>

                    @auth
                        <a href="{{ route('video.feed') }}" class="btn-coral w-full py-4 md:py-5 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest inline-block text-center">Enter the Feed</a>
                    @else
                        <button @click="openAuth = true; authMode = 'signup'; authRole = 'client'" class="btn-coral w-full py-4 md:py-5 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest">Start Discovering</button>
                    @endauth
                </div>
            @endunless

            @unless (Auth::check() && Auth::user()->role === 'client')
                <div class="glass-section rounded-[30px] md:rounded-[45px] p-8 md:p-12 flex flex-col items-center text-center">
                    <h3 class="text-2xl md:text-3xl font-bold mb-2 md:mb-4 text-white">I'm a Chef</h3>
                    <p class="text-gray-400 text-xs md:text-sm mb-6 md:mb-10 leading-relaxed">Turn hungry viewers into loyal customers.</p>

                    @auth
                        <a href="{{ route('vendor.dashboard') }}" class="btn-coral w-full py-4 md:py-5 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest inline-block text-center">Enter my Dashboard</a>
                    @else
                        <button @click="openAuth = true; authMode = 'signup'; authRole = 'restaurateur'" class="btn-coral w-full py-4 md:py-5 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest">Grow My Kitchen</button>
                    @endauth
                </div>
            @endunless
        </section>
    </main>

    <div x-show="openAuth" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-black/95 backdrop-blur-[30px]" x-transition.opacity>
        <div class="absolute inset-0" @click="openAuth = false"></div>
        <div class="w-full max-w-md p-6 sm:p-10 glass-section rounded-[35px] sm:rounded-[50px] relative z-[110] border-white/20 max-h-[90vh] overflow-y-auto">

            <button @click="openAuth = false" class="absolute top-6 right-6 sm:top-8 sm:right-8 text-gray-500 hover:text-white transition-colors">✕</button>

            <div class="text-center mb-6">
                <h2 class="text-2xl sm:text-3xl font-black text-[#FF5A5F] italic mb-4 sm:mb-6" x-text="authMode === 'login' ? 'Welcome Back' : 'Join Vidish'"></h2>

                @if ($errors->any())
                    <div class="mb-6 p-3 bg-red-500/10 border border-red-500/20 rounded-2xl">
                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest leading-relaxed">
                            {{ $errors->first() }}
                        </p>
                    </div>
                @endif

                <template x-if="authMode === 'signup'">
                    <div class="flex p-1 bg-white/5 rounded-2xl border border-white/10 mb-6 sm:mb-8 max-w-[280px] mx-auto gap-1">
                        <button type="button" @click="authRole = 'client'"
                            :class="authRole === 'client' ? 'bg-green-500 text-white shadow-[0_0_20px_rgba(34,197,94,0.4)] border-green-400/50' : 'text-gray-400 border-white/10'"
                            class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 border">
                            Diner
                        </button>
                        <button type="button" @click="authRole = 'restaurateur'"
                            :class="authRole === 'restaurateur' ? 'bg-green-500 text-white shadow-[0_0_20px_rgba(34,197,94,0.4)] border-green-400/50' : 'text-gray-400 border-white/10'"
                            class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 border">
                            Restaurant
                        </button>
                    </div>
                </template>
            </div>

            <form :action="authMode === 'login' ? '{{ route('login') }}' : '{{ route('register') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="authMode === 'signup'">
                    <input type="hidden" name="role" :value="authRole">
                </template>

                <template x-if="authMode === 'signup'">
                    <div>
                        <label class="text-[10px] text-gray-500 uppercase tracking-widest ml-4 mb-2 block">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" :placeholder="authRole === 'restaurateur' ? 'Restaurant Name' : 'Your Full Name'"
                            class="w-full px-5 py-3 sm:px-6 sm:py-4 bg-white/5 border border-white/10 rounded-2xl text-white outline-none transition-all placeholder:text-gray-700 @error('name') border-red-500/50 @enderror">
                        @error('name') <span class="text-[9px] text-red-500 font-bold uppercase tracking-tighter ml-4 mt-1 block italic">{{ $message }}</span> @enderror
                    </div>
                </template>

                <div>
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest ml-4 mb-2 block">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" :placeholder="authRole === 'restaurateur' ? 'business@restaurant.com' : 'hello@example.com'"
                        class="w-full px-5 py-3 sm:px-6 sm:py-4 bg-white/5 border border-white/10 rounded-2xl text-white outline-none transition-all placeholder:text-gray-700 @error('email') border-red-500/50 @enderror">
                    @error('email') <span class="text-[9px] text-red-500 font-bold uppercase tracking-tighter ml-4 mt-1 block italic">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest ml-4 mb-2 block">Security</label>
                    <input type="password" name="password" placeholder="••••••••" required
                        class="w-full px-5 py-3 sm:px-6 sm:py-4 bg-white/5 border border-white/10 rounded-2xl text-white outline-none transition-all placeholder:text-gray-600 text-sm @error('password') border-red-500/50 @enderror">
                    @error('password') <span class="text-[9px] text-red-500 font-bold uppercase tracking-tighter ml-4 mt-1 block italic">{{ $message }}</span> @enderror
                </div>

                <template x-if="authMode === 'signup'">
                    <div class="mt-4">
                        <label class="text-[10px] text-gray-500 uppercase tracking-widest ml-4 mb-2 block">Confirm Security</label>
                        <input type="password" name="password_confirmation" placeholder="Repeat Security Key" required
                            class="w-full px-5 py-3 sm:px-6 sm:py-4 bg-white/5 border border-white/10 rounded-2xl text-white outline-none transition-all placeholder:text-gray-600 text-sm">
                    </div>
                </template>

                <div class="flex items-center justify-between px-4 mt-4 mb-2">
                    <label class="flex items-center cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="remember" class="sr-only peer" {{ old('remember') ? 'checked' : '' }}>
                            <div class="w-5 h-5 border-2 border-white/10 rounded-md bg-white/5 peer-checked:bg-coral peer-checked:border-coral transition-all duration-300"></div>
                            <svg class="absolute top-1 left-1 w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-300"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="ml-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest group-hover:text-gray-300 transition-colors">Keep me logged in</span>
                    </label>
                    <a href="#" class="text-[9px] font-bold text-coral/60 hover:text-coral uppercase tracking-widest transition-colors">Forgot?</a>
                </div>

                <button type="submit" class="btn-coral w-full py-4 sm:py-5 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] sm:text-[11px] mt-6 shadow-2xl">
                    <span x-text="authMode === 'login' ? 'Enter Vidish' : 'Launch Account'"></span>
                </button>
            </form>

            <div class="mt-8 text-center">
                <button @click="authMode = (authMode === 'login' ? 'signup' : 'login')"
                    class="text-[10px] font-bold text-gray-500 hover:text-[#FF5A5F] transition-colors uppercase tracking-widest">
                    <span x-text="authMode === 'login' ? 'Need an account? Sign up' : 'Already have an account? Sign in'"></span>
                </button>
            </div>
        </div>
    </div>

    <footer class="w-full footer-concrete py-12 px-6 md:px-10 mt-20 relative z-20">
        <div class="max-w-6xl mx-auto flex items-center gap-3 opacity-50 grayscale hover:grayscale-0 transition-all cursor-default">
            <div class="w-6 h-6 bg-white rounded flex items-center justify-center text-[10px] font-black text-black italic">V</div>
            <span class="font-bold tracking-tighter uppercase text-sm text-white">Vidish &copy; 2026</span>
        </div>
    </footer>

</body>
</html>