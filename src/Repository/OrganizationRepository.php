<?php

namespace App\Repository;

use App\Entity\Organization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Yaml\Yaml;

/**
 * @method Organization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Organization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Organization[]    findAll()
 * @method Organization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationRepository extends ServiceEntityRepository
{
    const FILE_NAME = 'organizations.yaml';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organization::class);
    }

    public function findAll()
    {
        return Yaml::parseFile(self::FILE_NAME)['organizations'];
    }

    public function findOneByName(string $name): ?Organization
    {
        $name = strtolower($name);
        $organizations = $this->findAll();

        foreach ($organizations as $organization) {
            if ($name == strtolower($organization['name'])) {
                return new Organization($organization);
            }
        }

        return null;
    }

    public function removeByName(string $name)
    {
        $organizations = $this->findAll();
        $organizationsSaved = [];
        foreach ($organizations as $organization) {
            if ($name != strtolower($organization['name'])) {
                $organizationsSaved[] = $organization;
            }
        }
        $yaml = Yaml::dump(['organizations' => $organizationsSaved]);
        file_put_contents(self::FILE_NAME, $yaml);
    }

    // /**
    //  * @return Organization[] Returns an array of Organization objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Organization
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
