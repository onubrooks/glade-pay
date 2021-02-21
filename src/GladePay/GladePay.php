<?php 

namespace onubrooks\GladePay;

use \GuzzleHttp\Client;

/**
 * GladePay Class
 * 
 * This class contains all functionality of the gladepay library. Various helper instance methods are provided to make
 * interacting with the Glade API very painless and seamless.
 *
 * @author Abah Onuh <abahonuh@gmail.com.com>
 */
class GladePay
{
    /**
     * private HTTP client variable
     *
     * @var \GuzzleHttp\Client
     * 
     */
    private $client;

    /**
     * class constructor, used to instantiate an object of the class
     *
     * @param string $merchantId
     * @param string $merchantKey
     * @param string $api_endpoint
     * 
     * @author Abah Onuh <abahonuh@gmail.com.com>
     */
    public function __construct( $merchantId = 'GP0000001', $merchantKey = '123456789', $api_endpoint = 'https://demo.api.gladepay.com/'){
        
        $requestHeaders = ['mid' => $merchantId, 'key' => $merchantKey];
        $this->client = new Client(['Content-Type' => 'application/json', 'base_uri' => $api_endpoint, 'headers' => $requestHeaders]);
    }

    /**
     * Generic HTTP Request Helper
     * 
     * All methods use this private function to communicate via HTTP requests to the Glade API
     *
     * @param string $method
     * @param string $glade_action
     * @param object|array $data
     *
     * @return array
     * 
     * @author Abah Onuh <abahonuh@gmail.com.com>
     */
    private function fetch($method, $glade_action, $data)
    {
        $response = $this->client->request($method, "/$glade_action", ['body' => json_encode($data)]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Verify Transaction
     * 
     * This method accepts a transaction reference verifies the status of the corresponding transaction
     *
     * @param string $txnRef
     *
     * @return array
     * 
     * @author Abah Onuh <abahonuh@gmail.com.com>
     */
    public function verify($txnRef)
    {
        if (empty($txnRef) || !isset($txnRef)) {
            throw new \InvalidArgumentException('Transaction Ref is required.');
        }
        $data = [
            "action" => "verify",
            "txnRef" => $txnRef
        ];

        return $this->fetch('PUT', 'payment', $data);
    }

    /**
     * Make Payment via Bank Transfer
     * 
     * This method initiates a bank transfer transaction via the Glade API
     *
     * @param integer|float $amount
     * @param object $user
     * @param string $business_name
     * @param string $country
     * @param string $currency
     *
     * @return array
     * 
     * @author Abah Onuh <abahonuh@gmail.com.com>
     */
    public function bankTransfer($amount, $user = null, $business_name = null, $country = 'NG', $currency = 'NGN')
    {
        if(!is_int($amount) || $amount < 1)
        {
            throw new \InvalidArgumentException('Amount must be a positive integer.');
        }
        $data = [
            "action" => "charge",
            "paymentType" => "bank_transfer",
            "amount" => $amount,
            "user" => $user,
            "business_name" => $business_name,
            "country" => $country,
            "currency" => $currency
        ];

        return $this->fetch('PUT', 'payment', $data);
    }


}

