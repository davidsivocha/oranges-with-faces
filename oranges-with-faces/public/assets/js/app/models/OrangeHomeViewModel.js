var OrangeHomeViewModel = function () {
    var self = this;

    self.shippingName       = ko.observable();
    self.shippingAddress1   = ko.observable();
    self.shippingAddress2   = ko.observable();
    self.shippingCity       = ko.observable();
    self.shippingCounty     = ko.observable();
    self.shippingPostcode   = ko.observable();
    self.stripeToken        = ko.observable();
    self.customerEmail      = ko.observable();

    self.pCodeRegex         = /^(([gG][iI][rR] {0,}0[aA]{2})|((([A-PR-UWYZ][A-HK-Y]?[0-9][0-9]?)|(([A-PR-UWYZ][0-9][A-HJKSTUW])|([A-PR-UWYZ][a-hk-yA-HK-Y][0-9][ABEHMNPRV-Y]))) {0,}[0-9][ABD-HJLNP-UW-Z]{2}))$/i

    self.stripeHandler = StripeCheckout.configure({
        key: 'pk_test_XzVtgWma4WvkoIlvFH3XBQRJ',
        image: 'http://orangeswithfaces.com/img/logo.png',
        locale: 'auto',
        token: self.stripeHandlerCallback
    });

    self.stripeHandlerCallback = function (token) {
        self.stripeToken(token.id);
        self.customerEmail(token.email);


    };

    self.validateForm = function() {};

    self.getPayload = function() {
        return {
            'shippingName'      : self.shippingName(),
            'shippingAddress1'  : self.shippingAddress1(),
            'shippingAddress2'  : self.shippingAddress2(),
            'shippingCity'      : self.shippingCity(),
            'shippingCounty'    : self.shippingCounty(),
            'shippingPostcode'  : self.shippingPostcode(),
            'stripeToken'       : self.stripeToken(),
            'customerEmail'     : self.customerEmail()
        };
    };

    self.beforeSend = function() {};
    self.sendSuccess = function () {};
    self.sendError = function () {};
    self.sendComplete = function () {};

    self.resetForm = function () {};

    self.sendOrange = function() {
        self.stripeHandler.open({
            name: 'Oranges with Faces',
            description: "It's an Orange with a face!",
            amount: 1000,
            currency: 'gbp'
        });
    };
}
