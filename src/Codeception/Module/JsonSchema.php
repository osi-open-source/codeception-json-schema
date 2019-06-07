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
     * @param string|object|array|null $schemaRef object or json string
     * @return void
     */
    public function seeResponseIsValidOnSchema($schemaRef, $response = null): void
    {
        if(!isset($response)) {
            $response = $this->getModule('REST')->grabResponse();
        }
        if(is_array($response) || is_object($response)) {
            $response = json_encode($response);
        }

        $validator = new Validator();
        $decodedResponse = json_decode($response);
        $validator->validate($decodedResponse, $schemaRef);

        $message = '';
        $isValid = $validator->isValid();
        if (!$isValid) {
            $this->debug($response);
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
    public function seeResponseIsValidOnSchemaFile(string $schema)
    {
        $schemaRef = (object)['$ref' => 'file://' . realpath($schema)];
        $this->seeResponseIsValidOnSchema($schemaRef);
    }

    /**
     * Validate response by json schema
     * @param string|array|null $json json data
     * @param string $schema path to json schema file
     */
    public function seeJsonValidOnSchemaFile($json, string $schema)
    {
        $schemaRef = (object)['$ref' => 'file://' . realpath($schema)];
        $this->seeResponseIsValidOnSchema($schemaRef, $json);
    }
}
