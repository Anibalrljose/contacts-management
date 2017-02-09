app.controller('authCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {

    //initially set those objects to null to avoid undefined error

    $scope.signup = {};
    
    $scope.signup = {email:'',password:'',name:'',phone:'',address:''}; //gives the null value to those fields to avoid futures errors

    /**
     * function that post into api/v1/sinup the customer with the new contact that's going to be created
     */
    $scope.signUp = function (customer) {
        Data.post('signUp', {
            customer: customer
        }).then(function (results) {
            Data.toast(results); //prints the left-lower-coner's notification with the result [user created, updated, etc]
            if (results.status == "success") {
                $location.path('dashboard');
            }
        });
    };

        //this function is only called to save some important data from the view
    $scope.save = function(obj){
        $scope.retain = obj;
    };

    /**
     * function that post into api/v1/update the customer with the contact you want to update
     */
    $scope.update = function (customer) {

        Data.post('update', {
            customer: customer
        }).then(function (results) {
            Data.toast(results); //prints the left-lower-coner's notification
        });
    };
    /**
     * function that post into api/v1/delete the customer with the contact that's going to be deleted
     */
    $scope.delete = function (customer) {
         Data.post('delete', {
            customer: customer
        }).then(function (results) {
            Data.toast(results); //prints the left-lower-coner's notification
        });
    }
    
});