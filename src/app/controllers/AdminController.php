<?php

use Phalcon\Mvc\Controller;

class AdminController extends Controller
{
    /**
     * Order listing to admin
     *
     * @return void
     */
    public function ordersAction()
    {
        // if (!isset($this->session->userId) || ($this->session->userRole!='admin')) {
        //     $this->response->redirect('/app/users/login');
        // }
        $order= new Orders();
        $this->view->orders=$order->getOrders();
    }
    public function productsAction()
    {
        // if (!isset($this->session->userId) || ($this->session->userRole!='admin')) {
        //     $this->response->redirect('/app/users/login');
        // }
        $product= new Products;
        $this->view->products=$product->getProducts();
    }
    public function productEditAction($id)
    {
        // if (!isset($this->session->userId) || ($this->session->userRole!='admin')) {
        //     $this->response->redirect('/app/users/login');
        // }
        $productModel= new Products;
        $this->view->product=json_decode(json_encode($productModel->findProduct($id)),true);
        if ($this->request->isPost()) {
            $formData=$this->request->getPost();
            //Call for filter function
            $product=$this->filterProduct($formData);
            $productModel->updateProduct($product,$id);
        }
    }

    /**
     * Filter function
     * take product data and transform it to appropriate form
     * To insert into collection
     *
     * @param [array] $formData, Product data recieved on submit of form
     * @return [array] $product , Modified products array
     */
    
    private function filterProduct($formData)
    {
        $product=array();
        $product['name']=$formData['name'];
        $product['category']=$formData['category'];
        $product['price']=$formData['price'];
        $product['stock']=$formData['stock'];
        $product['meta']=array();
        $product['variation']=array();
        $i=1;
        $j=1;
        while ($i<$formData['noOfMetaFields']) {
            $metaField=[$formData['lableName'.$i.'']=>$formData['lableValue'.$i.'']];
            array_push($product['meta'],$metaField);
            $i+=1;
        }
        while ($j<$formData['noOfVariationFields']) {
            $variationField=[$formData['variationKey'.$j.'']=>$formData['variationValue'.$j.''], 'price'=>$formData['variationprice'.$j.'']];
            array_push($product['variation'],$variationField);
            $j+=1;
        }

        return $product;
    }
}
