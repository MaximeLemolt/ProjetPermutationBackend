<?php

namespace App\DataFixtures\Provider;

class PermutationProvider extends \Faker\Provider\Base
{
    protected static $informations = [
        'Je veux changer d\'air',
        'Raison familiale',
        'Ma compagne part travailler dans cette région',
        'Je souhaite me rapprocher de ma famille',
        'Mon fils veut faire un sport études pétanque',
        'Mon Papa âgée de 105 ans a besoin de moi pour l\'aider dans les gestes de la vie courante (Pipi caca quoi)',
        'Mon conjoint qui a la garde de nos enfants ne peut plus l\'assurer tout seul ou toute seule',
        'Mes enfants ne supportent plus le climat de la région',
        'Envie de nouveauté',
        'A la recherche de nouvelles aventures',
        'Envie de changement',
        'Conflit avec ma hiérarchie, je n\'en peux plus',
        'Le changement c\'est maintenant',
        'Je souhaite me rapprocher de ma famille',
        'Mon fils veut faire un sport études pétanque',
        'Mon Papa âgée de 105 ans a besoin de moi pour l\'aider dans les gestes de la vie courante (Pipi caca quoi)',
        'Mon conjoint qui a la garde de nos enfants ne peut plus l\'assurer tout seul ou toute seule',
        'Mes enfants ne supportent plus le climat de la région',
        'Envi de nouveauté',
        'A la recherche de nouvelles aventures',
    ];
    // 13 grades
    protected static $grades = [
        'Gardien de la paix',
        'Sous-brigadier',
        'Brigadier',
        'Brigadier-chef',
        'Officier de police',
        'Major',
        'Capitaine',
        'Commandant',
        'Commandant divisionnaire',
        'Commandant divisionnaire fonctionnel',
        'Commissaire de police',
        'Commissaire divisionnaire de police',
        'Commissaire général de police',
        'Adjoint de sécurité',
    ];

    // services
    protected static $services = [
        'DRCPN',
        'IGPN',
        'DCPJ',
        'DCSP',
        'DCPAF',
        'DCCRS',
        'DCRFPN',
        'DCI',
        'SDLP',
        'RAID',
        'UCLAT',
        'SVOPN',
        'SICoP',
        'SHPN',
        'MILAD', 
        'DAV',
        'UCSTC',
        'UGE',
        'SAELSI',
        'SCPTS',
        'DCRI',
        'DGSI',
        'DSPAP',
        'DOPC',
        'CRS',
        'PAF',
        'SIAAP',
        'ASPTS',
        'SIC',
        'IPTS',
        'BAC',
        'BRI',
        'GIPN',
        'GIR',
        'SDPL',
        'STUPS',
        'PTS',
        'BSU',
        'FMU',
        'SDLP',
        'Autres'
    ];

