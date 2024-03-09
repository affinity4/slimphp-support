<?php declare(strict_types=1);

namespace Affinity4\SlimSupport\Http;

use Affinity4\SlimSupport\Facades\ResponseFactory;
use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * @var ResponseInterface
     */
    protected ResponseInterface $response;

    /**
     * @param string $content
     * @param integer $statusCode
     */
    public function __construct(protected $content = '', protected $statusCode = 200)
    {
        $this->response = tap(ResponseFactory::createResponse(), function ($response) {
            $response->getBody()->write($this->content);
        })->withStatus($this->statusCode);
    }

    /**
     * Get the Response
     *
     * @return ResponseInterface
     */
    public function get(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Make a json response
     *
     * @param array $data
     * @return self
     */
    public function json(array $data): self
    {
        $this->response = tap($this->get(), function($response) use ($data) {
            $payload = json_encode($data);
            $response->getBody()->write($payload);
        })->withHeader('Content-Type', 'application/json');

        return $this;
    }
}
