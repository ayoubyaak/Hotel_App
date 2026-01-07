<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function isRoomAvailable(
        int $roomId,
        \DateTimeInterface $start,
        \DateTimeInterface $end
    ): bool
    {
        $qb = $this->createQueryBuilder('r');

        $qb->select('COUNT(r.id)')
            ->where('r.room = :room')
            ->andWhere('r.startDate < :end')
            ->andWhere('r.endDate > :start')
            ->setParameter('room', $roomId)
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        return (int) $qb->getQuery()->getSingleScalarResult() === 0;
    }
}
