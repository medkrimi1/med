<?php

namespace App\Repository;
use App\Data\SearchData;
use App\Entity\TypeJobs;
use App\Entity\Jobs;
use App\Data\SearchData1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jobs>
 *
 * @method Jobs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jobs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jobs[]    findAll()
 * @method Jobs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jobs::class);
    }

    public function add(Jobs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Jobs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


      public function findAll()
    {
        return $this->findBy(array(), array('id' => 'DESC'));
    }
    

  /** 
     * 
     * @return jobs[]
     */
    public function findSearch(SearchData $search): array
    { 
         $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
             ->orderBy('p.id', 'DESC')
             ->Where('p.title != :test')

             ->setParameter('test', 609679)
             ->Where('p.status != :test')
             ->setParameter('test', 'nonActif')
           
            ->join('p.skills', 'c')
            ;
               if ($search->status=="Actif") {
            $query = $query
              ->andWhere('p.ExpiredAt > :date')
            ->setParameter('date', new \DateTime());
       
        }

          if ($search->status=="Expiré") {
            $query = $query
              ->andWhere('p.ExpiredAt < :date')
            ->setParameter('date', new \DateTime());
       
        }
        

        if (!empty($search->Name)) {
            $query = $query
                ->andWhere('p.title LIKE :Name')
                ->setParameter('Name', "%{$search->Name}%");
        }
        if (!empty($search->Title)) {
            $query = $query
                ->andWhere('p.id IN (:Title)')
                ->setParameter('Title', $search->Title);
        }
        if ((!empty($search->startdate))AND(!empty($search->enddate)) ) {
            $query = $query
                ->andWhere('p.CreatedAt BETWEEN (:startdate) AND (:enddate)')
                ->setParameter('startdate', $search->startdate)
                ->setParameter('enddate', $search->enddate);
        }
         if (!empty($search->TypeJobs)) {
            $query = $query
                ->andWhere('p.typeid IN (:TypeJobs)')
                ->setParameter('TypeJobs', $search->TypeJobs);
        }
         if (!empty($search->employer)) {
            $query = $query
                ->andWhere('p.user IN (:employer)')
                ->setParameter('employer', $search->employer);
        }
        if (!empty($search->Experience)) {
            $query = $query
                ->andWhere('p.exp IN (:Experience)')
                ->setParameter('Experience', $search->Experience);

        }
         
         if (!empty($search->Country)) {
            $query = $query
                ->andWhere('p.country IN (:Country)')
                ->setParameter('Country', $search->Country);

        }

          if (!empty($search->Skills)) {
            $query = $query
                ->andWhere('c.id IN (:Skills)')
                ->setParameter('Skills', $search->Skills);
        }
         
   
    return $query->getQuery()->getResult();
    }




/** 
     * 
     * @return jobs[]
     */
    public function findSearchArchive(SearchData $search): array
    { 
         $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
             ->orderBy('p.id', 'DESC')
             ->Where('p.title != :test')
             ->setParameter('test', 609679)
             ->Where('p.status = :test')
             ->setParameter('test', 'nonActif')
           
            ->join('p.skills', 'c');
              if ($search->status=="Actif") {
            $query = $query
              ->andWhere('p.ExpiredAt > :date')
            ->setParameter('date', new \DateTime());
       
        }

          if ($search->status=="Expiré") {
            $query = $query
              ->andWhere('p.ExpiredAt < :date')
            ->setParameter('date', new \DateTime());
       
        }

        if (!empty($search->Name)) {
            $query = $query
                ->andWhere('p.title LIKE :Name')
                ->setParameter('Name', "%{$search->Name}%");
        }
        if (!empty($search->Title)) {
            $query = $query
                ->andWhere('p.id IN (:Title)')
                ->setParameter('Title', $search->Title);
        }
        if ((!empty($search->startdate))AND(!empty($search->enddate)) ) {
            $query = $query
                ->andWhere('p.CreatedAt BETWEEN (:startdate) AND (:enddate)')
                ->setParameter('startdate', $search->startdate)
                ->setParameter('enddate', $search->enddate);
        }
         
         if (!empty($search->TypeJobs)) {
            $query = $query
                ->andWhere('p.typeid IN (:TypeJobs)')
                ->setParameter('TypeJobs', $search->TypeJobs);
        }
         if (!empty($search->employer)) {
            $query = $query
                ->andWhere('p.user IN (:employer)')
                ->setParameter('employer', $search->employer);
        }
        if (!empty($search->Experience)) {
            $query = $query
                ->andWhere('p.exp IN (:Experience)')
                ->setParameter('Experience', $search->Experience);

        }
         if (!empty($search->Country)) {
            $query = $query
                ->andWhere('p.country IN (:Country)')
                ->setParameter('Country', $search->Country);

        }

          if (!empty($search->Skills)) {
            $query = $query
                ->andWhere('c.id IN (:Skills)')
                ->setParameter('Skills', $search->Skills);
        }
         
   
    return $query->getQuery()->getResult();
    }
      /** 
     * 
     * @return jobs[]
     */
    public function SearchForJob(SearchData1 $search): array
    { 
         $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
             ->orderBy('p.id', 'DESC')
             ->Where('p.title != :test')
             ->setParameter('test', 609679)
             ->Where('p.status != :test')
             ->setParameter('test', 'nonActif')
            ->andWhere('p.ExpiredAt > :date')
            ->setParameter('date', new \DateTime())
            ->join('p.skills', 'c')
            ->andWhere('p.ExpiredAt > :date')
            ->setParameter('date', new \DateTime());

        if (!empty($search->Name)) {
            $query = $query
                ->andWhere('p.title LIKE :Name or c.title Like :Skills')
                ->setParameter('Name', "%{$search->Name}%")     
                ->setParameter('Skills', "%{$search->Name}%")
                 ->andWhere('p.ExpiredAt > :date')
            ->setParameter('date', new \DateTime());


                ;
        }
          if (!empty($search->TypeJobs)) {
            $query = $query
                ->andWhere('p.typeid IN (:TypeJobs)')
                ->setParameter('TypeJobs', $search->TypeJobs);
        }
      
          
      
         
   
    return $query->getQuery()->getResult();
    }
      public function findForDashboard()
    { 
         $query = $this
            ->createQueryBuilder('p')
             ->orderBy('p.id', 'DESC')
             ->Where('p.title != :test')
             ->setParameter('test', 609679)
             ->Where('p.status != :test')
             ->setParameter('test', 'nonActif');
    
      
          
      
         
   
    return $query->getQuery()->getResult();
    }

//    /**
//     * @return Jobs[] Returns an array of Jobs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Jobs
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
