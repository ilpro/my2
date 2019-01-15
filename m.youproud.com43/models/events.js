'use strict';

var fs = require('fs');
var im = require('imagemagick');

var db = require('./db');
var Helper = require('./helper');
var STATUS = require('./status');

var Conversation = require('./conversation');

module.exports = {
	_isValidJson: function(json) {
		try {
			JSON.parse(json);
			return true;
		} catch (e) {
			return false;
		}
	}, 
	
	getAllEvents: function(send, data) {
		var self = this;
		db.query("SELECT t.*, CONCAT('[', GROUP_CONCAT(CONCAT('{\"id\":\"', t3.`userId`, '\",\"ph\":\"', t3.`userPhoto`, '\"}') SEPARATOR ','), ']') AS userPhoto, t4.`conversationId`, t5.`userPhoto` AS creatorPhoto \
FROM `tbl_events` AS t \
LEFT JOIN `ctbl_userevents` AS t2 ON t2.`eventId` = t.`eventId` \
LEFT JOIN `tbl_user` AS t3 ON t3.`userId` = t2.`userId` \
LEFT JOIN `tbl_events_conversations` AS t4 ON t4.`eventId` = t.`eventId` \
LEFT JOIN `tbl_user` AS t5 ON t5.`userId` = t.`userId` \
WHERE t.`eventModeration` = 1 OR t.`userId` = ? \
GROUP BY t.`eventId` \
ORDER BY t.`eventId` DESC \
LIMIT ?, ?;\
SELECT GROUP_CONCAT(`profileId`) AS favorites FROM `ctbl_userfavorite` WHERE `userId` = ? GROUP BY `userId`;", [data.userId, data.offset, data.count, data.userId], function(err, rows) {
			if(err) {
				console.log("Error in Events.getAllEvents#1\n" + err);
				send([]);
			}
			else {
				var events = rows[0];
				var favorites = (rows[1].length && rows[1][0].favorites) ? rows[1][0].favorites.split(",") : [];
				
				var userPhoto = [];
				for(var i=0; i<events.length; i++) {
					events[i].eventDate = events[i].eventDate.toString().split(" ");
					events[i].eventDate = events[i].eventDate[0] + ", " + events[i].eventDate[1] + " " + events[i].eventDate[2] + ", " + events[i].eventDate[4].slice(0, -3);
					
					events[i].eventModeration = events[i].eventModeration ? true : false;
					
					events[i].isJoin = false;
					events[i].participants = 1;
					
					if(events[i].userPhoto && self._isValidJson(events[i].userPhoto)) {
						userPhoto = [{userId:events[i].userId, photo:events[i].creatorPhoto}];
						
						events[i].userPhoto = JSON.parse(events[i].userPhoto);
						events[i].participants = events[i].userPhoto.length + 1;
						
						for(var j=0; j<events[i].userPhoto.length; j++) {
							if(events[i].userPhoto[j].id == data.userId)
								events[i].isJoin = true;
							
							if(events[i].userPhoto[j].id != data.userId && favorites.indexOf(events[i].userPhoto[j].id) != -1) {
								userPhoto.push({userId:events[i].userPhoto[j].id, photo:events[i].userPhoto[j].ph});
								// userPhoto.push(events[i].userPhoto[j]);
								if(userPhoto.length == 5)
									break;
							}
						}
						
						if(userPhoto.length < 5) {
							j = 0;
							while(userPhoto.length < 5 && j < events[i].userPhoto) {
								if(events[i].userPhoto[j].id != data.userId && favorites.indexOf(events[i].userPhoto[j].id) == -1)
									userPhoto.push({userId:events[i].userPhoto[j].id, photo:events[i].userPhoto[j].ph});
									// userPhoto.push(events[i].userPhoto[j]);
								j += 1;
							}
						}
						
						events[i].userPhoto = userPhoto;
					}
					else
						events[i].userPhoto = [{userId:events[i].userId, photo:events[i].creatorPhoto}];
				}
				
				send({events:events});
			}
		});
	}, 
	
	getEventById: function(send, data) {
		db.query("SELECT t.*, CONCAT('[', GROUP_CONCAT(CONCAT('{\"userId\":\"', t3.`userId`, '\",\"photo\":\"', t3.`userPhoto`, '\"}') SEPARATOR ','), ']') AS userPhoto, t4.`conversationId`, t5.`userPhoto` AS creatorPhoto \
FROM `tbl_events` AS t \
LEFT JOIN `ctbl_userevents` AS t2 ON t2.`eventId` = t.`eventId` \
LEFT JOIN `tbl_user` AS t3 ON t3.`userId` = t2.`userId` \
LEFT JOIN `tbl_events_conversations` AS t4 ON t4.`eventId` = t.`eventId` \
LEFT JOIN `tbl_user` AS t5 ON t5.`userId` = t.`userId` \
WHERE t.`eventId` = ? \
GROUP BY t.`eventId`;\
SELECT GROUP_CONCAT(`profileId`) AS favorites FROM `ctbl_userfavorite` WHERE `userId` = ? GROUP BY `userId`;", [data.eventId, data.userId], function(err, rows) {
			if(err) {
				console.log("Error in Events.getAllEvents#1\n" + err);
				send([]);
			}
			else {
				var userPhoto = [];
				if(rows[0].length) {
					var event = rows[0][0];
					var favorites = (rows[1].length && rows[1][0].favorites) ? rows[1][0].favorites.split(",") : [];
					
					event.eventDate = event.eventDate.toString().split(" ");
					event.eventDate = event.eventDate[0] + ", " + event.eventDate[1] + " " + event.eventDate[2] + ", " + event.eventDate[4].slice(0, -3);
					
					event.eventModeration = event.eventModeration ? true : false;
					
					event.isJoin = false;
					event.participants = 1;
					
					userPhoto = [{userId:event.userId, photo:event.creatorPhoto}];
					
					if(event.userPhoto) {
						event.userPhoto = JSON.parse(event.userPhoto);
						event.participants = event.userPhoto.length + 1;
						
						for(var j=0; j<event.userPhoto.length; j++) {
							if(event.userPhoto[j].userId == data.userId)
								event.isJoin = true;
							
							if(event.userPhoto[j].userId != data.userId && favorites.indexOf(event.userPhoto[j].userId) != -1)
								userPhoto.push(event.userPhoto[j]);
						}
						
						if(userPhoto.length < 5) {
							j = 0;
							while(userPhoto.length < 5 && j < event.userPhoto) {
								if(event.userPhoto[j].userId != data.userId && favorites.indexOf(event.userPhoto[j].userId) == -1)
									userPhoto.push(event.userPhoto[j]);
								j += 1;
							}
						}
					}
					
					event.userPhoto = userPhoto;
				}
				else
					var event = {};
				
				send({event:event});
			}
		});
	}, 
	
	uploadImage: function(send, data) {
		var self = this;

		var filename = data.image.name.split(".");
		var ext = filename[filename.length - 1];
		
		if(["jpg", "jpeg", "png"].indexOf(ext.toLowerCase()) != -1) {
			var time = (new Date()).getTime();
			
			var path = "public/uploads/events/";
			
			var imageName = data.userId + "_" + time + "." + ext;
			var imagePath = path + imageName;

			fs.writeFile(imagePath, data.image.data, function(err) {
				im.identify(imagePath, function(err, features) {
					if(err) {
						console.log("Error in Events.uploadImage#1\n" + err);
						send("Error image identify.");
					}
					else if(features.width > 635) {
						var opt = {
							srcPath: imagePath,
							dstPath: imagePath,
							width: 635
						};
						
						im.resize(opt, function(err, stdout, stderr) {
							if(err) {
								console.log("Error in Events.uploadImage#2\n" + err);
								send("Error image resize.");
							}
							else {
								im.identify(imagePath, function (err, features) {
									if (err) {
										console.log("Error in Events.uploadImage#3\n" + err);
										send("Error image identify.");
									}
									else
										send(false, JSON.stringify({image: "/uploads/events/" + imageName, width:features.width, height:features.height, error:false}));
								});
							}
						});
					}
					else
						send(false, JSON.stringify({image: "/uploads/events/" + imageName, width:features.width, height:features.height, error:false}));
				});
			});
		}
		else {
			console.log("Error in Events.uploadImage#6\nWrong image format![" + data.image.name + "]");
			send("Wrong image format! Only *.jpg, *.png.");
		}
	},

	deleteImage: function(send, data) {
		fs.unlink('public' + data.image, function(err) {
			if(err)
				console.log("Error in Events.deletePhoto#1\n" + err);
		});
	}, 
	
	addEvent: function(send, data) {
		var error = false;
		
		if(!data.name)
			error = "Set name";
		else if(!data.details)
			error = "Set details";
		else if(!data.image)
			error = "Set image";
		else if(!data.locationName)
			error = "Set locationName";
		else if(!data.locationLatLng)
			error = "Set locationLatLng";
		else if(!data.date)
			error = "Set date";
		
		if(!error) {
			data.locationLatLng = data.locationLatLng.split(",");
			if(!data.locationCity)
				data.locationCity = "";
			
			db.query("INSERT INTO `tbl_events` (`userId`, `eventName`, `eventDetails`, `eventImage`, `eventImageWidth`, `eventImageHeight`, `eventLocationCity`, `eventLocationName`, `eventLocationLat`, `eventLocationLng`, `eventDate`) \
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", 
				[data.userId, data.name, data.details, data.image, data.width, data.height, data.locationCity, data.locationName, data.locationLatLng[0], data.locationLatLng[1], data.date], 
			function(err, res){
				if(err)
					console.log("Error in Events.addEvent#1\n" + err);
				else {
					send(null, "addEvent", JSON.stringify({success:true, status:"OK", error:false, eventId:res.insertId}));
				}
			});
		}
		else
			send(null, "addEvent", JSON.stringify({success:false, status:"ERROR", error:error}));
	},

  joinToEvent: function(send, data) {
    db.query(
      'SELECT `id` FROM `ctbl_userevents` WHERE `userId` = ? AND `eventId` = ?;\
       SELECT `conversationId` FROM `tbl_events_conversations` WHERE `eventId` = ?;',
      [data.userId, data.eventId, data.eventId],
      function(err, rows){
        if(err)
          console.log("Error in Events.joinToEvent#1\n" + err);
        else {
          if(rows[0].length) {
            db.query("DELETE FROM `ctbl_userevents` WHERE `userId` = ? AND `eventId` = ?", [data.userId, data.eventId], function(err, res){
              if(err)
                console.log("Error in Events.joinToEvent#1\n" + err);
              else {
                send(null, "joinToEvent", JSON.stringify({result:"remove", userId:data.userId, eventId:data.eventId}));
              }
            });

            if(rows[1].length){
              Conversation.leave(data.userId, rows[1][0].conversationId);
            }
          }
          else {
            db.query("INSERT INTO `ctbl_userevents` (`userId`, `eventId`) VALUES (?, ?);", [data.userId, data.eventId], function(err, res){
              if(err)
                console.log("Error in Events.joinToEvent#1\n" + err);
              else {
                send(null, "joinToEvent", JSON.stringify({result:"add", userId:data.userId, eventId:data.eventId}));
              }
            });

            if(rows[1].length){
              Conversation.join(data.userId, rows[1][0].conversationId);
            }
          }
        }
      });
  },
};