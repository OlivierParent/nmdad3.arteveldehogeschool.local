<?php

namespace AppBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelDictionary;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    use KernelDictionary;

    private $logger;
    private $security;
    private $session;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenario()
    {
        // Find containers with: $ php app/console container:debug
        $container = $this->getKernel()->getContainer(); // Requires KernelDictionary trait.
        $this->logger = $container->get('logger');
        $this->security = $container->get('security.context');
        $this->session = $container->get('session');
    }

    /**
     * @Given the users are:
     */
    public function theUsersAre(TableNode $table)
    {
//        throw new PendingException();
    }
}
