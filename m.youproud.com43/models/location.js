const db = require('./db');
const Helper = require('./helper');

module.exports = {
	getObjectInZone: function (send, data) {
		var self = this;
		
		db.query('SELECT \
			t.`userId`, \
			t.`userNickname`, \
			t.`userPhoto`, \
			t.`userBdate`, \
			t.`userCity`, \
			t.`userLocationLat`, \
			t.`userLocationLng`, \
			t2.`id` AS inFavorite, \
			t3.`paramEn` AS userCountry \
		FROM `tbl_user` AS t \
		LEFT JOIN `ctbl_userfavorite` AS t2 ON t2.`userId` = ? AND t2.`profileId` = t.`userId` \
		LEFT JOIN `tbl_param_country` AS t3 ON t3.`paramId` = t.`userCountryId` \
		WHERE \
			t.`userActive` = 1 AND \
			t.`userRemoved` = 0 AND \
			t.`userShowInMap` = 1 AND \
			t.`userLocationLat` != 0 AND \
			t.`userLocationLng` != 0;\
		SELECT \
			placeId, \
			name, \
			city, \
			address, \
			lat, \
			lng, \
			phone, \
			email, \
			site, \
			details, \
			mainPhoto, \
			(SELECT \
			 CONCAT(\'[\',\
				GROUP_CONCAT(\
				 CONCAT(\'{\
					"id":"\', id, \'",\
					"path":"\', path, \'",\
					"width":"\', width, \'",\
					"height":"\', height, \'"\
				 }\') SEPARATOR ","\
				), \'\
			 ]\') AS images \
			 FROM tbl_city_place_image AS t_im \
			 WHERE t_im.placeId = tbl_city_place.placeId \
			) AS images \
		FROM tbl_city_place;', [data.userId], 
		function (err, rows) {
			if (err)
				console.log("Error in location.getObjectInZone #1\n" + err);
			else {
				var users = [];
				for(var i=0; i<rows[0].length; i++) {
					if(self.inZone(rows[0][i].userLocationLat, rows[0][i].userLocationLng, data.lat1, data.lng1, data.lat2, data.lng2, data.lat3, data.lng3, data.lat4, data.lng4)) {
						rows[0][i] = Helper.handleUserName(rows[0][i]);
						
						if(rows[0][i].userBdate && rows[0][i].userBdate != "0000-00-00")
							rows[0][i].userAge = Helper.getAge(new Date(rows[0][i].userBdate));
						else
							rows[0][i].userAge = false;
						
						if(rows[0][i].userCity)
							rows[0][i].userCity = rows[0][i].userCity.charAt(0).toUpperCase() + rows[0][i].userCity.substr(1);
						
						if(rows[0][i].userCountry)
							rows[0][i].userCountry = rows[0][i].userCountry.charAt(0).toUpperCase() + rows[0][i].userCountry.substr(1);
						
						users.push(rows[0][i]);
					}
				}
				
				var places = [];
				for(var i=0; i<rows[1].length; i++) {
					if(self.inZone(rows[1][i].lat, rows[1][i].lng, data.lat1, data.lng1, data.lat2, data.lng2, data.lat3, data.lng3, data.lat4, data.lng4)) {
						if(rows[1][i].images)
							rows[1][i].images = JSON.parse(rows[1][i].images);
						places.push(rows[1][i]);
					}
				}
				
				send(null, "getObjectInZone", JSON.stringify({users:users, places:places}));
			}
		});
	}, 
	
	inZone: function(lat, lng, lat1, lng1, lat2, lng2, lat3, lng3, lat4, lng4) {
		var a = (lng2 - lng1) * lat + (lat1 - lat2) * lng + lng1 * lat2 - lat1 * lng2;
		var b = (lng3 - lng2) * lat + (lat2 - lat3) * lng + lng2 * lat3 - lat2 * lng3;
		var c = (lng4 - lng3) * lat + (lat3 - lat4) * lng + lng3 * lat4 - lat3 * lng4;
		var d = (lng1 - lng4) * lat + (lat4 - lat1) * lng + lng4 * lat1 - lat4 * lng1;
		
		// console.log(a, b, c, d);
		
		if(a <=0 && b <=0 && c<= 0 && d <= 0 )
			return true;
		else
			return false;
	}
};