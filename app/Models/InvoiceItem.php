<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $primarykey ='id';
    protected $fillable = [
        'quantity',
        'buy',
        'sell',
        'min_sell',
        'expiry',
        'barcode'

    ];
}
