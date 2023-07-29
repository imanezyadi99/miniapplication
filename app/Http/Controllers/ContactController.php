<?php

namespace App\Http\Controllers;

use App\Models\Contact;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Make sure to add this line


class ContactController extends Controller
{
    
    public $duplicate = false;
    public $searchTerm='';


    public $contact_id, $nom,$prenom, $email,$entreprise,$adresse,$code_postal, $ville,$status, $contact_edit_id, $contact_delete_id;

    public $view_contact_id, $view_contact_nom, $view_contact_email, $view_contact_entreprise,$view_contact_adresse,$view_contact_code_postal,
    $view_contact_ville,$view_contact_status;


    public function checkDuplicate()
    {
        $existingContact = Contact::where('nom', $this->nom)->where('prenom', $this->prenom)->first();
        $existingEntreprise = Contact::where('entreprise', $this->entreprise)->first();

        return ($existingContact || $existingEntreprise);
    }


    public function ajouter(Request $request)
    {
         // Vérifier les doublons pour le nom et prénom
       $existingContact = Contact::where('nom', $request->nom)->where('prenom', $request->prenom)->first();
        if ($existingContact) {
            return redirect()->route('render')->with('error', 'Un contact  existe déjà avec le meme nom et le meme prénom , Etes-vous sur de 
            vouloir ajouter ce contact ?');
        }

        $existingEntreprise = Contact::where('entreprise', $request->entreprise)->first();
        if ($existingEntreprise) {
        return redirect()->route('render')->with('error', 'Un contact  existe déjà avec  le meme entreprise , Etes-vous sur de 
            vouloir ajouter ce contact ?');
       }

      

         // If no duplicates, proceed with adding the contact

        $validatedData = $request->validate(Contact::rules());

        $contact = new Contact();
        $contact->nom = ucwords(strtolower($validatedData['nom']));
        $contact->prenom = ucwords(strtolower($validatedData['prenom']));
        $contact->email = strtolower($validatedData['email']);
        $contact->entreprise = ucwords(strtolower($validatedData['entreprise']));
        $contact->adresse = $validatedData['adresse'];
        $contact->code_postal = $validatedData['code_postal'];
        $contact->ville = ucwords(strtolower($validatedData['ville']));
         $contact->status = $validatedData['status'];


        $contact->save();


        session()->flash('message', 'New contact has been added successfully');


        return redirect()->route('render');

     
    }


    public function resetInputs()
    {
        $this->student_id = '';
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->student_edit_id = '';
    }

    public function close()
    {
        $this->resetInputs();
    }


    //Delete Confirmation
    public function deleteConfirmation($id)
    {
        $this->contact_id = $id;
        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    public function deleteStudentData()
    {
        Contact::find($this->contact_id)->delete();
        $this->dispatchBrowserEvent('close-modal');
        session()->flash('message', 'Contact deleted successfully.');
    }

    public function cancel()
    {
        $this->contact_delete_id = '';
    }





    public function delete($id)
    {
    // Retrieve the contact from the database based on the provided ID
    $contact = Contact::find($id);

    // Check if the contact was found
    if (!$contact) {
        // Optionally, you can add an error message here to be displayed if the contact doesn't exist.
        session()->flash('message', 'Contact not found.');
        return redirect()->route('render');
    }

    // Delete the contact
    $contact->delete();

    // Optionally, you can add a success message to be displayed after deletion.
    session()->flash('message', 'Contact deleted successfully.');

    return redirect()->route('render');
     }


       public function render(Request $request)
       {
        
        $sort = $request->input('sort', 'nom','entreprise','status');
        $order = $request->input('order', 'asc');
        $search = $request->input('search', '');

        $contactsQuery = Contact::query();

        // Appliquer la recherche si un terme de recherche est saisi
        if (!empty($search)) {
            $contactsQuery->where('nom', 'like', '%' . $search . '%')
                          ->orWhere('prenom', 'like', '%' . $search . '%')
                          ->orWhere('entreprise', 'like', '%' . $search . '%');

             }

        // Appliquer le tri
        $contactsQuery->orderBy($sort, $order);

        // Paginer les résultats
        $contacts = $contactsQuery->paginate(10);

        return view('contact', compact('contacts', 'sort', 'order', 'search'));
     
        }


      public function search(Request $request)

      {

       $search = $request->input('search', '');

        $contacts = Contact::where('nom', 'like', '%' . $search . '%')
                           ->orWhere('prenom', 'like', '%' . $search . '%')
                           ->orWhere('entreprise', 'like', '%' . $search . '%')
                           ->paginate(3);

        return view('contact', compact('contacts', 'search'));
    
      
        }



       public function edit(Contact $contact,$id)

       {

       $contact=Contact::find($id);

       return view('contact', compact('contact'));
       }
   

      public function updatee(Request $request,$id)
      {

        $contact=Contact::find($id);
        $input=$request->all();
        $contact->fill($input)->save();
       
        return redirect()->route('render')
                        ->with('success','Contact updated successfully');

       }
  


       public function view($id)

       { 

    $contact = Contact::findOrFail($id);
    return redirect()->route('render');

       }


}
