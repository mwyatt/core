<?php

namespace Mwyatt\Core;

class Pagination extends \Mwyatt\Core\Data implements \Mwyatt\Core\PaginationInterface
{


	/**
	 * \Mwyatt\Core\Url
	 * @var object
	 */
	public $url;


	/**
	 * page you are currently on, defaulted to 1
	 * @var integer
	 */
	public $pageCurrent = 1;


	/**
	 * max items per page
	 * @var integer
	 */
	public $maxPerPage = 10;


	/**
	 * total results found
	 * @var int
	 */
	public $totalRows;


	/**
	 * the ceil for pages which could be
	 * @var int
	 */
	public $possiblePages;


	/**
	 * which is the prev page
	 * @var array
	 */
	public $previous;


	/**
	 * which is the next page
	 * @var array
	 */
	public $next;


	/**
	 * all the pages as assoc array
	 * @var array
	 */
	public $pages;
	

	/**
	 * inject dependencies
	 */
	public function __construct(\Mwyatt\Core\UrlInterface $url) {
		$this->url = $url;
	}


	public function setMaxPerPage($number)
	{
		return $this->maxPerPage = $number;
	}
	

	public function initialise()
	{
		if (! $this->getTotalRows()) {
			return;
		}

		// setup possible page count
        $this->setPossiblePages();

		// check validity
		$this->sanitizeUserPage();

        // set up the pagination array
		$this->setPagination();
	}


	public function getPageCurrent()
	{
		return $this->pageCurrent;
	}


	public function setPageCurrent($value)
	{
		$this->pageCurrent = $value;
	}


	public function getTotalRows()
	{
		return $this->totalRows;
	}


	public function setTotalRows($value)
	{
		$this->totalRows = $value;
	}


	public function setPossiblePages()
	{
		$this->possiblePages = ceil($this->totalRows / $this->maxPerPage);
	}


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


	public function getPossiblePages()
	{
		return $this->possiblePages;
	}


	/**
	 * constructs an array to allow the user to paginate
	 * possibly have 2 options, one with full pagination
	 * another with just next and previous
	 * @return array 
	 */
	public function setPagination()
	{

		// only 1 page, dont show
		if ($this->getPossiblePages() < 2) {
		    return;
		}

		// structure of object
		$data = new \StdClass();
		$data->previous = false;
		$data->pages = array();
		$data->next = false;

		// previous (if possible)
	    if ($this->getPageCurrent() > 1) {
			$page = new \StdClass();
			$page->url = $this->urlBuild($this->getPageCurrent() - 1);
	        $data->previous = $page;
	    }
		
		// page 1, 2, 3
	    for ($index = 1; $index <= $this->getPossiblePages(); $index ++) { 
			$page = new \StdClass();
	        $page->current = ($this->getPageCurrent() == $index ? true : false);
	        $page->url = $this->urlBuild($index);
	        $data->pages[$index] = $page;
	    }

	    // next only if possible
	    if ($this->getPageCurrent() < $this->getPossiblePages()) {
			$page = new \StdClass();
			// var_dump($this->getPageCurrent());
			// exit;
			$page->url = $this->urlBuild($this->getPageCurrent() + 1);
	        $data->next = $page;
	    }

	    // set
	    $this->setData($data);
	}


	public function getSummary()
	{
		return 'page ' . $this->getCurrentPage() . ' of ' . $this->getPossiblePages();
	}
	

    /**
     * returns a url without any queries except the page
     * number
     * @param  int $pageNumber 
     * @return string             url
     */
    public function urlBuild($key)
    {
    	$url = $this->url->generate();
    	$current = $this->url->getCache('current_sans_query');
    	$query = $this->url->getQuery();
		parse_str($query, $queryParts);
		$queryParts['page'] = $key;
    	$query = '?' . http_build_query($queryParts);
		return $current . $query;
    }

	
	/**
	 * Next Page
	 *
	 * @return string|false The array value or false if it does not exist
	 */	
	public function nextPage()
	{
		return $this->pageCurrent++;
	}


	public function getCurrentPage()
	{
		return $this->pageCurrent;
	}

	
	/**
	 * Check Page GET Variable
	 *
	 * @todo remove ability to add '-100' perhaps using regex match on '-'?
	 * @return true|false The page GET value
	 */	
	public function sanitizeUserPage($valid = false)
	{

		// page must exist
		if (! array_key_exists('page', $_GET)) {
			return;
		}

		// convert to int
		$page = $_GET['page'];
		$page = (int) $page;

		// under 1 or above possible is invalid
		if ($page < 1 || $page > $this->getPossiblePages()) {
			return;
		}

		// passed all checks
		$this->setPageCurrent($_GET['page']);
	}
}
