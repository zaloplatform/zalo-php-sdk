<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Zalo\Zalo;
use Zalo\ZaloEndPoint;

class TestSocialAPI extends TestCase
{
    /**
     * @var string
     */
    protected $accessToken = 'put your access token';
    /**
     * @var Zalo
     */
    protected $zalo;

    public function __construct()
    {
        try {
            $config = array(
                'app_id' => 'put your app id',
                'app_secret' => 'put your app secret key'
            );

            $this->zalo = new Zalo($config);
        } catch (\Exception $e) {
            print_r('Initial test fail ' . $e->getMessage());
            exit;
        }
    }

    public function testGetUserProfileAPI()
    {
        try {
            $params = ['fields' => 'id,name,picture'];
            $response = $this->zalo->get(ZaloEndPoint::API_GRAPH_ME, $this->accessToken, $params);
            $result = $response->getDecodedBody();
            print_r('testGetUserProfileAPI: ' . PHP_EOL);
            print_r($result);

            self::assertTrue(true);
        } catch (\Exception $e) {
            self::fail('Zalo SDK returned an error: ' . $e->getMessage());
        }
    }
}