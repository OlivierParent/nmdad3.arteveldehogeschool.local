<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Image;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

/**
 * Class LoadImageData.
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2015-2016, Artevelde University College Ghent
 */
class LoadImageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3; // The order in which fixture(s) will be loaded.
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em)
    {
        $locale = 'nl_BE';
        $faker = Faker::create($locale);

        $image = new Image();
        $em->persist($image); // Manage Entity for persistence.
        $image
            ->setTitle('Arteveldehogeschool Logo')
            ->setUri('http://www.arteveldehogeschool.be/images/artev_e-signature_rgb_s.png')
            ->setUser($this->getReference('TestUser')); // Get reference from a previous Data Fixture.

        for ($i = 0; $i < 10; ++$i) {
            $image = new Image();
            $em->persist($image); // Manage Entity for persistence.
            $image
                ->setTitle($faker->sentence(3))
                ->setUri($faker->imageUrl())
                ->setUser($this->getReference("TestUser-${i}")); // Get reference from a previous Data Fixture.
        }

        $em->flush(); // Persist all managed Entities.
    }
}
