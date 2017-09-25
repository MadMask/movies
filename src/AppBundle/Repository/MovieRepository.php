<?php

namespace AppBundle\Repository;

/**
 * MovieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MovieRepository extends \Doctrine\ORM\EntityRepository
{
    public function findUpcomingMovies($resultsNumber = 50)
    {
        //version dql
        $dql = "SELECT m
                FROM AppBundle:Movie m
                WHERE m.year >= :now
                ORDER BY m.year";

        $query = $this->getEntityManager()->createQuery($dql);

        $query->setParameters(["now" => new \DateTime()]);

        $query->setMaxResults($resultsNumber);
        $movies = $query->getResult();

        return $movies;
    }

    public function findMoviesListAdmin($resultsNumber = 50)
    {
        //version dql
        $dql = "SELECT m
                FROM AppBundle:Movie m
                ORDER BY m.dateCreated DESC";

        $query = $this->getEntityManager()->createQuery($dql);

        $query->setMaxResults($resultsNumber);
        $movies = $query->getResult();

        return $movies;
    }

}
