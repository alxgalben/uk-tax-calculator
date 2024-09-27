<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\TaxBand;

class TaxBandFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $band1 = new TaxBand();
        $band1->setLowerLimit(0)
              ->setUpperLimit(5000)
              ->setRate(0);
        $manager->persist($band1);

        $band2 = new TaxBand();
        $band2->setLowerLimit(5000)
              ->setUpperLimit(20000)
              ->setRate(20);
        $manager->persist($band2);

        $band3 = new TaxBand();
        $band3->setLowerLimit(20000)
              ->setUpperLimit(null)
              ->setRate(40);
        $manager->persist($band3);

        $manager->flush();
    }
}
