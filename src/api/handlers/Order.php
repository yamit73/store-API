<?php
namespace Api\Handlers;
use Phalcon\Di\Injectable;
use Exception;
use Orders;
use Products;
/**
 * Class to handle the product related API call
 */
class Order extends Injectable
{
    /**
     * Create order
     *
     * @return void
     */
    public function create()
    {
        try {
            $collection=new Orders();
            //Get order details from request body
            $order= json_decode(json_encode($this->request->getJsonRawBody()),true);
            //Add default status as Paid and order date
            if (isset($order)) {
                $productModel= new Products();
                //Ceck if product is present in the DB
                if ($productModel->findProduct($order['product_id'])) {
                    $order['customer_name']=CURRENT_USER_ID;
                    $order['status']='Paid';
                    $order['date']= new \MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s")));
                    //Create order in document
                    $orderId=$collection->add($order);
                    $this->response->setStatusCode(200)
                        ->setJsonContent(["message"=>"Order Created","order_id"=>(string)$orderId])
                        ->send();
                } else {
                    //If product not found
                    $this->response->setStatusCode(404)
                        ->setJsonContent(["message"=>"product not found"])
                        ->send();
                }
            } else {
                //If request body is empty
                $this->response->setStatusCode(400)
                     ->setJsonContent(["message"=>"no data provided"])
                     ->send();
            }
           
        } catch (Exception $e) {
            $this->response->setStatusCode(401)
                 ->setContent($e->getMessage())
                 ->send();
        }
        
    }
    /**
     * Update status of order
     *
     * @param [string] $orderId
     * @param [string] $status
     * @return void
     */
    function updateOrder($orderId, $status)
    {
        $collection=new Orders();
        try {
            $collection->updateOrder($orderId,$status);
            $this->response->setStatusCode(200)
            ->setContent("Order Status updated!")
            ->send();
        } catch(Exception $e) {
            $this->response->setStatusCode(401)
                 ->setContent($e->getMessage())
                 ->send();
        }
    }
    /**
     * To get all the orders
     *
     * @return void
     */
    function getOrders()
    {
        $collection=new Orders();
        $result=$collection->getOrders();
        $orders=[];
        foreach ($result as $key=>$val) {
            $orders[$key]=json_decode(json_encode($val),true);
        } 
        $this->response->setStatusCode(200)
                 ->setJsonContent($orders)
                 ->send();
    }
}