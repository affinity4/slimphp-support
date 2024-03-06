<?php declare(strict_types=1);

use SlimFacades\Http\Response;

/**
 * Response helper function
 *
 * @param string $content
 * @param integer $statusCode
 *
 * @return \Psr\Http\Message\ResponseInterface
 */
function response(string $content, int $statusCode)
{
    return new Response($content, $statusCode);
}
