var OrangeHomePageView = function () {
    var self = this;
    self.viewModel = new OrangeHomeViewModel();
    ko.applyBindings(self.viewModel);
};
