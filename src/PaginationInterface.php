<?php

namespace Mwyatt\Core;

interface PaginationInterface
{
	public function __construct(\Mwyatt\Core\UrlInterface $url);
	public function setMaxPerPage($value);
	public function setPossiblePages();
	public function getLimit($end = false);
	public function generate($pageCurrent, $totalRows);
	public function getSummaryText();
}
