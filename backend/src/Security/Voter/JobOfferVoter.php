<?php
/*
namespace App\Security\Voter;

use App\Entity\JobOffer;
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
        return in_array($attribute, [self::CREATE, self::EDIT, self::DELETE]) &&
            ($subject instanceof JobOffer || $subject === null); //null para el crear
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return true;
        /*
        $user = $token->getUser();

        if(!$user instanceof UserInterface){
            return false;
        }

        $companyEmails = $this->params->get('company_emails');

        //Solo empresas autorizadas van a poder crear
        if($attribute === self::CREATE){
            return in_array($user->getUserIdentifier(), $companyEmails);
        }

        //Solo podrá editar y/o borrar una oferta el dueño que la haya hechp
        if($attribute === self::EDIT || $attribute === self::DELETE){
            if(!$subject instanceof JobOffer){
                return false;
            }

            //verificar que getcompany no sea null antes de acceder a getId
            $company = $subject->getCompany();

            //si no hay company no hacer la comparación
            if($company === null){
                return false;
            }

            //return $company->getId() === $user->getId();
            return $company->getUserIdentifier() === $user->getUserIdentifier();
        }

        return false;
       // return in_array($user->getUserIdentifier(), $companyEmails);
       
    }
    



} //Cierre voter
 */
