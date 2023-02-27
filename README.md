# Corporate Indrivo profile for Drupal

## What is Indrivo Corporate Profile?
Indrivo Corporate is a Drupal 9 profile designed to build corporate websites. It's based on the latest frontend technologies. The maintainer of Indrivo Corporate is [Indrivo](https://indrivo.com).

## What's in this repository?
This repository contains a Drupal profile. When you put it in the `/profiles/contrib/corporate_indrivo` directory, the Drupal installer gets modified and installs base CI theme, some module dependencies, and demo content.

## Installation
The CI profile should be installed via Composer. If you are starting from the scratch - in the **repositories** section of your composer.json put:

```json
{
  "type": "package",
  "package": {
    "name": "indrivo/corporate-indrivo",
    "version": "dev-main",
    "type": "drupal-profile",
    "source": {
      "url": "https://gitlab+deploy-token-61:RW5EdSsYdAyZbcFQKhuJ@gitlab.dev.indrivo.com/php-team/indrivo_profiles/corporate-indrivo.git",
      "type": "git",
      "reference": "master"
    },
    "minimum-stability": "dev",
    "require": {
      "drupal/core": "^8 || ^9 || ^10"
    }
  }
}
```
