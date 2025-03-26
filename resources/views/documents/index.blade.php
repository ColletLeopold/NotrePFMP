<x-app-layout>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="py-12 bg-gray-100 min-h-screen flex flex-col items-center">
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">

            <!-- Titre de la Page -->
            <h1 class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-white">
                Déposer un Document
            </h1>

            <!-- Messages d'erreur -->
            @if ($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-100 text-red-800 border border-red-400 rounded-md">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Message de succès -->
            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 text-green-800 border border-green-400 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Note sur les formats et tailles -->
            <div class="text-sm text-gray-600 dark:text-gray-300 mb-6 text-right">
                Formats acceptés : PDF, JPG, PNG | Taille maximale : 10 Mo
            </div>

            <!-- Formulaire -->
            <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="flex flex-col items-center gap-6">
                @csrf

                <!-- Menu Déroulant pour le Type de Document -->
                <select name="type" class="px-4 py-2 border border-gray-300 rounded-md" required>
                    <option value="" disabled selected>Selectionner le type de document</option>
                    <option value="FR_PFMP1">Fiche de renseignements PFMP1</option>
                    <option value="FR_PFMP2">Fiche de renseignements PFMP2</option>
                    <option value="AS_PFMP1">Attestation de stage PFMP1</option>
                    <option value="AS_PFMP2">Attestation de stage PFMP2</option>
                </select>

                <!-- Champ de Fichier -->
                <div class="w-full text-center border-dashed border-2 border-gray-300 rounded-lg p-6 bg-gray-50 dark:bg-gray-700">
                    <input type="file" name="document" class="hidden" id="file-input" required>
                    <label for="file-input" class="cursor-pointer">
                        <!-- Icône d'Upload -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                        </svg>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">Cliquez ici pour sélectionner un fichier</p>
                    </label>
                    <!-- Aperçu du Fichier -->
                    <div id="file-preview" class="mt-4"></div>
                </div>

                <!-- Bouton Envoyer -->
                <button type="submit" class="px-6 py-3 bg-blue-500 text-white font-bold rounded-md hover:bg-blue-600 transition duration-300 ease-in-out">
                    Envoyer le document
                </button>
            </form>
        </div>
    </div>

    <!-- Script JS -->
    <script>
        document.getElementById('file-input').addEventListener('change', function(event) {
            const filePreview = document.getElementById('file-preview');
            const file = event.target.files[0]; // Récupère le fichier sélectionné
            
            if (file) {
                filePreview.innerHTML = `<p class="text-gray-700 dark:text-gray-300">Fichier sélectionné : <strong>${file.name}</strong></p>`;
                
                // Aperçu visuel pour les images
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        filePreview.innerHTML += `<img src="${e.target.result}" class="mt-2 rounded-lg" style="max-width: 200px;">`;
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                filePreview.innerHTML = `<p class="text-gray-700 dark:text-gray-300">Aucun fichier sélectionné.</p>`;
            }
        });
    </script>
</x-app-layout>
