(function() {
    var StripeBilling = {
        init: function() {
            this.form = $('#checkout-form-2');
            this.submitButton = this.form.find('input[type=submit]');
            this.submitButtonValue = this.submitButton.val();

            var stripeKey = $('meta[name="publishable-key"]').attr('content');
            Stripe.setPublishableKey(stripeKey);

            this.bindEvents();
        },

        bindEvents: function() {
            this.form.on('submit', $.proxy(this.sendToken, this));
        },

        sendToken: function(event) {
            this.submitButton.val('One Moment').prop('disabled', true);

            Stripe.createToken(this.form, $.proxy(this.stripeResponseHandler, this));
            event.preventDefault();
        },

        stripeResponseHandler: function(status, response) {
            if (response.error){
                this.form.find('.error').show().text(response.error.message);
                return this.submitButton.prop('disabled', false).val(this.submitButtonValue);
            }

            $('<input>', {
                type: 'hidden',
                name: 'stripe-token',
                value: response.id
            }).appendTo(this.form);

            this.form[0].submit();
        }
    };

    StripeBilling.init();
})();
