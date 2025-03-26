<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">

                <!-- Message de Succès -->
                @if (session('success'))
                    <div class="mb-4 px-4 py-3 bg-green-100 text-green-800 border border-green-400 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Message d'Erreur -->
                @if (session('error'))
                    <div class="mb-4 px-4 py-3 bg-red-100 text-red-800 border border-red-400 rounded-md">
                        {{ session('error') }}
                    </div>
                @endif

                <h1 class="text-2xl font-bold mb-6">Documents de {{ $lyceen->name }} {{ $lyceen->surname }}</h1>

                <!-- Message s'il n'y a aucun document -->
                @if ($documents->isEmpty())
                    <p class="text-gray-500 text-center">Aucun document déposé par ce lycéen.</p>
                @else
                <table class="table-auto w-full text-left">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Nom</th>
                            <th class="px-4 py-2">Taille</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Type</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $document)
                            <tr>
                                <td class="px-4 py-2">{{ $document->name }}</td>
                                <td class="px-4 py-2">{{ $document->size }} Ko</td>
                                <td class="px-4 py-2">{{ $document->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ ucfirst(str_replace('_', ' ', $document->type)) }}</td>
                                <td class="px-4 py-2 flex gap-2">
                                    <!-- Bouton Visionner -->
                                    <a href="{{ Storage::url('documents/' . $document->name) }}" target="_blank" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">Visionner</a>

                                    <!-- Bouton Validé -->
                                    <form method="POST" action="{{ route('documents.validate', $document->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600">
                                            Validé
                                        </button>
                                    </form>

                                    <!-- Bouton Refusé -->
                                    <form method="POST" action="{{ route('documents.refuse', $document->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600">
                                            Refusé
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
