<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
            @if (Auth::user()->role === 'restaurateur')
            <a href="{{ route('vendor.dashboard') }}"
                class="ml-4 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition font-bold">
                Tableau de bord
            </a>
        @endif
        </h2>
        
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (auth()->user()->role === 'restaurateur')
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold">Manage Your Restaurant</h3>
                            <a href="{{ route('restaurants.create') }}"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                                + Create Restaurant
                            </a>
                        </div>
                        <p class="mt-4 text-gray-400">Welcome, Chef! Start by creating your restaurant profile so you
                            can upload dish videos.</p>
                        @if (auth()->user()->restaurant)
                            <form action="{{ route('dishes.store') }}" method="POST" enctype="multipart/form-data"
                                class="mt-8 bg-gray-700 p-6 rounded-lg">
                                @csrf
                                <h4 class="text-white mb-4 font-bold">Upload a New Dish Video</h4>

                                <input type="hidden" name="restaurant_id" value="{{ auth()->user()->restaurant->id }}">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <input type="text" name="name" placeholder="Dish Name (e.g. Ndolé)"
                                        class="rounded bg-gray-800 border-none text-white" required>
                                    <input type="number" name="price" placeholder="Price (CFA)"
                                        class="rounded bg-gray-800 border-none text-white" required>
                                </div>

                                <div class="mt-4">
                                    <label class="text-gray-400 text-sm">Upload Video (MP4)</label>
                                    <input type="file" name="video" accept="video/*"
                                        class="block w-full text-white mt-2" required>
                                </div>

                                <button type="submit"
                                    class="mt-4 bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-full font-bold">
                                    🚀 Post to Vidish
                                </button>
                            </form>
                        @else
                            <p>Please create a restaurant first!</p>
                        @endif
                    @else
                        <h3 class="text-lg font-bold">Discover Food</h3>
                        <p class="mt-4 text-gray-400">Welcome back! Scroll through the feed to find your next meal.</p>
                        <a href="/" class="mt-4 inline-block bg-pink-600 text-white px-4 py-2 rounded">Go to Video
                            Feed</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
