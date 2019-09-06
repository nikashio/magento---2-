<?php

namespace Dev\ProductComments\Controller\Adminhtml\Comment;

use Exception;
use Dev\ProductComments\Model\Comment;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Dev\ProductComments\Model\ResourceModel\Comment as ResourceComment;

class NewAction extends Action
{
    /**
     * @var Comment
     */
    private $commentModel;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var ResourceComment
     */
    private $resourceModel;

    /**
     * NewAction constructor.
     * @param Context         $context
     * @param PageFactory     $resultPageFactory
     * @param Comment         $commentModel
     * @param ResourceComment $resourceModel
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Comment $commentModel,
        ResourceComment $resourceModel
    ) {
        parent::__construct($context);
        $this->commentModel = $commentModel;
        $this->resultPageFactory = $resultPageFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * Edit A Contact Page
     *
     * @return                                  ResultInterface
     * @throws                                  Exception
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $this->_view->loadLayout();
        $this->_view->renderLayout();

        $commentData = $this->getRequest()->getParam('comment_id');
        if (is_array($commentData)) {
            $this->commentModel->setData($this->resourceModel->save($commentData));

            return $resultRedirect->setPath('*/*/add');
        }
        return null;
    }
}
