<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
   	protected $table = 'dealers';
   	protected $fillable = ['order_no'];
}