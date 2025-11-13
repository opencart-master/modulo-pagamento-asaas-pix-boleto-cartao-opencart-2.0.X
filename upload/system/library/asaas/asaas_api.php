<?php
class AsaasApi {
    private $api_key;
    private $base_url;

    public function __construct($api_key, $sandbox = true) {
        $this->api_key = $api_key;
        $this->base_url = $sandbox ? 'https://sandbox.asaas.com/api/v3/' : 'https://www.asaas.com/api/v3/';
    }

    private function request($method, $endpoint, $data = []) {
        $soap_do = curl_init($this->base_url . $endpoint);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'User-Agent: Master/1.0.0.0 (Plataforma opencart.com - DEV Opencar Master)',
            'access_token: ' . $this->api_key
        ]);
        if ($method !== 'GET') {
            curl_setopt($soap_do, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($soap_do, CURLOPT_POSTFIELDS, json_encode($data));
        }
        $response = curl_exec($soap_do);
        curl_close($soap_do);
        return json_decode($response, true);
    }

    public function createCustomer($data) {
        return $this->request('POST', 'customers', $data);
    }

    public function createPayment($data) {
        return $this->request('POST', 'payments', $data);
    }

    public function getPayment($id) {
        return $this->request('GET', 'payments/' . $id);
    }

    public function getCustomer($email) {
        return $this->request('GET', 'customers?email=' . $email);
    }

    public function onlyNumbe($numeber) {
        return preg_replace("/[^0-9]/", '', $numeber);
    }
}