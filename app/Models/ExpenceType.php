<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenceType extends Model
{
    use HasFactory;
    protected $primarykey ='id';
    protected $fillable = [
        'expence_type',    
    ];
}
