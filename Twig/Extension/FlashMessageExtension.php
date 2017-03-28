<?php

namespace ArturDoruch\FlashMessageBundle\Twig\Extension;

use Symfony\Component\HttpFoundation\Session\Session;
use ArturDoruch\FlashMessageBundle\Templating\Helper\FlashMessageHelper as Helper;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class FlashMessageExtension extends \Twig_Extension
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Helper
     */
    private $helper;

    public function __construct(Session $session, Helper $helper)
    {
        $this->session = $session;
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'arturdoruch_flash_message';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('ad_flash_messages', [$this, 'renderMessages'], [
                    'is_safe' => ['html'],
                    'needs_environment' => true
                ]),
            new \Twig_SimpleFunction('ad_flash_messages_class_name', [$this, 'getMessageClassName'])
        );
    }

    /**
     * @param \Twig_Environment $environment
     * @param string            $type Message type. If null returns all types messages,
     *                                otherwise returns messages with given types.
     *
     * @return string
     */
    public function renderMessages(\Twig_Environment $environment, $type = null)
    {
        if ($type === null) {
            $messages = $this->session->getFlashBag()->all();
        } else {
            $messages[$type] = $this->session->getFlashBag()->get($type);
        }

        return $environment->render('ArturDoruchFlashMessageBundle::messages.html.twig', [
                'messages' => $messages
            ]);
    }

    /**
     * @param string $type Message type
     *
     * @return string String to use as HTML tag class name
     */
    public function getMessageClassName($type)
    {
        return $this->helper->getMessageClassName($type);
    }

}
 