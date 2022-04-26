<?php
namespace App\Events;

use Phalcon\Di\Injectable;
use WebHooks;
use GuzzleHttp\Client;

/**
 * Event Listener class
 * Handle all the attached events
 */
class EventListener extends Injectable
{
    /**
     * Event, it will be triggrerd when admin will make change
     * In product's stock
     *
     * @param [object] $event
     * @param [type] $controller
     * @param [array] $data
     * @return void
     */
    public function stockEvent($event, $controller, $data)
    {
        $hooksCollection=new WebHooks;
        $hooks=$hooksCollection->getHooks("Product.stock");
        $headers =$this->config->get('client')->get('headers');
         foreach($hooks as $hook) {
            $client = new Client(['base_uri'=>$hook->url]);
            $client->request('POST',"", ['headers' => json_decode(json_encode($headers),true), 'body' => json_encode($data)]);
        }
    }

    /**
     * Add product
     * When admin will add any product this event will be triggered
     *
     * @param [object] $event
     * @param [object] $controller
     * @param [array] $product
     * @return void
     */
    public function addEvent($event, $controller, $product)
    {
        $hooksCollection=new WebHooks;
        $hooks=$hooksCollection->getHooks("Product.add");
        $headers = $this->config->get('client')->get('headers');
         foreach($hooks as $hook) {
            $client = new Client(['base_uri'=>$hook->url]);
            $client->request('POST',"", ['headers' => json_decode(json_encode($headers),true), 'body' => json_encode($product)]);
        }
    }
}
