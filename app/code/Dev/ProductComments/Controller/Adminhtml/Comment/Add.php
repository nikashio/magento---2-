<?php

namespace Dev\ProductComments\Controller\Adminhtml\Comment;

use Magento\Framework\Registry;
use Dev\ProductComments\Model\Comment;
use Magento\Framework\View\Result\Page;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use Dev\ProductComments\Model\CommentRepository;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Backend\Model\View\Result\Page as PageAlias;

class Add extends \Dev\ProductComments\Controller\Adminhtml\Comment
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Comment
     */
    private $commentModel;
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * Add constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param Comment $commentModel
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        Comment $commentModel,
        CommentRepository $commentRepository
    ) {
        parent::__construct($context, $coreRegistry);
        $this->resultPageFactory = $resultPageFactory;
        $this->commentModel = $commentModel;
        $this->commentRepository = $commentRepository;
    }
    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */

    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Dev'), __('Dev'))
            ->addBreadcrumb(__('Product Comment'), __('Product Comment'));
        return $resultPage;
    }

    /**
     *
     * @return ResponseInterface|Redirect|ResultInterface|Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('comment_id');
        $model = $this->commentModel;

        if ($id) {
            try {
                $this->commentRepository->getById($id);
            } catch (NoSuchEntityException $e) {
            }
            if (!$model->getId()) {
                $this->messageManager
                    ->addErrorMessage(__('This Comment no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('product_comments', $model);

        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Comment') : __('New Comment'),
            $id ? __('Edit Comment') : __('New Comment')
        );
        $resultPage->getConfig()
            ->getTitle()
            ->prepend(
                $model->getId()
                ? __('Edit Comment %1', $model->getId()) : __('New Comment')
            );
        return $resultPage;
    }
}
