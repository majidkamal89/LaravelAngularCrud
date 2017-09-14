angular.module('userService', [])
.factory('User', function($http){
	
	return {
		get: function(){
			return $http.get('/settings/user/list');
		},
		save: function(newData){
			return $http({
				method: 'POST',
				url: '/create/user/save',
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
				data: $.param(newData)
			});
		},
		deleteRow: function(id){
			return $http({
				method: 'GET',
				url: '/settings/user/delete/'+id,
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
			});
		}
	}
})