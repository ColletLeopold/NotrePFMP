<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <!-- Barre de recherche -->
                <form method="GET" action="{{ route('dashboard2') }}" class="mb-6 flex items-center">
                    <input 
                        type="text" 
                        name="lyceen" 
                        placeholder="Rechercher un étudiant"
                        value="{{ $search ?? '' }}" 
                        class="form-input rounded-md shadow-sm w-full text-gray-700 dark:text-gray-300 dark:bg-gray-900 border-gray-300 dark:border-gray-700"
                    />
                    <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">
                        Rechercher
                    </button>
                </form>

                <!-- Liste des étudiants -->
                @if($lyceens->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">Aucun étudiant trouvé.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prénom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Identifiant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($lyceens as $lyceen)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $lyceen->surname }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $lyceen->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $lyceen->identifiant }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        <!-- Bouton Supprimer -->
                                        <form method="POST" action="{{ route('users.destroy', $lyceen->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                                Supprimer
                                            </button>
                                        </form>

                                        <!-- Bouton Générer un nouveau mot de passe -->
                                        <form method="POST" action="{{ route('users.regeneratePassword', $lyceen->id) }}">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                                Générer un nouveau mot de passe
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
