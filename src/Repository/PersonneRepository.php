<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

// where id = 1 and nom = modou  => array("id"=> 1, "nom" => "modou")
// order by id asc, prenom desc => array("id" => "ASC", "prenom" => "DESC")

/**
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Personne $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Personne $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
// Select * from personne p where p.nom = ? and p.prenom = ? limit 1

    public function getOneByNomAndPrenom(string $nom, string $prenom){
        $myquery = $this->createQueryBuilder('p');
        $myquery
            ->where("p.nom = :name") // name => une clé
            ->setParameter("name", $nom)
            ->andWhere("p.prenom = :prenom")
            ->setParameter("prenom", $prenom);
        $query = $myquery->getQuery();
        return $query->setMaxResults(1)->getOneOrNullResult();
    }

    public function getOneByNomAndPrenomDQL(string $nom, string $prenom){
        $em = $this->getEntityManager();
        $myQuery = $em->createQuery(
            'SELECT p FROM App\Entity\Personne p WHERE p.nom = :nom and p.prenom = :prenom'
        )->setParameter('nom', $nom)
         ->setParameter('prenom', $prenom);
        return $myQuery->setMaxResults(1)->getOneOrNullResult();
    }

    public function getOnlyNames(){
        $em = $this->getEntityManager();
        $myQuery = $em->createQuery('SELECT p.nom from App\Entity\Personne p');
        return $myQuery->getArrayResult();
    }


    public function getPersonsGreatThan(int $age){
        // Select * from personnes as p where p.age > $age
        $myQuery = $this->createQueryBuilder('p');
        $myQuery
            ->where('p.age > :age')
            ->setParameter('age', $age);
        // getResult => fetch
        // getArrayResult => FetchAll
        return $myQuery->getQuery()->getArrayResult();
    }

    // /**
    //  * @return Personne[] Returns an array of Personne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Personne
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
