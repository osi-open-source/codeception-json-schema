<?php

namespace Codeception\Module;

use Codeception\Module;
use JsonSchema\Validator;

/**
 * Json schema module for codeception
 */
class JsonSchema extends Module
{
    /**
     * Validate response by json schema
     * @param string $schemaRef object or json string
     */
    public function seeResponseIsValidOnSchema($schemaRef)
    {
        $response = $this->getModule('REST')->response;

        $validator = new Validator();
        $decodedResponse = json_decode($response);
        $validator->validate($decodedResponse, $schemaRef);

        $message = '';
        $isValid = $validator->isValid();
        if (!$isValid) {
            $message = 'JSON does not validate. Violations:' . PHP_EOL;
            foreach ($validator->getErrors() as $error) {
                $message .= $error['property'] . ' ' . $error['message'] . PHP_EOL;
            }
        }

        $this->assertTrue($isValid, $message);
    }

    /**
     * Validate response by json schema
     * @param string $schema path to json schema file
     */
    public function seeResponseIsValidOnSchemaFile($schema)
    {
        $schemaRef = (object)['$ref' => 'file://' . realpath($schema)];
        $this->seeResponseIsValidOnSchema($schemaRef);
    }
}
