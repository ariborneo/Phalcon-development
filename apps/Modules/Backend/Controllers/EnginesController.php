<?php
namespace Modules\Backend\Controllers;

use Models\Currency;
use Models\Engines;
use Modules\Backend\Forms;
use Phalcon\Mvc\View;
use Uploader\Uploader;

/**
 * Class EnginesController
 * @package    Backend
 * @subpackage    Modules\Backend\Controllers
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @filesource /apps/Modules/Backend/Controllers/EnginesController.php
 */
class EnginesController extends ControllerBase
{
    /**
     * Controller name
     * @use for another Controllers to set views , paths
     * @const
     */
    const NAME = 'Engines';

    /**
     * Cache key
     * @use for every action
     * @access public
     */
    public $cacheKey = false;

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
     * @return null
     */
    public function indexAction()
    {
        $title = ucfirst(self::NAME);
        $this->tag->prependTitle($title);

        // add crumb to chain (name, link)

        $this->_breadcrumbs->add($title);

        // get all records

        $engines = (new Engines())->get();

        $this->view->setVars([
            'items' => $engines,
            'title' => $title,
        ]);
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        try {
            // handling POST data
            if ($this->request->isGet()) {

                $id = $this->dispatcher->getParams()[0];

                $engines = (new Engines())->setId($id);

                if (!$engines->delete()) {
                    // the store failed, the following message were produced
                    foreach ($engines->getMessages() as $message)
                        $this->flashSession->error((string)$message);
                } else // saved successfully
                    $this->flashSession->success('The engine was successfully deleted!');

                if ($this->_logger)
                    $this->_logger->log('Delete engine #' . $id . ' by ' . $this->request->getClientAddress());

                // forward does not working correctly with this  action type
                // by the way this handle need to remove in another action (
                return
                    $this->response->redirect([
                        'for' => 'dashboard-full',
                        'controller' => $this->router->getControllerName(),
                    ]);
            }
        } catch (\Phalcon\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Shows the view to create (edit) engine
     */
    public function assignAction()
    {

        $id = $this->dispatcher->getParams();

        // check "edit" or "new" action in use
        $engine = (empty($id) === true) ? new Engines() : Engines::findFirst($id[0]);

        if (!$engine instanceof Engines)
            return $this->response->redirect([
                'for' => 'dashboard-full',
                'controller' => $this->router->getControllerName(),
                'action' => $this->router->getActionName()
            ]);


        try {

            // handling POST data
            if ($this->request->isPost()) {

                $engines =
                    $engine
                        ->setName($this->request->getPost('name'))
                        ->setDescription($this->request->getPost('description'), null, '')
                        ->setHost($this->request->getPost('host'))
                        ->setCode($this->request->getPost('code'))
                        ->setCurrencyId($this->request->getPost('currency_id', null, 1))
                        ->setStatus($this->request->getPost('status', null, 0));

                // check uploaded logo (if exist)

                if($this->request->hasFiles() !== false) {

                    echo 'Files exist<br><br>';
                    // call uploader

                    $uploader = $this->di->get('uploader');

                    $uploader->setRules([
                        'directory' =>  DOCUMENT_ROOT.'/files',
                        'minsize'   =>  [10],
                        'maxsize'   =>  1000000,
                        'hash'      =>  'crc32',
                        'sanitize'  =>  true,
                        'mimes'     =>  [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                        ],
                        'extensions'     =>  [
                            'gif',
                            'jpeg',
                            'jpg',
                            'png',
                        ],
                    ]);

                    $uploader->addFilters([
                        new \Uploader\Filters\Basic([
                            'extensions'     =>  [
                                'gif',
                                'jpeg',
                                'jpg',
                                'png',
                            ],
                            'mimes'     =>  [
                                'image/gif',
                                'image/jpeg',
                                'image/png',
                            ],
                        ]),
                        new \Uploader\Filters\Basic([
                            'extensions'     =>  [
                                'gif',
                                'jpeg',
                                'jpg',
                                'png',
                            ],
                            'mimes'     =>  [
                                'image/gif',
                                'image/jpeg',
                                'image/png',
                            ],
                        ])
                    ]);

                    if($uploader->isValid() === true) {

                        echo 'Uploader valid<br>';

                        //var_dump($uploader->move());

                    }
                    else {
                        //var_dump('Errors', $uploader->getErrors());
                    }
                }


                exit('Close');

                if (!$engines->save()) {

                    // the store failed, the following message were produced
                    foreach ($engines->getMessages() as $message)
                        $this->flashSession->error((string)$message);

                    // forward does not working correctly with this  action type
                    // by the way this handle need to remove in another action (
                    return
                        $this->response->redirect([
                            'for' => 'dashboard-full',
                            'controller' => $this->router->getControllerName(),
                            'action' => $this->router->getActionName()
                        ]);
                } else {

                    // saved successfully
                    if (empty($id) === true)
                        $this->flashSession->success('The engine was successfully added!');
                    else
                        $this->flashSession->success('The engine was successfully updated!');

                    if ($this->_logger)
                        $this->_logger->log('Engine assigned by ' . $this->request->getClientAddress());

                    // forward does not working correctly with this  action type
                    // by the way this handle need to remove in another action (
                    return
                        $this->response->redirect([
                            'for' => 'dashboard-full',
                            'controller' => $this->router->getControllerName(),
                        ]);
                }
            }

            // build meta data
            $title = (empty($id) === true) ? 'Add' : 'Edit';
            $this->tag->prependTitle($title . ' - ' . self::NAME);

            // add crumb to chain (name, link)
            $this->_breadcrumbs->add(self::NAME, $this->url->get(['for' => 'dashboard-controller', 'controller' => 'engines']))
                ->add($title);

            // set variables output to view
            $this->view->setVars([
                'title' => $title,
                'form' => (new Forms\EngineForm(null, [
                    'currency' => Currency::find(),
                    'default' => (empty($id) === true) ? null : $engine
                ]))
            ]);
        } catch (\Phalcon\Exception $e) {
            echo $e->getMessage();
        }
    }
}

