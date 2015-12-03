var Oranges = {};

Oranges.dataModel = function () {
    var self = this;

    self.victimName = ko.observable('').extend({ required: true, minLength: 5, maxLength: 70 });
    self.address1   = ko.observable('').extend({ required: true, maxLength: 100 });
    self.address2   = ko.observable('').extend({ maxLength: 100 });
    self.city       = ko.observable('').extend({ required: true, maxLength: 100 });
    self.county     = ko.observable('').extend({ required: true, minLength: 3, maxLength: 100 });
    self.postCode   = ko.observable('').extend({ required: true, pattern: /^(([gG][iI][rR] {0,}0[aA]{2})|((([A-PR-UWYZ][A-HK-Y]?[0-9][0-9]?)|(([A-PR-UWYZ][0-9][A-HJKSTUW])|([A-PR-UWYZ][a-hk-yA-HK-Y][0-9][ABEHMNPRV-Y]))) {0,}[0-9][ABD-HJLNP-UW-Z]{2}))$/i });

    self.emailAddress = ko.observable('').extend({ required: true, email: true });
    self.buyerName    = ko.observable('').extend({ required: true, minLength: 3, maxLength: 70 });
    self.cardNumber   = ko.observable('').extend({ required: true, minLength: 16 });
    self.expiryDate   = ko.observable('').extend({ required: true, minLength: 7, pattern: /^(0[1-9]|1[0-2]) ?\/? ?([0-9]{4}|[0-9]{2})$/ });
    self.cvc          = ko.observable('').extend({ required: true, minLength: 3 });

    self.token = ko.observable('');

    self.getCardDetails = function() {
        return {
            number : self.cardNumber(),
            name: self.buyerName(),
            exp: self.expiryDate(),
            cvc: self.cvc()
        };
    };

    self.getPayload = ko.computed(function (){
        var payload = {
            victimName   : self.victimName(),
            address1     : self.address1(),
            city         : self.city(),
            county       : self.county(),
            postCode     : self.postCode(),
            emailAddress : self.emailAddress(),
            buyerName    : self.buyerName(),
            token        : self.token(),
            _token       : $('.csrf-token').val()
        };

        if (self.address2().trim != '') {
            payload.address2 = self.address2();
        }

        return payload;
    });
};

Oranges.contactModel = function () {
    var self = this;
    self.name = ko.observable('').extend({ required: true, minLength: 5, maxLength: 70 });
    self.emailAddress = ko.observable('').extend({ required: true, email: true });
    self.message = ko.observable('').extend({ required: true });

    self.getPayload = ko.computed(function () {
        return {
            name         : self.name(),
            emailAddress : self.emailAddress(),
            message      : self.message(),
            _token       : $('.csrf-token').val()
        };
    });
}

Oranges.viewModel = function () {
    var self = this;

    self.dataModel = ko.validatedObservable(new Oranges.dataModel());
    self.contactModel = ko.validatedObservable(new Oranges.contactModel());

    self.isSubmitting = ko.observable(false);
    self.sendingMessage = ko.observable(false);
    self.messageSent = ko.observable(false);
    self.messageFailed = ko.observable(false);

    self.hasErrors = ko.observable(false);
    self.hasSuccess = ko.observable(false);

    self.errorMessage = ko.observable();

    self.resetForm = function () {
        self.dataModel().victimName('');
        self.dataModel().address1('');
        self.dataModel().address2('');
        self.dataModel().city('');
        self.dataModel().county('');
        self.dataModel().postCode('');
        self.dataModel().emailAddress('');
        self.dataModel().buyerName('');
        self.dataModel().cardNumber('');
        self.dataModel().expiryDate('');
        self.dataModel().cvc('');
        self.dataModel.errors.showAllMessages(false);
        var evt = document.createEvent('HTMLEvents');
        evt.initEvent('keyup', false, true);
        document.getElementById('cvc').dispatchEvent(evt);
        document.getElementById('expDate').dispatchEvent(evt);
        document.getElementById('buyerName').dispatchEvent(evt);
        document.getElementById('cardNumber').dispatchEvent(evt);
    };

    self.resetContactForm = function () {
        self.contactModel().name('');
        self.contactModel().emailAddress('');
        self.contactModel().message('');
        self.contactModel.errors.showAllMessages(false);
    };

    self.buttonHtml = function () {
        if(self.isSubmitting()) {
            return "<i class='fa fa-cog fa-spin'></i> Processing...";
        }
        return "Send an Orange Now!";
    };

    self.messageButtonHtml = function () {
        if(self.sendingMessage()) {
            return "<i class='fa fa-cog fa-spin'></i> Processing...";
        }
        return "Send Message!";
    };

    self.onBeforeSendCallback = function () {
        self.errorMessage('');
        self.hasErrors(false);
        self.hasSuccess(false);
        self.isSubmitting(true);
    };

    self.onSuccessCallback = function () {
        self.resetForm();
        self.hasSuccess(true);
    };

    self.onErrorCallback = function (response) {
        self.errorMessage(response.responseJSON.error);
        self.hasErrors(true);
    };

    self.onCompleteCallback = function () {
        self.isSubmitting(false);
        self.dataModel().token('');
    };

    self.sendForm = function () {
        $.ajax({
            method: 'POST',
            url: "/order",
            dataType: 'json',
            data: self.dataModel().getPayload(),
            beforeSend: self.onBeforeSendCallback,
            success: self.onSuccessCallback,
            error: self.onErrorCallback,
            complete: self.onCompleteCallback
        });
    };

    self.processCardCallback = function (status, response) {
        if (response.error) {
            self.hasErrors(true);
            console.log(response.error.message);
            self.errorMessage(response.error.message);
            self.onCompleteCallback();
        } else {
            self.dataModel().token(response.id);
            self.sendForm();
        }
    };

    self.processCard = function () {
        self.onBeforeSendCallback();
        Stripe.card.createToken(self.dataModel().getCardDetails(), self.processCardCallback);
    };

    self.submitForm = function () {
        if (self.isSubmitting()) {
            return false;
        }

        if(self.dataModel.isValid()) {
            self.processCard();
        } else {
            self.errorMessage("It looks like you haven't filled everything out yet, Check the form before trying to submit again");
            self.hasErrors(true);
        }
    };

    self.sendContactForm = function () {
        if(!self.contactModel.isValid()){
            return false;
        }

        $.ajax({
            method: 'POST',
            url: "/contact",
            dataType: 'json',
            data: self.contactModel().getPayload(),
            beforeSend: function () {
                self.sendingMessage(true);
                self.messageSent(false);
                self.messageFailed(false);
            },
            success: function () {
                self.messageSent(true);
                self.resetContactForm();
            },
            error: function () {
                self.messageFailed(true);
            },
            complete: function () {
                self.sendingMessage(false);
            }
        });
    };
};

Oranges.buyNow = function() {
    var self = this;
    self.viewModel = new Oranges.viewModel();
    ko.applyBindings(self.viewModel);
};
