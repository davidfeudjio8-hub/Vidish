<x-app-layout>
    <div class="h-screen bg-black overflow-y-scroll snap-y snap-mandatory">
        @foreach($videos as $video)
            <div class="h-screen w-full flex items-center justify-center snap-start relative">
                <video class="h-full w-full object-cover" loop muted autoplay>
                    <source src="{{ asset('storage/' . $video->file_path) }}" type="video/mp4">
                </video>

                <div class="absolute bottom-10 left-10 text-white bg-black/40 p-6 rounded-xl backdrop-blur-md">
                    <h2 class="text-2xl font-bold">{{ $video->dish->name }}</h2>
                    <p class="text-orange-400 font-semibold">{{ $video->dish->restaurant->name }}</p>
                    
                    @if(isset($video->distance))
                        <span class="text-xs bg-gray-800 px-2 py-1 rounded mt-2 inline-block">
                            📍 À {{ round($video->distance, 1) }} km
                        </span>
                    @endif

                    <div class="mt-4">
                        <button class="bg-orange-600 px-6 py-2 rounded-full font-bold hover:bg-orange-500 transition">
                            Commander - {{ $video->dish->price }} FCFA
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>