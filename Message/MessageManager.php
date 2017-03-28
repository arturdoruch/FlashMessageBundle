<?php

namespace ArturDoruch\FlashMessageBundle\Message;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use ArturDoruch\FlashMessageBundle\Message\MessageTranslatorHelper;

/**
 * Sets custom type translated flash message. Override previous message is was set.
 * @method set(string $type, $message = null, array $parameters = array(), string $domain = null)
 *
 * @method setSuccess($message = null, array $parameters = array(), string $domain = null)
 * @method setError($message = null, array $parameters = array(), string $domain = null)
 * @method setNotice($message = null, array $parameters = array(), string $domain = null)
 *
 * Adds custom type translated flash message.
 * @method add(string $type, $message = null, array $parameters = array(), string $domain = null)
 *
 * @method addSuccess($message = null, array $parameters = array(), string $domain = null)
 * @method addError($message = null, array $parameters = array(), string $domain = null)
 * @method addNotice($message = null, array $parameters = array(), string $domain = null)
 *
 * Gets custom type translated message. Not adds into session flash bug.
 * Just creates, translates and returns message.
 * @method get(string $type, $message = null, array $parameters = array(), string $domain = null)
 *
 * @method getSuccess($message = null, array $parameters = array(), string $domain = null)
 * @method getError($message = null, array $parameters = array(), string $domain = null)
 * @method getNotice($message = null, array $parameters = array(), string $domain = null)
 *
 * Adds custom type CRUD action translated flash message.
 * @method addCrud(string $type, string $entity, $item = null, string $action = null)
 *
 * @method addCrudSuccess(string $entity, $item = null, string $action = null)
 * @method addCrudNotice(string $entity, $item = null, string $action = null)
 * @method addCrudError(string $entity, $item = null, string $action = null)
 *
 * Gets custom type CRUD action translated message.
 * @method getCrud(string $type, string $entity, $item = null, string $action = null)
 *
 * @method getCrudSuccess(string $entity, $item = null, string $action = null)
 * @method getCrudNotice(string $entity, $item = null, string $action = null)
 * @method getCrudError(string $entity, $item = null, string $action = null)
 *
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class MessageManager
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var MessageTranslatorHelper
     */
    private $translatorHelper;

    private $messageTypes = array(
        'success',
        'error',
        'notice'
    );

    public function __construct(Session $session, MessageTranslatorHelper $translatorHelper)
    {
        $this->flashBag = $session->getFlashBag();
        $this->translatorHelper = $translatorHelper;
    }

    /**
     * @param string $method
     * @param array $params
     */
    public function __call($method, $params)
    {
        preg_match('/^(add|set|get)(Crud|)(.*)$/', $method, $match);

        if ($match) {
            $type = empty($match[3]) ? array_shift($params) : $this->matchMessageType($match[3]);
            if ($type) {
                $method = ($match[2] === 'Crud') ? 'attachCrud' : 'attach';

                array_unshift($params, $type);     // Add type error, success, notice or custom name
                array_unshift($params, $match[1]); // Add action (set, add, get)

                return $this->callMethod($method, $params);
            }
        }

        throw new \InvalidArgumentException(
            sprintf('Method "%s" does not exists in class "%s".', $method, __CLASS__)
        );
    }

    private function matchMessageType($type)
    {
        foreach ($this->messageTypes as $msgType) {
            if ($type === ucfirst($msgType)) {
                return $msgType;
            }
        }

        return false;
    }

    /**
     * @param string $method
     * @param array $params
     *
     * @return string
     */
    private function callMethod($method, array $params)
    {
        return call_user_func_array(array($this, $method), $params);
    }

    /**
     * Adds, sets or gets (depend on $action parameter) translated messages for CRUD operations.
     * This method is calls by __call method.
     *
     * @param string $action            Action name [add, set, get]
     * @param string $type              Message type like: error, success, notice or custom name
     * @param string|object $entity
     * @param null|string $item
     * @param null|string $crudAction
     *
     * @return null|string
     */
    private function attachCrud($action, $type, $entity, $item = null, $crudAction = null)
    {
        $transId = $this->translatorHelper->getCrudTranslationId($type, $crudAction);
        $transParams = $this->parseCrudTranslateParams($entity, $item);

        return $this->attach($action, $type, $transId, $transParams, 'crudMessages');
    }

    /**
     * Adds, sets or gets translated messages depend on $action parameter.
     * This method is calls by __call method.
     *
     * @param string $action        Action name [add, set, get]
     * @param string $type          Message type like: error, success, notice or custom name
     * @param null|string $message
     * @param array $parameters     Parameters uses for translation message.
     * @param null|string $domain   Translate domain
     *
     * @return null|string
     */
    private function attach($action, $type, $message = null, array $parameters = array(), $domain = null)
    {
        $message = $this->translatorHelper->translate($type, $message, $parameters, $domain);

        if ($action !== 'get') {
            $this->flashBag->$action($type, $message);

            return null;
        } else {
            return $message;
        }
    }


    private function parseCrudTranslateParams($entity, $item = null)
    {
        if (is_object($entity)) {
            $getMethod = ($item === null) ? 'getName' : 'get' . ucfirst($item);
            if (method_exists($entity, $getMethod)) {
                $item = $entity->$getMethod();
            }

            $entity = get_class($entity);
            $entity = str_replace('\\', ' ', substr($entity, strrpos($entity, 'Entity\\') + 7));
        } elseif (!is_string($entity)) {
            $entity = 'entity';
        }

        if (!is_string($item)) {
            $item = null;
        }

        return array(
            '%entity%' => ucfirst($entity),
            '%item%' => $item
        );
    }

}
 