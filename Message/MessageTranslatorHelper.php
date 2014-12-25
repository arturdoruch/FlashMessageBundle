<?php
/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */

namespace ArturDoruch\FlashMessageBundle\Message;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;


class MessageTranslatorHelper
{
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var Request
     */
    private $request;

    public function __construct(TranslatorInterface $translator, RequestStack $requestStack)
    {
        $this->translator = $translator;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param null|string $messageType
     * @param null|string $message
     * @param array $parameters
     * @param null|string $domain
     * @param null|string $locale
     *
     * @return string Translated message
     */
    public function translate($messageType = null, $message = null, array $parameters = array(), $domain = null, $locale = null)
    {
        if ($message === null) {
            $message = $this->getTranslationId($messageType);
        }

        return $this->translator->trans($message, $parameters, $domain, $locale);
    }

    /**
     * Generates translationId string based on controller name. E.g.
     * Portfolio\ProjectBundle\Controller\TypeController::listAction will be converted into
     * "portfolio_project_type.list".
     *
     * @param string $type Message type (success, error, etc.).
     *                     If set then will be added to the end of the translationId string, like:
     *                     "portfolio_project_type.list.success"
     *
     * @return string
     */
    public function getTranslationId($type = null)
    {
        $controller = $this->request->get('_controller');
        $id = preg_replace('/(Bundle\\\|Controller(?=\\\|:)|Action$)/mi', "", $controller);
        $id = str_replace(array('\\', '::'), array('_', '.'), $id);
        if ($type !== null) {
            $id .= '.' . $type;
        }

        return strtolower($id);
    }

    /**
     * Generates translationId string for CRUD actions
     * based on controller action name and action type (create, remove, update)
     *
     * @param string $type Message type (success, error, etc.).
     * @param null|string $action Crud action name
     *
     * @return string
     */
    public function getCrudTranslationId($type, $action = null)
    {
        if (empty($action)) {
            $controller = explode('::', $this->request->get('_controller'));
            $action = substr($controller[1], 0, -6);
        }

        return strtolower(sprintf('crud.%s.%s', $action, $type));
    }

}
 