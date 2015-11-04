<?php

namespace AppBundle\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait ContainerTrait
{
    /**
     * @var ContainerInterface|null
     */
    protected $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface|null
     */
    public function getContainer()
    {
        return $this->container;
    }
}