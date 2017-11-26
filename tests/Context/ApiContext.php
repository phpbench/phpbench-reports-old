<?php

namespace Phpbench\Reports\Tests\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use PHPUnit\Framework\Assert;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\BrowserKit\Client;

class ApiContext extends RawMinkContext implements Context
{
    /**
     * @Given I post the following payload to :url:
     */
    public function iPostTheFollowingPayloadTo($url, PyStringNode $payload)
    {
        $this->getClient()->request('POST', $url, [], [], [], (string) $payload);
    }

    private function getClient(): Client
    {
        return $this->getSession()->getDriver()->getClient();
    }
}
