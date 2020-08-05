<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
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
            && $subject instanceof User;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $userConnected = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$userConnected instanceof User) {
            return false;
        }

        // you know $subject is a User object, thanks to supports
        /** @var User $user */
        $user = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                // Le User connecté peut-il modifier la question ?
                return $this->isAuthorized($user, $userConnected);
            case 'SHOW':
                // Le User connecté peut-il voir la question ?
                return $this->isAuthorized($user, $userConnected);
            case 'REMOVE':
                // Le User connecté peut-il voir la question ?
                return $this->isAuthorized($user, $userConnected);
        }

        return false;
    }

    private function isAuthorized(User $user, User $userConnected)
    {
        // Avant de vérifier si l'utilisateur courant est le propriétaire de la annonce
        // On vérifie son rôle car les administrateurs peuvent eux aussi
        // modifier n'importe quelle annonces

        // Ici, on autorise les admins à editer les annonce
        if($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Retroune True si l'utilisateur courant est l'auteur de la annonce
        return $userConnected === $user;
    }
}
