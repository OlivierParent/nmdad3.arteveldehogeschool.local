<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData.
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2015-2016, Artevelde University College Ghent
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function hashPassword($user)
    {
        $passwordEncoder = $this->container->get('security.password_encoder');
        $encodedPassword = $passwordEncoder->encodePassword($user, $user->getSalt());
        $user->setPassword($encodedPassword);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1; // The order in which fixture(s) will be loaded.
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em)
    {
        $locale = 'nl_BE';
        $faker = Faker::create($locale);

        $user = new User();
        $em->persist($user); // Manage Entity for persistence.
        $user
            ->setFirstName('Test')
            ->setLastName('User')
            ->setUsername('TestUser')
            ->setPassword('TestUser');
        $this->hashPassword($user);
        $this->addReference('TestUser', $user); // Reference for the next Data Fixture(s).

        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $em->persist($user);
            $user
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setUsername($faker->userName)
                ->setPassword($faker->password());
            $this->hashPassword($user);
            $this->addReference("TestUser-${i}", $user); // Reference for the next Data Fixture(s).
        }

        $em->flush(); // Persist all managed Entities.
    }
}
