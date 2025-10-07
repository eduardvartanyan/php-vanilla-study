<?php
declare(strict_types=1);

namespace Eduardvartanan\PhpVanilla\Domain;

use Eduardvartanan\PhpVanilla\Attributes\Attribute;

class Validator
{
    /** @return string[] */
    public function validateObject(object $obj): array
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

    /**
     * @throws \ReflectionException
     */
    public function validateValue(string $field, mixed $value, array $rules): array
    {
        $errors = [];

        foreach ($rules as $rule) {
            if (preg_match('/^([A-Za-z_][A-Za-z0-9_]*)(?:\((.*)\))?$/', $rule, $matches)) {
                $className = $matches[1];
                $args = [];
                if (isset($matches[2]) && $matches[2] !== '') {
                    $args = array_map('trim', explode(',', $matches[2]));
                }

                $fqcn = "Eduardvartanan\\PhpVanilla\\Attributes\\$className";
                if (!class_exists($fqcn)) {
                    $errors[] = "Валидатор $className не найден";
                    continue;
                }

                $refClass = new \ReflectionClass($fqcn);
                /** @var Attribute $instance */
                $instance = $refClass->newInstanceArgs($args);

                $err = $instance->validate($value, $field);
                if ($err) {
                    $errors[] = $err;
                }
            } else {
                $errors[] = "Некорректное правило: $rule";
            }
        }

        return $errors;
    }
}