<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// modelo de los países
class Country extends Model
{
    // nombre de la tabla
    protected $table = 'country';

    // LLAVE POR CODIGO DE PAIS 
    protected $primaryKey = 'Code';

    // el código se hizo texto, no un número que aumenta solo
    public $incrementing = false;
    protected $keyType = 'string';

    // la tabla no tiene fechas de creación ni actualización
    public $timestamps = false;

    // ciudades del país
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'CountryCode', 'Code');
    }

    // idiomas del país
    public function languages(): HasMany
    {
        return $this->hasMany(CountryLanguage::class, 'CountryCode', 'Code');
    }
}