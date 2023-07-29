<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table = 'contacts';
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'entreprise',
        'adresse',
        'code_postal',
        'ville',
        'status',
    ];

    public static function rules()
    {
        return [
           
            'prenom' => 'required|string|alpha',
            'nom' => 'required|string|alpha',
            'email' => 'required|email',
            'entreprise' => 'required|string|alpha_num',
            'adresse' => 'required|string|max:255',
            'code_postal' => 'required|numeric',
            'ville' => 'required|string|max:255',
            'status' => 'required|in:lead,client,prospect',

           
        ];
    }

}
