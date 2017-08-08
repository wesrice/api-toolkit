<?php

namespace wesrice\apitoolkit\models;

use Craft;
use craft\base\Model;
use wesrice\apitoolkit\schema\v2\Craft as CraftSchema;
use wesrice\apitoolkit\base\SchemaInterface;

class Settings extends Model
{
    public $schemata = [
        CraftSchema::class,
    ];

    public function rules()
    {
        return [
            [['schemata'], 'required'],
            [['schemata'], 'isArrayOfValidSchemaObjects'],
        ];
    }

    public function isArrayOfValidSchemaObjects($attribute, $params, $validator)
    {
        if (!is_array($this->$attribute)) {
            $this->addError(
                $attribute, 
                sprintf(
                    '`%s` must be an array.',
                    $attribute
                )
            );
        }

        foreach ($this->$attribute as $object) {
            $object = new $object;

            if (!$object instanceof SchemaInterface) {
                $this->addError(
                    $attribute, 
                    sprintf(
                        '`%s` must be an instance of `%s`.',
                        $attribute,
                        SchemaInterface::class
                    )
                );
            }

            $object->validate();

            Craft::dd($object->errors);
        }
    }
}
