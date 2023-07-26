<?php
namespace Devstree\InventoryAudit\Ui\Component\Listing\Grid\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class Pdf extends Column
{
    /** Url path */
    const ROW_EDIT_URL = 'inventoryaudit/grid/pdf';
    /** @var UrlInterface */
    protected $_urlBuilder;

    /**
     * @var string
     */
    private $_editUrl;

    /**
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     * @param string             $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::ROW_EDIT_URL
    ) {
        $this->_urlBuilder = $urlBuilder;
        $this->_editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
   
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['inventory_audit_id'])) {
                    if (($item['pdf'])) {     
                        $item[$this->getData('name')] = [
                            'edit' => [
                                'href' => $this->_urlBuilder->getUrl(
                                    $this->_editUrl,
                                    [
                                        'fileName' => $item['pdf' ],
                                    ]
                                ),
                                'label' => __('Download'),
                            ]
                        ];
                    }
                }
            }
        }
        return $dataSource;
    }
}
