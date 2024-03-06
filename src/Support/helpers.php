<?php declare(strict_types=1);

use SlimFacades\Http\Response;
use SlimFacades\Support\HigherOrderTapProxy;

if (!function_exists('response')) {
    /**
     * Response helper function
     *
     * @param string $content
     * @param integer $statusCode
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    function response(string $content = '', int $statusCode = 200): Response
    {
        return new Response($content, $statusCode);
    }
}

if (!function_exists('tap')) {
    /**
     * Call the given Closure with the given value then return the value.
     *
     * @param  mixed  $value
     * @param  callable|null  $callback
     * @return mixed
     */
    function tap($value, $callback = null)
    {
        if (is_null($callback)) {
            return new HigherOrderTapProxy($value);
        }

        $callback($value);

        return $value;
    }
}

