<?php

namespace Component;

use function json_encode;

/**
 * Class Controller
 * @package Component
 */
abstract class Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Database
     */
    protected Database $db;

    /**
     * @var View
     */
    protected View$view;

    /**
     * @var Filesystem
     */
    protected Filesystem $image;

    /**
     * Controller constructor.
     * @param Request $request
     * @param Database $db
     * @param View $view
     * @param Filesystem $image
     */
    public function __construct(
        Request $request,
        Database $db,
        View $view,
        Filesystem $image
    ) {
        $this->request  = $request;
        $this->db       = $db;
        $this->view     = $view;
        $this->image    = $image;
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    public function render(string $template, array $data = [])
    {
        return $this->view->render($template, $data);
    }

    public function createRepository(string $modelClass)
    {
        return new $modelClass($this->db);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function json(array $data): Response
    {
        return new Response(json_encode($data), 200, ['Content-type' => 'application/json; charset=utf-8']);
    }

    public function redirect(string $url = '/')
    {
        header(sprintf('Location: %s', $url));
        exit;
    }

    public function isUser()
    {
        return Session::instance()->userId();
    }

    public function userId()
    {
        return Session::instance()->userId();
    }
}
