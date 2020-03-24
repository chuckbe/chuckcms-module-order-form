# Order Form module for ChuckCMS 

### Requirements 

- Bootstrap v4.0 or higher
- jQuery v3.2.1 or higher
- ChuckCMS v0.1.39 or higher
- Laravel v5.8 or higher

### Installation

- Use composer to install
``` 
composer require chuckbe/chuckcms-module-order-form 
```
- Install module in ChuckCMS
``` 
php artisan chuckcms-module-order-form:install 
```
- Publish assets
``` 
php artisan vendor:publish --tag=chuckcms-module-order-form-public --force 
```
- Publish config
``` 
php artisan vendor:publish --tag=chuckcms-module-order-form-config --force 
```

### Usage

- Create a page for the order form in ChuckCMS and use a custom template file
- Inside that custom template file you can use the following method to call the necessary files
``` 
//use this to load css and styles
{!! ChuckModuleOrderForm::renderStyles() !!}

//use this to load js and scripts
{!! ChuckModuleOrderForm::renderScripts() !!}

//use this to load the form itself - do not wrap it in a container
{!! ChuckModuleOrderForm::renderForm() !!}
```
- Create a page for the order followup in ChuckCMS and use another custom template file
- Inside that custom template file you can use the following method to call the necessary files
``` 
//use this to load css and styles
{!! ChuckModuleOrderForm::followupStyles() !!}

//use this to load js and scripts
{!! ChuckModuleOrderForm::followupScripts() !!}

//use this to load the followup content itself - do not wrap it in a container
{!! ChuckModuleOrderForm::followupContent() !!}
```
- Update config file for necessary settings
- Add products
- Start accepting orders!

### Methods

``` 
ChuckModuleOrderForm::firstAvailableDate(string $location) 
```
This method accepts a location key as used in the config file and will return the first available date for ordering

``` 
ChuckModuleOrderForm::firstAvailableDateInDaysFromNow(string $location) 
```
This method accepts a location key as used in the config file and will return the first available date for ordering in number of days from now

``` 
ChuckModuleOrderForm::totalSales() 
```
The total amount of sales returned as formatted (no thousands separator, 2 decimals, ',' as a decimal separator) result

``` 
ChuckModuleOrderForm::totalSalesLast7Days() 
```
The total amount of sales of the last 7 days returned as formatted result

``` 
ChuckModuleOrderForm::totalSalesLast7DaysQty() 
```
The total number of sales of the last 7 days returned as integer

### Security 

Any security bugs discovered, please email to karel@chuck.be instead of using the issue reporter.

### License

© MIT License