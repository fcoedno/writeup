<?php

declare(strict_types=1);

namespace Abdurrahmanriyad\Writeup\Tests;

use Abdurrahmanriyad\Writeup\WriteupRequestMiddleware;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Log;

class WriteupRequestMiddlewareTest extends TestCase
{
    /**
     * @environment-setup setConfig
     */
    public function testShouldLogRequestSize()
    {
        $body = 'foobar';
        $request = new Request([], [], [], [], [], [], $body);

        $expectedSize = strlen($body);

        Log::shouldReceive('info')
            ->once()
            ->with("Writeup Request", \Mockery::on(function ($data) use ($expectedSize) {
                $actualSize = $data['size'] ?? null;
                return $actualSize === $expectedSize;
            }));

        $middleware = new WriteupRequestMiddleware();
        $middleware->handle($request, function () {});
    }

    protected function setConfig($app)
    {
        $app->config->set('writeup.request_log.size', true);
    }
}