    protected static $departments = [
        [
          "name" => "Ain",
          "code" => "01",
          "codeRegion" => "84"
        ],
        [
          "name" => "Aisne",
          "code" => "02",
          "codeRegion" => "32"
        ],
        [
          "name" => "Allier",
          "code" => "03",
          "codeRegion" => "84"
        ],
        [
          "name" => "Alpes-de-Haute-Provence",
          "code" => "04",
          "codeRegion" => "93"
        ],
        [
          "name" => "Hautes-Alpes",
          "code" => "05",
          "codeRegion" => "93"
        ],
        [
          "name" => "Alpes-Maritimes",
          "code" => "06",
          "codeRegion" => "93"
        ],
        [
          "name" => "Ardèche",
          "code" => "07",
          "codeRegion" => "84"
        ],
        [
          "name" => "Ardennes",
          "code" => "08",
          "codeRegion" => "44"
        ],
        [
          "name" => "Ariège",
          "code" => "09",
          "codeRegion" => "76"
        ],
        [
          "name" => "Aube",
          "code" => "10",
          "codeRegion" => "44"
        ],
        [
          "name" => "Aude",
          "code" => "11",
          "codeRegion" => "76"
        ],
        [
          "name" => "Aveyron",
          "code" => "12",
          "codeRegion" => "76"
        ],
        [
          "name" => "Bouches-du-Rhône",
          "code" => "13",
          "codeRegion" => "93"
        ],
        [
          "name" => "Calvados",
          "code" => "14",
          "codeRegion" => "28"
        ],
        [
          "name" => "Cantal",
          "code" => "15",
          "codeRegion" => "84"
        ],
        [
          "name" => "Charente",
          "code" => "16",
          "codeRegion" => "75"
        ],
        [
          "name" => "Charente-Maritime",
          "code" => "17",
          "codeRegion" => "75"
        ],
        [
          "name" => "Cher",
          "code" => "18",
          "codeRegion" => "24"
        ],
        [
          "name" => "Corrèze",
          "code" => "19",
          "codeRegion" => "75"
        ],
        [
          "name" => "Côte-d'Or",
          "code" => "21",
          "codeRegion" => "27"
        ],
        [
          "name" => "Côtes-d'Armor",
          "code" => "22",
          "codeRegion" => "53"
        ],
        [
          "name" => "Creuse",
          "code" => "23",
          "codeRegion" => "75"
        ],
        [
          "name" => "Dordogne",
          "code" => "24",
          "codeRegion" => "75"
        ],
        [
          "name" => "Doubs",
          "code" => "25",
          "codeRegion" => "27"
        ],
        [
          "name" => "Drôme",
          "code" => "26",
          "codeRegion" => "84"
        ],
        [
          "name" => "Eure",
          "code" => "27",
          "codeRegion" => "28"
        ],
        [
          "name" => "Eure-et-Loir",
          "code" => "28",
          "codeRegion" => "24"
        ],
        [
          "name" => "Finistère",
          "code" => "29",
          "codeRegion" => "53"
        ],
        [
          "name" => "Corse-du-Sud",
          "code" => "2A",
          "codeRegion" => "94"
        ],
        [
          "name" => "Haute-Corse",
          "code" => "2B",
          "codeRegion" => "94"
        ],
        [
          "name" => "Gard",
          "code" => "30",
          "codeRegion" => "76"
        ],
        [
          "name" => "Haute-Garonne",
          "code" => "31",
          "codeRegion" => "76"
        ],
        [
          "name" => "Gers",
          "code" => "32",
          "codeRegion" => "76"
        ],
        [
          "name" => "Gironde",
          "code" => "33",
          "codeRegion" => "75"
        ],
        [
          "name" => "Hérault",
          "code" => "34",
          "codeRegion" => "76"
        ],
        [
          "name" => "Ille-et-Vilaine",
          "code" => "35",
          "codeRegion" => "53"
        ],
        [
          "name" => "Indre",
          "code" => "36",
          "codeRegion" => "24"
        ],
        [
          "name" => "Indre-et-Loire",
          "code" => "37",
          "codeRegion" => "24"
        ],
        [
          "name" => "Isère",
          "code" => "38",
          "codeRegion" => "84"
        ],
        [
          "name" => "Jura",
          "code" => "39",
          "codeRegion" => "27"
        ],
        [
          "name" => "Landes",
          "code" => "40",
          "codeRegion" => "75"
        ],
        [
          "name" => "Loir-et-Cher",
          "code" => "41",
          "codeRegion" => "24"
        ],
        [
          "name" => "Loire",
          "code" => "42",
          "codeRegion" => "84"
        ],
        [
          "name" => "Haute-Loire",
          "code" => "43",
          "codeRegion" => "84"
        ],
        [
          "name" => "Loire-Atlantique",
          "code" => "44",
          "codeRegion" => "52"
        ],
        [
          "name" => "Loiret",
          "code" => "45",
          "codeRegion" => "24"
        ],
        [
          "name" => "Lot",
          "code" => "46",
          "codeRegion" => "76"
        ],
        [
          "name" => "Lot-et-Garonne",
          "code" => "47",
          "codeRegion" => "75"
        ],
        [
          "name" => "Lozère",
          "code" => "48",
          "codeRegion" => "76"
        ],
        [
          "name" => "Maine-et-Loire",
          "code" => "49",
          "codeRegion" => "52"
        ],
        [
          "name" => "Manche",
          "code" => "50",
          "codeRegion" => "28"
        ],
        [
          "name" => "Marne",
          "code" => "51",
          "codeRegion" => "44"
        ],
        [
          "name" => "Haute-Marne",
          "code" => "52",
          "codeRegion" => "44"
        ],
        [
          "name" => "Mayenne",
          "code" => "53",
          "codeRegion" => "52"
        ],
        [
          "name" => "Meurthe-et-Moselle",
          "code" => "54",
          "codeRegion" => "44"
        ],
        [
          "name" => "Meuse",
          "code" => "55",
          "codeRegion" => "44"
        ],
        [
          "name" => "Morbihan",
          "code" => "56",
          "codeRegion" => "53"
        ],
        [
          "name" => "Moselle",
          "code" => "57",
          "codeRegion" => "44"
        ],
        [
          "name" => "Nièvre",
          "code" => "58",
          "codeRegion" => "27"
        ],
        [
          "name" => "Nord",
          "code" => "59",
          "codeRegion" => "32"
        ],
        [
          "name" => "Oise",
          "code" => "60",
          "codeRegion" => "32"
        ],
        [
          "name" => "Orne",
          "code" => "61",
          "codeRegion" => "28"
        ],
        [
          "name" => "Pas-de-Calais",
          "code" => "62",
          "codeRegion" => "32"
        ],
        [
          "name" => "Puy-de-Dôme",
          "code" => "63",
          "codeRegion" => "84"
        ],
        [
          "name" => "Pyrénées-Atlantiques",
          "code" => "64",
          "codeRegion" => "75"
        ],
        [
          "name" => "Hautes-Pyrénées",
          "code" => "65",
          "codeRegion" => "76"
        ],
        [
          "name" => "Pyrénées-Orientales",
          "code" => "66",
          "codeRegion" => "76"
        ],
        [
          "name" => "Bas-Rhin",
          "code" => "67",
          "codeRegion" => "44"
        ],
        [
          "name" => "Haut-Rhin",
          "code" => "68",
          "codeRegion" => "44"
        ],
        [
          "name" => "Rhône",
          "code" => "69",
          "codeRegion" => "84"
        ],
        [
          "name" => "Haute-Saône",
          "code" => "70",
          "codeRegion" => "27"
        ],
        [
          "name" => "Saône-et-Loire",
          "code" => "71",
          "codeRegion" => "27"
        ],
        [
          "name" => "Sarthe",
          "code" => "72",
          "codeRegion" => "52"
        ],
        [
          "name" => "Savoie",
          "code" => "73",
          "codeRegion" => "84"
        ],
        [
          "name" => "Haute-Savoie",
          "code" => "74",
          "codeRegion" => "84"
        ],
        [
          "name" => "Paris",
          "code" => "75",
          "codeRegion" => "11"
        ],
        [
          "name" => "Seine-Maritime",
          "code" => "76",
          "codeRegion" => "28"
        ],
        [
          "name" => "Seine-et-Marne",
          "code" => "77",
          "codeRegion" => "11"
        ],
        [
          "name" => "Yvelines",
          "code" => "78",
          "codeRegion" => "11"
        ],
        [
          "name" => "Deux-Sèvres",
          "code" => "79",
          "codeRegion" => "75"
        ],
        [
          "name" => "Somme",
          "code" => "80",
          "codeRegion" => "32"
        ],
        [
          "name" => "Tarn",
          "code" => "81",
          "codeRegion" => "76"
        ],
        [
          "name" => "Tarn-et-Garonne",
          "code" => "82",
          "codeRegion" => "76"
        ],
        [
          "name" => "Var",
          "code" => "83",
          "codeRegion" => "93"
        ],
        [
          "name" => "Vaucluse",
          "code" => "84",
          "codeRegion" => "93"
        ],
        [
          "name" => "Vendée",
          "code" => "85",
          "codeRegion" => "52"
        ],
        [
          "name" => "Vienne",
          "code" => "86",
          "codeRegion" => "75"
        ],
        [
          "name" => "Haute-Vienne",
          "code" => "87",
          "codeRegion" => "75"
        ],
        [
          "name" => "Vosges",
          "code" => "88",
          "codeRegion" => "44"
        ],
        [
          "name" => "Yonne",
          "code" => "89",
          "codeRegion" => "27"
        ],
        [
          "name" => "Territoire de Belfort",
          "code" => "90",
          "codeRegion" => "27"
        ],
        [
          "name" => "Essonne",
          "code" => "91",
          "codeRegion" => "11"
        ],
        [
          "name" => "Hauts-de-Seine",
          "code" => "92",
          "codeRegion" => "11"
        ],
        [
          "name" => "Seine-Saint-Denis",
          "code" => "93",
          "codeRegion" => "11"
        ],
        [
          "name" => "Val-de-Marne",
          "code" => "94",
          "codeRegion" => "11"
        ],
        [
          "name" => "Val-d'Oise",
          "code" => "95",
          "codeRegion" => "11"
        ],
        [
          "name" => "Guadeloupe",
          "code" => "971",
          "codeRegion" => "01"
        ],
        [
          "name" => "Martinique",
          "code" => "972",
          "codeRegion" => "02"
        ],
        [
          "name" => "Guyane",
          "code" => "973",
          "codeRegion" => "03"
        ],
        [
          "name" => "La Réunion",
          "code" => "974",
          "codeRegion" => "04"
        ],
        [
          "name" => "Mayotte",
          "code" => "976",
          "codeRegion" => "06"
        ]
      ];


    
      //régions
    
