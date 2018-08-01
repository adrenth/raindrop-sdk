# Hydro Raindrop SDK PHP

This package provides a suite of convenience functions intended to simplify the integration of Hydro's [Raindrop authentication](https://www.hydrogenplatform.com/hydro) into your project. 
More information, including detailed API documentation, is available in the [Raindrop documentation](https://www.hydrogenplatform.com/docs/hydro/v1/#Raindrop). 

Raindrop comes in two flavors:

## Client-side Raindrop
Client-side Raindrop is a next-gen 2FA solution. Hydro has open-sourced the [code powering Client-side Raindrop](https://github.com/hydrogen-dev/smart-contracts/tree/master/client-raindrop).

## Server-side Raindrop
Server-side Raindrop is an enterprise-level security protocol to secure APIs and other shared resources. Hydro has open-sourced the [code powering Server-side Raindrop](https://github.com/hydrogen-dev/smart-contracts/tree/master/hydro-token-and-raindrop-enterprise).

## Installation instructions

`composer require adrenth/raindrop-sdk`

## Usage

```
$settings = new \Adrenth\Raindrop\ApiSettings(
    $clientId,
    $clientSecret,
    new SandboxEnvironment
);

// Create token storage for storing the API's access token.
$tokenStorage = new \Adrenth\Raindrop\TokenStorage\FileTokenStorage(__DIR__ . '/token.txt');

// Ideally create your own TokenStorage adapter. 
// The shipped FileTokenStorage is purely an example of how to create your own.

/*
 * Client-side calls
 */
$client = new \Adrenth\Raindrop\Client($settings, $tokenStorage, $applicationId);

// (Un)register a user by it's Hydro ID
$client->registerUser($hydroId);
$client->unregisterUser($hydroId);

// Generate 6 digit message
$message = $client->generateMessage();

// Verify signature
$client->verifySignature($hydroId, $message);

/*
 * Server-side calls
 */
$server = new \Adrenth\Raindrop\Server($settings, $tokenStorage);

$server->whitelist('0x..'); // Provide ETH address
$server->challenge('41579b51-c365-406e-86a8-3839fcad576f');
$server->authenticate('41579b51-c365-406e-86a8-3839fcad576f');
```
