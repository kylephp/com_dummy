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

    private $bblUserId;

    private $bblUserEmail;

    private $vardiaCustomer;

    private $vardiaCustomerId;

    private $vardiaPartnerIds = array();

    private $cachedTable = '#__bbl_vardia_customer';

    private $cachedTableColumns  = array('bbl_customer_id', 'bbl_customer_email', 'vardia_customer_id', 'vardia_customer_info_cache');

    /**
     * @var RDatabaseDriverMysqli
     */
    private $db;


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


        $db = JFactory::getDbo();
        $match->setDb($db);

        return $match;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->db = $db;
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
                    $this->bblUserId = isset($this->bblUser['id']) ? $this->bblUser['id']: null;
                    $this->bblUserEmail = isset( $this->bblUser['email']) ? $this->bblUser['email'] : null;

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

        //Check for cached vardia customer
        $cachedVardiaCustomer = $this->getCachedVardiaCustomer();
        if( $cachedVardiaCustomer ){
            $this->vardiaCustomer = $cachedVardiaCustomer;
            return $this->vardiaCustomer;
        }

        //If there is nothing cached
        $email = trim(strtolower($this->bblUser['email']));
        $uri = JUri::getInstance($this->vardiaCustomerSearchAPI . '?email=' . $email);

        $transport = new JHttpTransportCurl(new JRegistry);
        $response = $transport->request('GET', $uri);

        if ($response)
        {
            $response = json_decode($response->body, true);

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
                        $this->cacheVardiaCustomer();
                        return $this->vardiaCustomer;
                   }
                }
            }
        }

        return false;
    }

    /**
     * Cache vardia customer locally
     */
    public function cacheVardiaCustomer()
    {

        if( $this->parseCustomerIdAndPartnerIds()) {
            $query = $this->db->getQuery(true);

            $values = array($this->bblUserId, $this->db->quote($this->bblUserEmail), $this->vardiaCustomerId, $this->db->quote(json_encode($this->vardiaCustomer)));

            $query = $this->db->getQuery(true);
            $query->insert($this->db->quoteName($this->cachedTable))
                ->columns($this->db->quoteName($this->cachedTableColumns))
                ->values(implode(',', $values));

            $this->db->setQuery($query);
            $this->db->execute();
            return true;
        }
        return false;
    }


    /**
     * Look into local database to see if there is any cached customer
     */
    public function getCachedVardiaCustomer()
    {
        $query = $this->db->getQuery(true);

        $query->select($this->db->quoteName($this->cachedTableColumns))
            ->from($this->db->quoteName($this->cachedTable));


        $this->db->setQuery($query);
        $result = $this->db->loadObjectList();

        if( is_array( $result) && count($result) > 0 ){
            $result = $result[0];
            if( $result instanceof \stdClass){
                $result = json_decode(json_encode($result), true);

                $vardiaCustomer = isset( $result['vardia_customer_info_cache'] )
                    ? $result['vardia_customer_info_cache']
                    : null ;

                if( $vardiaCustomer) {
                    $vardiaCustomer = json_decode($vardiaCustomer, true);

                    return $vardiaCustomer;
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
        $result = @file_get_contents($apiUrl);
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

                @$result = file_get_contents($apiUrl);
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