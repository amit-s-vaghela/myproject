<?php

namespace Magento\Quote\Api\Data;

use Magento\Quote\Api\Data\AddressInterface as DataAddressInterface;

interface AddressInterface extends DataAddressInterface
{
    public const KEY_GST_NUMBER = 'gst_number';

    public const KEY_COMPANY_NAME = 'company_name';

    public function getGstNumber();
    public function setGstNumber($gstNumber);

    public function getCompanyName();
    public function setCompanyName($companyName);
}
