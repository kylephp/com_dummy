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
        if (!$this->bblUser) {
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


    /**
     * [vardiaSearchCustomer description]
     * @return [type] [description]
     */
    public function vardiaSearchCustomer()
    {

    }

    /**
     * [vardiaGetPolicy description]
     * @return [type] [description]
     */
    public function vardiaGetPolicies()
    {

        $isVardiaCustomer = true; //TODO this depends on vardiaSearchCustomer method
        //TODO $vardiaCustomer is dummy data, this depends on vardiaSearchCustomer methods also
        $vardiaCustomer = json_decode("{\"customerId\":\"23\",\"nin\":\"198403150751\",\"name\":\"Kivan Kaivanipour\",\"email\":\"Kaivanipour@contemi.com.vn\",\"mobileNumber\":\"083899456\",\"telephoneNumber\":\"\",\"customerFlag\":\"Ingen markering\",\"dateOfBirth\":\"1984-03-15T00:00:00Z\",\"address\":\"Rådmansgatan 76 C Lgh 1101, Stockholm, 11360\",\"customerType\":\"Private\",\"partners\":[{\"customerId\":\"12\",\"customerReference\":\"10011\",\"partnerId\":\"1\",\"partnerName\":\"Vardia bil & boendeförsäkring\",\"sourceSystem\":\"SourceSystem_CPS1\",\"customerStatus\":\"Active\"},{\"customerId\":\"23\",\"customerReference\":\"10023\",\"partnerId\":\"14\",\"partnerName\":\"AON Direkt Försäkringsservice\",\"sourceSystem\":\"SourceSystem_CPS1\",\"customerStatus\":\"Active\"}]}", true);

        $vardiaCustomerId = isset($vardiaCustomer['customerId']) ? $vardiaCustomer['customerId'] : null;

        if ($isVardiaCustomer && is_array($vardiaCustomer) && $vardiaCustomerId) {
            $partnerIds = array();

            if (isset($vardiaCustomer['partners'])) {
                foreach ($vardiaCustomer['partners'] as $partner) {
                    if (isset($partner['partnerId'])) {
                        $partnerIds[] = $partner['partnerId'];
                    }
                }
            }

            $policies = array();
            foreach ($partnerIds as $partnerId) {
                $apiUrl = $this->vardiaGetPolicyAPI . "?customerId=$vardiaCustomerId&partnerId=$partnerId";

                $policies = array_merge($policies, $this->getVardiaPoliciesByCustomerIdAndPartnerId($apiUrl));
            }
            return $policies;

        }
        return array();
    }

    private function getVardiaPoliciesByCustomerIdAndPartnerId($apiUrl)
    {
        $result = file_get_contents($apiUrl);

        $result = json_decode($result, true);

        return is_array($result) ? $result : array();
    }

    /**
     * [vardiaGetQuote description]
     * @return [type] [description]
     */
    public function vardiaGetQuote()
    {

    }
}