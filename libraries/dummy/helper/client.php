<?php
/**
 * Created by Jerry Pham.
 * Date: 13/11/2015
 * Time: 14:16
 */


class DummyClientHelper
{
    const JSON_RPC_API = 'http://all6.demo.php/pegasus-mobile-sales-service/server.php';

    private $email;

    private $pass;

    /*
    * Hold logged in account information
    */
    private $account;

    private $accountSession;

    public function __construct($email, $pass)
    {
        $this->email = $email;
        $this->pass = $pass;
    }


    public function makeRequest($data)
    {
        $curl = \CURL::init(self::JSON_RPC_API)
            ->addHttpHeader('Content-type', 'application/json')
            ->setPost(TRUE)
            ->setTimeOut(30)
            ->setPostFields($data)
            ->setReturnTransfer(TRUE);

        $response = $curl->execute();
        $curl->close();
        if ($response) {
            return json_decode($response, 1);
        }

        return $response;
    }


    public function login()
    {
        $data = <<<JSON
{
    "method": "login",
    "id": "711",
    "params": [
        {"AccountIdentity": {"Email": "{$this->email}", "Phone": null, "Password": "{$this->pass}"}},
        ""
    ],
    "id": "1",
    "jsonrpc": "2.0"
}
JSON;

        $response = $this->makeRequest($data);
        $this->account = isset($response['result']['Account']) ? $response['result']['Account'] : null;
        $this->accountSession = isset($response['result']['AccountSession']) ? $response['result']['AccountSession'] : null;

        return $response;
    }

    public function isLoggedIn()
    {
        return $this->account || $this->accountSession;
    }


    /**
     * @param $policyId
     * @return mixed|null
     */
    public function getPolicy($policyId)
    {

        $sessionToken = $this->accountSession['SessionToken'];
        $data = <<<JSON
{
    "method": "getPolicy",
    "id" : null,
    "params": [
        {
            "Id": "$policyId"
        },
        "$sessionToken"
    ],
    "jsonrpc": "2.0"
}
JSON;

        $response =  $this->makeRequest($data);
        if( is_array($response)  && isset( $response['result'])){
            return $response['result'];
        }
        return $response;
    }


    /**
     *
     */
    public function getPolicies( $limit = 5)
    {

        $sessionToken = $this->accountSession['SessionToken'];

        $data = <<<JSON
{
    "method": "getListPolicy",
    "params": [
        {
            "Pagination": {
                "CacheEnabled": true,
                "CurrentPageNumber": 1,
                "ItemCountPerPage": $limit,
                "TotalItemCount": null,
                "CurrentItems": null
            }
        },
        "{$sessionToken}"
    ],
    "id": "1",
    "jsonrpc": "2.0"
}
JSON;

        $response = $this->makeRequest($data);
        return $response;
    }


    /**
     * @param $policyId
     * @param array $params
     * @return bool
     */
    public function createClaim($policyId, $params )
    {

        $sessionToken = $this->accountSession['SessionToken'];
        $policy = $this->getPolicy($policyId);

        if ($policy) {

            $accountId = $policy['Policy']['AccountId'];
            $productId = $policy['Policy']['ProductId'];

            $data = <<<JSON
{
    "method": "createClaim",
    "id": 1,
    "params": [
        {
            "Claim": {
                "PolicyId": "$policyId",
                "AgentId": null,
                "AgentType": null,
                "Id": 1,
                "AccountId": $accountId,
                "Status": 0,
                "CreateDate": "2015-10-30",
                "UpdateDate": null,
                "DateOfLoss": "2015-11-20",
                "TimeOfLoss": "30:00:0000",
                "Place": "Somewhere",
                "PolicyOfficeInChargeOfLoss": "Test tui",
                "CauseOfLoss": "Randomly",
                "SituationOfLoss": "Unknownnn",
                "UploadedFiles": null,
                "Policy": null,
                "ProductName": null,
                "Documents": null,
                "serviceData": null,
                "ExternalData": null,
                "ProductVersion": null,
                "Product": null,
                "Customer": null,
                "PolicyInternalId": null
            }
        },
        "$sessionToken"
    ],
    "jsonrpc": "2.0"
}
JSON;


            $response = $this->makeRequest($data);
            if( is_array( $response) && iset( $response['result']['Claim'])){
                return $response['result']['Claim'];
            }

            return false;
        }

        throw new \Exception('Couldnt get policy by id: '.$policyId);
    }


}
