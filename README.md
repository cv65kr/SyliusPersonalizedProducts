# Sylius Personalized Products Plugin

## Overview

I used [Apache PredictionIO](http://predictionio.apache.org/index.html) to create plugin, which shows personalize recommended products, based on watched products.

## Installation 

### Sylius

```bash
composer require cv65kr/sylius-personalized-products
```

Add plugin dependencies to your AppKernel.php file:
```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...
        
        new \cv65kr\SyliusPersonalizedProducts\SyliusPersonalizedProductsPlugin(),
    ]);
}
```

Import required config in your app/config/config.yml file and setup parameters:
```yaml
imports:
    ...
    
    - { resource: "@SyliusPersonalizedProductsPlugin/Resources/config/services.yml" }
    
parameters:
    sylius_prediction_event_host: http://machine_learning
    sylius_prediction_event_port: 7070
    sylius_prediction_engine_host: https://machine_learning
    sylius_prediction_engine_port: 8000
    sylius_prediction_key: 'a-mevXQWyArRnxmHvlFKrjHLdjuvhnpqOgYEu8XgvfpLW0RTuPl_wUUQo3ZWQa5F'
```

Import routing in your app/config/routing.yml file:
```yaml
app_personalized_products:
    resource: "@SyliusPersonalizedProductsPlugin/Resources/config/routing.yml"
```

Embed in template:
```twig
{{ render(path('sylius_personalized_products_controller')) }}
```

**Note 1:** Controller render should be used only for logged customers.

**Note 2:** You can use `limit` and `template` parameres in route.


### Apache PredictionIO

First of all, add in `docker-compose.yml` machine learning:
```yaml
  machine_learning:
      build: ../ml
      ports:
        - "9000:9000"
        - "7070:7070"
        - "8000:8000"
      volumes:
        - /ml/engine:/CustomEngine
``` 

And go inside container:
```bash
docker exec -it machine_learning bash
```

Next:
```bash
cd /CustomEngine
```

We need, download template for 0.9 version:
```bash
pio template get apache/predictionio-template-recommender --version v0.3.2 MyRecommendation
```

Create API key and paste them in Sylius config parameter - `sylius_prediction_key`:
```bash
pio app new SyliusPersonalizedProducts
```

Let’s verify that our new app is there with this command:
```bash
pio app list
```

In Sylius run:
```bash
bin/console s:p:p
```

And now back into `machine_learning` container
```bash
cd /CustomEngine/MyRecommendation
```

Build, they may few minutes:
```bash
pio build --verbose
```

Edit `engine.json`:
```bash
vim engine.json
```

Change:
```bash
"appName": "INVALID_APP_NAME"
```

To:
```bash
"appName": "SyliusPersonalizedProducts"
```

Next train:

Create sample data for training and import them by:
```bash
pio import --appid 1 --input data-sample.json
```

**Note:** Example sample file, You need modify this file: https://gist.githubusercontent.com/vaghawan/0a5fb8ddb85e03631dd500d7c8f0677d/raw/17487437dd8269588d9dd1ac859b129a43842ba5/data-sample.json

Next run:
```bash
pio train
```

And deploy, hooray!
```bash
pio deploy
```

## Contribution

Learn more about our contribution workflow on http://docs.sylius.org/en/latest/contributing/.
