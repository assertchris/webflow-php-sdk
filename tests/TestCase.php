<?php

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        $env = Dotenv::create(__DIR__."/..");
        $env->load();
    }
}
