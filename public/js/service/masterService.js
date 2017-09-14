angular.module('masterService', [])
.factory('Master', function($http, Upload){
	
	return {
		get: function(data_type){
			if(data_type == 'company'){
				return $http.get('/settings/company/list');
			} else {
				return $http.get('/settings/list/'+data_type);
			}
		},
		save: function(newData){
			return $http({
				method: 'POST',
				url: '/settings/save',
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
				data: $.param(newData)
			});
		},
		deleteRow: function(id){
			return $http({
				method: 'GET',
				url: '/settings/delete/'+id,
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
			});
		},
		saveCompany: function(newData){
			return Upload.upload({
				method: 'POST',
				url: '/settings/company/save',
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
				data: newData
			});
		}
	}
})