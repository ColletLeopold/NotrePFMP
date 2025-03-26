<?php

use App\Models\User;
use App\Models\Lyceen;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProfileController;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\LyceenController;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



// Afficher la liste des lycéens
Route::get('/dashboard2', [LyceenController::class, 'index'])->name('dashboard2');

// Supprimer un lycéen
Route::delete('/users/{id}', [LyceenController::class, 'destroy'])->name('users.destroy');


// Générer un nouveau mot de passe
Route::post('/users/{id}/regenerate-password', [LyceenController::class, 'regeneratePassword'])->name('users.regeneratePassword');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/users/{id}', [LyceenController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';

Route::get('/upload', function () {
    return view('upload');
});

Route::post('/upload-excel', function (Request $request) {
    $request->validate([
        'excel_file' => 'required|mimes:xlsx',
    ]);

    $file = $request->file('excel_file');
    $filePath = $file->storeAs('excel', 'liste.xlsx');

    $collection = (new FastExcel)->import(Storage::path($filePath));

    $ajoutes = 0;
    $nonAjoutes = 0;

    $updatedCollection = $collection->map(function ($item) use (&$ajoutes, &$nonAjoutes) {
        if (!empty($item['nom']) && !empty($item['prenom'])) {
            // Vérifiez si l'utilisateur existe déjà (en fonction du nom et du prénom)
            $exists = DB::table('users')
                ->where('name', $item['prenom'])
                ->where('surname', $item['nom'])
                ->where('email', $item['email'])
                ->where('classe', $item['classe'])
                ->exists();
    
            if ($exists) {
                $nonAjoutes++;
                return $item; // L'utilisateur existe déjà, on ne l'ajoute pas
            }
    
            // Générer un nouvel identifiant et mot de passe
            $identifiant = strtolower($item['nom']) . '.' . strtolower($item['prenom']) . rand(100, 999);
            $motDePasse = Str::random(10);
            $item['identifiant'] = $identifiant;
            $item['mot_de_passe'] = $motDePasse;
    
            // Ajouter dans la table "users"
            DB::table('users')->insert([
                'name' => $item['prenom'],
                'surname' => $item['nom'],
                'identifiant' => $identifiant,
                'email' => $item['email'],
                'password' => Hash::make($motDePasse), // Hacher le mot de passe
                'classe' => $item['classe'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            $ajoutes++;
        }
        return $item;
    });
    


    return view('resultat-upload', ['ajoutes' => $ajoutes, 'nonAjoutes' => $nonAjoutes]);
});
Route::get('/lyceens', function (Request $request) {
    $lyceen = $request->input('lyceen');

    if ($lyceen) {
        $users = DB::table('users')
            ->where('name', 'LIKE', "%{$lyceen}%")
            ->orWhere('surname', 'LIKE', "%{$lyceen}%")
            ->get();
    } else {
        $users = DB::table('users')->get();
    }

    return view('lyceens', ['users' => $users, 'lyceen' => $lyceen]);
});


Route::get('/lyceens/{query?}', function ($query = null) {
    $lyceens = $query
        ? Lyceen::where('nom', 'LIKE', "%{$query}%")
                 ->orWhere('prenom', 'LIKE', "%{$query}%")
                 ->get()
        : Lyceen::all();

    return view('lyceens', ['lyceens' => $lyceens, 'query' => $query]);
});



Route::delete('/users/{id}', function ($id) {
    $user = DB::table('users')->find($id);
    if ($user) {
        DB::table('users')->where('id', $id)->delete();
        return redirect('/lyceens')->with('success', 'Utilisateur supprimé avec succès');
    }
    return redirect('/lyceens')->with('error', 'Utilisateur introuvable');
});


Route::post('/users/{id}/regenerate-password', function ($id) {
    $user = DB::table('users')->find($id);
    if ($user) {
        $nouveauMotDePasse = Str::random(10);
        DB::table('users')->where('id', $id)->update([
            'password' => $nouveauMotDePasse,
            'updated_at' => now(),
        ]);
        return view('nouveau-mot-de-passe', ['motDePasse' => $nouveauMotDePasse]);
    }
    return redirect('/lyceens')->with('error', 'Utilisateur introuvable');
});

use App\Http\Controllers\DocumentController;

Route::middleware(['auth'])->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
});
Route::get('/documents/download/{id}', [DocumentController::class, 'download'])->name('documents.download');
Route::get('/lyceens/{id}/documents', [LyceenController::class, 'showDocuments'])->name('lyceens.documents');
Route::put('/documents/{id}/validate', [DocumentController::class, 'validateDocument'])->name('documents.validate');
Route::put('/documents/{id}/refuse', [DocumentController::class, 'refuseDocument'])->name('documents.refuse');

