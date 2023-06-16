<?php

namespace Magento\GST\Plugin\Checkout;

class ShippingInformationManagementPlugin
{
    protected $quoteRepository;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $extAttributes = $addressInformation->getShippingAddress()->getExtensionAttributes();
        $gstNumber = $extAttributes->getGstNumber();
        $companyName = $extAttributes->getCompanyName();

        $quote = $this->quoteRepository->getActive($cartId);
        $quote->getBillingAddress()->setGstNumber($gstNumber);
        $quote->getBillingAddress()->setCompanyName($companyName);
    }
}
