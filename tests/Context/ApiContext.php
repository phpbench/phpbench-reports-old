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
        $response = $this->getClient()->getResponse();
        Assert::assertEquals('[]', $response->getContent());
    }

    /**
     * @When I submit the :payloadName payload to the iterations endpoint
     */
    public function iSubmitThePayloadToTheIterationsEndpoint($payloadName)
    {
        $this->submitPayload($payloadName, '/api/v1/iterations');
        $response = $this->getClient()->getResponse();
        Assert::assertEquals('[]', $response->getContent());
    }

    /**
     * @When I submit with an invalid API key
     */
    public function iSubmitWithAnInvalidApiKey()
    {
        $this->submitPayload('variant1.json', '/api/v1/iterations', 'invalid-api-key');
    }

    /**
     * @Then I should receive a link to the results
     */
    public function iShouldReceiveALinkToTheResults()
    {
        $response = $this->getClient()->getResponse();
        $response = json_decode($response, true);
    }

    private function getClient(): Client
    {
        return $this->getSession()->getDriver()->getClient();
    }

    private function submitPayload($payloadName, string $url, $apiKey = 'valid-api-key'): void
    {
        $payload = file_get_contents(__DIR__ . '/payloads/' . $payloadName);
        $this->getClient()->request('POST', $url, [], [], [
            'HTTP_X-API-Key' => $apiKey
        ], (string) $payload);
    }
}
