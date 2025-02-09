<?php

namespace App\Tests\Feature\Pact;

use Dotenv\Dotenv;
use GuzzleHttp\Psr7\Uri;
use PhpPact\Standalone\ProviderVerifier\Model\Config\CustomHeaders;
use PhpPact\Standalone\ProviderVerifier\Model\Source\Url;
use PhpPact\Standalone\ProviderVerifier\Model\VerifierConfig;
use PhpPact\Standalone\ProviderVerifier\Verifier;
use PHPUnit\Framework\TestCase;

class PactVerifyTest extends TestCase
{
    const PACT_PATH = __DIR__.'/../../../storage/app/pacts/';

    const CONTRACT_URL = 'https://raw.githubusercontent.com/rodrigopaco1986/nur-ddd-sales-front/refs/heads/main/storage/app/pacts/OrderServiceClient-OrderManagementAPI.json';

    const ENV_PATH = __DIR__.'/../../../';

    protected function setUp(): void
    {
        //exec("kill -9 $(lsof -t -i:8000) 2>/dev/null");

        //exec('nohup php artisan serve --host=127.0.0.1 --port=8000 > /dev/null 2>&1 &');

        //sleep(5);
    }

    protected function tearDown(): void
    {
        //exec("kill -9 $(lsof -t -i:8000) 2>/dev/null");
    }

    /**
     * This test will run after the web server is started.
     */
    public function test_pact_verify_consumer()
    {
        $apiToken = $this->getApiToken();

        $config = new VerifierConfig;
        $config->getProviderInfo()
            ->setName('OrderManagementAPI') // Providers name to fetch.
            ->setHost('127.0.0.1')
            ->setPort(8000);

        $config->getProviderState()
            ->setStateChangeUrl(new Uri('http://127.0.0.1:8000/pact-state'));
        $config->setCustomHeaders(
            (new CustomHeaders)
                ->addHeader('Authorization', 'Bearer '.$apiToken)
        );

        if ($level = \getenv('PACT_LOGLEVEL')) {
            $config->setLogLevel($level);
        }

        $verifier = new Verifier($config);
        $urlContract = new Url;
        $urlContract->setUrl(new Uri(self::CONTRACT_URL));
        $verifier->addFile(self::PACT_PATH.'OrderServiceClient-OrderManagementAPI.json');
        //$verifier->addUrl($urlContract);

        $verifyResult = $verifier->verify();

        $this->assertTrue($verifyResult);
    }

    protected function getApiToken(): ?string
    {
        $dotenv = Dotenv::createImmutable(self::ENV_PATH);
        $dotenv->safeLoad();

        return getenv('API_TOKEN') ?: $_ENV['API_TOKEN'] ?? null;
    }
}
