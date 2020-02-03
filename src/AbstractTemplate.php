<?php

/** LICENSE */

namespace OBSolutions\Module\Templater;

abstract class AbstractTemplate implements ResponseInterface
{
    const TEMPLATES_RELATIVE_PATH = 'views' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;

    /**
     * @var \Module
     */
    protected $module;

    /**
     * @var \Context
     */
    protected $context;

    public function __construct(\Module $module)
    {
        $this->context = \Context::getContext();
        $this->module = $module;
    }

    protected function assign($keyOrValuesArray, $value = null)
    {
        if (is_array($keyOrValuesArray)) {
            $this->context->smarty->assign($keyOrValuesArray);
        } else {
            $this->context->smarty->assign($keyOrValuesArray, $value);
        }
    }

    protected function getTemplatePath($template) {
        return $this->module->getTemplatePath(self::TEMPLATES_RELATIVE_PATH .$template);
    }

    protected function fetch($template)
    {
        $templatePath = $this->getTemplatePath($template);

        if(is_callable([$this->module, 'fetch'])) {
            return $this->module->fetch($templatePath);
        }

        return $this->context->smarty->fetch($templatePath);
    }

    protected function l($translatableText) {
        try {
            $reflect = new \ReflectionClass($this);
            return $this->module->l($translatableText, strtolower($reflect->getShortName()));
        } catch (\ReflectionException $e) {
            return $translatableText;
        }
    }

    abstract public function getHtml();
}
