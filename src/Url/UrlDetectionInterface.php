<?php
/**
 * 
 * @author : linhndh
 */

namespace Zalo\Url;

/**
 * Interface UrlDetectionInterface
 *
 * @package Zalo
 */
interface UrlDetectionInterface
{
    /**
     * Get the currently active URL.
     *
     * @return string
     */
    public function getCurrentUrl();
}
