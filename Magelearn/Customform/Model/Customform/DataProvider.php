<?php
declare(strict_types=1);

namespace Magelearn\Customform\Model\Customform;

use Magelearn\Customform\Model\ResourceModel\Customform\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $loadedData;
    protected $collection;
	protected $filesystem;
    protected $dataPersistor;
	/**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
		$this->filesystem = $filesystem;
		$this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
		$mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
		$destinationPath = $mediaDirectory->getAbsolutePath('magelearn/customform');
        $items = $this->collection->getItems();
        foreach ($items as $model) {
        	$itemData = $model->getData();
			if ($model->getImage()) {
            $imageName = $itemData['image']; // Your database field 
            //unset($itemData['image']);
            $itemData['image'] = array(
                array(
                    'name'  =>  $imageName,
                    'type'   =>  'image',
                    'url'   =>  $this->storeManager
                ->getStore()
                ->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ).'magelearn/customform'.$itemData['image'] // Should return a URL to view the image. For example, http://domain.com/pub/media/../../imagename.jpeg
                )
            );
			}
            $this->loadedData[$model->getId()] = $itemData;
        }
        $data = $this->dataPersistor->get('mladmincustomform');
        
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('mladmincustomform');
        }
        
        return $this->loadedData;
    }

//    public function getData()
//    {
//         if (isset($this->_loadedData)) {
//             return $this->_loadedData;
//             }
//         $items = $this->collection->getItems();
//         if($items) {
//             foreach ($items as $item) {
//                 $this->_loadedData[$item->getId()] = $item->getData();
//                 $i['image'][0]['name'] = 'test'; //TODO
//                 $i['image'][0]['url'] =" https://asmi.docker/media/magelearn/customform/1/0/107_home_theater_systems_e5dfd86da21eee5a93e2c0856cd6baa8_1.jpg";
//                 $i['image'][0]['type'] = 'image';
//                 $allData = $this->_loadedData;
//                 $this->_loadedData[$item->getId()] = array_merge($allData[$item->getId()], $i);
//             }
//             return $this->_loadedData;
//         }
//         return;
//     }
	
}

