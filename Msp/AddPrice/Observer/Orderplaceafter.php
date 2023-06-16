<?php
namespace Msp\AddPrice\Observer;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
 
use Psr\Log\LoggerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ObjectManager;
 
class Orderplaceafter implements ObserverInterface
{
    protected $ruleModelFactory;
 
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\SalesRule\Model\RuleFactory $ruleModelFactory
    )
    {
        $this->logger = $logger;
        $this->ruleModelFactory = $ruleModelFactory;
    }
    public function execute(Observer $observer) {

        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom_logtest.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        $order = $observer->getEvent()->getOrder();

        $coupon_code = $order['coupon_code'];
        $applied_rule_ids = $order['applied_rule_ids'];

        $ruleModel = $this->ruleModelFactory->create()->load($applied_rule_ids);
        $ruleModel->setData('is_active','0');
        $ruleModel->save();
        
        $logger->info("Rule Desable Success...");


        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();

        $phoneUtil = PhoneNumberUtil::getInstance();

        $defaultCountry = $shippingAddress['country_id'];//'IN';
        $phoneNumber =  $shippingAddress['telephone'];//"123";

        $phoneUtil = PhoneNumberUtil::getInstance();
        $swissNumberProto = $phoneUtil->parse($phoneNumber, $defaultCountry);

        $phone = $phoneUtil->format($swissNumberProto, PhoneNumberFormat::INTERNATIONAL);

        // $numberString = "+12123456789";

        // $numberPrototype = $phoneUtil->parse($numberString, "IN");

        // echo "Input: " .          $numberString . "\n";
        // echo "isValid: " .       ($phoneUtil->isValidNumber($numberPrototype) ? "true" : "false") . "\n";
        // echo "E164: " .           $phoneUtil->format($numberPrototype, PhoneNumberFormat::E164) . "\n";
        // echo "National: " .       $phoneUtil->format($numberPrototype, PhoneNumberFormat::NATIONAL) . "\n";
        // echo "International: " .  $phoneUtil->format($numberPrototype, PhoneNumberFormat::INTERNATIONAL) . "\n";
        //die();

        $logger->info("defaultCountry =".$defaultCountry);
        $logger->info("phoneNumber =".$phoneNumber);
        $logger->info("phoneUtil =".$phone);
        // $logger->info(print_r($billingAddress->getData(), true));
    
        return $this;
        
    }
   
}