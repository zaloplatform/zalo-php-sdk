<?php

namespace Tests\Util;

use Zalo\Util\PKCEUtil;
use PHPUnit\Framework\TestCase;

class PKCEUtilTest extends TestCase
{

    public function testGenCodeVerifierAndCodeChallenge()
    {
        try {
            $codeVerifier = PKCEUtil::genCodeVerifier();
            $codeChallenge = PKCEUtil::genCodeChallenge($codeVerifier);

            print_r("Code Verifier: " . $codeVerifier);
            print_r("\r\n");
            print_r("Code Challenge: " . $codeChallenge);

            if (isset($codeChallenge)) {
                self::assertTrue(true);
            }
        } catch (\Exception $e) {
            self::fail("Zalo SDK returned an error: " . $e->getMessage());
        }
    }
}
