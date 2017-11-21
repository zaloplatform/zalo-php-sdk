<?php
/**
 * Zalo © 2017
 *
 */

namespace Zalo;

use Zalo\Exceptions\ZaloSDKException;

/**
 * Class ZaloOA
 *
 * @package Zalo
 */
class ZaloOA implements \Serializable
{
    /**
     * @var string The oa ID.
     */
    protected $id;

    /**
     * @var string The oa secret.
     */
    protected $secret;

    /**
     * @param string $id
     * @param string $secret
     *
     * @throws ZaloSDKException
     */
    public function __construct($id, $secret)
    {
        if (!is_string($id)
          // Keeping this for BC. Integers greater than PHP_INT_MAX will make is_int() return false
          && !is_int($id)) {
            throw new ZaloSDKException('The "oaid" must be formatted as a string since many oa ID\'s are greater than PHP_INT_MAX on some systems.');
        }
        // We cast as a string in case a valid int was set on a 64-bit system and this is unserialised on a 32-bit system
        $this->id = (string) $id;
        $this->secret = $secret;
    }

    /**
     * Returns the oa ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the oa secret.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Serializes the ZaloApp entity as a string.
     *
     * @return string
     */
    public function serialize()
    {
        return implode('|', [$this->id, $this->secret]);
    }

    /**
     * Unserializes a string as a ZaloApp entity.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list($id, $secret) = explode('|', $serialized);

        $this->__construct($id, $secret);
    }
}
