<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Article;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

/**
 * Class LoadArticleData.
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2015-2016, Artevelde University College Ghent
 */
class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    const COUNT = 3;

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

        $article = new Article();
        $em->persist($article); // Manage Entity for persistence.
        $article
            ->setTitle('Test Artikel')
            ->setBody($faker->paragraph(3))
            ->setUser($this->getReference('TestUser')); // Get reference from a previous Data Fixture.

        for ($userCount = 0; $userCount < LoadUserData::COUNT; ++$userCount) {
            for ($articleCount = 0; $articleCount < self::COUNT; ++$articleCount) {
                $article = new Article();
                $em->persist($article); // Manage Entity for persistence.
                $article
                    ->setTitle($faker->sentence(3))
                    ->setBody($faker->paragraph(3))
                    ->setUser($this->getReference("TestUser-${userCount}")); // Get reference from a previous Data Fixture.
            }
        }

        $em->flush(); // Persist all managed Entities.
    }
}
