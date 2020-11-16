<?php

namespace ramprasadm1986\paypal;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

use PayPalHttp\HttpException;
use yii\helpers\Url;
use Yii;

class PayPalPayment
{
    
   
    public $live;
    public $intent;
    public $client_id;
    public $client_secret;
    public $cancel_url;
    public $return_url;

    private $environment;
    private $client;


    private function InitClient()
    {
        
        
       if(!$this->live)
        $this->environment = new SandboxEnvironment($this->client_id, $this->client_secret);
       else
       $this->environment = new ProductionEnvironment($this->client_id, $this->client_secret);
    
        $this->client   = new PayPalHttpClient($this->environment);
       
    }
    
    
    public function doCheckout($data){
        $this->InitClient();
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
            
           
            $response = $this->client->execute($request);
            
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            return $response;
        }
        catch (HttpException $ex) {
            return $ex;
        }
            
    }
    
    public function doCapture($id){
        $this->InitClient();
        $request = new OrdersCaptureRequest($id);
        $request->prefer('return=representation');
        try {
            // Call API with your client and get a response for your call
           $response = $this->client->execute($request);
            
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
           return $response;
        }catch (HttpException $ex) {
           return $ex;
        }
        
    }
    
    
    
}