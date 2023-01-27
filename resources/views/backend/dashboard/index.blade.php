@extends('chuckcms::backend.layouts.base')

@section('title')
	Order Form
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		<li class="breadcrumb-item active"><a href="{{ route('dashboard.module.order_form.index') }}">Overzicht</a></li>
	</ol>
@endsection

@section('content')
<div class="container">
<!-- START ROW -->
<div class="row">
  <div class="col-sm-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mt-3">
        <li class="breadcrumb-item active" aria-current="Overzicht">Overzicht</li>
      </ol>
    </nav>
  </div>
  <div class="col-lg-6 col-sm-12  d-flex flex-column">
    
    <!-- START WIDGET widget_weekly_sales_card-->
    <div class="card no-border widget-loader-bar m-b-10 mb-2">
      <div class="container-xs-height full-height">
        <div class="row-xs-height">
          <div class="col-xs-height col-top">
            <div class="card-header  top-left top-right">
              <div class="card-title">
                <span class="font-montserrat fs-11 all-caps">Opbrengst Laatste 7. Dagen <i class="fa fa-chevron-right"></i>
	                        </span>
              </div>
              <div class="card-controls">
                <ul style="list-style: none">
                  <li><a href="#" class="portlet-refresh text-black" data-toggle="refresh"><i class="portlet-icon portlet-icon-refresh"></i></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row-xs-height">
          <div class="col-xs-height col-top">
            <div class="p-l-20 p-t-50 p-b-40 p-r-20 p-3 pb-5">
              <h3 class="no-margin p-b-5">€ {{ ChuckModuleOrderForm::totalSalesLast7Days() }}</h3>
              <span class="small hint-text pull-left">{{ ChuckModuleOrderForm::totalSalesLast7DaysQty() }} bestellingen</span>
            </div>
          </div>
        </div>

      </div>
    </div>
    <!-- END WIDGET -->

    <!-- START WIDGET widget_weekly_sales_card-->
    <div class="card no-border widget-loader-bar m-b-10 mt-2">
      <div class="container-xs-height full-height">
        <div class="row-xs-height">
          <div class="col-xs-height col-top">
            <div class="card-header  top-left top-right">
              <div class="card-title">
                <span class="font-montserrat fs-11 all-caps">Bestellingen <i class="fa fa-chevron-right"></i>
	                        </span>
              </div>
              <div class="card-controls">
                <ul style="list-style: none">
                  <li><a href="#" class="portlet-refresh text-black" data-toggle="refresh"><i class="portlet-icon portlet-icon-refresh"></i></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row-xs-height">
          <div class="col-xs-height col-top">
            <div class="p-l-20 p-t-50 p-b-40 p-r-20 p-3 pb-5">
              <h3 class="no-margin p-b-5">{{ count($orders) }}</h3>
              <span class="small hint-text pull-left">Totaal</span>
            </div>
          </div>
        </div>
        
      </div>
    </div>
    <!-- END WIDGET -->
  </div>

  <div class="col-lg-6 m-b-10 d-flex">
    <!-- START WIDGET widget_pendingComments.tpl-->
    <div class="col p-0 widget-11-2 card no-border card-condensed no-margin widget-loader-circle align-self-stretch d-flex flex-column">
      <div class="card-header top-right">
        <div class="card-controls">
          <ul style="list-style: none">
            <li><a data-toggle="refresh" class="portlet-refresh text-black" href="#"><i
							class="portlet-icon portlet-icon-refresh"></i></a>
            </li>
          </ul>
        </div>
      </div>
      <div class="padding-25 p-3">
        <div class="pull-left">
          <h2 class="text-success no-margin">{{ ChuckSite::getSite('name') }}</h2>
          <p class="no-margin">Laatste bestellingen</p>
        </div>
        <div class="pull-right">
			<p class="no-margin ">Totaalomzet</p>
        	<div class="clearfix"></div>
        	<h3 class=" semi-bold">
        	€ {{ ChuckModuleOrderForm::totalSales() }}
			</h3>
		</div>
      </div>
      <div class="auto-overflow widget-11-2-table">
        <table class="table table-condensed table-hover">
          <tbody>
          	@foreach($orders as $order)
            <tr>
              <td class="font-montserrat all-caps fs-12 w-75">Bestelling #{{ $order->entry['order_number'] }}</td>
              <td class="w-25 b-l b-dashed b-grey">
                <span class="font-montserrat fs-18">€ {{ number_format((float)$order->entry['order_price'], 2, ',', '.') }}</span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="padding-25 mt-auto p-3">
        <p class="small no-margin">
          <a href="{{ route('dashboard.module.order_form.orders.index') }}"><i class="fa fs-16 fa-arrow-circle-o-down text-success m-r-10"></i></a>
          <span class="hint-text ">Bekijk alle bestellingen</span>
        </p>
      </div>
    </div>
    <!-- END WIDGET -->
  </div>
</div>
<!-- END ROW -->
</div>
@endsection

@section('scripts')

@endsection