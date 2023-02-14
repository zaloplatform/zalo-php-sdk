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
    protected $accessToken = 'gHafLTJLq5oY4IPGrztJNwSI9bBImDSdv6vbJvVqa0Q59HnIgvhm0Bel2NR6iASboc4HV-NeuIJoSbbs_TpU58rPNMsTw-aHgbDcODRbqIwXS7zdiDll595j0sYIg8yMjZqTHikqXKQs4YKGcP-AP8eBCsk1fhyzZWWyF8x5hc7_RImD_QA5GDLcQJ3gsyrzvn1S7TYXz67g0b0UoQ71HDekRnN9W-zd-J9xPikcsH_q2rmHqANxVSy6LmtWmwLRqMq21iNwyq-qD7uLXvY6HwGqCa-Pl84MKsX5_C4usClRL0';
    /**
     * @var Zalo
     */
    protected $zalo;

    public function __construct()
    {
        try {
            $config = array(
                'app_id' => '3980813874004753450',
                'app_secret' => '4B52V2ARl5627KIMFKC6'
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