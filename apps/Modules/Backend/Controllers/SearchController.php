<?php
namespace Modules\Backend\Controllers;

use Phalcon\Exception;
use Phalcon\Mvc\View;

/**
 * Class SearchController
 * @package    Backend
 * @subpackage    Modules\Backend\Controllers
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @filesource /apps/Modules/Backend/Controllers/SearchController.php
 */
class SearchController extends ControllerBase
{
    /**
     * Controller name
     * @use for another Controllers to set views , paths
     * @const
     */
    const NAME = 'Search';

    /**
     * Cache key
     * @use for every action
     * @access public
     */
    public $cacheKey = false;

    /**
     * Query string
     * @access public
     */
    public $query = '';

    /**
     * Message string
     * @access public
     */
    public $message = '';

    /**
     * initialize() Initialize constructor
     * @access public
     * @return null
     */
    public function initialize()
    {
        parent::initialize();
        $this->tag->setTitle(' - ' . DashboardController::NAME);

        // create cache key
        $this->cacheKey = md5(\Modules\Backend::MODULE . self::NAME . $this->router->getControllerName() . $this->router->getActionName());

        $this->_breadcrumbs->add(DashboardController::NAME, $this->url->get(['for' => 'dashboard']));
    }

    /**
     * Get list of all engines
     * @use \Searcher
     * @return null
     */
    public function indexAction()
    {
        $title = ucfirst(self::NAME);
        $this->tag->prependTitle($title);

        // add crumb to chain (name, link)

        $this->_breadcrumbs->add($title);

        $this->query = $this->request->get('query', null, null);

        if ($this->request->isAjax() === true) {

            if(isset($this->query) && $this->query !== null) {

                try {
                    // call searcher instance
                    $searcher = $this->di->get('searcher');

                    // prepare models and fields to participate in search
                    $searcher->setFields([
                        '\Models\Engines'    =>    [
                            'name',
                            'description',
                            'host',
                        ],
                        '\Models\Users'    =>    [
                            'login',
                            'name',
                        ]
                    ])->setQuery($this->query);

                    $result = $searcher->run();
                }
                catch(\Searcher\Searcher\Factories\ExceptionFactory $e) {

                    echo $this->message = (string)$e->getMessage();

                }
                $this->view->setVar('title', $title. ' "'. $this->query.'"');
                $this->view->setVar('message', $this->message);

            }
            else {
                $this->view->setVar('title', $title);
            }
         }
    }
}

