<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
//    use HasFactory;
    public function Statistics() {
        return $this->hasMany(Statistic::class, 'country_id', 'id');
    }
}
