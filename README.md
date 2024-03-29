# Order Form module for ChuckCMS 

### Requirements 

- Laravel v6.2 or higher
- ChuckCMS v0.1.39 or higher
- Bootstrap v4.0 or higher
- jQuery v3.2.1 or higher

### Installation

- Use composer to install
``` 
composer require chuckbe/chuckcms-module-order-form 
```
- Publish config
``` 
php artisan vendor:publish --provider="Chuckbe\ChuckcmsModuleOrderForm\ChuckcmsModuleOrderFormServiceProvider" --tag=order-form-config 
```
- Publish migrations
``` 
php artisan vendor:publish --provider="Chuckbe\ChuckcmsModuleOrderForm\ChuckcmsModuleOrderFormServiceProvider" --tag=order-form-migrations 
```
- Publish assets
``` 
php artisan vendor:publish --provider="Chuckbe\ChuckcmsModuleOrderForm\ChuckcmsModuleOrderFormServiceProvider" --tag=order-form-assets 
```
- Publish views
``` 
php artisan vendor:publish --provider="Chuckbe\ChuckcmsModuleOrderForm\ChuckcmsModuleOrderFormServiceProvider" --tag=order-form-views 
```
> Or publish all at once!
> ``` 
> php artisan vendor:publish --provider="Chuckbe\ChuckcmsModuleOrderForm\ChuckcmsModuleOrderFormServiceProvider" --tag=order-form-config 
> ```

- Run migrations
``` 
php artisan migrate 
```
- Install module in ChuckCMS
``` 
php artisan chuckcms-module-order-form:install 
```

### Usage

- After installation make sure to add a location, add a category and a product.
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
@if(session('order_number'))
{!! ChuckModuleOrderForm::followupStyles(session('order_number')) !!}
@endif

//use this to load js and scripts
@if(session('order_number'))
{!! ChuckModuleOrderForm::followupScripts(session('order_number')) !!}
@endif

//use this to load the followup content itself - do not wrap it in a container
@if(session('order_number'))
{!! ChuckModuleOrderForm::followupContent(session('order_number')) !!}
@endif
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