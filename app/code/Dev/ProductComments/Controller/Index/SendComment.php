<?php

namespace Dev\ProductComments\Controller\Index;

use Zend_Validate;
use Zend_Validate_Exception;
use Dev\ProductComments\Model\Comment;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Dev\ProductComments\Model\ResourceModel\Comment as ResourceComment;
use Magento\Framework\Event\ObserverInterface;


class SendComment extends Action
{
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var Comment
     */
    private $commentModel;
    /**
     * @var ResourceComment
     */
    private $resourceModel;

    /**
     * SendComment constructor.
     * @param \Magento\Framework\Event\Manager $eventManager
     * @param Context $context
     * @param Comment $commentModel
     * @param ResourceComment $resourceModel
     */
    public function __construct(
        \Magento\Framework\Event\Manager $eventManager,
        Context $context,
        Comment $commentModel,
        ResourceComment $resourceModel
    ) {
        parent::__construct($context);
        $this->commentModel = $commentModel;
        $this->resourceModel = $resourceModel;
        $this->eventManager = $eventManager;
    }
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $name = $this->getRequest()->getParam('name');
        $email = $this->getRequest()->getParam('email');
        $comment = $this->getRequest()->getParam('comment');
        $productId =$this->getRequest()->getParam('productId');
        try {
            if (!Zend_Validate::is($name, 'NotEmpty')) {
                $this->messageManager->addErrorMessage('Name must not be empty');
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            }

            if (!Zend_Validate::is($comment, 'NotEmpty')) {
                $this->messageManager->addErrorMessage('Comment must not be empty');
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            } elseif (!Zend_Validate::is($email, 'EmailAddress')) {
                $this->messageManager->addErrorMessage('Email address not valid');
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            } else {
                $this->commentModel
                    ->setData('name', $name)
                    ->setData('email', $email)
                    ->setData('comment', $comment)
                    ->setData('status', 'not approved')
                    ->setData('product_id', $productId);
                try {
                    $this->resourceModel->save($this->commentModel);
                } catch (\Exception $e) {
                }

                $this->messageManager->addSuccessMessage('Comment request has been sent. Wait for admin approve');

                $this->_eventManager->dispatch('Comment_Approval',['ProductId'=>$productId,'SenderName'=>$name,'sentEmail'=>$email,'sentComment'=>$comment]);

                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            }
        } catch (Zend_Validate_Exception $e) {
        }
        return null;
    }
}
