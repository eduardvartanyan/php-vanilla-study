<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla;

class Validator
{
    public function validate(object $obj): array
    {
        $errors = [];
        $ref = new \ReflectionObject($obj);
        $refProps = $ref->getProperties();
        foreach ($refProps as $prop) {
            $value = $prop->getValue($obj);
            $attrs = $prop->getAttributes();
            foreach ($attrs as $attr) {
                $instance = $attr->newInstance();
                $err = $instance->validate($value);
                $err = (string) $err;
                if ($err) {
                    $errors[] = $err;
                }
            }
        }
        return $errors;
    }
}