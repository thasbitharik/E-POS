<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $primarykey ='id';
    protected $fillable = [
        'item_name',
        'measure'    
    ];
}
