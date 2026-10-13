<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

// controlador de la pantalla de países
class CountryController extends Controller
{
    // muestra la pantalla con la lista de países
    public function index(): View
    {
        // traer los países ordenados por nombre
        $countries = Country::query()
            ->orderBy('Name')
            ->get(['Code', 'Name']);

        // enviar los países a la vista
        return view('countries.index', compact('countries'));
    }

    // devuelve las ciudades e idiomas del país elegido
    public function cities(string $code): JsonResponse
    {
        // buscar el país, si no existe devuelve error 404
        $country = Country::query()
            ->where('Code', strtoupper($code))
            ->firstOrFail(['Code', 'Name', 'Continent', 'Region', 'Population']);

        // traer las ciudades de mayor a menor población
        $cities = $country->cities()
            ->orderByDesc('Population')
            ->orderBy('Name')
            ->get(['ID', 'Name', 'District', 'Population']);

        // traer los idiomas, primero el más hablado
        $languages = $country->languages()
            ->orderByDesc('Percentage')
            ->get(['Language', 'IsOfficial', 'Percentage'])
            ->map(fn ($lang) => [
                'language'    => $lang->Language,
                'is_official' => $lang->IsOfficial === 'T', // T = oficial, F = no oficial
                'percentage'  => (float) $lang->Percentage,
            ]);

        // armar la respuesta en JSON
        return response()->json([
            // datos del país
            'country' => [
                'code'       => $country->Code,
                'name'       => $country->Name,
                'continent'  => $country->Continent,
                'region'     => $country->Region,
                'population' => (int) $country->Population,
            ],
            // cantidad de ciudades
            'total_cities'    => $cities->count(),
            // las 10 más pobladas
            'most_populated'  => $cities->take(10)->values(),
            // las 10 menos pobladas
            'least_populated' => $cities->sortBy('Population')->take(10)->values(),
            // todas las ciudades
            'all_cities'      => $cities->values(),
            // idiomas del país
            'languages'       => $languages,
        ]);
    }
}