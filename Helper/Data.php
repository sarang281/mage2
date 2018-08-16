<?php

namespace Sarang\StoreMaintenance\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @param string
     */
    public function getConfig($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }
}
