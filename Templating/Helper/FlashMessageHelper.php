<?php
/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */

namespace ArturDoruch\FlashMessageBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;


class FlashMessageHelper extends Helper
{
    /**
     * @var array Message html tag class names
     */
    private $classNames = array();

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return 'arturdoruch_flash_message';
    }

    public function setMessageClassNames(array $classNames = array())
    {
        $defaultMessageClassNames = array(
            'success' => 'success',
            'error' => 'danger',
            'notice' => 'warning'
        );

        $this->classNames = array_merge($defaultMessageClassNames, $classNames);
    }

    /**
     * @param string $type Message type
     * @return string Message html tag class name based on message type
     */
    public function getMessageClassName($type)
    {
        return isset($this->classNames[$type]) ? $this->classNames[$type] : '';
    }
}
