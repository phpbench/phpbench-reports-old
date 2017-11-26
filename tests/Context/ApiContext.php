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
     * @When I submit the :arg1 payload to the variants endpoint
     */
    public function iSubmitThePayloadToTheVariantsEndpoint($payloadName)
    {
        $this->submitPayload($payloadName, '/api/v1/suite');
    }

    /**
     * @When I submit the :payloadName payload to the iterations endpoint
     */
    public function iSubmitThePayloadToTheIterationsEndpoint($payloadName)
    {
        $this->submitPayload($payloadName, '/api/v1/iterations');
    }

    private function getClient(): Client
    {
        return $this->getSession()->getDriver()->getClient();
    }

    private function submitPayload($payloadName, string $url): void
    {
        $payload = file_get_contents(__DIR__ . '/payloads/' . $payloadName);
        $this->getClient()->request('POST', $url, [], [], [], (string) $payload);
    }
}
