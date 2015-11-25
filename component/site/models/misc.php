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
     * Method to get user data
     * @return mixed
     */
    public function getData()
    {
        $match = DummyHelperBblvardia::factory();

        if (!$match->bblLogin())
        {
            return false;
        }

        $customer = $match->vardiaSearchCustomer();
        $policies = $match->vardiaGetPolicies();
        $quotes = $match->vardiaGetQuotes();

        return array(
            'bbl' => $match,
            'customer' => $customer,
            'policies' => $policies,
            'quotes' => $quotes
        );
    }
}
