<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Glassmorphism Concrete</title>
    @vite('resources/css/app.css')
    <style>
        :root {
            --charcoal-glass: rgba(20, 22, 26, 0.8);
            --coral: #FF5A5F;
            --coral-glow: rgba(255, 90, 95, 0.4);
        }

        body {
            background: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2070');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .glass-section {
            background: var(--charcoal-glass);
            backdrop-filter: blur(30px) saturate(160%);
            -webkit-backdrop-filter: blur(30px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* FORCED CORAL BUTTON CLASS */
        .btn-coral {
            background-color: var(--coral) !important;
            color: white !important;
            box-shadow: 0 10px 20px var(--coral-glow);
            transition: all 0.3s ease;
        }

        .btn-coral:hover {
            transform: scale(1.05);
            filter: brightness(1.1);
        }

        .glass-border-btn {
            border: 1px solid var(--coral);
            color: var(--coral);
            transition: all 0.3s ease;
        }

        .footer-concrete {
            background: #1A1C1E;
            /* Deep Matte Concrete */
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* The Hover Overlay (Tooltip) */
        .info-overlay {
            background: rgba(40, 42, 46, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid var(--coral);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        /* Table Glass Styling */
        .glass-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .glass-table th {
            background: rgba(255, 255, 255, 0.05);
            color: var(--coral);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.75rem;
            padding: 1.5rem;
        }

        .glass-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .check-coral {
            color: var(--coral);
            font-weight: bold;
        }

        .cross-gray {
            color: rgba(255, 255, 255, 0.2);
        }

        .carousel-container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 2rem;
            padding: 2rem;
            scrollbar-width: none;
            /* Hide scrollbar for Chrome/Safari */
        }

        .carousel-container::-webkit-scrollbar {
            display: none;
        }

        .app-screenshot {
            flex: 0 0 280px;
            /* Smaller base for mobile */
            scroll-snap-align: center;
            transform: perspective(1000px) rotateY(-5deg);
            /* Reduced rotation for mobile clarity */
            transition: all 0.5s ease;
        }

        @media (min-width: 768px) {
            .app-screenshot {
                flex: 0 0 350px;
                /* Larger for your Asus TUF screen */
                transform: perspective(1000px) rotateY(-15deg);
            }
        }

        .app-screenshot:hover,
        .app-screenshot.active {
            transform: perspective(1000px) rotateY(0deg) scale(1.05);
            filter: saturate(1.2) brightness(1);
            border-color: var(--coral);
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.min.js"></script>
</head>

<body class="antialiased text-white font-sans" <div x-data="{ 
    open: {{ $errors->any() ? 'true' : 'false' }}, 
    mode: '{{ $errors->has('name') || old('role') ? 'signup' : 'login' }}',
    role: '{{ old('role', 'client') }}' 
}">>
    <div x-data="{
        open: {{ $errors->any() ? 'true' : 'false' }},
        mode: '{{ $errors->has('name') || old('role') ? 'signup' : 'login' }}',
        role: '{{ old('role', 'client') }}'
    }">
        <nav
            class="fixed top-4 md:top-6 left-1/2 -translate-x-1/2 w-[95%] max-w-6xl z-50 glass-section rounded-2xl md:rounded-3xl px-4 md:px-8 py-3 md:py-4 flex justify-between items-center border-white/10">
            <div class="flex items-center gap-2 md:gap-3">
                <div
                    class="w-7 h-7 md:w-8 md:h-8 btn-coral rounded-lg flex items-center justify-center font-black italic shadow-lg shadow-coral/30 text-sm md:text-base">
                    V</div>
                <span class="font-bold tracking-tighter uppercase text-base md:text-lg">Vidish</span>
            </div>
            <div class="flex gap-4 md:gap-8 items-center">
                <button @click="open = true; mode = 'login'"
                    class="text-xs md:text-sm text-gray-400 hover:text-white transition-colors">Sign In</button>
                <button @click="open = true; mode = 'signup'"
                    class="px-4 md:px-6 py-2 md:py-2.5 bg-coral text-white text-[10px] md:text-xs font-black rounded-full uppercase tracking-widest shadow-lg shadow-coral/30 hover:bg-[#FF7075] transition-all">Join</button>
            </div>
        </nav>

        <main class="pt-36 pb-24 px-6 flex flex-col items-center gap-16">

            <section
                class="w-full max-w-5xl glass-section rounded-[30px] md:rounded-[50px] p-8 md:p-24 text-center relative overflow-hidden">
                <h2
                    class="text-[10px] font-black text-coral uppercase tracking-[0.3em] md:tracking-[0.5em] mb-4 md:mb-6">
                    Discovery Redefined</h2>
                <h1 class="text-3xl sm:text-5xl md:text-7xl font-black mb-6 md:mb-8 leading-tight tracking-tighter">
                    STOP <span class="italic text-transparent" style="-webkit-text-stroke: 1px white;">GUESSING.</span>
                    <br class="hidden md:block">
                    START <span class="text-coral">WATCHING.</span>
                </h1>
                <p class="text-gray-400 text-sm md:text-xl max-w-2xl mx-auto leading-relaxed font-light mb-8 md:mb-10">
                    The first video-first platform where every dish is an experience.
                </p>
                <button @click="open = true; mode = 'signup'"
                    class="px-8 md:px-10 py-3 md:py-4 btn-coral text-white font-black rounded-full uppercase text-[10px] md:text-xs tracking-[0.2em] md:tracking-[0.3em] shadow-xl shadow-coral/20 hover:scale-105 transition-transform">
                    Get Early Access
                </button>
            </section>
        </main>
        <main class="pt-36 pb-24 px-6 flex flex-col items-center gap-16">

            <section class="w-full max-w-6xl glass-section rounded-[50px] overflow-hidden">
                <div class="flex flex-col md:flex-row">

                    <div class="w-full md:w-1/2 p-12 md:p-20 flex flex-col justify-center">
                        <span class="text-[#FF5A5F] text-xs font-black uppercase tracking-[0.4em] mb-4">The
                            Mission</span>
                        <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-6 italic">Why <span
                                style="color: var(--coral)">Vidish?</span></h2>
                        <p class="text-gray-400 text-lg leading-relaxed font-light mb-8">
                            Tired of looking at bland, static photos that don't tell the whole story?
                            Vidish brings local culinary masterpieces to life. We believe food is an experience—one that
                            should be heard, seen, and craved before it's ordered.
                        </p>
                        <div>
                            <button @click="open = true; mode = 'signup'"
                                class="btn-coral px-8 py-3 rounded-full font-bold uppercase text-xs tracking-widest">
                                Join the Revolution
                            </button>
                        </div>
                    </div>

                    <div class="w-full md:w-1/2 p-6 md:p-10">
                        <div
                            class="w-full h-[400px] md:h-full min-h-[400px] rounded-[35px] overflow-hidden border border-white/10 relative group">
                            <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=1974"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                alt="Vidish Experience">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        </div>
                    </div>

                </div>
            </section>
            <section class="w-full max-w-6xl glass-section rounded-[50px] p-12 md:p-20 overflow-hidden">
                <div class="text-center mb-16">
                    <span class="text-coral text-xs font-black uppercase tracking-[0.5em] mb-4 block">The
                        Platform</span>
                    <h2 class="text-4xl md:text-5xl font-black text-white italic">Capabilities & <span
                            class="text-coral">Limitations</span></h2>
                </div>

                <div class="overflow-x-auto rounded-3xl border border-white/10 bg-white/5">
                    <table class="w-full text-left glass-table">
                        <thead>
                            <tr>
                                <th>Feature</th>
                                <th class="text-center">Clients</th>
                                <th class="text-center">Restaurants</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Discover high-quality food videos</td>
                                <td class="text-center check-coral">✓</td>
                                <td class="text-center check-coral">✓</td>
                            </tr>
                            <tr>
                                <td>Upload & Edit video content</td>
                                <td class="text-center cross-gray">✕</td>
                                <td class="text-center check-coral">✓</td>
                            </tr>
                            <tr>
                                <td>Direct message/Support access</td>
                                <td class="text-center check-coral">✓</td>
                                <td class="text-center check-coral">✓</td>
                            </tr>
                            <tr>
                                <td>Manage menu & pricing updates</td>
                                <td class="text-center cross-gray">✕</td>
                                <td class="text-center check-coral">✓</td>
                            </tr>
                            <tr>
                                <td>Order food via discovery feed</td>
                                <td class="text-center check-coral">✓</td>
                                <td class="text-center cross-gray">✕</td>
                            </tr>
                            <tr>
                                <td>Review and Rate experiences</td>
                                <td class="text-center check-coral">✓</td>
                                <td class="text-center cross-gray">✕</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-16 border-t border-white/5 pt-12">
                    <h3 class="text-white font-bold mb-4 uppercase tracking-widest text-sm">Beyond the Feed</h3>
                    <p class="text-gray-400 leading-relaxed font-light text-lg">
                        Vidish operates as a curated ecosystem where quality is our top priority. While <b
                            class="text-white">Restaurants</b> hold the creative power to showcase their kitchen's soul
                        through immersive video storytelling, <b class="text-white">Clients</b> drive the engine of
                        discovery through engagement and real-time feedback. <b class="text-yellow-200">Restaurants are
                            strictly prohibited from using static stock imagery</b>, as our platform algorithm
                        prioritizes
                        raw, authentic kitchen footage. Conversely, Clients cannot upload promotional content, ensuring
                        your
                        feed remains an ad-free zone focused solely on genuine culinary experiences.
                    </p>
                </div>
            </section>
            <section class="w-full max-w-7xl mt-10 mx-auto" x-data="{ activeSlide: 1 }">
                <div class="text-center mb-12">
                    <span class="text-coral text-xs font-black uppercase tracking-[0.5em] mb-4 block">Sneak Peek</span>
                    <h2 class="text-4xl font-black text-white italic">The <span class="text-coral">Interface</span></h2>
                </div>

                <div class="carousel-container pb-12">
                    @forelse($videos as $video)
                        <div class="app-screenshot glass-section rounded-[30px] p-4 border border-white/20">
                            <div class="rounded-[20px] overflow-hidden bg-black h-[500px] relative group">

                                <video
                                    class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity"
                                    autoplay muted loop playsinline>
                                    <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                                </video>

                                <div
                                    class="absolute inset-0 flex flex-col justify-end p-6 bg-gradient-to-t from-black/90 via-black/20 to-transparent">
                                    <span
                                        class="text-white font-black text-xl uppercase tracking-tighter leading-none mb-1">
                                        {{ $video->dish->name ?? 'Signature Dish' }}
                                    </span>

                                    <div class="flex justify-between items-center">
                                        <span class="text-coral font-black text-sm tracking-widest">
                                            {{ number_format($video->dish->price ?? 0) }} CFA
                                        </span>
                                        <span
                                            class="text-[10px] text-gray-400 font-bold uppercase tracking-widest bg-white/5 px-2 py-1 rounded border border-white/10">
                                            {{ $video->dish->restaurant->name ?? 'Local Kitchen' }}
                                        </span>
                                    </div>

                                    <div class="mt-4">
                                        @auth
                                            <a href="{{ route('dishes.show', $video->dish->id) }}"
                                                class="w-full py-3 bg-white/10 backdrop-blur-md border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-coral hover:border-coral transition-all duration-300 text-center block text-white">
                                                View Details
                                            </a>
                                        @endauth

                                        @guest
                                            <button @click="open = true; mode = 'login'"
                                                class="w-full py-3 bg-white/10 backdrop-blur-md border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-coral hover:border-coral transition-all duration-300 text-white">
                                                View Details
                                            </button>
                                        @endguest
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-full text-center py-20 opacity-50 col-span-full">
                            <p class="text-xs font-black uppercase tracking-[0.5em] text-white">No sizzle yet. Start
                                uploading!</p>
                        </div>
                    @endforelse
                </div>

                <div class="flex justify-center gap-4 mt-4">
                    <p class="text-[10px] text-[#FF5A5F] font-bold uppercase tracking-widest flex items-center gap-2">
                        <span class="w-8 h-[1px] bg-white/20"></span>
                        Scroll to explore
                        <span class="w-8 h-[1px] bg-white/20"></span>
                    </p>
                </div>
            </section>
            <section class="w-full max-w-6xl grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">
                <div
                    class="glass-section rounded-[30px] md:rounded-[45px] p-8 md:p-12 flex flex-col items-center text-center">
                    <h3 class="text-2xl md:text-3xl font-bold mb-2 md:mb-4 text-white">I'm a Diner</h3>
                    <p class="text-gray-400 text-xs md:text-sm mb-6 md:mb-10 leading-relaxed">Swipe through the best
                        kitchens in the city.</p>
                    <button @click="open = true; mode = 'signup'; role = 'client'"
                        class="btn-coral w-full py-4 md:py-5 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest">
                        Start Discovering
                    </button>
                </div>

                <div
                    class="glass-section rounded-[30px] md:rounded-[45px] p-8 md:p-12 flex flex-col items-center text-center">
                    <h3 class="text-2xl md:text-3xl font-bold mb-2 md:mb-4 text-white">I'm a Chef</h3>
                    <p class="text-gray-400 text-xs md:text-sm mb-6 md:mb-10 leading-relaxed">Turn hungry viewers into
                        loyal customers.</p>
                    <button @click="open = true; mode = 'signup'; role = 'restaurateur'"
                        class="btn-coral w-full py-4 md:py-5 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest">
                        Grow My Kitchen
                    </button>
                </div>
            </section>
        </main>
        <div x-show="open"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-black/90 backdrop-blur-[30px]"
            x-transition.opacity style="display: none;">

            <div class="absolute inset-0" @click="open = false"></div>

            <div
                class="w-full max-w-md p-6 sm:p-10 glass-section rounded-[35px] sm:rounded-[50px] relative z-[110] border-white/20 max-h-[90vh] overflow-y-auto">

                <button @click="open = false"
                    class="absolute top-6 right-6 sm:top-8 sm:right-8 text-gray-500 hover:text-white transition-colors">✕</button>

                <div class="text-center mb-6">
                    <h2 class="text-2xl sm:text-3xl font-black text-[#FF5A5F] italic mb-4 sm:mb-6"
                        x-text="mode === 'login' ? 'Welcome Back' : 'Join Vidish'"></h2>

                    <template x-if="mode === 'signup'">
                        <div
                            class="flex p-1 bg-white/5 rounded-2xl border border-white/10 mb-6 sm:mb-8 max-w-[280px] mx-auto">
                            <button @click="role = 'client'"
                                :class="role === 'client' ? 'bg-coral text-white shadow-lg' : 'text-gray-400'"
                                class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300">
                                Diner
                            </button>
                            <button @click="role = 'restaurateur'"
                                :class="role === 'restaurateur' ? 'bg-coral text-white shadow-lg' : 'text-gray-400'"
                                class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300">
                                Restaurant
                            </button>
                        </div>
                    </template>
                </div>

                <form :action="mode === 'login' ? '{{ route('login') }}' : '{{ route('register') }}'"
                    method="POST" class="space-y-4">
                    @csrf

                    <template x-if="mode === 'signup'">
                        <input type="hidden" name="role" :value="role">
                    </template>

                    <template x-if="mode === 'signup'">
                        <div>
                            <label
                                class="text-[10px] text-gray-500 uppercase tracking-widest ml-4 mb-2 block">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full px-5 py-3 sm:px-6 sm:py-4 bg-white/5 border {{ $errors->has('name') ? 'border-coral' : 'border-white/10' }} rounded-2xl text-white outline-none transition-all">
                            @error('name')
                                <span
                                    class="text-[10px] text-coral font-bold ml-4 mt-1 block italic">{{ $message }}</span>
                            @enderror
                        </div>
                    </template>

                    <div>
                        <label class="text-[10px] text-gray-500 uppercase tracking-widest ml-4 mb-2 block">Email
                            Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-5 py-3 sm:px-6 sm:py-4 bg-white/5 border {{ $errors->has('email') ? 'border-coral' : 'border-white/10' }} rounded-2xl text-white outline-none transition-all">
                        @error('email')
                            <span
                                class="text-[10px] text-coral font-bold ml-4 mt-1 block italic">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="text-[10px] text-gray-500 uppercase tracking-widest ml-4 mb-2 block">Security</label>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full px-5 py-3 sm:px-6 sm:py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:border-[#FF5A5F] outline-none transition-all placeholder:text-gray-600 text-sm">
                    </div>

                    <template x-if="mode === 'signup'">
                        <div class="mt-4">
                            <label class="text-[10px] text-gray-500 uppercase tracking-widest ml-4 mb-2 block">Confirm
                                Security</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm Password"
                                required
                                class="w-full px-5 py-3 sm:px-6 sm:py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:border-[#FF5A5F] outline-none transition-all placeholder:text-gray-600 text-sm">
                        </div>
                    </template>

                    <button type="submit"
                        class="btn-coral w-full py-4 sm:py-5 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] sm:text-[11px] mt-6 shadow-2xl">
                        <span x-text="mode === 'login' ? 'Enter Vidish' : 'Launch Account'"></span>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <button @click="mode = (mode === 'login' ? 'signup' : 'login')"
                        class="text-[10px] font-bold text-gray-500 hover:text-[#FF5A5F] transition-colors uppercase tracking-widest">
                        <span
                            x-text="mode === 'login' ? 'Need an account? Sign up' : 'Already have an account? Sign in'"></span>
                    </button>
                </div>
            </div>
        </div>

        <footer class="w-full footer-concrete py-12 px-6 md:px-10 mt-20 relative z-20">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8"
                x-data="{
                    activeTooltip: null,
                    timeout: null,
                    show(type) {
                        if (this.timeout) clearTimeout(this.timeout);
                        this.activeTooltip = type;
                    },
                    hide() {
                        this.timeout = setTimeout(() => {
                            this.activeTooltip = null;
                        }, 400);
                    },
                    toggleTooltip(type) {
                        if (window.innerWidth < 768) {
                            this.activeTooltip = (this.activeTooltip === type) ? null : type;
                        }
                    }
                }">

                <div
                    class="flex items-center gap-3 opacity-50 grayscale hover:grayscale-0 transition-all cursor-default">
                    <div
                        class="w-6 h-6 bg-white rounded flex items-center justify-center text-[10px] font-black text-black italic">
                        V</div>
                    <span class="font-bold tracking-tighter uppercase text-sm text-white">Vidish &copy; 2026</span>
                </div>

                <div
                    class="flex flex-wrap justify-center gap-6 md:gap-12 text-[10px] md:text-xs font-bold uppercase tracking-[0.2em] text-gray-500">

                    <div class="relative py-2" @mouseenter="show('owner')" @mouseleave="hide()"
                        @click="toggleTooltip('owner')">
                        <span class="hover:text-white cursor-help transition-colors uppercase">About the owner</span>

                        <div x-show="activeTooltip === 'owner'" x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" @mouseenter="show('owner')"
                            @mouseleave="hide()"
                            class="info-overlay absolute bottom-full mb-4 left-1/2 -translate-x-1/2 w-64 p-6 rounded-2xl text-center z-50 shadow-2xl"
                            style="display: none;">

                            <div class="absolute h-6 w-full top-full left-0"></div>

                            <p class="text-white normal-case font-medium mb-1 text-sm">Creative Developer</p>
                            <p class="text-gray-400 normal-case font-light text-[11px] leading-relaxed mb-3">
                                Building digital experiences that make you hungry.
                            </p>
                            <a href="https://github.com/davidfeudjio8-hub" target="_blank"
                                class="text-coral hover:underline normal-case text-[10px]">View Portfolio →</a>
                        </div>
                    </div>

                    <div class="relative py-2" @mouseenter="show('contact')" @mouseleave="hide()"
                        @click="toggleTooltip('contact')">
                        <span class="hover:text-white cursor-help transition-colors uppercase">Contact Us</span>

                        <div x-show="activeTooltip === 'contact'"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" @mouseenter="show('contact')"
                            @mouseleave="hide()"
                            class="info-overlay absolute bottom-full mb-4 left-1/2 -translate-x-1/2 w-72 p-6 rounded-2xl z-50 shadow-2xl"
                            style="display: none;">

                            <div class="absolute h-6 w-full top-full left-0"></div>

                            <div class="space-y-4 normal-case text-left">
                                <div class="flex items-center gap-3">
                                    <span class="text-coral">●</span>
                                    <span class="text-white text-[11px] font-medium">Discord:</span>
                                    <span class="text-gray-400 text-[11px] select-all">vidish_dev</span>
                                </div>
                                <a href="mailto:davidfeudjio8@gmail.com" class="flex items-center gap-3 group/mail">
                                    <span class="text-coral">●</span>
                                    <span class="text-white text-[11px] font-medium">Mail:</span>
                                    <span
                                        class="text-gray-400 text-[11px] group-hover:text-coral transition-colors underline decoration-white/10 underline-offset-4">davidfeudjio8@gmail.com</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="relative py-2" @mouseenter="show('support')" @mouseleave="hide()"
                        @click="toggleTooltip('support')">
                        <span class="hover:text-white cursor-help transition-colors uppercase">Support</span>

                        <div x-show="activeTooltip === 'support'"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" @mouseenter="show('support')"
                            @mouseleave="hide()"
                            class="info-overlay absolute bottom-full mb-4 left-1/2 -translate-x-1/2 w-64 p-6 rounded-2xl text-center z-50 shadow-2xl"
                            style="display: none;">

                            <div class="absolute h-6 w-full top-full left-0"></div>

                            <p class="text-white normal-case font-medium mb-1">Help Center</p>
                            <p class="text-gray-400 normal-case font-light text-[11px] leading-relaxed">
                                Having trouble? Our support team is available 24/7.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-6 opacity-30">
                    <div class="w-4 h-4 bg-white rounded-full hover:bg-coral transition-all cursor-pointer"></div>
                    <div class="w-4 h-4 bg-white rounded-full hover:bg-coral transition-all cursor-pointer"></div>
                    <div class="w-4 h-4 bg-white rounded-full hover:bg-coral transition-all cursor-pointer"></div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
