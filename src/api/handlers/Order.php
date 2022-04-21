<?php
namespace Api\Handlers;

use Exception;
use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Orders;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Class to handle the product related API call
 */
class Order
{
    public $collection;
    public function initialize()
    {
        $this->collection=new Orders();
    }
    /**
     * Create order
     *
     * @return void
     */
    public function create()
    {
        try {
            $request=new Request();
            //Get token from query
            $token = $request->getQuery('token');
            //Token key
            $key = "example_key";
            //Decode token to get customer name
            $decodedToken = JWT::decode($token, new Key($key, 'HS256'));
            $collection=new Orders();
            $response=new Response();
            //Get order details from POST
            $order= json_decode(json_encode($request->getJsonRawBody()),true);
            //Add default status as Paid and order date
            if (isset($order)) {
                $order['customer_name']=$decodedToken->nam;
                $order['status']='Paid';
                $order['date']= new \MongoDB\BSON\UTCDateTime(new \DateTimeImmutable(date("Y-m-d H:i:s")));
                //Create order in document
                $collection->add($order);
                $response->setStatusCode(200)
                     ->setContent("Order Created")
                     ->send();
            } else {
                $response->setStatusCode(400)
                     ->setContent("no data provided")
                     ->send();
            }
           
        } catch (Exception $e) {
            $response->setStatusCode(401)
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
        try {
            $collection=new Orders();
            $response=new Response();
            $collection->updateOrder($orderId,$status);
            $response->setStatusCode(200)
            ->setContent("Order Status updated!")
            ->send();
        } catch(Exception $e) {
            $response->setStatusCode(401)
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
        $response=new Response();
        $collection=new Orders();
        $result=$collection->getOrders();
        $orders=[];
        foreach ($result as $key=>$val) {
            $orders[$key]=json_decode(json_encode($val),true);
        } 
        $response->setStatusCode(200)
                 ->setJsonContent($orders)
                 ->send();
    }
}