<?php
/**
 * @link      https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license   https://craftcms.com/license
 */

namespace digitalpros\commerce\braintree\migrations;

use Craft;
use digitalpros\commerce\braintree\gateways\Gateway;
use craft\db\Migration;
use craft\db\Query;

/**
 * Installation Migration
 *
 * @author Digital Pros - Referenced from Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since  1.0
 */
class Install extends Migration
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Convert any built-in Braintree AIM gateways to ours
        $this->_convertGateways();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }

    // Private Methods
    // =========================================================================

    /**
     * Convert any built-in Braintree AIM gateways to this one
     *
     * @return void
     */
    private function _convertGateways()
    {
        $gateways = (new Query())
            ->select(['id'])
            ->where(['type' => 'Braintree'])
            ->from(['{{%commerce_gateways}}'])
            ->all();

        $dbConnection = Craft::$app->getDb();

        foreach ($gateways as $gateway) {
	        	        
            $values = [
                'type' => Gateway::class,
            ];

            $dbConnection->createCommand()
                ->update('{{%commerce_gateways}}', $values, ['id' => $gateway['id']])
                ->execute();
        }

    }
}
