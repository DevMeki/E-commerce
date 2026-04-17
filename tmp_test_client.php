<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/tests/TestCase.php';

use Tests\TestCase;

class TmpTestClient extends TestCase
{
    public function runTest(): void
    {
        $this->setUp();
        echo 'Route login exists: '.(\Illuminate\Support\Facades\Route::has('login') ? 'yes' : 'no')."\n";
        echo 'Route count: '.count(\Illuminate\Support\Facades\Route::getRoutes())."\n";
        $response = $this->get('/login');
        $content = $response->getContent();
        echo 'Status: '.$response->status()."\n";
        echo 'Content length: '.strlen($content)."\n";
        echo 'Body start:\n'.substr($content, 0, 500)."\n";
        echo 'Body contains login: '.(strpos($content, 'login') !== false ? 'yes' : 'no')."\n";
    }
}

$test = new TmpTestClient('runTest');
$test->runTest();
