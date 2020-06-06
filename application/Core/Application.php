<?php
/** For more info about namespaces plase @see http://php.net/manual/en/language.namespaces.importing.php */
namespace Mini\Core;

use Mini\Controller\AbstractController;
use Mini\Controller\ErrorController;
use Mini\Core\Renderer\DefaultRenderer;
use Mini\Core\Renderer\RendererInterface;
use Mini\Libs\Helper;

/**
 * Class Application
 * @package Mini\Core
 *
 * @property-read string $url_controller
 * @property-read string $url_action
 * @property-read array $url_params
 * @property-read string $urlPath
 * @property string $view
 * @property-read array $viewData
 * @property-read RendererInterface $renderer
 */
class Application
{
	const DEFAULT_CONTROLLER = 'index';
	const DEFAULT_ACTION     = 'index';

    /** @var string The controller */
    private $url_controller;

    /** @var string The method (of the above controller), often also named "action" */
    private $url_action;

    /** @var array URL parameters */
    private $url_params = [];

	/** @var string controller/action */
	private $urlPath;

	/** @var string La vue qui sera appeler */
	public $view;

	/** @var array tableau de variable donné à la vue */
	private $viewData = [];

	/** @var RendererInterface */
	private $renderer;

	/**
	 * @param $name
	 * @return mixed
	 * @throws \Exception
	 */
	public function __get($name) {
		if(property_exists($this,$name))
			return $this->$name;
		else
			throw new \Exception("Propriété non définie ou inaccessible");
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

        // create array with URL parts in $url
        $this->splitUrl();


        // check for controller
        if (file_exists(APP . 'Controller/' . ucfirst($this->url_controller) . 'Controller.php')) {

            // if so, then load this file and create this controller
            $controller = "\\Mini\\Controller\\" . ucfirst($this->url_controller) . 'Controller';
            $this->url_controller = new $controller($this);

            // check for method: does such a method exist in the controller ?
            if (method_exists($this->url_controller, $this->url_action) &&
                is_callable(array($this->url_controller, $this->url_action))) {
                
                if (!empty($this->url_params)) {
                    // Call the method and pass arguments to it
                    $this->viewData = call_user_func_array(array($this->url_controller, $this->url_action), $this->url_params) ?? [];
                } else {
                    // If no parameters are given, just call the method without parameters, like $this->home->method();
					$this->viewData =  $this->url_controller->{$this->url_action}() ?? [];
                }

				$this->renderer->render($this);
				return;
            }
        }

        //unknown controller or unknown action : 404 page
		http_response_code(404);
		$this->viewData = (new ErrorController($this))->index();
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
			$strController = $url[0] ?? self::DEFAULT_CONTROLLER;
			$strAction     = $url[1] ?? self::DEFAULT_ACTION;

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
			$strController = self::DEFAULT_CONTROLLER;
			$strAction     = self::DEFAULT_ACTION;
		}

		$this->url_controller = Helper::dashToUpper($strController);
		$this->url_action     = Helper::dashToUpper($strAction);

		$this->view = $this->urlPath = trim($strController . '/' . $strAction,' /');
    }

	/**
	 * @param RendererInterface $renderer
	 */
	public function setRenderer(RendererInterface $renderer): void {
		$this->renderer = $renderer;
	}
}
