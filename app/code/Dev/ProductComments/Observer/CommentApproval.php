<?php

namespace Dev\ProductComments\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Dev\ProductComments\Block\View;

class CommentApproval implements ObserverInterface
{
    /**
     * @var View
     */
    private $view;

    public function __construct(
     View $view
    ) {

        $this->view = $view;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $productId = $observer->getData('ProductId');
        $product = $this->view->getProductName($productId);
        $comment = $observer->getData('sentComment');
        $name = $observer->getData('SenderName');
        $to = $observer->getData('sentEmail');
        $subject = 'Your Comment is waiting for admin approval.';
        $message = 'Hellow '.$name.' your comment is currently beeing reviewed.' . "\r\n" .
            'you left comment for: '.$product . "\r\n" .
            'your comment is: '.$comment;
        $headers = 'From: magento@shopify.com' . "\r\n" .
            'Reply-To: Dev-alliance@xacho.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($to,$subject,$message,$headers);
    }
}

