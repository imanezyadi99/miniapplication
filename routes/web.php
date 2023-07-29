<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contacts', [ContactController::class, 'render'])->name('render');
Route::delete('/contact/{id}', [ContactController::class, 'delete'])->name('delete');
Route::post('/add/contact', [ContactController::class, 'ajouter'])->name('ajouter');
Route::put('/update/{id}', [ContactController::class, 'update'])->name('updateContact');
Route::get('/edit-contact/{id}', [ContactController::class, 'editContact'])->name('edit');
/*Route::put('/update-contact/{contact}/{id}', [ContactController::class, 'update'])->name('update');*/


/*Route::resource("/contact", ContactController::class);*/

/*Route::get('/contact/{id}/edit', [ContactController::class, 'edit'])->name('edit');*/

Route::get('/contact/{contact}/edit', [ContactController::class, 'edit'])->name('edit');
Route::put('/contact/{contact}', [ContactController::class, 'update'])->name('update');

// Route pour afficher le formulaire de modification d'un employé
Route::get('/employees/{employee}', [ContactController::class, 'edit'])->name('employees.edit');

// Route pour mettre à jour les informations de l'employé
Route::patch('/update/{id}', [ContactController::class, 'updatee'])->name('contact.update');
Route::get('/view/{id}', [ContactController::class, 'view'])->name('contact.view');

Route::post('/check-duplicate', [ContactController::class, 'checkDuplicate'])->name('checkDuplicate');

Route::get('/search-contacts', [ContactController::class, 'search'])->name('search.contacts');























