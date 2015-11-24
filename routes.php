<?php

$this->mux->any('/', [$this->controllerNamespace . 'Test', 'testSimple'], ['id' => 'test.simple', 'constructor_args' => [$database, $view, $url]]);
$this->mux->any('/product/:name/:id/', [$this->controllerNamespace . 'Test', 'testParams'], ['id' => 'test.params', 'constructor_args' => [$database, $view, $url]]);

// example submux
// $subMux = new \Pux\Mux;
// $subMux->get('/bar/', [$this->controllerNamespace . 'Test', 'bar'], ['id' => 'foo.bar']);
// $this->mux->mount('/foo', $subMux);
