<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.3.4
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Event\Event;

/**
 * Error Handling Controller
 *
 * Controller used by ExceptionRenderer to render error responses.
 */
class ErrorController extends AppController
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('RequestHandler');
    }

    /**
     * beforeFilter callback.
     *
     * @param \Cake\Event\Event $event Event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(Event $event)
    {
        $origin = $this->request->header('Origin');
        if($this->request->header('CONTENT_TYPE') != "application/x-www-form-urlencoded; charset=UTF-8"){
              $this->request->env('CONTENT_TYPE', 'application/json');
        }
        $this->request->env('HTTP_ACCEPT', 'application/json');
        if (!empty($origin)) {
          $this->response->header('Access-Control-Allow-Origin', $origin);
        }

        if ($this->request->method() == 'OPTIONS') {
          $method  = $this->request->header('Access-Control-Request-Method');
          $headers = $this->request->header('Access-Control-Request-Headers');
          $this->response->header('Access-Control-Allow-Headers', $headers);
          $this->response->header('Access-Control-Allow-Methods', empty($method) ? 'GET, POST, PUT, DELETE' : $method);
          $this->response->header('Access-Control-Allow-Credentials', 'true');
          $this->response->header('Access-Control-Max-Age', '120');
          $this->response->send();
          die;
        }
        // die;
        $this->response->cors($this->request)
        ->allowOrigin(['*'])
        ->allowMethods(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'])
        ->allowHeaders(['X-CSRF-Token','token'])
        ->allowCredentials()
        ->exposeHeaders(['Link'])
        ->maxAge(300)
        ->build();
    }

    /**
     * beforeRender callback.
     *
     * @param \Cake\Event\Event $event Event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        $this->viewBuilder()->setTemplatePath('Error');
    }

    /**
     * afterFilter callback.
     *
     * @param \Cake\Event\Event $event Event.
     * @return \Cake\Http\Response|null|void
     */
    public function afterFilter(Event $event)
    {
    }
}
