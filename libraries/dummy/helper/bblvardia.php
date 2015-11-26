<?php

/**
 * Created by Jerry Pham.
 * Date: 24/11/2015
 * Time: 13:45
 */
class DummyHelperBblvardia
{
    //private $bblPrivateKey = 'GIU1spfAmfBRa6tw8Xub5qypw7dTlnvi';
    private $bblPrivateKey = 'TAr5HM8aFe93SZSEVtCcUITW7rZ153jP';

    private $bblLoginAPI;

    private $bblLoginEmail;

    private $bblLoginPass;

    private $vardiaCustomerSearchAPI;

    private $vardiaGetPolicyAPI;

    private $vardiaGetQuoteAPI;

    private $bblUser;

    private $vardiaCustomer;

    private $vardiaCustomerId;

    private $vardiaPartnerIds = array();


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
     * Method to get current logged user
     * @return array
     */
    public function getBblUserInfo()
    {
        return $this->bblUser;
    }

    /**
     * Login and set result to $this->bblUser
     * @return bool
     * @throws Exception
     */
    public function bblLogin()
    {
        if (!$this->bblUser) {

            $queryString = preg_split("/[?]+/", $this->bblLoginAPI);
            $queryString = isset($queryString[1]) ? $queryString[1] : '';
            $authKey = hash_hmac('sha256', $queryString, $this->bblPrivateKey, false);

            //$authKey2 = '63cead7ef39c7d288ca234a91416806adac47e438ad7907318b33a4fda4e4993'; //TODO calculate this
            //var_dump($authKey === $authKey2);exit;

            $curl = \DummyHelperCurl::init($this->bblLoginAPI)
                ->addHttpHeader('Content-type', 'application/json')
                ->addHttpHeader('auth-key', $authKey)
                //->addHttpHeader('bblid', 4)
                ->setPost(TRUE)
                ->setTimeOut(30)
                ->setPostFields("{\"email\":\"{$this->bblLoginEmail}\", \"password\": \"{$this->bblLoginPass}\"}")
                ->setReturnTransfer(TRUE);

            $response = $curl->execute();
            $curl->close();

            if ($response) {
                $response = json_decode($response, true);
                if (is_array($response) && isset($response['success']) && $response['success'] == true && isset($response['data'])) {
                    //Login success
                    $this->bblUser = $response['data'];
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
        if (!isset($this->bblUser['email']))
        {
            return false;
        }

        $email = trim(strtolower($this->bblUser['email']));
        $request = $this->vardiaCustomerSearchAPI . '?email=' . $this->bblUser['email'];
        $curl = \DummyHelperCurl::init($this->vardiaCustomerSearchAPI)
            ->setReturnTransfer(TRUE);

        $response = $curl->execute();
        $curl->close();

        if ($response)
        {
            $response = json_decode($response, true);

            if (is_array($response) && isset($response['results']))
            {
                $customers = $response['results'];

                foreach ($customers as $customer)
                {
                   $partners = $customer['partners'];
                   $compare = trim(strtolower($customer['email']));

                   if (!empty($partners) 
                        && $partners[0]['sourceSystem'] == 'SourceSystem_CPS1'
                        && $compare == $email)
                   {
                        $this->vardiaCustomer = $customer;
                        return $this->vardiaCustomer;
                   }
                }
            }
        }

        return false;
    }

    /**
     * Parse customerId and partner ids from result of search customer
     * @return bool
     */
    private function parseCustomerIdAndPartnerIds()
    {
        if( $this->vardiaCustomerId && !empty($this->vardiaPartnerIds)){
            return true;
        }

        $this->vardiaCustomerId = isset($this->vardiaCustomer['customerId'])
            ? $this->vardiaCustomer['customerId']
            : null;

        if( $this->vardiaCustomerId){
            if (isset($this->vardiaCustomer['partners'])) {
                foreach ($this->vardiaCustomer['partners'] as $partner) {
                    if (isset($partner['partnerId'])) {
                        $this->vardiaPartnerIds[] = $partner['partnerId'];
                    }
                }
            }
            return true;
        }

        return false;
    }



    /**
     * [vardiaGetPolicy description]
     * @return [type] [description]
     */
    public function vardiaGetPolicies()
    {
        if ($this->parseCustomerIdAndPartnerIds()) {
            $policies = array();
            foreach ($this->vardiaPartnerIds as $partnerId) {
                $apiUrl = $this->vardiaGetPolicyAPI . "?customerId=$this->vardiaCustomerId&partnerId=$partnerId";
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
    public function vardiaGetQuotes()
    {
        $quotes = array();
        if($this->parseCustomerIdAndPartnerIds()){

            foreach( $this->vardiaPartnerIds as $partnerId) {
                $apiUrl = $this->vardiaGetQuoteAPI . "?request[0][customerId]={$this->vardiaCustomerId}&request[0][partnerId]=$partnerId";

                $result = file_get_contents($apiUrl);
                $result = json_decode($result, true);

                if(is_array($result)){
                    foreach($result as $item){
                        if( isset($item['quotes']) && is_array($item['quotes'])){
                            $quotes = array_merge($quotes, $item['quotes']);
                        }
                    }
                }
            }
        }

        return $quotes;
    }
}