<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        \Illuminate\Support\Facades\Http::fake([
            'https://www.theregister.com/*' => \Illuminate\Support\Facades\Http::response('<?xml version="1.0" encoding="utf-8"?><feed xmlns="http://www.w3.org/2005/Atom"></feed>', 200),
        ]);
    }
}