      protected static $regions = [
        [
          "name" => "Guadeloupe",
          "code" => "01"
        ],
        [
          "name" => "Martinique",
          "code" => "02"
        ],
        [
          "name" => "Guyane",
          "code" => "03"
        ],
        [
          "name" => "La Réunion",
          "code" => "04"
        ],
        [
          "name" => "Mayotte",
          "code" => "06"
        ],
        [
          "name" => "Île-de-France",
          "code" => "11"
        ],
        [
          "name" => "Centre-Val de Loire",
          "code" => "24"
        ],
        [
          "name" => "Bourgogne-Franche-Comté",
          "code" => "27"
        ],
        [
          "name" => "Normandie",
          "code" => "28"
        ],
        [
          "name" => "Hauts-de-France",
          "code" => "32"
        ],
        [
          "name" => "Grand Est",
          "code" => "44"
        ],
        [
          "name" => "Pays de la Loire",
          "code" => "52"
        ],
        [
          "name" => "Bretagne",
          "code" => "53"
        ],
        [
          "name" => "Nouvelle-Aquitaine",
          "code" => "75"
        ],
        [
          "name" => "Occitanie",
          "code" => "76"
        ],
        [
          "name" => "Auvergne-Rhône-Alpes",
          "code" => "84"
        ],
        [
          "name" => "Provence-Alpes-Côte d'Azur",
          "code" => "93"
        ],
        [
          "name" => "Corse",
          "code" => "94"
        ]
    ];

