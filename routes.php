<?php

$this->mux->get('/', [$this->controllerNamespace . 'Test', 'testSimple'], ['id' => 'test.simple']);
$this->mux->get('/product/:name/:id/', [$this->controllerNamespace . 'Test', 'testParams'], ['id' => 'test.params']);

// example submux
$subMux = new \Pux\Mux;
$subMux->get('/bar/', [$this->controllerNamespace . 'Test', 'bar'], ['id' => 'foo.bar']);
$this->mux->mount('/foo', $subMux);
