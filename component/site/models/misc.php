<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dummy
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Single item model for a demo
 *
 * @package     Joomla.Site
 * @subpackage  com_dummy
 * @since       1.5
 */
class DummyModelMisc extends JModelLegacy
{
    /**
     * Method to get list of policies
     * @return mixed
     */
    public function getPolicies()
    {

        // Example of usage
        $match = DummyHelperBblvardia::factory();
        if($match->bblLogin()){
            $match->vardiaSearchCustomer();
        };

        return array();
    }
}
