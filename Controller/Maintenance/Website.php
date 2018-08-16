<?php

namespace Sarang\StoreMaintenance\Controller\Maintenance;
 
class Website extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;
    
    /**
     * @var \Sarang\StoreMaintenance\Helper\Data
     */
    private $helper;
    
    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    private $remoteAddress;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress
     * @param \Sarang\StoreMaintenance\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \Sarang\StoreMaintenance\Helper\Data $helper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->remoteAddress = $remoteAddress;
        $this->helper = $helper;
        parent::__construct($context);
    }
    
    public function execute()
    {
        $currentIp = $this->remoteAddress->getRemoteAddress();
        $allowedIps = $this->helper->getConfig("website/general/allowed_ips");
        $enabled = $this->helper->getConfig("website/general/enable");
        if (!$enabled || strpos($allowedIps, $currentIp) !== false) {
            $this->_redirect('/');
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setStatusHeader(503, '1.1', 'Service Temporarily Unavailable');
        $resultPage->setHeader('Status', '503 Service Temporarily Unavailable');
        return $resultPage;
    }
}
