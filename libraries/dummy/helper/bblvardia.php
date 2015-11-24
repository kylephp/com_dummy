<?php

/**
 * Created by Jerry Pham.
 * Date: 24/11/2015
 * Time: 13:45
 */
class DummyHelperBblVardia
{
    private $bblLoginAPI;

    private $vardiaCustomerSearchAPI;

    private $vardiaGetPolicyAPI;

    private $vardiaGetQuoteAPI;

    private $bblUser;


    public function construct()
    {

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
     * Login and set result to $this->bblUser
     * @param $user
     * @param $pass
     * @param $bbl
     */
    public function bblLogin( $user, $pass, $bbl)
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