<?php

namespace Project\Controller;

use Component\Controller;
use Component\Response;
use Project\Repository\Book;
use Project\Validator\ItemValidator;
use RuntimeException;

/**
 * Class BookController
 * @package Project\Controller
 */
class BookController extends Controller
{

    /**
     * @return false|string
     */
    public function index()
    {
        if (!$this->isUser()) {
            return $this->render('book/index.php');
        }

        /* @var $repository Book */
        $repository = $this->createRepository(Book::class);
        $items = $repository->findByUserId($this->userId());

        if ($this->request->isXmlHttpRequest()) {
            return $this->json($items);
        }

        return $this->render('book/list.php', [
            'items' => $items
        ]);
    }

    /**
     * @return false|string
     */
    public function create()
    {
        if (!$this->isUser()) {
            throw new \RuntimeException('access denied');
        }

        $response = [];

        if ($this->request->isPost()) {
            $itemData = $this->request->post('Item');
            $file = $this->request->files('image');

            $validator = new ItemValidator($itemData, $file);

            if ($validator->isValid()) {
                $itemData['user_id'] = $this->userId();
                $itemData['image'] = $this->image->createFromUpload($file);
                /* @var $repository Book */
                $repository = $this->createRepository(Book::class);
                $response = $repository->createItem($itemData);
            } else {
                $response['errors'] = $validator->getErrors();
            }
        }

        return $this->json($response);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function update(int $id): Response
    {
        if (!$this->isUser()) {
            throw new RuntimeException('access denied');
        }

        if ($this->request->isPost()) {
            /* @var $repository Book */
            $repository = $this->createRepository(Book::class);

            $item = $repository->find($id, $this->userId());

            if (!$item) {
                throw new RuntimeException('Item not found.');
            }

            $itemData = $this->request->post('Item');
            $file = $this->request->files('image');

            $file = empty($file['tmp_name']) ? [] : $file;

            $validator = new ItemValidator($itemData, $file);

            if ($validator->isValid()) {
                $itemData['image'] = $item->image;

                if (!empty($file['tmp_name'])) {
                    $itemData['image'] = $this->image->createFromUpload($file);
                    $this->image->delete($item->image);
                }

                $repository->update($id, $itemData);
                $itemData['id'] = $id;
            } else {
                $itemData = ['errors' => $validator->getErrors()];
            }

            return $this->json($itemData);
        }

        return $this->json(['error' => 'method not allowed']);
    }

    /**
     * @param int $id
     * @return false|string
     */
    public function delete(int $id)
    {
        if (!$this->isUser()) {
            throw new \RuntimeException('access denied');
        }

        /* @var $repository Book */
        $repository = $this->createRepository(Book::class);

        $repository->deleteItem($id, $this->userId(), $this->image);

        return $this->json(['code' => true]);
    }
}
