<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User; // Assurez-vous que vos lycéens sont dans la table "users"
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class LyceenController extends Controller
{
    /**
     * Affiche la liste des lycéens.
     */
    public function index(Request $request)
    {
        // Recherche par le champ "nom" ou "identifiant"
        $search = $request->input('lyceen');
        
        $lyceens = User::query()
            ->where('surname', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('identifiant', 'LIKE', "%{$search}%")
            ->get();
    
        // Passer les résultats à la vue
        return view('dashboard2', compact('lyceens', 'search'));
    }
    public function destroy($id)
    {
        $lyceen = User::findOrFail($id); // Trouve l'utilisateur ou génère une erreur 404
        $lyceen->delete(); // Supprime l'utilisateur
    
        return redirect()->route('dashboard2')->with('success', 'Étudiant supprimé avec succès.');
    }
    
    public function regeneratePassword($id)
    {
        $lyceen = User::findOrFail($id);
        $newPassword = Str::random(10);
        $lyceen->password = Hash::make($newPassword);
        $lyceen->save();

        return redirect()->route('dashboard2')->with('success', "Nouveau mot de passe : $newPassword");
    }
    public function showDocuments($id)
    {
        $lyceen = User::findOrFail($id);
        $documents = $lyceen->documents; // Récupérer les documents du lycéen
    
        return view('lyceens.documents', compact('lyceen', 'documents'));
    }
    

    

}
