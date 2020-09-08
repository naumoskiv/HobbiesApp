<?php

namespace app\core;

use app\core\db\Database;
use app\core\db\DbModel;
use app\models\User;

class Application
{
    public static string $ROOD_DIR;
    public string $userClass;
    public string $layout = 'main';
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public static Application $app;
    public ?Controller $controller = null;
    public ?UserModel $user;
    public View $view;

    public static function isGuest()
    {
        return !self::$app->user;
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function __construct($rootPath, array $config) {
        self::$ROOD_DIR = $rootPath;
        self::$app = $this;
        $this->userClass = $config['userClass'];
        $this->request = new Request();
        $this->respose = new Response();
        $this->session = new Session();
        $this->view = new View();
        $this->router = new Router($this->request, $this->respose);
        $this->db = new Database($config['db']);
        $primaryValue = $this->session->get('user');

        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass->findOne([$primaryKey => $primaryValue]);
        }
        else {
            $this->user = null;
        }

    }

    public  function run(){
        try {
            echo $this->router->resolve();
        }catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }


}