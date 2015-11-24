<?php

/**
 * Created by Jerry Pham.
 * Date: 24/11/2015
 * Time: 13:45
 */
class DummyHelperBblvardia
{
    private $bblLoginAPI;

    private $bblLoginEmail;

    private $bblLoginPass;


    private $vardiaCustomerSearchAPI;

    private $vardiaGetPolicyAPI;

    private $vardiaGetQuoteAPI;

    private $bblUser;


    /**
     * Create new instance of DummyHelperBBlvardia
     * @return DummyHelperBblvardia
     */
    public static function factory()
    {
        $match = new Self();

        $params = JComponentHelper::getParams('com_dummy');

        $bblLoginApi = $params->get('bbl_login_api');
        $match->setBblLoginAPI($bblLoginApi);

        $bbLoginEmail = $params->get('bbl_login_email');
        $match->setBblLoginEmail($bbLoginEmail);

        $bbLoginPass = $params->get('bbl_login_pass');
        $match->setBblLoginPass($bbLoginPass);


        $vardiaSearchCustomerApi = $params->get('search_customer_api');
        $match->setVardiaCustomerSearchAPI($vardiaSearchCustomerApi);

        $vardiaGetPolicyApi = $params->get('get_policies_api');
        $match->setVardiaGetPolicyAPI($vardiaGetPolicyApi);

        $vardiaGetQuoteApi = $params->get('get_quotes_api');
        $match->setVardiaGetQuoteAPI($vardiaGetQuoteApi);

        return $match;
    }

    /**
     * @param mixed $bblLoginAPI
     */
    public function setBblLoginAPI($bblLoginAPI)
    {
        $this->bblLoginAPI = $bblLoginAPI;
    }

    /**
     * @param mixed $vardiaCustomerSearchAPI
     */
    public function setVardiaCustomerSearchAPI($vardiaCustomerSearchAPI)
    {
        $this->vardiaCustomerSearchAPI = $vardiaCustomerSearchAPI;
    }

    /**
     * @param mixed $vardiaGetPolicyAPI
     */
    public function setVardiaGetPolicyAPI($vardiaGetPolicyAPI)
    {
        $this->vardiaGetPolicyAPI = $vardiaGetPolicyAPI;
    }

    /**
     * @param mixed $vardiaGetQuoteAPI
     */
    public function setVardiaGetQuoteAPI($vardiaGetQuoteAPI)
    {
        $this->vardiaGetQuoteAPI = $vardiaGetQuoteAPI;
    }

    /**
     * @param mixed $bblLoginEmail
     */
    public function setBblLoginEmail($bblLoginEmail)
    {
        $this->bblLoginEmail = $bblLoginEmail;
    }

    /**
     * @param mixed $bblLoginPass
     */
    public function setBblLoginPass($bblLoginPass)
    {
        $this->bblLoginPass = $bblLoginPass;
    }


    /**
     * Login and set result to $this->bblUser
     * @return bool
     * @throws Exception
     */
    public function bblLogin()
    {
        if( !$this->bblUser ) {
            $authKey = '63cead7ef39c7d288ca234a91416806adac47e438ad7907318b33a4fda4e4993'; //TODO calculate this

            $curl = \DummyHelperCurl::init($this->bblLoginAPI)
                ->addHttpHeader('Content-type', 'application/json')
                ->addHttpHeader('auth-key', $authKey)
                ->addHttpHeader('bblid', 4)
                ->setPost(TRUE)
                ->setTimeOut(30)
                ->setPostFields("{\"email\":\"{$this->bblLoginEmail}\", \"password\": \"{$this->bblLoginPass}\"}")
                ->setReturnTransfer(TRUE);

            $response = $curl->execute();
            $curl->close();

            if ($response) {
                $response = json_decode($response, true);
                if (is_array($response) && isset($response['success']) && $response['success'] == true && isset($response['data']['member'])) {
                    //Login success
                    $this->bblUser = $response['data']['member'];
                    return true;
                }
                return false;
            }

            throw new Exception("Can't login, there is something wrong with API Call");
        }

        return true;
    }


    public function vardiaSearchCustomer()
    {

    }

    public function vardiaGetPolicy()
    {

    }

    public function vardiaGetQuote()
    {

    }


}