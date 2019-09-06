<?php

namespace Dev\ProductComments\Controller\Adminhtml;

use Magento\Framework\Registry;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;

class Comment extends Action
{

    const ADMIN_RESOURCE = 'Dev_ProductComments::top_level';

    protected $coreRegistry;

    public function __construct(
        Context $context,
        Registry $_coreRegistry
    ) {
        $this->coreRegistry = $_coreRegistry;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        // TODO: Implement execute() method.
    }
}
