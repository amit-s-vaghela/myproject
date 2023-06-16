<?php

namespace Magento\Discount\Block;

use Magento\Discount\Model\BannerRepository;

/**
 * Check is available add to compare.
 */
class BannerListing extends \Magento\Framework\View\Element\Template
{
    private $bannerRepository;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        BannerRepository $bannerRepository,
        array $data = []
    ) {
        $this->bannerRepository = $bannerRepository;
        parent::__construct($context, $data);
    }
    /**
     * Wrapper for the PostHelper::getPostData().
     */
    public function getBannerData(): array
    {
        return $this->bannerRepository->getList();
    }
}
