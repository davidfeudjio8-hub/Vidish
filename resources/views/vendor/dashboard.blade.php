<x-app-layout>
    <div class="flex h-screen bg-gray-100">
        <div class="w-64 bg-white shadow-md">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-orange-600">Vidish Vendor</h2>
            </div>
            <nav class="mt-6">
                <a href="#" class="flex items-center px-6 py-3 text-gray-700 bg-gray-200 border-r-4 border-orange-500">
                    <span class="mx-3">Tableau de bord</span>
                </a>
                <a href="#" class="flex items-center px-6 py-3 mt-2 text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition">
                    <span class="mx-3">Mes Plats</span>
                </a>
                <a href="#" class="flex items-center px-6 py-3 mt-2 text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition">
                    <span class="mx-3">Statistiques Vidéos</span>
                </a>
                <a href="#" class="flex items-center px-6 py-3 mt-2 text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition">
                    <span class="mx-3">Paramètres Resto</span>
                </a>
            </nav>
        </div>

        <div class="flex-1 overflow-x-hidden overflow-y-auto">
            <header class="flex items-center justify-between px-8 py-4 bg-white border-b">
                <h1 class="text-2xl font-semibold text-gray-800">Bienvenue, {{ Auth::user()->name }}</h1>
                <div class="flex items-center">
                    <button class="px-4 py-2 text-white bg-orange-500 rounded-lg hover:bg-orange-600">
                        + Ajouter un plat
                    </button>
                </div>
            </header>

            <main class="p-8">
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
                    <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                        <p class="text-sm font-medium text-gray-500 uppercase">Vues Totales</p>
                        <p class="text-3xl font-bold text-gray-800">1,284</p>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                        <p class="text-sm font-medium text-gray-500 uppercase">Plats Actifs</p>
                        <p class="text-3xl font-bold text-gray-800">12</p>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                        <p class="text-sm font-medium text-gray-500 uppercase">Note Moyenne</p>
                        <p class="text-3xl font-bold text-gray-800">4.8 / 5</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Aperçu de vos plats</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-gray-400 uppercase text-xs">
                                    <th class="py-3 px-4">Nom</th>
                                    <th class="py-3 px-4">Prix</th>
                                    <th class="py-3 px-4">Statut</th>
                                    <th class="py-3 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                <tr class="border-t">
                                    <td class="py-4 px-4 font-medium">Burger Signature</td>
                                    <td class="py-4 px-4">4500 FCFA</td>
                                    <td class="py-4 px-4 text-green-500">En ligne</td>
                                    <td class="py-4 px-4">
                                        <button class="text-blue-500 hover:underline">Éditer</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>