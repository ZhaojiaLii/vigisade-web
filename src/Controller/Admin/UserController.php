<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends EasyAdminController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserController constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function persistEntity($entity)
    {
        $this->encodePassword($entity);
        parent::persistEntity($entity);
    }

    public function encodePassword($user)
    {
        if (!$user instanceof User) {
            return;
        }

        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPlainPassword())
        );
    }

    protected function updateUserEntity(User $entity, Form $editForm = null)
    {
        if ($editForm) {
            $postedPassword = $editForm->get('plainPassword')->getData();

            if (!empty($postedPassword)) {
                $this->encodePassword($entity);
            }
        }

        parent::updateEntity($entity);
    }

    public function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $response =  parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);

        if ($sortField) {
            if ($sortField == 'entity') {
                $response->leftJoin('entity.entity', 'e');
                $response->orderBy('e.name', $sortDirection ?: 'DESC');
            }
        }

        return $response;
    }

    public function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        $response =  parent::createSearchQueryBuilder($entityClass, $searchQuery, $searchableFields, $sortField, $sortDirection, $dqlFilter);

        if ($searchQuery) {
            $response->leftJoin('entity.entity', 'e');
            $response->orWhere('e.name LIKE :search');
            $response->setParameter('search', $searchQuery);
        }

        return $response;
    }
}
