<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Interval;
use App\Exception\IntervalRepositoryException;
use App\Exception\NotFoundException;
use App\Model\ClientInput;
use App\Model\IntervalInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class IntervalRepository extends ServiceEntityRepository
{

    private LoggerInterface $logger;

    public function __construct(
        ManagerRegistry $registry,
        LoggerInterface $logger
    )
    {
        parent::__construct($registry, Interval::class);

        $this->logger = $logger;
    }

    /**
     * @throws NotFoundException
     */
    public function getByClientInput(ClientInput $clientInput): array
    {
        $qb = $this->createQueryBuilder('i');

        $records =  $qb
            ->where('i.min <= :minValue')
            ->andWhere('i.max >= :maxValue')
            ->setParameter('minValue', $clientInput->getInput())
            ->setParameter('maxValue', $clientInput->getInput())
            ->getQuery()
            ->getResult();

        if (!$records) {
            throw new NotFoundException('Can not find records by given value');
        }

        return $records;
    }


    /**
     * @param IntervalInterface[] $intervals
     *
     * @throws IntervalRepositoryException
     */
    public function saveMultiple(array $intervals): void
    {
        try {

            foreach ($intervals as $interval) {
                $this->getEntityManager()->persist($interval);
            }
            $this->getEntityManager()->flush();

        } catch (Exception $e) {

            $this->logger->error(
                'Can not save intervals',
                [
                    'exception'  => $e,
                    '__METHOD__' => __METHOD__
                ]
            );

            throw new IntervalRepositoryException('Can not save application');
        }
    }
}
