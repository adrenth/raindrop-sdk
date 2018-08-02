# Hydro Raindrop SDK PHP

## 1.2.0

* Lower PHP requirement to `7.0`.
* [BC] Refactor `ApiAccessToken` method `getExpiresIn` to `getExpiresAt`.
* [BC] Refactor `TokenStorage` method `getAccessToken` to not be nullable anymore, 
  instead the exception `UnableToAcquireAccessToken` will be thrown when an access token 
  cannot be acquired from the storage. 

## 1.1.2

* Add proper `@throws` PhpDoc for some functions.

## 1.1.1

* Make method `getAccessToken` public. Can be used to verify if provided `ApiSettings` are valid.

## 1.1.0

* Added `UserAlreadyMappedToApplication` and `UsernameDoesNotExist` exceptions to allow custom error handling.
* Change `User-Agent` header value. Example: `adrenth.raindrop-sdk (PHP) version 1.1.0`

## 1.0.0

* Initial release.
