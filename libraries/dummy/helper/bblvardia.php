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
     * @param $user
     * @param $pass
     * @param $bbl
     */
    public function bblLogin($user, $pass, $bbl)
    {

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