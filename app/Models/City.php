<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// modelo de las ciudades
class City extends Model
{
    // nombre de la tabla
    protected $table = 'city';

    // la llave es ID en mayúscula
    protected $primaryKey = 'ID';

    // la tabla no tiene fechas de creación ni actualización
    public $timestamps = false;

    // país al que pertenece la ciudad
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'CountryCode', 'Code');
    }
}