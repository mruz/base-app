<h2>{{ __('Payment') ~ ' ' ~ __('Checkout') }}</h2><hr />
<table class="table">
    <thead>
        <tr>
            <td>{{ __('Description') }}</td>
            <td class="text-center" style="background: #ddd">{{ __('Quantity') }}</td>
            <td class="text-center">{{ __('Price') }}</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><span class="fwb">{{ __('Chocolate') }}</span></td>
            <td class="text-center" style="background: #f1f1f1">{{ checkout['quantity'] }}</td>
            <td class="text-center">${{ checkout['price'] }}</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="text-right">{{ __('Total') }}:</td>
            <td class="text-center text-success">${{ checkout['price']*checkout['quantity'] }}</td>
        </tr>
    </tfoot>
</table><br />
<h4>{{ __('Choose the payment method') }}:</h4><hr />
<div class="text-center">
    {{ linkTo(['payment/new/paypal', image('img/paypal.png', 'alt': 'PayPal'), 'title': 'PayPal']) }}
    {{ linkTo(['payment/new/dotpay', image('img/dotpay.png', 'alt': 'dotpay'), 'title': 'dotpay' ]) }}
</div>
<br />
<p>{{ __('Time of transfer realization is usually immediate (online), sometimes (eg. credit card payments) this time is up to 24 hours.') }}</p>
<br />
<p class="content-box notification box-green">
    {{ __('Payments are supported by') }}:
    {{ linkTo(['http://www.paypal.com', 'paypal.com', 'target': '_blank', 'local': false]) }} 
    {{ linkTo(['http://www.dotpay.pl', 'dotpay.pl', 'target': '_blank', 'local': false]) }}
</p>
            