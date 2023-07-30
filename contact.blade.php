@extends('layouts.base')

@section('content')
<div>
    <div class="container mt-5">
        <div class="row mb-5">
            <div class="col-md-12 text-center">
                <h2><strong> Mini Application</strong></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="float: center;"><strong>Liste des Contacts</strong></h5>
                      
                        <form action="{{ route('render') }}" method="GET">
                            <input type="search" name="search" id="" class="form-control" placeholder="Recherche" value="{{ $search }}" style="width: 230px" /><br/>
                            <button type="submit"style="float:left;" class="btn btn-primary">Search</button>
                        </form>
                        <button class="btn btn-sm btn-primary" style="float: right;" data-toggle="modal" data-target="#addStudentModal"><i class="fas fa-plus"></i>Ajouter</button>
                    </div>
                    <div class="card-body">
                     
                        <table  id="datatable" class="table align-middle mb-0 bg-white">
                            <thead class="bg-light">
                                <tr>
                                    <th><a href="{{ route('render', ['sort' => 'nom', 'order' => $sort === 'nom' && $order === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}">Nom du Contact</a></th>
                                    <th><a href="{{ route('render', ['sort' => 'entreprise', 'order' => $sort === 'entreprise' && $order === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}">Entreprise</a></th>
                                    <th><a href="{{ route('render', ['sort' => 'status', 'order' => $sort === 'status' && $order === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}">Status</a></th>

                                    <th style="text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($contacts as $contact)
                                        <tr>
                                            <td> <p class="fw-normal mb-1">{{$contact->nom}} {{$contact->prenom}}</p></td>

                                           <td> <p class="fw-normal mb-1">{{$contact->entreprise}}</p></td>

                                           <td>
                                            @if ($contact->status === 'client')
                                                <span class="badge badge-danger rounded-pill d-inline">
                                                    {{ $contact->status }}
                                                </span>
                                            @elseif ($contact->status === 'lead')
                                                <span class="badge badge-success rounded-pill d-inline">
                                                    {{ $contact->status }}
                                                </span>
                                            @elseif ($contact->status === 'prospect')
                                                <span class="badge badge-primary rounded-pill d-inline">
                                                    {{ $contact->status }}
                                                </span>
                                            @else
                                                {{ $contact->status }}
                                            @endif
                                           </td>

                                            <td style="text-align: center;">
                                                <button class="btn btn-link" data-toggle="modal" data-target="#viewModal{{$contact->id}}" wire:click="viewStudentDetails({{ $contact->id }})"><i class='far fa-eye'></i></button>


                                                <button class="btn btn-link"  data-toggle="modal" data-target="#editModal{{$contact->id}}" wire:click="$edit('show-edit-modal', {{ $contact->id }})"><i class='far fa-edit'></i></button>

                                                <button class="btn btn-link "  data-toggle="modal" data-target="#deleteStudentModal{{$contact->id}}" wire:click="deleteConfirmation({{ $contact->id }})"><i class='far fa-trash-alt'></i></button>
                                            </td>
                                        </tr>
                                        <form action="{{ route('delete', $contact->id) }}" method="POST" enctype="multipart/form-data">
                                            {{ method_field('delete') }}
                                            {{ csrf_field() }}
                                    
                                            <div class="modal fade" id="deleteStudentModal{{$contact->id}}" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">  <i class="fas fa-exclamation-triangle"></i>Supprimer le contact</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body pt-4 pb-4">
                                                            <h6>Etes-vous sur de vouloir supprimer le contact <b>{{$contact->nom}}</b>? <br>
                                                            Cette opération est irreversible</h6>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-sm btn-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Annuler</button>
                                                            <button class="btn btn-sm btn-danger" wire:click="deleteStudentData()">Confirmer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <form action="{{ route('contact.update', $contact->id) }}" method="POST"  enctype="multipart/form-data">
                                            @method('PATCH')
                                            @csrf
                                            <div class="modal fade " id="editModal{{ $contact->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Modifier le contact</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="nom" class="form-label">Nom</label>
                                                                <input type="text" name="nom" class="form-control" value="{{ $contact->nom }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="prenom" class="form-label">Prénom</label>
                                                                <input type="text" name="prenom" class="form-control" value="{{ $contact->prenom }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="entreprise" class="form-label">Entreprise</label>
                                                                <input type="text" name="entreprise" class="form-control" value="{{ $contact->entreprise }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="email" class="form-label">Email</label>
                                                                <input type="email" name="email" class="form-control" value="{{ $contact->email }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="adresse" class="form-label">Adresse</label>
                                                                <input type="text" name="adresse" class="form-control" value="{{ $contact->adresse }}">
                                                            </div> <div class="mb-3">
                                                                <label for="ville" class="form-label">Ville</label>
                                                                <input type="text" name="ville" class="form-control" value="{{ $contact->ville }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="status" class="form-label">Statut</label>
                                                                <input type="text" name="status" class="form-control" value="{{ $contact->status }}">
                                                            </div> <div class="mb-3">
                                                                <label for="codepostale" class="form-label">Code postale</label>
                                                                <input type="text" name="code_postal" class="form-control" value="{{ $contact->code_postal }}">
                                                            </div>
                                                            
                                                            <!-- Ajoutez d'autres champs ici pour les autres attributs du contact -->
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Modifier</button>
                                                            <button type="button" class="btn btn-danger" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Annuler</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <form action="{{ route('contact.view', $contact->id) }}" method="POST"  enctype="multipart/form-data">
                                            @method('GET')
                                            @csrf
                                            <div class="modal fade " id="viewModal{{ $contact->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Voir le contact</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <p><strong>Nom:</strong> {{ $contact->nom }}</p>
                                                        <p><strong>Prénom:</strong> {{ $contact->prenom }}</p>
                                                        <p><strong>Email:</strong> {{ $contact->email }}</p>
                                                        <p><strong>Entreprise:</strong> {{ $contact->entreprise }}</p>
                                                        <p><strong>Adresse:</strong> {{ $contact->adresse }}</p>
                                                        <p><strong>Code Postal:</strong> {{ $contact->code_postal }}</p>
                                                        <p><strong>Ville:</strong> {{ $contact->ville }}</p>
                                                        <p><strong>Status:</strong> {{ $contact->status }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" wire:click="cancel()" data-dismiss="modal" aria-label="Close">Annuler</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
    
     
                            @endforeach

                                                             
  

                            </tbody>
                          
                        
                        </table>
                        <div class="pagination" style="
                        float: right;
                    ">
                    
                    {{ $contacts->links() }}

                     </div>
                    </div>
                   
                </div>
              
            </div>
       
        </div>
    </div>


    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel"> 
                        <i class="fas fa-exclamation-triangle"></i>Doublon
                    </h5>
                  
                </div>
                <div class="modal-body">
                    {{ session('error') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancel()" data-dismiss="modal" aria-label="Close" id="errorModalCancelButton">Annuler</button>

                </div>
            </div>
        </div>
    </div>
     

    <!-- Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"  wire:submit.prevent="ajouter">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="myModalLabel"> Détail du contact</h5>


                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('ajouter')}}" method="POST" enctype="multipart/form-data">
                      
        
                        @csrf

                        <div class="form-group row">
                            <label for="nom" class="col-3">Nom</label>
                            <div class="col-9">
                                <input type="text" name="nom" class="form-control" wire:model="nom">
                                @error('nom')
                                    <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="prenom" class="col-3">Prenom</label>
                            <div class="col-9">
                                <input type="text" name="prenom" class="form-control" wire:model="prenom">
                                @error('prenom')
                                    <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-3">Email</label>
                            <div class="col-9">
                                <input type="email" name="email" class="form-control" wire:model="email">
                                @error('email')
                                    <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="entreprise" class="col-3">Entreprise</label>
                            <div class="col-9">
                                <input type="text" name="entreprise" class="form-control" wire:model="entreprise">
                                @error('entreprise')
                                    <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="adresse" class="col-3">Adresse</label>
                            <div class="col-9">
                                <input type="text" name="adresse" class="form-control" wire:model="adresse">
                                @error('adresse')
                                    <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                           <div class="form-group row">
                            <label for="code" class="col-3">Code Postale</label>
                            <div class="col-9">
                                <input type="text" name="code_postal" class="form-control" wire:model="code_postal">
                                @error('code_postal')
                                    <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> 
                          <div class="form-group row">
                            <label for="ville" class="col-3">Ville</label>
                            <div class="col-9">
                                <input type="text" name="ville" class="form-control" wire:model="ville">
                                @error('ville')
                                    <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="status" class="col-3">Status</label>
                        <div class="col-9">
                            <select name="status"  class="form-control">
                                <option value="lead">Lead</option>
                                <option value="client">Client</option>
                                <option value="prospect">Prospect</option>
                            </select>
                        </div>
                        </div>
                    

                        <div class="form-group row">
                            <label for="" class="col-3"></label>
                            <div class="col-9">
                                <button type="submit" class="btn btn-sm btn-primary" >Valider</button>
                                <button class="btn btn-sm btn-primary" wire:click="cancel()" data-dismiss="modal" aria-label="Close"  >Annuler</button>

                            </div>
                        </div>

                      
                    </div>
                    
                    
                    </form>
                </div>
            </div>
        </div>
    </div>

   
   


    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>


    <script>
    
        window.addEventListener('close-modal', () => {
            // Masquer tous les modals
            $('#addStudentModal').modal('hide');
            $('.editModal').modal('hide');
            $('#deleteStudentModal').modal('hide');

        });
    
        window.addEventListener('show-edit-modal', (contactId) => {
            // Afficher le modal d'édition spécifique en utilisant l'ID du contact
            $('#editModal-' + contactId).modal('show');
        });
    
        window.addEventListener('show-delete-modal', (contactId) => {
            // Afficher le modal de suppression spécifique en utilisant l'ID du contact
            $('#deleteStudentModal-' + contactId).modal('show');
        });
    
        window.addEventListener('show-view-student-modal', () => {
            $('#viewStudentModal').modal('show');
        });
    </script>

<script>

   @if (session('error'))
    // Show the error modal when the page loads
    $(document).ready(function () {
        $('#errorModal').modal('show');
    });
</script>
@endif

<script>
    $(document).ready(function () {
        $('#errorModalCancelButton').on('click', function () {
            $('#errorModal').modal('hide');
        });
    });
</script>

    @endpush

    @endsection