<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Afficher la liste des documents de l'utilisateur connecté.
     */
    public function index()
    {
        $documents = Auth::user()->documents; // Récupérer les documents de l'utilisateur connecté
        return view('documents.index', compact('documents'));
    }

    /**
     * Gérer l'upload de documents.
     */
    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,png,jpg|max:5120', // Autorise uniquement PDF, PNG, et JPG
            'type' => 'required|string', // Le type de document est requis
        ]);
    
        $file = $request->file('document');
        $extension = $file->getClientOriginalExtension(); // Récupère l'extension du fichier
        $type = $request->input('type');
        $year = now()->year; // Année de dépôt
        $userName = strtolower(Auth::user()->name);
        $userSurname = strtolower(Auth::user()->surname); // Nom utilisateur (converti en minuscule)
        $userSession = strtolower(Auth::user()->session); // Ajoutez la session si nécessaire
    
        // Renommer le fichier selon la catégorie
        $filename = "{$userSurname}{$userName}_{$type}_{$year}.{$extension}"; 
        $filePath = $file->storeAs('documents', $filename, 'public'); // Sauvegarde du fichier renommé
    
        // Enregistrement dans la base de données
        Document::create([
            'name' => $filename,
            'user_id' => Auth::id(),
            'size' => $file->getSize() / 1024, // Taille en Ko
            'type' => $type, // Enregistre le type de document
        ]);
    
        return back()->with('success', 'Document déposé avec succès !');
    }
    
    
    
    public function download($id)
{
    $document = Document::findOrFail($id);
    return Storage::download('storage/app/public/documents/' . $document->name, 'file.txt');
}
public function validateDocument($id)
{
    $document = Document::findOrFail($id);
    $document->update(['status' => 'validé']); // Met à jour le statut à "validé"
    return back()->with('success', 'Le document a été validé.');
}

public function refuseDocument($id)
{
    $document = Document::findOrFail($id);
    $document->update(['status' => 'refusé']); // Met à jour le statut à "refusé"
    return back()->with('success', 'Le document a été refusé.');
}


}

