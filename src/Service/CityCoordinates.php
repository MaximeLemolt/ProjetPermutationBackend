<?php

namespace App\Service;

use App\Entity\City;

class CityCoordinates
{
    /**
     * Get city coordinates
     * 
     * @param City $city City to get coordinates
     */
    public function getCoordinates(City $city)
    {
        // urlencode() permet d'encoder une chaine au format URL
        $cityToSearch = urlencode($city->getCodeINSEE());

        // On crée l'URL de destination JSON à aller chercher
        $url = 'https://geo.api.gouv.fr/communes?code='.$cityToSearch.'&format=geojson';

        // On va lire le contenu via
        // https://www.php.net/manual/fr/function.file-get-contents.php
        $responseContent = file_get_contents($url);

        // On decode ce contenu en JSON
        // JSON => PHP Object
        $data = json_decode($responseContent);

        // Si pas de résultat trouvé
        if (empty($data->features)) {
            return null;
        }

        // On récupère les coordonnées dans le json de la réponse
        $coordinates = $data->features['0']->geometry->coordinates;
        $longitude = $coordinates['0'];
        $latitude = $coordinates['1'];

        // On retoure un tableau contenant les coordonnées
        return $coordinates = [
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
    }
}