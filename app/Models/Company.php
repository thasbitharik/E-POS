<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $primarykey ='id';
    protected $fillable = [
        'comapny_name',
        'tp',
        'email',
        'description'    
    ];
}
