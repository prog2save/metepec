<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GeocodingService
{
    private string $apiKey;
    private string $geocodeUrl;

    private int $cacheTtl = 604800; //TTL de cache (7 días)

    public function __construct()
    {
        $this->apiKey     = config('services.arcgis.key');
        $this->geocodeUrl = config('services.arcgis.geocode_url');
    }

    public function getCoordinates(string $address): ?array
    {

        // Generar una llave unica para la direccion
        $cacheKey = 'geocoding_' . md5(Str::lower(trim($address)));
        //asegura que mayusculas o espacios extra no generen direcciones duplicadas

        // Si ya está en cache, devolverla sin llamar a ArcGIS
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($address) {
            return $this->fetchFromArcGIS($address);
        });

        
    }

    public function fetchFromArcGIS(string $address): ?array
    {
        try {
            $response = Http::get($this->geocodeUrl, [
                'singleLine'   => $address, //Dirección completa
                'f'            => 'json', //formato en que va a responder
                'token'        => $this->apiKey, 
                'maxLocations' => 1, // solo regresa la mejor coincidencia
                'outFields'    => 'Match_addr', //normalizar dirección
            ]);

            if (!$response->ok()) {
                Log::error('ArcGIS error HTTP', [
                    'status' => $response->status()
                ]);
                return null;
            }

            $candidate = $response->json('candidates.0');

            if (!$candidate) {
                return null;
            }

            // Resultado no es confiable (score < 80 sobre 100)
            if ($candidate['score'] < 80) {
                return null;
            }

            return [
                'lat'     => $candidate['location']['y'],
                'lng'     => $candidate['location']['x'],
                'address' => $candidate['attributes']['Match_addr'],
                'score'   => $candidate['score'],
            ];

        } catch (\Exception $e) {
            Log::error('ArcGIS excepción', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    } 
}