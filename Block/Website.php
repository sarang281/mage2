<?php

namespace Sarang\StoreMaintenance\Block;

class Website extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Sarang\StoreMaintenance\Helper\Data
     */
    private $helper;
    
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    private $filterProvider;
    
    /**
     * @param \Magento\Framework\View\Element\Template\Context
     * @param \Sarang\StoreMaintenance\Helper\Data
     * @param \Magento\Cms\Model\Template\FilterProvider
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Sarang\StoreMaintenance\Helper\Data $helper,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider
    ) {
        $this->helper = $helper;
        $this->filterProvider = $filterProvider;
        parent::__construct($context);
    }

    public function getPageContent()
    {
        $content = $this->helper->getConfig("website/general/display_text");
        return $this->filterProvider->getPageFilter()->filter($content);
    }
}
