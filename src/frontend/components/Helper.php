<?php
namespace Frontend\Components;
use Phalcon\Di\Injectable;
use Firebase\JWT\JWT;

class Helper extends Injectable
{
    /**
     * Check if user is logged in
     *
     * @return boolean
     */
    public function userLogin()
    {
        //Check if User is logged in
        if (!isset($this->frontendSession->userId)) {
            return false;
        }
        return true;
    }

    public function getToken($userId)
    {
        $key = "example_key";
            $now = new \DateTimeImmutable();
            $payload = array(
                "iat" => $now->getTimestamp(),
                "nbf" => $now->modify('-1 minute')->getTimestamp(),
                "exp" => $now->modify('+1 days')->getTimestamp(),
                'sub' => 'api_token',
                'uid' => $userId,
                'rol' => 'user',
            );
            $token = JWT::encode($payload, $key, 'HS256');
            return $token;
    }
    /**
     * Filter function
     * take product data and transform it to appropriate form
     * To insert into collection
     *
     * @param [array] $formData, Product data recieved on submit of form
     * @return [array] $product , Modified products array
     */
    
    public function filterProduct($formData)
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
