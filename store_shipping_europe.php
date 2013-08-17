<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
====================================================================================================
 Author: Peter Lewis
 http://www.peteralewis.com
====================================================================================================
 This file must be placed in the Expresso Store shipping rules folder in your ExpressionEngine third_party folder.
 Probably /third_party/expressionengine/third_party/store/libraries/store_shipping/
 version 		Version 1.0.0
 copyright      Acknowledging all source Copyright (c) 2010-2013 Exp:resso
 Last Update    May 2013
----------------------------------------------------------------------------------------------------
 Purpose:		Provides additional selection of Europe in shipping rules to simplify general european
                location targetting for shipping pricing. This replaces the default shipping plugin
                as it extends it's functionality.
====================================================================================================
 Change Log:

v1.0.0  Initial version
====================================================================================================
*/

//###   Include default shipping class   ###
if (!class_exists('Store_shipping_default'))
    require_once(PATH_THIRD.'store/libraries/store_shipping/store_shipping_default.php');

class Store_shipping_europe extends Store_shipping_default {

	public function calculate_shipping($order) {
		// find the first matching rule for this plugin
		$rules = $this->EE->store_shipping_model->get_all_shipping_rules($this->shipping_method_id, TRUE);
		$found_rule = FALSE;
		foreach ($rules as $rule) {
			if ($this->_test_shipping_rule($rule, $order)) {
				$found_rule = $rule;
				break;
			}
		}

		if ($found_rule === FALSE) {
			return array('error:shipping_method' => lang('no_rules_match_cart_error'));
		}

		return array(
			'order_shipping_val' => $this->_calc_shipping_rule($found_rule, $order),
			'shipping_method_rule' => $found_rule['title']
		);
	}

	protected function _test_shipping_rule($rule, $order) {
        $europe = array("be","fr","at","bg","it","pl","cz","cy","pt","dk","lv","ro","de","lt","si","ee","lu","sk","ie","hu","fi","el","mt","se","es","nl","uk","gb");

        // geographical filters
        if ($rule['country_code'] == "eu") {
            if (in_array($order['shipping_country'], $europe) == FALSE) return FALSE;
		} else if ($rule['country_code'] AND $rule['country_code'] != $order['shipping_country']) {
            return FALSE;
        }

		if ($rule['region_code'] AND $rule['region_code'] != $order['shipping_region']) return FALSE;
		if ($rule['postcode'] != '' AND $rule['postcode'] != $order['shipping_postcode']) return FALSE;

		// order qty rules are inclusive (min <= x <= max)
		if ($rule['min_order_qty'] AND $rule['min_order_qty'] > $order['order_shipping_qty']) return FALSE;
		if ($rule['max_order_qty'] AND $rule['max_order_qty'] < $order['order_shipping_qty']) return FALSE;

		// order total rules exclude maximum limit (min <= x < max)
		if ($rule['min_order_total_val'] AND $rule['min_order_total_val'] > $order['order_shipping_subtotal_val']) return FALSE;
		if ($rule['max_order_total_val'] AND $rule['max_order_total_val'] <= $order['order_shipping_subtotal_val']) return FALSE;

		// order weight rules exclude maximum limit (min <= x < max)
		if ($rule['min_weight'] AND $rule['min_weight'] > $order['order_shipping_weight']) return FALSE;
		if ($rule['max_weight'] AND $rule['max_weight'] <= $order['order_shipping_weight']) return FALSE;

		// all rules match
		return TRUE;
	}
}
