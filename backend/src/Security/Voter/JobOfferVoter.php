<?php

namespace App\Security\Voter;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class JobOfferVoter extends Voter{
    
    public const CREATE = 'JOB_OFFER_CREATE';
    public const EDIT = 'JOB_OFFER_EDIT';
    public const DELETE = 'JOB_OFFER_DELETE';

    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::CREATE, self::EDIT, self::DELETE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if(!$user instanceof UserInterface){
            return false;
        }

        $companyEmails = $this->params->get('company_emails');

        return in_array($user->getUserIdentifier(), $companyEmails);
    }
    



} //Cierre voter
