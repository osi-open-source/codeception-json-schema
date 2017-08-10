<?php

namespace Codeception\Module;

use JsonSchema\Uri\UriResolver;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;

/**
* Json schema module for codeception
*/
class JsonSchema extends \Codeception\Module
{
    /**
    *  Validate response by json schema
    *
    *  @param string $schema path to json schema file
    */
    public function canSeeResponseIsValidOnSchemaFile($schema)
    {
        $schemaRealPath = realpath($schema);

        $response = $this->getModule('REST')->response;

        $validator = new Validator();
        $schemaRef = (object)['$ref' => 'file://' . $schemaRealPath];
        $decodedResponse = json_decode($response);
        $validator->validate($decodedResponse, $schemaRef);

        $message = '';
        $isValid = $validator->isValid(); 
        if (! $isValid) {
            $message = 'JSON does not validate. Violations:'.PHP_EOL;
            foreach ($validator->getErrors() as $error) {
                $message .= $error['property'].' '.$error['message'].PHP_EOL;
            }
        }

        $this->assertTrue($isValid, $message);
    }
}
