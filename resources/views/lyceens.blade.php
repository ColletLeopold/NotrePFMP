<!DOCTYPE html>
<html>
<head>
    <title>Liste des Lycéens</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Liste des Lycéens</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="/lyceens" class="form-inline mb-3">
            <input type="text" name="lyceen" class="form-control mr-2" placeholder="Rechercher un étudiant" value="{{ $lyceen ?? '' }}">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>




        <!-- Boîte déroulante avec des boutons -->
        <div class="list-group" style="max-height: 300px; overflow-y: auto;">
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->surname }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->classe }}</td>
                <td>{{ $user->identifiant }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('lyceens.documents', $user->id) }}" class="px-4 py-2 bg-blue-500 text-black rounded-md hover:bg-blue-600">
                        Documents
                    </a>
                    <form action="/users/{{ $user->id }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                    <form action="/users/{{ $user->id }}/regenerate-password" method="POST" onsubmit="return confirm('Voulez-vous vraiment générer un nouveau mot de passe ?');">
                        @csrf
                        <button type="submit" class="btn btn-warning">Générer un mot de passe</button>
                    </form>
                </td>
            </tr>
            @endforeach

        </div>
    </div>
</body>
</html>
