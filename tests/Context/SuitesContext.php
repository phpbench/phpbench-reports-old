<?php

namespace Phpbench\Reports\Tests\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

class SuitesContext extends RawMinkContext implements Context
{
    /**
     * @When I visit :arg1
     */
    public function iVisit($arg1)
    {
        $this->getSession()->visit($arg1);
    }

    /**
     * @Then I should see a list of suites
     */
    public function iShouldSeeAListOfSuites()
    {
        throw new PendingException();
    }
}
