<?php

$this->mux->any('/', [$this->controllerNamespace . 'People', 'all'], ['id' => 'people.all', 'view' => 'Person']);
$this->mux->any('/product/:name/:id/', [$this->controllerNamespace . 'Test', 'testParams'], ['id' => 'test.params']);

// example submux
// $subMux = new \Pux\Mux;
// $subMux->get('/bar/', [$this->controllerNamespace . 'Test', 'bar'], ['id' => 'foo.bar']);
// $this->mux->mount('/foo', $subMux);
