<?php
/** For more info about namespaces plase @see http://php.net/manual/en/language.namespaces.importing.php */
namespace Mini\Core;

use Exception;
use Mini\Controller\AbstractController;
use Mini\Controller\ErrorController;
use Mini\Entity\UserEntity;
use Mini\Renderer\DefaultRenderer;
use Mini\Renderer\RendererInterface;
use Mini\Libs\Helper;
use Mini\Repository\UserRepository;

/**
 * Class Application
 * @package Mini\Core
 *

 * @property-read Router $router
 * @property-read Authenticator $auth
 * @property-read UserEntity $user
 * @property string $view
 * @property-read array $viewData
 * @property-read RendererInterface $renderer
 */
class Application
{
	/** @var Router */
	private $router;

	/** @var Authenticator */
	private $auth;

	/** @var UserEntity */
	private $user;

	/** @var string La vue qui sera appeler */
	public $view;

	/** @var array tableau de variable donné à la vue */
	private $viewData = [];

	/** @var RendererInterface */
	private $renderer;

	/**
	 * @param $name
	 * @return mixed
	 * @throws Exception
	 */
	public function __get($name) {
		if(property_exists($this,$name))
			return $this->$name;
		else
			throw new Exception("Propriété non définie ou inaccessible");
	}

	public function __isset($name) {
		return property_exists($this, $name) && isset($this->$name);
	}


    /**
     * "Start" the application:
     * Analyze the URL elements and calls the according controller/method or the fallback
     */
    public function __construct()
    {
    	$this->setRenderer(new DefaultRenderer());
    	$this->auth = new Authenticator();
    	$repoUser = new UserRepository();
    	$this->user = $repoUser->getById($this->auth->getUserId()) ?: new UserEntity();

		$this->router = new Router($_GET['url']??null);
		$this->view = $this->view = $this->router->controllerUrl.'/'.$this->router->actionUrl;


        // check for controller
        if (class_exists($this->router->controller)) {

            $controller = new $this->router->controller($this);

            if (method_exists($controller, $this->router->action) &&
                is_callable(array($controller, $this->router->action))) {
                
            	$this->viewData = call_user_func_array([$controller, $this->router->action], $this->router->params) ?? [];

				$this->renderer->render($this);
				return;
            }
        }

        //unknown controller or unknown action : 404 page
		http_response_code(404);
		$this->viewData = (new ErrorController($this))->error404() ?? [];
		$this->view = 'error/index';
		$this->renderer->render($this);
    }

    /**
     * Get and split the URL
     */
    private function splitUrl()
    {
        if (isset($_GET['url'])) {

            // split URL
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            // Put URL parts into according properties
			$this->url_controller = trim($url[0] ?? self::DEFAULT_CONTROLLER);
			$this->url_action     = trim($url[1] ?? self::DEFAULT_ACTION);

            // Remove controller and action from the split URL
            unset($url[0], $url[1]);

            // Rebase array keys and store the URL params
            $this->url_params = array_values($url);

            // for debugging. uncomment this if you have problems with the URL
            //echo 'Controller: ' . $this->url_controller . '<br>';
            //echo 'Action: ' . $this->url_action . '<br>';
            //echo 'Parameters: ' . print_r($this->url_params, true) . '<br>';
        }
		else {
			$this->url_controller = self::DEFAULT_CONTROLLER;
			$this->url_action     = self::DEFAULT_ACTION;
		}
    }

	/**
	 * @param RendererInterface $renderer
	 */
	public function setRenderer(RendererInterface $renderer): void {
		$this->renderer = $renderer;
	}
}
