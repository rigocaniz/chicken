app.directive('tooltip', function(){
    return {
        restrict: 'A',
        link: function(scope, element, attrs){
            $(element).hover(function(){
                $(element).tooltip('show');
            }, function(){
                $(element).tooltip('hide');
            });
        }
    };
});

app.directive('numbersOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if ( text ) {
                    var transformedInput = text.replace( /[^0-9]/g, '' );

                    if ( transformedInput !== text ) {
                        ngModelCtrl.$setViewValue( transformedInput );
                        ngModelCtrl.$render();
                    }
                
                    return transformedInput;
                }

                return undefined;
            }

            ngModelCtrl.$parsers.push( fromUser );
        }
    };
});

app.directive('autofocus', function($timeout) {
    return {
        link: function(scope, element, attrs) {
            $timeout(function() {
                element.focus();
            });
        }
    }
});

app.directive('focusEnter', function () {
    return {
        restrict: 'A',
        link: function ($scope, selem, attrs) {
            selem.bind('keydown', function (e) {
                var code = e.keyCode || e.which;
                if (code === 13) {
                    e.preventDefault();
                    var pageElems = document.querySelectorAll('input, select, textarea, button'),
                        elem = e.srcElement || e.target,
                        focusNext = false,
                        len = pageElems.length;
                    for (var i = 0; i < len; i++) {
                        var pe = pageElems[i];
                        if (focusNext) {
                            if (pe.style.display !== 'none') {
                                angular.element(pe).focus();
                                break;
                            }
                        } else if (pe === elem) {
                            focusNext = true;
                        }
                    }
                }
            });
        }
    }
});
