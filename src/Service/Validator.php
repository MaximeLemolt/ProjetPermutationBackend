<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    
    /**
     * Return errors if entity data isn't validate or null if no errors
     * 
     * @param object $object The object to validate
     * @return array|null
     */
    public function validate(object $object)
    {
        $errors = $this->validator->validate($object);
        if (count($errors) !== 0) {
            $jsonErrors = [];
            foreach ($errors as $error) {
                $jsonErrors[] = [
                    'field' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                ];
            }
            // Return array of errors
            return self::mergeErrors($jsonErrors);
        }
        return null;
    }

    /**
     * Merge error message
     * 
     * @param array $errors Errors array
     * @return array
     */
    static public function mergeErrors(array $errors) : array
    {
        // On initialise un tableau qui va lister les erreurs
        $errorsList = [];

        // On boucle sur les erreurs
        foreach($errors as $error) {
            // On récupère le dernier index du tableau $errorsList
            $lastIndex = array_key_last($errorsList);

            // Si le tableau d'erreur n'est pas vide
            // et que la valeur 'field' du dernier index est égale à la valeur 'field' de l'erreur actuelle
            if (!empty($errorsList) && $errorsList[$lastIndex]['field'] == $error['field'])
            {
                // Alors on regroupe les messages d'erreurs car ils concernent le même champ
                $errorsList[$lastIndex]['message'][] = $error['message'];
            // sinon, si l'erreur actuelle concerne un autre champ on l'ajoute normalement à la liste des erreurs
            // en stockant le message d'erreur dans un tableau pour simplifier l'ajout d'un second message d'erreurs
            } else {
                $errorsList[] = [
                    'field' => $error['field'],
                    'message' => [$error['message']]
                ];
            }
        }

        return $errorsList;
    }
}