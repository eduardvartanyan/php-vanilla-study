<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain;

use Eduardvartanan\PhpVanilla\Attributes\Attribute;

class Validator
{
    /** @return string[] */
    public function validate(object $obj): array
    {
        $errors = [];
        $ref = new \ReflectionObject($obj);
        $refProps = $ref->getProperties();
        foreach ($refProps as $prop) {
            $prop->setAccessible(true);
            $value = $prop->getValue($obj);
            $attrs = $prop->getAttributes();
            foreach ($attrs as $attr) {
                $instance = $attr->newInstance();
                if (!$instance instanceof Attribute) {
                    continue;
                }

                $propName = $prop->getName();
                $err = $instance->validate($value, $propName);
                $err = (string) $err;
                if ($err) {
                    $errors[] = $err;
                }
            }
        }
        return $errors;
    }
}