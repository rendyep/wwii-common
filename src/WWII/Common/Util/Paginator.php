<?php

namespace WWII\Common\Util;

class Paginator extends \Doctrine\ORM\Tools\Pagination\Paginator
{
    protected $serviceManager;

    protected $routeManager;

    protected $maxItemPerPage = 20;

    protected $numberOfShownItem = 5;

    public function __construct(\WWII\Service\ServiceManagerInterface $serviceManager, \Doctrine\ORM\Query $query)
    {
        $this->serviceManager = $serviceManager;
        $this->routeManager = $serviceManager->get('RouteManager');

        parent::__construct($query);
    }

    public function setMaxItemPerPage($maxItemPerPage)
    {
        $this->maxItemPerPage = $maxItemPerPage;
    }

    public function getMaxItemPerPage()
    {
        return $this->maxItemPerPage;
    }

    public function setNumberOfShownItem($numberOfShownItem)
    {
        if ($numberOfShownItem % 2 === 0) {
            throw new \Exception('Number of shown item must be odd.');
        }

        $this->numberOfShownItem = $numberOfShownItem;
    }

    public function getNumberOfShownItem()
    {
        return $this->numberOfShownItem;
    }

    public function getNumberOfPages()
    {
        return ceil($this->count() / $this->maxItemPerPage);
    }

    public function getControl()
    {
        $currentPageNumber = $this->routeManager->getPage();

        $btnFirst = 1;
        $btnLast  = $this->getNumberOfPages();

        $controlFirst = $currentPageNumber - floor($this->numberOfShownItem / 2);
        $controlLast = $currentPageNumber + floor($this->numberOfShownItem / 2);

        if ($controlFirst < 1) {
            $controlFirst = 1;
            $controlLast = $this->numberOfShownItem;
        }

        if ($controlLast > $this->getNumberOfPages()) {
            $controlLast = $this->getNumberOfPages();
        }

        $strControl = '<nav class="pagination"><ul>'
            . '<li><a rel="first" href="' . $this->routeManager->generateRoute(array('page' => $btnFirst)) . '">&laquo;</a></li>';

        for ($i = $controlFirst; $i <= $controlLast; $i++) {
            $class = '';

            if ($i == $currentPageNumber) {
                $class = ' class="current"';
            }

            $strControl .= '<li><a' . $class . ' href="' . $this->routeManager->generateRoute(array('page' => $i)) . '">' . $i . '</a></li>';
        }

        $strControl .= '<li><a rel="last" href="' . $this->routeManager->generateRoute(array('page' => $btnLast)) . '">&raquo;</a></li>'
            . '</ul></nav>';

        return $strControl;
    }
}
