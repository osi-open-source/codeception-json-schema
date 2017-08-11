# codeception-json-schema
Codeception Module to validate json on a schema.

## Installation
```bash
composer require digitaladapt/codeception-json-schema
```

## Usage
```php
<?php
class MessageApiCest
{
    public function aTest(ApiTester $I)
    {
        /* call api */
        $I->wantTo('Ensure API Returns Json which matches schema file.');
        $I->sendGET('/path/to/api');

        /* check if api matches schema */
        $I->seeResponseIsValidOnSchemaFile('/path/to/schema.json');
    }

    public function bTest(ApiTester $I)
    {
        /* call api */
        $I->wantTo('Ensure API Returns Json which matches schema file.');
        $I->sendGET('/path/to/api');

        /* alternative syntax, check if api matches schema */
        $I->canSeeResponseIsValidOnSchemaFile('/path/to/schema.json');
    }

    public function cTest(ApiTester $I)
    {
        /* call api */
        $I->wantTo('Ensure API Returns Json which matches inline schema.');
        $I->sendGET('/path/to/api');

        /* if you don't have a separate schema file, that is alright, you can use inline schema */
        /* this schema expects the api to return something like {"message": "SOME_STRING"} */
        /* schema as php objects */
        $schema = (object)[
            'type' => 'object',
            'properties' => (object)[
                'message' => (object)[
                    'type' => 'string',
                ],
            ],
            'required' => ['message'],
        ];

        $I->seeResponseIsValidOnSchema($schema);
    }

    public function dTest(ApiTester $I)
    {
        /* call api */
        $I->wantTo('Ensure API Returns Json which matches inline schema.');
        $I->sendGET('/path/to/api');

        /* if you don't have a separate schema file, that is alright, you can use inline schema */
        /* this schema expects the api to return something like {"message": "SOME_STRING"} */
        /* json string, must be decoded before checking if response is valid */
        $jsonSchema = '{"type":"object","properties":{"message":{"type":"string"}},"required":["message"]}';

        /* alternative syntax, notice we decoded the json string */
        $I->canSeeResponseIsValidOnSchema(json_decode($jsonSchema));
    }
}
```

## Also See
Codeception has a [built-in syntax for simple json matches](http://codeception.com/docs/modules/REST#seeResponseMatchesJsonType),
however it is not compatible with json schema, which is why this was created.

```php
<?php
class MessageApiCest
{
    public function eTest(ApiTester $I)
    {
        /* call api */
        $I->wantTo('Ensure API Returns Json which matches type.');
        $I->sendGET('/path/to/api');

        /* this type expects the api to return something like {"message": "SOME_STRING"} */
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);
    }
}
```

## Contributing
If you notice anything wrong or have any suggestions on how to make this better, please open an issue.
