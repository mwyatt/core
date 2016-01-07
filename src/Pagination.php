<?php

namespace Mwyatt\Core;

class Pagination implements \Mwyatt\Core\PaginationInterface
{


    /**
     * @var object
     */
    protected $url;


    /**
     * page you are currently on, defaulted to 1
     * @var integer
     */
    protected $pageCurrent = 1;


    /**
     * max items per page
     * @var integer
     */
    protected $maxPerPage = 10;


    /**
     * total results found
     * @var int
     */
    protected $totalRows;


    /**
     * the ceil for pages which could be
     * @var int
     */
    protected $possiblePages;


    public function __construct(\Mwyatt\Core\UrlInterface $url, $pageCurrent, $totalRows)
    {
        $this->url = $url;
        $this->totalRows = $totalRows;
        $this->setPossiblePages();
        $this->setPageCurrent($pageCurrent);
    }


    /**
     * configurable from outside
     * @param int $value 
     */
    public function setMaxPerPage($value)
    {
        $this->maxPerPage = $value;
    }

   
    /**
     * possible pages found by using total rows and maximum per page
     */
    public function setPossiblePages()
    {
        $this->possiblePages = ceil($this->totalRows / $this->maxPerPage);
    }


    /**
     * get a limit array usable in an sql query
     * @param  boolean $end ?
     * @return array|int       
     */
    public function getLimit($end = false)
    {
        $bottom = ($this->maxPerPage * ($this->pageCurrent - 1));
        $top = $this->maxPerPage;
        if ($end === false) {
            return array($bottom, $top);
        }
        if ($end === 0) {
            return $bottom;
        }
        if ($end === 1) {
            return $top;
        }
    }


    /**
     * build the pagination array
     * @return array 
     */
    public function generate()
    {
        if ($this->possiblePages < 2) {
            return;
        }

        $pageCurrent = $this->pageCurrent;
        $pagination = $this->getPaginationContainer();

        // previous
        if ($pageCurrent > 1) {
            $pagination['previous'] = $this->getPaginationPage($this->urlBuildPageLink($pageCurrent - 1));
        }
        
        for ($index = 1; $index <= $this->possiblePages; $index ++) {
            $pagination['pages'][$index] = $this->getPaginationPage($this->urlBuildPageLink($index), ($pageCurrent == $index ? true : false));
        }

        // next
        if ($pageCurrent < $this->possiblePages) {
            $pagination['next'] = $this->getPaginationPage($this->urlBuildPageLink($pageCurrent + 1));
        }

        return $pagination;
    }


    protected function getPaginationContainer()
    {
        return [
            'previous' => null,
            'pages' => [],
            'next' => null
        ];
    }


    protected function getPaginationPage($url, $current = null)
    {
        return [
            'url' => $url,
            'current' => $current
        ];
    }


    /**
     * little text summary 'page 1 of 20'
     * @return string 
     */
    public function getSummaryText()
    {
        return 'page ' . $this->pageCurrent . ' of ' . $this->possiblePages;
    }
    

    /**
     * add the page to the query string
     * create an absolute url
     * @param  int $page 
     * @return string       absolute url
     */
    protected function urlBuildPageLink($page)
    {
        $url = $this->url;
        $urlBase = $url->generate() . $url->getPath();
        $queryParts = $url->getQueryArray();
        $queryParts['page'] = $page;
        return $urlBase . '?' . http_build_query($queryParts);
    }

        
    /**
     * page is set within the constraints of lowest and highest value
     * @param int $page 
     */
    protected function setPageCurrent($page)
    {

        // need both of these?
        $page += 0;
        $page = (int) $page;

        // cant be below 1
        $page = $page ? $page : 1;

        // cant be above possible
        if ($page > $this->possiblePages) {
            $page = $this->possiblePages;
        }

        $this->pageCurrent = $page;
    }
}
