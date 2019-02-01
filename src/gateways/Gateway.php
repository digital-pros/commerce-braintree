<?php

namespace digitalpros\commerce\braintree\gateways;

use Craft;
use digitalpros\commerce\braintree\models\BraintreePaymentForm;
use digitalpros\commerce\braintree\BraintreePaymentBundle;
use craft\commerce\base\RequestResponseInterface;
use craft\commerce\models\payments\BasePaymentForm;
use craft\commerce\omnipay\base\CreditCardGateway;
use craft\commerce\omnipay\events\SendPaymentRequestEvent;
use craft\commerce\omnipay\events\GatewayRequestEvent;
use craft\commerce\models\Transaction;
use craft\web\View;
use Omnipay\Common\AbstractGateway;
use Omnipay\Omnipay;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\BraintreeNet\AIMGateway as OmnipayGateway;
use yii\base\Event;
use yii\base\NotSupportedException;

/**
 * Gateway represents WorldPay gateway
 *
 * @author    Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since     1.0
 */
class Gateway extends CreditCardGateway
{
    // Properties
    // =========================================================================

    /**
     * @var string
     */
    public $merchantId;

    /**
     * @var string
     */
    public $publicKey;
    
    /**
     * @var string
     */
    public $privateKey;

    /**
     * @var string
     */
    public $testMode;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('commerce', 'Braintree');
    }

    /**
     * @inheritdoc
     */
    public function getPaymentFormHtml(array $params)
    {
        $defaults = [
            'gateway' => $this,
            'paymentForm' => $this->getPaymentFormModel()
        ];

        $params = array_merge($defaults, $params);
				
        $view = Craft::$app->getView();

        $previousMode = $view->getTemplateMode();
        $view->setTemplateMode(View::TEMPLATE_MODE_CP);
		
        $view->registerAssetBundle(BraintreePaymentBundle::class);

        $html = Craft::$app->getView()->renderTemplate('commerce-braintree/paymentForm', $params);
        $view->setTemplateMode($previousMode);
    
		return $html;
    }

    /**
     * @inheritdoc
     */
    public function getPaymentFormModel(): BasePaymentForm
    {
        return new BraintreePaymentForm();
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('commerce-braintree/gatewaySettings', ['gateway' => $this]);
    }
    
    /**
     * @inheritdoc
     */
    public function init() 
    {
	    
	    Event::on(Gateway::class, Gateway::EVENT_BEFORE_SEND_PAYMENT_REQUEST, function(SendPaymentRequestEvent $e) {
            
            $e->modifiedRequestData = $e->requestData;

            if(isset($e->modifiedRequestData->transactionRequest->transactionType) && $e->modifiedRequestData->transactionRequest->transactionType != "refundTransaction") {

            	$e->modifiedRequestData->transactionRequest->payment->token = $_POST['payment_method_nonce'];
            }            
            
        }); 

    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createGateway(): AbstractGateway
    {
        /** @var OmnipayGateway $gateway */
        $gateway = Omnipay::create($this->getGatewayClassName());

        $gateway->setApiLoginId($this->apiLoginId);
        $gateway->setTransactionKey($this->transactionKey);
        $gateway->setDeveloperMode($this->developerMode);
       
		
        return $gateway;
    }

    /**
     * @inheritdoc
     */
    protected function getGatewayClassName()
    {
        return '\\'.OmnipayGateway::class;
    }
        
}
