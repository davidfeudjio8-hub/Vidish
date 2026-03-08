<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Setup Your Restaurant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('restaurants.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Restaurant Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="address" :value="__('Location / Address')" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea name="description" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"></textarea>
                    </div>

                    <x-primary-button>{{ __('Save Restaurant') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>