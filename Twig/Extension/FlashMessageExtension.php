<?php
/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */

namespace ArturDoruch\FlashMessageBundle\Twig\Extension;

use Symfony\Component\HttpFoundation\Session\Session;
use ArturDoruch\FlashMessageBundle\Templating\Helper\FlashMessageHelper as Helper;

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

    /**
     * @var \Twig_Environment
     */
    private $environment;

    public function __construct(Session $session, Helper $helper)
    {
        $this->session = $session;
        $this->helper = $helper;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
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
            new \Twig_SimpleFunction('ad_flash_messages', array($this, 'renderMessages'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFunction('ad_flash_messages_class_name', array($this, 'getMessageClassName'), array())
        );
    }

    /**
     * @param null|string $type Message type. If null returns all types messages,
     * otherwise returns messages only with given types.
     * @return string
     */
    public function renderMessages($type = null)
    {
        if ($type === null) {
            $messages = $this->session->getFlashBag()->all();
        } else {
            $messages[$type] = $this->session->getFlashBag()->get($type);
        }

        return $this->environment->render('ArturDoruchFlashMessageBundle::messages.html.twig', array(
                'messages' => $messages
            ));
    }

    /**
     * @param string $type Message type
     * @return string String to use as HTML tag class name
     */
    public function getMessageClassName($type)
    {
        return $this->helper->getMessageClassName($type);
    }

}
 