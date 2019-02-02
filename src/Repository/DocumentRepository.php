<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Document::class);
    }

    /*
    *  - soit l'utilisateur est l auteur
    *  - soit le doc est public
    *  - soit l'utilisateur existe dans la table de liaison dans la table de liaison
    */
    public function getAllowedDocuments(\App\Entity\User $user)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT d.id, d.name, d.creation_date, d.is_public, d.user_id, u.username
            FROM document d
            JOIN fos_user u ON u.id=d.user_id
            WHERE d.user_id=:user_id
            OR d.is_public=:is_public
            OR d.id IN(SELECT document_id FROM document_user WHERE user_id=:user_id)
            ORDER BY d.creation_date ASC';

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'user_id'   => $user->getId(),
            'is_public' => 1
        ]);

        return $stmt->fetchAll();
    }
}
