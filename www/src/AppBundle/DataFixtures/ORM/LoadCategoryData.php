<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Traits\ContainerTrait;
use AppBundle\Traits\PasswordTrait;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Class LoadCategoryData.
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2015-2016, Artevelde University College Ghent
 */
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    const COUNT = 5;
    const COUNT_CHILDREN = 2;

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

        for ($categoryCount = 0; $categoryCount < self::COUNT; ++$categoryCount) {
            $category = new Category();
            $em->persist($category);
            $category
                ->setName($faker->word());
            for ($childrenCount = 0; $childrenCount < self::COUNT_CHILDREN; ++$childrenCount) {
                $childCategory = new Category();
                $em->persist($childCategory);
                $childCategory
                    ->setParent($category)
                    ->setName($faker->word());
            }
        }

        $em->flush(); // Persist all managed Entities.
    }
}
