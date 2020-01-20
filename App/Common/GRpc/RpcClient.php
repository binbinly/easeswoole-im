<?php


namespace App\Common\GRpc;


use App\Utility\JsonHelper;
use EasySwoole\Component\Singleton;
use Pb\Easymicro\EMServiceClient;
use SensioLabs\Consul\Client;

class RpcClient
{
    use Singleton;

    protected $client = null;

    private function __construct()
    {
        $client = new Client(['base_uri' => '192.168.1.250:8500']);
        $ret = $client->get('/v1/agent/health/service/name/djBmBridge1');

        $body = $ret->getBody();

        $services = JsonHelper::decode($body);

        foreach($services as $service) {
            $this->client[] = new EMServiceClient($service['Service']['Address'].':'.$service['Service']['Port'], [
                'credentials' => \Grpc\ChannelCredentials::createInsecure(),
                'timeout' => 1000,
            ]);
        }

    }

    public function getClient() {
        $key = array_rand($this->client,1);
        return $this->client[$key];
    }
}