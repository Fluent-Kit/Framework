@extends('layouts.master')

@section('content')
    <script>
        function formController($scope, $http, CSRF_TOKEN, $timeout) {

			// create a blank object to hold our form information
			// $scope will allow this to pass between controller and view
			$scope.formData = {
                "_token": CSRF_TOKEN
            }; 
            
            $scope.submit = 'Submit!';
            $scope.global_error = '';
            $scope.install_msgs = '';
            
            $scope.processForm = function() {
                $scope.global_error = '';
                $scope.sql_error = '';
                $scope.submit = 'Submitting...';
                $scope.timeout = 0;
                $http.post('/install', $scope.formData)
                .success(function(data) {
                    document.getElementById("progress").scrollIntoView();
                    console.log(data);
                    if(data.status == 'error'){
                        $scope.global_error = data.message;
                        $scope.sql_error = data.sql_error;
                        if(data.errors){
                            if(data.errors.url){
                                $scope.form.url.remote_error = data.errors.url;
                            }
                            if(data.errors.dbhost){
                                $scope.form.dbhost.remote_error = data.errors.dbhost;
                            }
                            if(data.errors.dbname){
                                $scope.form.dbname.remote_error = data.errors.dbname;
                            }
                            if(data.errors.dbuser){
                                $scope.form.dbuser.remote_error = data.errors.dbuser;
                            }
                            if(data.errors.key){
                                $scope.form.key.remote_error = data.errors.key;
                            }
                        }
                        $scope.submit = 'Resubmit!';
                    }else if(data.status == 'success'){
                        $scope.global_success = data.message;
                        $scope.install_msgs = data.tasks;
                        $scope.submit = 'Submit!';
                    }
                });
			};

		}
    </script>
    <div class="row" ng-controller="formController" style="padding-bottom:60px;">
        <div class="col-md-6 col-md-offset-3">
            <form role="form" novalidate name="form">
                
                <div class="page-header">
                  <h1 class="textcenter"><span class="glyphicon glyphicon-inbox"></span> FluentKit Installer</h1>
                </div>
                
                <div class="label label-danger" ng-show="global_error">
                    [[ global_error ]]
                    <code ng-show="sql_error">[[ sql_error ]]</code>
                </div>
                
                <div ng-repeat="msg in install_msgs track by $index" id="progress"><span class="label label-success">[[ msg ]]</span></div>
                
                <div class="label label-success" ng-show="global_success">[[ global_success ]]</div>
                
                
                
                <div id="url-group" class="form-group" ng-class="{ 'has-error' : form.url.$error.required && form.url.$dirty || form.url.$error.url && form.url.$dirty || form.url.remote_error }">
                    <p>Enter the url FluentKit will be installed on.</p>
                    <label for="url">Website Address</label>
                    <input type="url" name="url" id="url" class="form-control" placeholder="http://fluentkit.com" ng-model="formData.url" required>
                    <span class="help-block" ng-show="form.url.$error.required && form.url.$dirty">Website Address Required!</span>
                    <span class="help-block" ng-show="form.url.$error.url && form.url.$dirty">Website Address Invalid!</span>
                    <span class="help-block" ng-show="form.url.remote_error">[[ form.url.remote_error ]]</span>
                </div>
                
                <div class="page-header" ng-show="form.url.$valid">
                  <h4 class="textcenter"><span class="glyphicon glyphicon-inbox"></span> FluentKit Database Details</h4>
                </div>
                
                <p ng-show="form.url.$valid">Enter your MySQL database details so FluentKit can install the required tables.</p>
                
                <div id="db-host-group" class="form-group" ng-class="{ 'has-error' : form.dbhost.$error.required && form.dbhost.$dirty || form.dbhost.remote_error }" ng-show="form.url.$valid">
                    <label for="db-host">Database Host</label>
                    <input type="text" name="dbhost" id="db-host" class="form-control" placeholder="localhost" ng-model="formData.dbhost" required>
                    <span class="help-block" ng-show="form.dbhost.$error.required && form.dbhost.$dirty">Database Host Required!</span>
                    <span class="help-block" ng-show="form.dbhost.remote_error">[[ form.dbhost.remote_error ]]</span>
                </div>
                
                <div id="db-name-group" class="form-group" ng-class="{ 'has-error' : form.dbname.$error.required && form.dbname.$dirty || form.dbname.remote_error }" ng-show="form.dbhost.$valid">
                    <label for="db-name">Database Name</label>
                    <input type="text" name="dbname" id="db-name" class="form-control" placeholder="fluentkit" ng-model="formData.dbname" required>
                    <span class="help-block" ng-show="form.dbname.$error.required  && form.dbname.$dirty">Database Name Required!</span>
                    <span class="help-block" ng-show="form.dbname.remote_error">[[ form.dbname.remote_error ]]</span>
                </div>
                
                <div id="db-user-group" class="form-group" ng-class="{ 'has-error' : form.dbuser.$error.required && form.dbuser.$dirty || form.dbuser.remote_error }" ng-show="form.dbname.$valid">
                    <label for="db-user">Database User</label>
                    <input type="text" name="dbuser" id="db-user" class="form-control" placeholder="root" ng-model="formData.dbuser" required>
                    <span class="help-block" ng-show="form.dbuser.$error.required && form.dbuser.$dirty">Database Username Required!</span>
                    <span class="help-block" ng-show="form.dbuser.remote_error">[[ form.dbuser.remote_error ]]</span>
                </div>
                
                <div id="db-password-group" class="form-group" ng-show="form.dbuser.$valid">
                    <label for="db-password">Database Password</label>
                    <input type="text" name="dbpassword" id="db-password" class="form-control" placeholder="" ng-model="formData.dbpassword">
                    <span class="help-block" ng-show="form.dbpassword.remote_error">[[ form.dbpassword.remote_error ]]</span>
                </div>
                
                <div class="page-header" ng-show="form.dbuser.$valid">
                  <h4 class="textcenter"><span class="glyphicon glyphicon-lock"></span> FluentKit Secret Key</h4>
                </div>
                
                <p ng-show="form.dbuser.$valid">Providing a Secret Key is a required to ensure secure details such as passwords can be hashed securely.</p>
                
                <div id="key-group" class="form-group" ng-class="{ 'has-error' : form.key.$error.minlength && form.key.$dirty || form.key.$error.maxlength && form.key.$dirty || form.key.remote_error}" ng-show="form.dbuser.$valid">
                    <label for="key">Secure Key</label>
                    <input type="text" name="key" id="key" class="form-control" placeholder="" ng-model="formData.key" required ng-minlength="32" ng-maxlength="32">
                    <span class="help-block" ng-show="form.key.$error.required && form.key.$dirty">Secret Key Required!</span>
                    <span class="help-block" ng-show="form.key.$error.minlength && form.key.$dirty || form.key.$error.maxlength && form.key.$dirty">Secret Key Must Be 32 Characters!</span>
                    <span class="help-block" ng-show="form.key.remote_error">[[ form.key.remote_error ]]</span>
                </div>
                
                <div class="page-header" ng-show="form.key.$valid">
                  <h4 class="textcenter"><span class="glyphicon glyphicon-file"></span> License</h4>
                </div>
                
                <p ng-show="form.key.$valid">Please read our License and Terms & conditions before agreeing and installing FluentKit.</p>
                
                <div id="tos-group" class="form-group" ng-class="{ 'has-error' : form.tos.$error.required && form.tos.$dirty || form.tos.remote_error}" ng-show="form.key.$valid">
                    <label for="tos">Terms & Conditions</label>
                    <div class="checkbox">
                        <label><input type="checkbox" name="tos" id="tos" ng-model="formData.tos" value="1" required> Click to accept our License and Terms & Conditions</label>
                    </div>
                    <span class="help-block" ng-show="form.tos.$error.required && form.tos.$dirty">Acceptance Required!</span>
                    <span class="help-block" ng-show="form.tos.remote_error">[[ form.tos.remote_error ]]</span>
                </div>
                
                <p><button type="submit" class="btn btn-success btn-lg btn-block" ng-show="form.$valid" ng-disabled="form.$invalid" ng-click="processForm()">
                    <span class="glyphicon glyphicon-flash"></span> [[ submit ]]
                </button></p>
                
            </form>
        </div>
    </div>
@stop