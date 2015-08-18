# shop Plugin

for cakePHP 1.3

eCommerce platform

## Installation

1. Mettre le contenu du plugin dans un dossier `newsletter` dans `app/plugins/`
2. Ajouter les plugins suivant dans "app/plugins" :
    - https://github.com/webtechnick/CakePHP-Paypal-IPN-Plugin/tree/cakephp1.3
    - http://projets.o2web.ca/gitlab/cakephp-plugins/o2form
3. Créé la Bd en exécutants les SQL suivant :
    - app\plugins\shop\database.sql
    - app\plugins\paypal_ipn\paypal_ipn.sql
    - le sql retourné par /langviews.php
    
## Configuration

### app\config\plugins\shop.php

Configuration de base :

```php
 <?php
     Configure::write('Shop.payment.paypal', array(
         //this is often an email
         'business'=>'paypal_username@hostname.ca',
         'devMode'=> true,
     ));
     
     Configure::write('Shop.currencies', array('cad','usd'));
     Configure::write('Shop.currency', 'cad');
     Configure::write('Shop.shippingTypes', array(
         'default'=>array(
             'price'=>50,
             'tax_applied'=>true,
             'descr'=>,
     )));
     
     Configure::write('Shop.emailAdmin', array('to'=>array('info@hostname.com')));
     Configure::write('Shop.dev.emailAdmin', array('to'=>array('dev@hostname.com')));
     
     Configure::write('Shop.countries', array('CA','US'));
     
     Configure::write('Shop.defaultCountry', 'CA');
     Configure::write('Shop.defaultRegion', 'QC');
 ?>
```

voir /libs/shop_config.php pour plus d'information

### Behavior Shop.Product

Ajouter le behavior "Shop.Product" dans le ou les models qui vont être des produits

```php
var $actsAs = array('Shop.Product');
```

## Intégration

### Lien mon panier

Ajouter le helper "Shop.Cart"

```php
var $helpers = array("Shop.Cart");
```

Pour avoir le nombre d'item le component "Shop.CartMaker" est nécessaire

```php
 <?php 
   echo $this->Cart->cartLink(array('label' => __("Your cart (%nbItem%)",true))); 
 ?>
```

```php
 <?php 
   //using html in the label
   echo $this->Cart->cartLink(array(
     'label' => __("Your cart <span>%nbItem%</span>",true),
     'escape' => false,
   )); 
 ?>
```

### Afficher le prix

Dans view d'un produit

```php
<?php echo $this->Shop->fullprice(); ?>
```

Ailleur :
```php
 <?php echo $this->Shop->fullprice($variable_contenant_le_data_du_produit); ?>
```

### Lien Acheter

Dans view d'un produit

```php
 <?php echo $this->Cart->buyLink(array('label'=>__('Add to your cart',true))) ?>
```
 
Ailleur :

```php
 <?php echo $this->Cart->buyLink(array('label'=>__('Add to your cart',true),'model'=>'MODEL','id'=>'ID')) ?>
```


### Bouton Acheter avec quantité ou autres options
```php
 <?php if($this->Shop->productDispo()){ ?>
   <?php echo $this->Form->create('ShopCart',array('url'=>$this->Cart->buyUrl(array('routed'=>false)))); ?>
   <?php echo $this->Cart->qteInput(array('label'=>__('Quantité',true))); ?>
   <?php echo $this->Form->submit(__('Add to your cart',true)); ?>
   <?php echo $this->Form->end(); ?>
 <?php } ?>
```

### Admin

Pour ajouter les champs de eCommerce dans les view Add et Edit d'un model

Ajouter le helper "Shop.Shop" dans le controller :

```php
var $helpers = array("Shop.Shop");
```

À l'intérieur des formulaires des views :

```php
 <?php
   echo $this->Shop->editForm();
 ?>
```