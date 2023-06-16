<?php

namespace Magento\Discount\Model\Banner;

use Magento\Discount\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Framework\App\ObjectManager;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $_loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        FileInfo $fileInfo = null,
        ?Image $postImage = null,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->fileInfo = $fileInfo ?: ObjectManager::getInstance()->get(FileInfo::class);
        $this->postImage = $postImage ?? ObjectManager::getInstance()->get(Image::class);
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $post) {
            $postData = $post->getData();
            $postData = $this->convertValues($post, $postData);
            $this->_loadedData[$post->getId()] = $postData;
        }

        return $this->_loadedData;
    }

    private function convertValues($post, $postData): array
    {
        unset($postData['image']);

        $fileName = $post->getData('image');

        if ($this->fileInfo->isExist($fileName)) {
            $stat = $this->fileInfo->getStat($fileName);
            $mime = $this->fileInfo->getMimeType($fileName);

            $postData['image'][0]['name'] = basename($fileName);

            $postData['image'][0]['url'] = $this->postImage->getUrl($post, 'image');

            $postData['image'][0]['size'] = $stat['size'];
            $postData['image'][0]['type'] = $mime;
        }
        return $postData;
    }
}
