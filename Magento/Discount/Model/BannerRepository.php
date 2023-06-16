<?php

namespace Magento\Discount\Model;

use Magento\Discount\Api\BannerRepositoryInterface;
use Magento\Discount\Model\Banner\Image;
use Magento\Discount\Model\ResourceModel\Banner\CollectionFactory as BannerCollectionFactory;
use Magento\Framework\App\ObjectManager;

class BannerRepository extends \Magento\Ui\DataProvider\AbstractDataProvider implements BannerRepositoryInterface
{
    protected $bannerCollectionFactory;
    protected $_loadedData;
    protected $bannerImage;
    public function __construct(
        BannerCollectionFactory $bannerCollectionFactory,
        ?Image $bannerImage = null
    ) {
        $this->bannerCollectionFactory = $bannerCollectionFactory->create();
        $this->bannerImage = $bannerImage ?? ObjectManager::getInstance()->get(Image::class);
    }

    public function getList(): array
    {
        $data = $this->bannerCollectionFactory->getItems();
        $response = [];
        foreach ($data as $banner) {
            $bannerData = $banner->getData();
            $bannerData = $this->convertValues($banner, $bannerData);

            $this->_loadedData[$banner->getId()] = $bannerData;
        }
        $response['data'] = $this->_loadedData;
        return $response;
    }

     private function convertValues($banner, $bannerData): array
     {
         unset($bannerData['image']);

         $fileName = $banner->getData('image');
         $bannerData['image']['name'] = basename($fileName);

         $bannerData['image']['url'] = $this->bannerImage->getUrl($banner, 'image');
         return $bannerData;
     }
}
