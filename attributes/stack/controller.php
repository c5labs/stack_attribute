<?php
/**
 * Stack Attribute Controller File.
 *
 * @author   Oliver Green <oliver@boxedcode.co.uk>
 * @license  See attached license file
 */
namespace Concrete\Package\StackAttribute\Attribute\Stack;

use Concrete\Core\Attribute\FontAwesomeIconFormatter;

class Controller extends \Concrete\Attribute\Number\Controller
{
    public function getIconFormatter()
    {
        return new FontAwesomeIconFormatter('navicon');
    }

    protected function getStacksKeyValueList()
    {
        $stacks = [];
        $list = new \Concrete\Core\Page\Stack\StackList;

        foreach ($list->get() as $stack) {
            $stacks[$stack->getCollectionID()] = $stack->getCollectionName();
        }

        return $stacks;
    }

    protected function getValueStackId()
    {
        if (($value = $this->getAttributeValue()) && ($obj = $value->getValueObject())) {
            return intval($obj->getValue());
        }
    }

    public function getSearchIndexValue()
    {
        return $this->getValueStackId();
    }

    public function getDisplayValue()
    {
        return $this->getPlainTextValue();
    }

    public function getAttributeValueClass()
    {
        return \Concrete\Core\Entity\Attribute\Value\Value\NumberValue::class;
    }

    public function getPlainTextValue()
    {
        $value = $this->getValue();

        if ($value) {
            return $value->getCollectionName();
        }
    }

    public function getValue()
    {
        if ($stackID = $this->getValueStackId()) {
            return \Concrete\Core\Page\Stack\Stack::getByID(
                $stackID
            );
        }
    }

    public function form()
    {
        parent::form();

        $stacks = $this->getStacksKeyValueList();

        $this->set('stacks', $stacks);
        $this->set('stackID', $this->getValueStackId());
    }

    protected function validateStackId($id)
    {
        $list = $this->getStacksKeyValueList();

        return array_key_exists($id, $list);
    }

    public function validateForm($p)
    {
        return $this->validateStackId($p['value']);
    }

    public function validateValue()
    {
        $val = $this->getAttributeValue()->getValue();

        return $this->validateStackId($val);
    }
}