<?php

/**
 * This file is part of the Purl package, a project by Jonathan H. Wage.
 *
 * (c) 2013 Jonathan H. Wage
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Purl;

/**
 * Parser interface.
 *
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 */
interface ParserInterface
{
    /**
     * @param string|\Purl\Url $url
     *
     * @return array $parsedUrl
     */
    public function parseUrl($url);
}
