var app = angular.module('app', ['ngRoute','ngCookies']);
var server = 'http://personal.localhost/mercurio/';
app.config(function($routeProvider) {
    $routeProvider

    .when('/:category/:name/:action', {
        templateUrl : 'views/action.html',
        controller  : 'actionController'
    })

    .when('/:category/:name', {
        templateUrl : 'views/package.html',
        controller  : 'packageController'
    })

    .when('/', {
        templateUrl : 'views/category.html',
        controller  : 'categoryController'
    })

});

app.controller('mainController', function($scope, $http, $routeParams) {})

app.controller('categoryController', function($scope, $http, $routeParams) {
    var args={
        category:'script',
        name:'plugin',
        action:'list'
    }
    $http.get(server, {params:args}).then(res=>{
        $scope.title="All packages"
        var packages = []
        for (id in res.data.data){
            var package_=res.data.data[id]
            packages.push({
                name:package_,
                url:'#!/script/'+package_
            })
        }
        $scope.packages=packages
    })
})

app.controller('packageController', function($scope, $http, $routeParams) {
    $scope.backurl="#!/"
    var args={
        category:'script',
        name:'plugin',
        action:'info'
    }
    args.arguments = {
        0:$routeParams.category,
        1:$routeParams.name,
    }
    $http.get(server, {params:args}).then(res=>{
        var data = res.data.data
        $scope.title=data.title
        $scope.description=data.description
        var methods = []
        for (id in data.method){
            var method=data.method[id]
            var method_info = method.split(/ (.+)/);
            methods.push({
                name:method_info[0],
                description:method_info[1],
                url:'#!/'+$routeParams.category+'/'+$routeParams.name+'/'+method_info[0]
            })
        }
        $scope.methods=methods
    })
})


app.controller('actionController', function($scope, $http, $routeParams, $cookies) {
    var args={
        category:'script',
        name:'plugin',
        action:'info'
    }
    args.arguments = {
        0:$routeParams.category,
        1:$routeParams.name,
        2:$routeParams.action
    }
    $scope.backurl='#!/'+$routeParams.category+'/'+$routeParams.name
    $scope.hiddens=[]
    $http.get(server, {params:args}).then(res=>{
        types=[]
        var data = res.data.data
        params=data.param
        for (id in params){
            var type=params[id]
            var type_info = type.split(/ (.+)/);
            if (type_info[0]!=="hidden") {
                types.push({type: type_info[0], title: type_info[1], id: id})
            } else {
                $scope.hiddens.push({value: type_info[1], id: id})
            }

        }
        $scope.types=types
        $scope.title=data.title
        $scope.description=data.description
    })



    var call = () => {
        var args={
            category:$routeParams.category,
            name:$routeParams.name,
            action:$routeParams.action
        }
        args.arguments = $scope.form
        if (args.arguments==null) {
            args.arguments=[]
        }
        for (id in $scope.hiddens){
            var hidden = $scope.hiddens[id]
            var value = hidden.value
            var cookie = $cookies.get(hidden.value)
            if (cookie != "") {
                value = cookie
            }
            args.arguments[hidden.id]=value
        }
        $http.get(server, {params:args}).then(res=>{
            var cookies = []
            if (res.data.hasOwnProperty("cookies")) {
                cookies = res.data.cookies
            }
            if(res.data.hasOwnProperty("data")){
                $scope.result=res.data.data
            }
            if (res.data.hasOwnProperty("debug")) {
                for (id in res.data.debug){
                    console.debug(res.data.debug[id])
                }
            }
            for (key in cookies){
                $cookies.put(key, cookies[key])
            }
        }, function errorCallback(res) {
            $scope.error=true
            $scope.result=res.data.data
        })
    }

    $scope.submit = call

});
