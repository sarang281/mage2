<?php

namespace Sarang\StoreMaintenance\Observer;

use Magento\Framework\Event\ObserverInterface;

class Maintenance implements ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlInterface;
    
    /**
     * @var \Sarang\StoreMaintenance\Helper\Data
     */
    private $helper;
    
    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    private $remoteAddress;
    
    /**
     * @param \Psr\Log\LoggerInterface
     * @param \Magento\Framework\UrlInterface
     * @param \Sarang\StoreMaintenance\Helper\Data
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\UrlInterface $urlInterface,
        \Sarang\StoreMaintenance\Helper\Data $helper,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress
    ) {
        $this->logger = $logger;
        $this->urlInterface = $urlInterface;
        $this->helper = $helper;
        $this->remoteAddress = $remoteAddress;
    }
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $controller = $observer->getControllerAction();
        $request = $observer->getRequest();
        $moduleStatus = $this->helper->getConfig("website/general/enable");
        if ($moduleStatus && 'maintenance' !== $request->getRouteName()) {
            $allowedIps = $this->helper->getConfig("website/general/allowed_ips");
            if (!$this->allowAccess($allowedIps)) {
                $url = $this->urlInterface->getUrl('maintenance/maintenance/website');
                $observer->getControllerAction()
                        ->getResponse()
                        ->setRedirect($url);
            }
        }
    }
    
    /**
     * @param string
     */
    private function allowAccess($allowedIps)
    {
        $currentIp = $this->remoteAddress->getRemoteAddress();
        if (strpos($allowedIps, $currentIp) !== false) {
            return true;
        }
        return false;
    }
}
