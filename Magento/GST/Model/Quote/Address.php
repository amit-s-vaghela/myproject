<?php

namespace Magento\GST\Model\Quote;

use Magento\Quote\Model\Quote\Address as DataAddress;

class Address extends DataAddress
{
    public function getGstNumber()
    {
        return $this->getData(self::KEY_GST_NUMBER);
    }

     public function SetGstNumber($gstNumber)
     {
         return $this->setData(self::KEY_COMPANY_NAME, $gstNumber);
     }
     public function getCompany()
     {
         return $this->getData(self::KEY_GST_NUMBER);
     }

     public function setCompanyName($companyName)
     {
         return $this->setData(self::KEY_COMPANY_NAME, $companyName);
     }
}
