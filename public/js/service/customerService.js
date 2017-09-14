angular.module('customerService', [])
.factory('Customer', function($http){
	
	return {
		get: function(){
			return $http.get('/customer/list');
		},
		save: function(newData){
			return $http({
				method: 'POST',
				url: '/customer/save',
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
				data: $.param(newData)
			});
		},
		deleteRow: function(id){
			return $http({
				method: 'GET',
				url: '/customer/delete/'+id,
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
			});
		}
	}
})