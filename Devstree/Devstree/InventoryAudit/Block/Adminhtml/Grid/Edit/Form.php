<?php
namespace Devstree\InventoryAudit\Block\Adminhtml\Grid\Edit;

/**
 * Adminhtml Add New Row Form.
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context,
     * @param \Magento\Framework\Registry $registry,
     * @param \Magento\Framework\Data\FormFactory $formFactory,
     * @param \Webkul\Grid\Model\Status $options,
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Webkul\Grid\Model\Status $options,
        array $data = []
    ) {
        $this->_options = $options;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('row_data');
        $form = $this->_formFactory->create(
            ['data' => [
                            'id' => 'edit_form',
                            'enctype' => 'multipart/form-data',
                            'action' => $this->getData('action'),
                            'method' => 'post'
                        ]
            ]
        );

        $form->setHtmlIdPrefix('wkgrid_');
        // if ($model->getEntityId()) {
        //     $fieldset = $form->addFieldset(
        //         'base_fieldset',
        //         ['legend' => __('Edit Row Data'), 'class' => 'fieldset-wide']
        //     );
        //     $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        // } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add Row Data'), 'class' => 'fieldset-wide']
            );
       // }


        $fieldset->addField(
            'is_active',
            'select',
            [
                'name' => 'is_active',
                'label' => __('Select Brand '),
                'id' => 'is_active',
                'title' => __('Select Brand '),
                'values' => $this->_options->getOptionArray(),
                'class' => 'status',
                'required' => true,
            ]
        );
        
        //$form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
