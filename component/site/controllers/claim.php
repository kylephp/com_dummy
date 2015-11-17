<?php
/**
 * @package     Dummy.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

/**
 * The objects controller
 *
 * @package     Dummy.Backend
 * @subpackage  Controller.Objects
 * @since       1.0
 */
class DummyControllerClaim extends RControllerAdmin
{

    /**
     * Method to get object list
     * @return void
     */
    public function ajaxGetPolicyList()
    {
        $app = JFactory::getApplication();
        $policies = $this->getPolicyList();

        header('Content-type: application/json');
        echo json_encode( $policies);
        $app->close();
    }

    public function ajaxCreateClaim()
    {
        $app = JFactory::getApplication();

        header('Content-type: application/json');

        $policyId = isset( $_POST['policyId'] ) ? $_POST['policyId'] : null;
        $lossDate = isset( $_POST['lossDate']) ? $_POST['lossDate'] : null;

        $lossDate = \DateTime::createFromFormat('Y-m-d h:i:s', $lossDate);

        $dateOfLoss = $lossDate ? $lossDate->format('Y-m-d') : null ;
        $timeOfLoss = $lossDate? $lossDate->format('h:i:s') : null;


        $placeOfLoss = isset( $_POST['lostPlace']) ? $_POST['lostPlace'] : null;
        $inChargeOfLoss= isset( $_POST['lossInCharge']) ? $_POST['lossInCharge'] : null;
        $causeOfLoss= isset( $_POST['lossReason']) ? $_POST['lossReason'] : null;
        $situationOfLoss = isset( $_POST['lossSituation']) ? $_POST['lossSituation'] : null;
        $description = isset( $_POST['description']) ? $_POST['description'] : null;


        $result = array(
            'succes' => false,
            'message' => 'Unknown error'
        );
        if( $policyId ) {
            $client = $this->getAPIClient();
            $response = $client->createClaim($policyId, array(
                'DateOfLoss' => $dateOfLoss,
                'TimeOfLoss' => $timeOfLoss,
                'PlaceOfLoss' => $placeOfLoss,
                'InChargeOfLoss' => $inChargeOfLoss,
                'CauseOfLoss' => $causeOfLoss,
                'SituationOfLoss' => $situationOfLoss,
                'Description' => $description
            ));

            if( is_array( $response) && isset( $response['Id'])){
                $result = array(
                    'success' => true,
                    'claim' => $response
                );
            } else {
                $error = $client->error();
                if( is_array( $error) && isset( $error['message'])){
                    $result['message'] = $error['message'];
                }
            }
        }

        echo json_encode( $result);
        $app->close();
    }


    /**
     * @return array
     */
    private function getPolicyList()
    {
        $images = array(
            'Travel' => 'http://icons.iconarchive.com/icons/unclebob/spanish-travel/1024/plane-icon.png',
            'Motor' => 'http://wall--art.com/wp-content/uploads/2014/09/car-icon.png',
        );


        $client = $this->getAPIClient();
        $policies = $client->getPolicies();

        //Parse policies for mobile

        $data = array();

        if (is_array($policies) && isset( $policies['Policies'])) {
            foreach ($policies['Policies'] as $policy) {

                $holder = $policy['QuestionAnswer']['PolicyHolder'];
                if( isset($holder['CustomerAndInsuredPersonFirstNameTag'])) {
                    $holderName = $holder['CustomerAndInsuredPersonFirstNameTag'] . ' '
                        . $holder['CustomerAndInsuredPersonLastNameTag'];
                } else {
                    $holderName = $holder['Pre_CustomerAndInsuredPersonFirstNameTag'] . ' '
                        . $holder['Pre_CustomerAndInsuredPersonLastNameTag'];
                }

                $productType = $policy['QuestionAnswer']['ProductType'];

                $insureDate = new \DateTime( $policy['InsureDate']);
                $date = $insureDate->format('Y-m-d');

                $image = isset( $images[$productType] )
                    ? $images[ $productType]
                    : 'http://vcadvisors.com.au/wp-content/uploads/2015/03/insurance-icon-man.png';

                $item = array(
                    'id' => $policy['Id'],
                    'name' => $productType.' Insurance',
                    'type' => $policy['Type'],
                    'date' => $date,
                    'status' => $policy['Status'],
                    'policy_detail' => $holderName,
                    'image' => $image

                );
                $data[] = $item;
            }
        }

        return $data;
    }

    /**
     * Get API client
     * @param bool|true $login
     * @return MobileServiceAPIClient
     */
    private function getAPIClient($login = true)
    {

        $params = JComponentHelper::getParams('com_dummy');
        $api = $params->get('JSON_RPC_API');
        $user = $params->get('JSON_RPC_API_USER');
        $pass = $params->get('JSON_RPC_API_PASS');

        $client = new \DummyHelperClient( $api, $user, $pass);
        if (true === $login) {
            $client->login();
        }
        return $client;
    }

}


