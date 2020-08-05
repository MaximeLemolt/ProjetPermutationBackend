<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Annonce;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AnnonceVoter extends Voter
{
    private $security;

    /**
     * On récupère le service Security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['EDIT', 'SHOW', 'REMOVE'])
            && $subject instanceof Annonce;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        // you know $subject is a Annonce object, thanks to supports
        /** @var Annonce $annonce */
        $annonce = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                // Le User connecté peut-il modifier l'annonce'?
                return $this->isAuthorized($annonce, $user);
            case 'SHOW':
                // Le User connecté peut-il voir l'annonce'?
                return $this->isAuthorized($annonce, $user);
            case 'REMOVE':
                // Le User connecté peut-il voir l'annonce'?
                return $this->isAuthorized($annonce, $user);
        }

        return false;
    }

    private function isAuthorized(Annonce $annonce, User $user)
    {
        // Avant de vérifier si l'utilisateur courant est le propriétaire de la annonce
        // On vérifie son rôle car les administrateurs peuvent eux aussi
        // modifier n'importe quelle annonces

        // Ici, on autorise les admins à editer les annonce
        if($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Retroune True si l'utilisateur courant est l'auteur de la annonce
        return $user === $annonce->getUser();
    }
}
