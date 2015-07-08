/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



odooApp.directive('angdatepicker', function() {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, element, attrs, ngModelCtrl) {
            element.datepicker({
                dateFormat: 'yy-mm-dd',
                inline: true,
                autoclose: true,
                onSelect: function(date) {
                    scope.date = date;
                    scope.$apply();
                }
            });
        }
    };
});



odooApp.directive('decimalPoint', function() {
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, modelCtrl) {
            modelCtrl.$parsers.push(function(inputValue) {
                if (inputValue === undefined)
                    return '';

                var transformedInput = inputValue.replace(/[^0-9.]/g, '');
                if (transformedInput !== inputValue) {
                    modelCtrl.$setViewValue(transformedInput);
                    modelCtrl.$render();
                }
                return transformedInput;
            });
        }
    };
});



odooApp.directive('numbersOnly', function() {
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, modelCtrl) {
            modelCtrl.$parsers.push(function(inputValue) {
                // this next if is necessary for when using ng-required on your input. 
                // In such cases, when a letter is typed first, this parser will be called
                // again, and the 2nd time, the value will be undefined
                if (inputValue === undefined)
                    return '';

                var transformedInput = inputValue.replace(/[^0-9]/g, '');
                if (transformedInput !== inputValue) {
                    modelCtrl.$setViewValue(transformedInput);
                    modelCtrl.$render();
                }
                return transformedInput;
            });
        }
    };
});




odooApp.directive('modal', function() {
    return {
        template: '<div class="modal fade">' +
                '<div class="modal-dialog">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
                '<h4 class="text-left modal-title">{{ title  }}</h4>' +
                '</div>' +
                '<div class="modal-body" ng-transclude></div>' +
                '</div>' +
                '</div>' +
                '</div>',
        restrict: 'E',
        transclude: true,
        replace: true,
        scope: true,
        link: function postLink(scope, element, attrs) {
            scope.title = attrs.title;
            scope.$watch(attrs.visible, function(value) {
                (value === true) ? $(element).modal('show') : $(element).modal('hide');
            });

            $(element).on('shown.bs.modal', function() {
                scope.$apply(function() {
                    scope.$parent[attrs.visible] = true;

                });
            });

            $(element).on('hidden.bs.modal', function() {
                scope.$apply(function() {
                    scope.$parent[attrs.visible] = false;
                });
            });
        }
    };
});



odooApp.directive('datePicker', function() {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, element, attrs, ngModelCtrl) {
            $(function() {
                element.datepicker({
                    dateFormat: 'yy-mm-dd',
                    showOn: 'both',
                    buttonImage: "images/calendar-128.png",
                    buttonImageOnly: true,
                     autoclose: true,
                    buttonText: 'Date',
                  // showButtonPanel: true,
                  //  changeMonth: true,
                  //  changeYear: true,
                    beforeShow: function(element, datepicker) {
                        if (attrs.minDate) {
                            angular.element(element).datepicker("option", "minDate", attrs.minDate);
                        }
                        if (attrs.maxDate) {
                            angular.element(element).datepicker("option", "maxDate", attrs.maxDate);
                        }
                       // angular.element(element).datepicker("option", "showAnim", 'slideDown');
                      
                        
                    },
                    onSelect: function(date) {
                        scope.$apply(function() {
                            ngModelCtrl.$setViewValue(date);
                           
                        });
                    }
                });
            });
        }
    };
});


odooApp.directive('idatePicker', function() {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, element, attrs, ngModelCtrl) {
            $(function() {
                element.datepicker({
                    dateFormat: 'yy-mm-dd',
                  //  showOn: 'both',
                    beforeShow: function(element, datepicker) {
                        if (attrs.minDate) {
                            angular.element(element).datepicker("option", "minDate", attrs.minDate);
                        }
                        if (attrs.maxDate) {
                            angular.element(element).datepicker("option", "maxDate", attrs.maxDate);
                        }
                    },
                    onSelect: function(date) {
                        scope.$apply(function() {
                            ngModelCtrl.$setViewValue(date);                          
                        });
                    }
                });
            });
        }
    };
});





