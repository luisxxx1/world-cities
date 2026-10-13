<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// modelo de los idiomas por país
class CountryLanguage extends Model
{
    // nombre de la tabla
    protected $table = 'countrylanguage';

    // la llave es doble (país + idioma), como solo leemos datos no la usamos
    protected $primaryKey = null;
    public $incrementing = false;

    // la tabla no tiene fechas de creación ni actualización
    public $timestamps = false;

    // país al que pertenece el idioma
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'CountryCode', 'Code');
    }
}