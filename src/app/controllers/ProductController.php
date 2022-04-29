<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;

final class ProductController extends Controller
{
    /**
     * Initializing objects
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->helper = new \App\Components\Helper();
        $this->collection = new Products();
    }
    /**
     * Product Listing
     *
     * @return void
     */
    public function indexAction(): void
    {
        if (! $this->helper->userLogin()) {
            $this->response->redirect('/app/users/login');
        }
        $this->view->products = $this->collection->getProducts();
    }
    /**
     * Add all product to database
     *
     * @return void
     */
    public function addAction(): void
    {
        if (! $this->helper->userLogin()) {
            $this->response->redirect('/app/users/login');
        }
        if ($this->request->isPost()) {
            $productId = $this->collection->add($this->request->getPost());
            $product = json_decode(
                json_encode(
                    $this->collection->findProduct($productId)
                ),
                true,
            );
            //firing event
            $eventsManagers = $this->di->get('EventsManager');
            $eventsManagers->fire('event:addEvent', $this, $product);
        }
    }
    /**
     * Product update
     *
     * @param string $id
     * @return void
     */
    public function productEditAction($id): void
    {
        if (! $this->helper->userLogin()) {
            $this->response->redirect('/app/users/login');
        }
        $productBeforeEdit = json_decode(json_encode($this->collection->findProduct($id)), true);
        $this->view->product = $productBeforeEdit;
        if ($this->request->isPost()) {
            $product = $this->request->getPost();
            $updatedProduct = $this->collection->updateProduct($product, $id);
            //Check if Prodcut was updated successfully
            if ($updatedProduct > 0) {
                //Check if product stock was changed
                if ($product['stock'] !== $productBeforeEdit['stock']) {
                    $data['updatedId'] = $id;
                    $data['field'] = ['stock' => $product['stock']];
                    //creating an object of event manager
                    $eventsManagers = $this->di->get('EventsManager');
                    //firing event
                    $eventsManagers->fire('event:stockEvent', $this, $data);
                }
            }
        }
    }
}
