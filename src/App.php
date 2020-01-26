<?php

namespace Project;

use Throwable;
use BadMethodCallException;
use Component\{
    Config,
    Loader,
    Request,
    Response,
    Router,
    Session,
    Image,
    View,
    Database
};

class App
{
    protected Config $config;
    protected Database $db;
    protected View $view;
    protected Loader $loader;
    protected Request $request;
    protected Router $router;
    protected Image $image;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
        $this->config = new Config($this->loader);
    }

    public function bootstrap(): App
    {
        class_alias(Session::class, 'Session');
        Session::instance();

        $this->request = new Request($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
        $this->router = new Router($this->config->get('routes'));

        $viewConfig = $this->config->get('view', true);
        $this->view = new View(...$viewConfig);

        $dbConfig = $this->config->get('db', true);
        $this->db = new Database(...$dbConfig);

        $uploaderConfig = $this->config->get('uploader', true);
        $this->image = new Image(...$uploaderConfig);

        return $this;
    }

    public function proccess(): void
    {
        try {
            $requestUri = $this->request->server('request_uri') ?? '/';
            if (!$this->router->match($requestUri)) {
                throw new BadMethodCallException('Page not found.');
            }

            $controller = $this->router->dispatch();

            $content = $controller($this->request, $this->db, $this->view, $this->image);
        } catch (Throwable $throwable) {
            $content = $this->view->render('error.php', ['message' => $throwable->getMessage()]);
        }


        if (is_string($content)) {
            echo $this->view->render('layout.php', [
                'user_id' => Session::instance()->userId(),
                'user_email' => Session::instance()->get('email'),
                'content' => $content
            ]);
        }

        if ($content instanceof Response) {
            echo $content;
        }
    }
}
