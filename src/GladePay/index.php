<?php 

namespace onubrooks\GladePay;

class Index
{
    private $client;

    public function __construct( $merchantId = 'GP0000001', $merchantKey = '123456789', $api_endpoint = 'https://demo.api.gladepay.com/'){
        
        $requestHeaders = ['mid' => $merchantId, 'key' => $merchantKey];
        $this->client = new \GuzzleHttp\Client(['Content-Type' => 'application/json', 'base_uri' => $api_endpoint, 'headers' => $requestHeaders]);
    }

    private function fetch($method, $glade_action, $data)
    {
        $response = $this->client->request($method, "/$glade_action", ['body' => json_encode($data)]);

        return json_decode($response->getBody());
    }

    public function verify($txnRef)
    {
        $data = [
            "action" => "verify",
            "txnRef" => $txnRef
        ];

        return $this->fetch('PUT', 'payment', $data);
    }

    public function bankTransfer($amount, $user = null, $business_name = null, $country = 'NG', $currency = 'NGN')
    {
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

