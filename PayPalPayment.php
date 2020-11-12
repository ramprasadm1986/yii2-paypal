<?php

namespace ramprasadm1986\paypal;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

use yii\web\HttpException;
use yii\helpers\Url;
use Yii;
class PayPalPayment
{
    
   
    public $mode;
    public $intent;
    public $clientId;
    public $clientSecret;
    public $cancel_url;
    public $return_url;

    private $environment;
    private $client; = new PayPalHttpClient($environment);


    public function __construct()
    {
        
        if(!$this->mode)
        $this->environment = new SandboxEnvironment($this->clientId, $this->clientSecret);
        else
        $this->environment = new ProductionEnvironment($this->clientId, $this->clientSecret);
    
        $this->client   = new PayPalHttpClient($this->environment);
       
    }
    
    
    public function doCheckout($data){
        
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
                             "intent" => $this->intent,
                             "purchase_units" => [$data],
                             "application_context" => [
                                  "cancel_url" => $this->cancel_url,
                                  "return_url" => $this->return_url
                             ] 
                         ];

        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($request);
            
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            return $response;
        }
        catch (HttpException $ex) {
            return ex;
        }
            
    }
    
    
    
}