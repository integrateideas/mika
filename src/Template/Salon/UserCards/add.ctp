<?php

$salonTemplate = [
        'button' => '<button class="btn btn-primary m-b" {{attrs}}>{{text}}</button>',
        'input' => '<input type="{{type}}" class="form-control" name="{{name}}"{{attrs}}/>',
        'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
        'label' => '<label class="col-sm-2 control-label" {{attrs}}>{{text}}</label>',
];

$this->Form->setTemplates($salonTemplate);

?>
<script src="https://js.stripe.com/v3/"></script>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Add User Card') ?></h5>
            </div>
            <div class="ibox-content">
              
              <?= $this->Form->create($userCards, ['id' => 'payment-form'])?>
                <input name="stripe_token" id='stripe_token' hidden ='true' value='' >
                <div class="form-group">
                  <label for="card-element">
                    Credit or debit card
                  </label>
                  <div class="form-group">
                    <div id="card-element">
                      <!-- a Stripe Element will be inserted here. -->
                    </div>
                  </div>
                  <!-- Used to display form errors -->
                  <div id="card-errors" role="alert"></div>
                </div>
                <div class="form-group">
                  <div class="col-sm-4 col-sm-offset-4">
                    <button type='submit' class ='btn  btn-primary'>Submit</button>
                    <?= $this->Html->link('Cancel', ['controller' => 'Users' , 'action' => 'index'],['class' => ['btn', 'btn-danger']]);?>
                  </div>
                </div>
              <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
  .StripeElement {
  background-color: white;
  height: 40px;
  padding: 10px 12px;
  border-radius: 4px;
  border: 1px solid transparent;
  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}

.StripeElement--focus {
  box-shadow: 0 1px 3px 0 #cfd7df;
}

.StripeElement--invalid {
  border-color: #fa755a;
}

.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}
</style>
<script type="text/javascript">
  // Create a Stripe client
  var stripe = Stripe('pk_test_0Q6dnCgnIwyVHUdwHGVrwYnU');
  
  // Create an instance of Elements
  var elements = stripe.elements();

  // Custom styling can be passed to options when creating an Element.
  // (Note that this demo uses a wider set of styles than the guide below.)
  var style = {
    base: {
      color: '#32325d',
      lineHeight: '18px',
      fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
      fontSmoothing: 'antialiased',
      fontSize: '16px',
      '::placeholder': {
        color: '#aab7c4'
      }
    },
    invalid: {
      color: '#fa755a',
      iconColor: '#fa755a'
    }
  };

  // Create an instance of the card Element
  var card = elements.create('card', {style: style});

  // Add an instance of the card Element into the `card-element` <div>
  card.mount('#card-element');

  // Handle real-time validation errors from the card Element.
  card.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
      displayError.textContent = event.error.message;
    } else {
      displayError.textContent = '';
    }
  });

  // Handle form submission
  var form = document.getElementById('payment-form');
  form.addEventListener('submit', function(event) {
    event.preventDefault(); 


    stripe.createToken(card).then(function(result) {
      if (result.error) {
        // Inform the user if there was an error
        var errorElement = document.getElementById('card-errors');
        errorElement.textContent = result.error.message;
      } else {
        // Send the token to your server
        $('#stripe_token').val(result.token.id);
        form.submit();
      }
    });
  });

</script>