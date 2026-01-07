<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\RoomClass;
use App\Entity\Room;
use App\Entity\Client;
use App\Entity\Reservation;
use App\Entity\Payment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // --- Categories
        $catSingle = (new Category())
            ->setName('Single')
            ->setDescription('Chambre pour 1 personne');
        $catDouble = (new Category())
            ->setName('Double')
            ->setDescription('Chambre pour 2 personnes');
        $catSuite = (new Category())
            ->setName('Suite')
            ->setDescription('Suite confortable');
        $manager->persist($catSingle);
        $manager->persist($catDouble);
        $manager->persist($catSuite);

        // --- Room classes
        $rcEconomy = (new RoomClass())
            ->setName('Economy')
            ->setDescription('Budget');
        $rcDeluxe = (new RoomClass())
            ->setName('Deluxe')
            ->setDescription('Confort supérieur');
        $manager->persist($rcEconomy);
        $manager->persist($rcDeluxe);

        // --- Rooms
        $room101 = (new Room())
            ->setNumber('101')
            ->setPrice(45.0)
            ->setDescription('Chambre simple économique')
            ->setAvailable(true)
            ->setCategory($catSingle)
            ->setRoomClass($rcEconomy);
        $room102 = (new Room())
            ->setNumber('102')
            ->setPrice(80.0)
            ->setDescription('Chambre double confortable')
            ->setAvailable(true)
            ->setCategory($catDouble)
            ->setRoomClass($rcDeluxe);
        $room201 = (new Room())
            ->setNumber('201')
            ->setPrice(150.0)
            ->setDescription('Suite luxueuse')
            ->setAvailable(true)
            ->setCategory($catSuite)
            ->setRoomClass($rcDeluxe);

        $manager->persist($room101);
        $manager->persist($room102);
        $manager->persist($room201);

        // --- Clients
        $client1 = new Client();
        if (method_exists($client1, 'setFullName')) $client1->setFullName('Said Ben');
        if (method_exists($client1, 'setCin')) $client1->setCin('A123456');
        if (method_exists($client1, 'setEmail')) $client1->setEmail('said@example.com');
        if (method_exists($client1, 'setPhone')) $client1->setPhone('0600000001');

        $client2 = new Client();
        if (method_exists($client2, 'setFullName')) $client2->setFullName('Sara Ali');
        if (method_exists($client2, 'setCin')) $client2->setCin('B987654');
        if (method_exists($client2, 'setEmail')) $client2->setEmail('sara@example.com');
        if (method_exists($client2, 'setPhone')) $client2->setPhone('0600000002');

        $manager->persist($client1);
        $manager->persist($client2);

        // --- Reservation (use \DateTime)
        $start = new \DateTime('tomorrow');
        $end = (clone $start)->modify('+3 days');

        // calculate days difference (integer)
        $diffDays = (int)$start->diff($end)->format('%a');
        $totalPrice = $diffDays * $room101->getPrice();

        $reservation = new Reservation();
        if (method_exists($reservation, 'setClient')) $reservation->setClient($client1);
        if (method_exists($reservation, 'setRoom')) $reservation->setRoom($room101);
        if (method_exists($reservation, 'setStartDate')) $reservation->setStartDate($start);
        if (method_exists($reservation, 'setEndDate')) $reservation->setEndDate($end);
        if (method_exists($reservation, 'setTotalPrice')) $reservation->setTotalPrice($totalPrice);

        $manager->persist($reservation);

        // --- Payment (use \DateTime)
        $payment = new Payment();
        if (method_exists($payment, 'setAmount')) $payment->setAmount($reservation->getTotalPrice());
        if (method_exists($payment, 'setMethod')) $payment->setMethod('cash');
        if (method_exists($payment, 'setDate')) $payment->setDate(new \DateTime());
        if (method_exists($payment, 'setReservation')) $payment->setReservation($reservation);

        $manager->persist($payment);

        // --- Admin user
        $admin = new User();
        if (method_exists($admin, 'setEmail')) $admin->setEmail('admin@example.com');
        // set name if available (some User entities have a name field required)
        if (method_exists($admin, 'setName')) $admin->setName('Admin');
        // or fullName
        if (method_exists($admin, 'setFullName')) $admin->setFullName('Admin');

        $hashed = $this->passwordHasher->hashPassword($admin, 'admin');
        if (method_exists($admin, 'setPassword')) $admin->setPassword($hashed);
        if (method_exists($admin, 'setRoles')) $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();
    }
}
