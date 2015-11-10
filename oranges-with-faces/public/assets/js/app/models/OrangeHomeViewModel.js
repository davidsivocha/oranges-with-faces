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

    self.sendOrange = function() {};
    self.validateForm = function() {};
    self.getPayload = function() {};
}
