<?php

namespace Dev\ProductComments\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\Store;
use Dev\ProductComments\Block\View;
use Exception;

class CommentApproval implements ObserverInterface
{
    protected $transportBuilder;
    /**
     * @var View
     */
    private $view;

    public function __construct(
        TransportBuilder $transportBuilder,
        View $view
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->view = $view;
    }
    public function execute(Observer $observer)
    {
        $email = $observer->getData('sentEmail');
        $comment = $observer->getData('sentComment');
        $name = $observer->getData('SenderName');
        $productId = $observer->getData('ProductId');
        $product = $this->view->getProductName($productId);
        $sender = [
            'name' => 'Magento team',
            'email' => 'Dev-alliance@magento.com',
        ];
        $templateParams = [
            'comment' => $comment,
            'name' => $name,
            'product' =>$product
        ];
        $transport = $this->transportBuilder
            ->setTemplateIdentifier('commentvisibility_email_template')
            ->setTemplateOptions(['area' => 'frontend', 'store' => Store::DEFAULT_STORE_ID])
            ->addTo($email)
            ->setTemplateVars($templateParams)
            ->setFrom($sender)
            ->getTransport();
        try {
            $transport->sendMessage();
        } catch (Exception $e) {
        }
        return $this;
    }
}