    protected static $cities = [
      [
        'name' => 'Rouen',
        'codeINSEE' => '76540',
        'codeDepartement' => '76',
        'codeRegion' => '28',
        'longitude' => 1.0984,
        'latitude' => 49.4432
      ],
      [
        'name' => 'Paris',
        'codeINSEE' => '75056',
        'codeDepartement' => '75',
        'codeRegion' => '11',
        'longitude' => 2.3752,
        'latitude' => 48.845
      ],
      [
        'name' => 'Marseille',
        'codeINSEE' => '13055',
        'codeDepartement' => '13',
        'codeRegion' => '93',
        'longitude' => 5.3722,
        'latitude' => 43.2545
      ],
      [
        'name' => 'Lyon',
        'codeINSEE' => '69123',
        'codeDepartement' => '69',
        'codeRegion' => '84',
        'longitude' => 4.8236,
        'latitude' => 45.7685
      ],
      [
        'name' => 'Nice',
        'codeINSEE' => '06088',
        'codeDepartement' => '06',
        'codeRegion' => '93',
        'longitude' => 7.2651,
        'latitude' => 43.7123
      ],
      [
        'name' => 'Strasbourg',
        'codeINSEE' => '67482',
        'codeDepartement' => '67',
        'codeRegion' => '44',
        'longitude' => 7.7553,
        'latitude' => 48.5673
      ],
      [
        'name' => 'Amiens',
        'codeINSEE' => '80021',
        'codeDepartement' => '80',
        'codeRegion' => '32',
        'longitude' => 2.2924,
        'latitude' => 49.8935
      ],
      [
        'name' => 'Angers',
        'codeINSEE' => '49007',
        'codeDepartement' => '49',
        'codeRegion' => '52',
        'longitude' => -0.5628,
        'latitude' => 47.4776
      ],
      [
        'name' => 'Tours',
        'codeINSEE' => '37261',
        'codeDepartement' => '37',
        'codeRegion' => '24',
        'longitude' => 0.6947,
        'latitude' => 47.3952
      ],
      [
        'name' => 'Toulouse',
        'codeINSEE' => '31555',
        'codeDepartement' => '31',
        'codeRegion' => '76',
        'longitude' => 1.4368,
        'latitude' => 43.6089
      ],
      [
        'name' => 'Biarritz',
        'codeINSEE' => '64122',
        'codeDepartement' => '64',
        'codeRegion' => '75',
        'longitude' => -1.5612,
        'latitude' => 43.4808
      ],
      [
        'name' => 'Troyes',
        'codeINSEE' => '10387',
        'codeDepartement' => '10',
        'codeRegion' => '44',
        'longitude' => 4.082,
        'latitude' => 48.2981
      ],
      [
        'name' => 'Lille',
        'codeINSEE' => '59350',
        'codeDepartement' => '59',
        'codeRegion' => '53',
        'longitude' => 3.0476,
        'latitude' => 50.6344
      ],
      [
        'name' => 'Lens',
        'codeINSEE' => '62498',
        'codeDepartement' => '62',
        'codeRegion' => '32',
        'longitude' => 2.8228,
        'latitude' => 50.4341
      ],
      [
        'name' => 'Bordeaux',
        'codeINSEE' => '33063',
        'codeDepartement' => '33',
        'codeRegion' => '75',
        'longitude' => -0.5874,
        'latitude' => 44.8572
      ],
      [
        'name' => 'Lorient',
        'codeINSEE' => '56121',
        'codeDepartement' => '56',
        'codeRegion' => '53',
        'longitude' => -3.3825,
        'latitude' => 47.7495
      ],
      [
        'name' => 'Nantes',
        'codeINSEE' => '44109',
        'codeDepartement' => '44',
        'codeRegion' => '52',
        'longitude' => -1.5492,
        'latitude' => 47.2397
      ],
      [
        'name' => 'Montpellier',
        'codeINSEE' => '34172',
        'codeDepartement' => '34',
        'codeRegion' => '76',
        'longitude' => 3.8592,
        'latitude' => 43.6183
      ],
      [
        'name' => 'Rennes',
        'codeINSEE' => '35238',
        'codeDepartement' => '35',
        'codeRegion' => '53',
        'longitude' => -1.6815,
        'latitude' => 48.1105
      ],
      [
        'name' => 'Dijon',
        'codeINSEE' => '21231',
        'codeDepartement' => '21',
        'codeRegion' => '27',
        'longitude' => 5.0377,
        'latitude' => 47.3238
      ],
    ];
    /**
    * Retourne une information au hasard
    */
    public static function permutationInformation()
    {
        // On peut ici bénéficié de la méthode static randomElement de faker
        // pour obtenir une valeur aléatoire 
        return self::$informations;
    }
    /**
     * Retourne un grade au hasard
     */
    public static function permutationGrade()
    {
        // On peut ici bénéficié de la méthode static randomElement de faker
        // pour obtenir une valeur aléatoire 
        return self::randomElement(self::$grades);
    }

    /**
     * Retourne un service au hasard
     */
    public static function permutationService()
    {
        // On peut ici bénéficié de la méthode static randomElement de faker
        // pour obtenir une valeur aléatoire 
        return self::randomElement(self::$services);
    }

    /**
     * Retourne une région au hasard
     */
    public static function permutationRegion()
    {
        // On peut ici bénéficié de la méthode static randomElement de faker
        // pour obtenir une valeur aléatoire 
        return self::randomElement(self::$regions);
    }

    /**
    * Retourne un département au hasard
    */
    public static function permutationDepartment()
    {
        // On peut ici bénéficié de la méthode static randomElement de faker
        // pour obtenir une valeur aléatoire 
        return self::randomElement(self::$departments);
    }

    /**
    * Retourne une ville au hasard
    */
    public static function permutationCity()
    {
        // On peut ici bénéficié de la méthode static randomElement de faker
        // pour obtenir une valeur aléatoire 
        return self::randomElement(self::$cities);
    }
}