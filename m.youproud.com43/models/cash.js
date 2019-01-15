/** Created by alex on 28.02.2017 **/
'use strict';
var db = require('./db');
var Helper = require('./helper');

module.exports = {
	getNumber: function (data, send){
		var userId = data.userId;

		db.query("SELECT `userCash` FROM `tbl_user` WHERE `userId` = ?", [userId], function(err, rows) {
			if(err)
				console.log('Error occurs in Cash.getNumber', err);
			else
				send(null, 'userCash', JSON.stringify({userCash: rows[0].userCash}));
		});
	},


	increase: function (userId, number){
	},

	decrease: function (data, send){
		var self = this;

		var userId = data.userId;
		var cost = data.actionCost;

		db.query("UPDATE `tbl_user` SET `userCash` = (`userCash` - ?) WHERE `userId` = ?", [cost, userId], function(err, rows) {
			if(err)
				console.log('Error occurs in Cash.decrease', err);
			else
				self.getNumber(data, send);
		});
	},

	addTransaction: function(data, callback){
		db.query("SELECT `transactionTypeTrend` FROM `tbl_transaction_type` WHERE `transactionTypeId` = ?;", [data.typeId], function(err, rows) {
			if(err)
				console.log('Error occurs in Cash.addTransaction #2\n' + err);
			else {
				if(!rows[0].transactionTypeTrend)
					data.sum *= -1;
				db.query("INSERT INTO `tbl_transaction` (`userId`, `profileId`, `transactionTypeId`, `transactionSum`, `transactionComment`) VALUES (?, ?, ?, ?, ?);\
					UPDATE `tbl_user` SET `userCash` = (`userCash` + ?) WHERE `userId` = ?;\
					SELECT t.*, t2.`userCash`, t3.transactionTypeName AS transactionType FROM `tbl_transaction` AS t \
					LEFT JOIN `tbl_user` AS t2 ON t2.`userId` = t.`userId` \
					LEFT JOIN `tbl_transaction_type` AS t3 ON t3.`transactionTypeId` = t.`transactionTypeId` \
					WHERE t.`userId` = ? AND t.`profileId` = ? ORDER BY t.`transactionDate` DESC LIMIT 0, 1;",
					[data.userId, data.profileId, data.typeId, data.sum, data.comment, data.sum, data.userId, data.userId, data.profileId],
				function(err, rows2) {
					if(err)
						console.log('Error occurs in Cash.addTransaction #2\n' + err);
					else
						var dateObj = new Date(rows2[2][0].transactionDate);
						rows2[2][0].transactionDate = dateObj.getHours() + ":" + ((dateObj.getMinutes() < 10) ? '0' : '') + dateObj.getMinutes() + " " + dateObj.getDate() + "." + (dateObj.getMonth() + 1) + "." + dateObj.getFullYear();
						callback(rows2[2][0]);
				});
			}
		});
	},

	getTransaction: function (send, data){
		db.query("SELECT\
			 t.*,\
			 t2.`userNickname`,\
			 t2.`useNickname`,\
			 t2.`userPhoto`,\
			 t2.`userName`,\
			 t2.`userLastName`,\
			 DATE(t.`transactionDate`) as date,\
			 t3.`transactionTypeName` AS transactionType\
			 FROM `tbl_transaction` AS t\
			 LEFT JOIN `tbl_user` AS t2 ON t2.`userId` = t.`profileId`\
			 LEFT JOIN `tbl_transaction_type` AS t3 ON t3.`transactionTypeId` = t.`transactionTypeId`\
			 WHERE t.`userId` = ?\
			 ORDER BY t.`transactionDate` DESC;", [data.userId],
		function(err, rows) {
			if(err)
				console.log('Error occurs in Cash.getTransaction', err);
			else {
				for(var i=0; i<rows.length; i++) {
					rows[i] = Helper.handleUserName(rows[i]);
					
					var dateObj = new Date(rows[i].transactionDate);
					rows[i].transactionDate = dateObj.getHours() + ":" + ((dateObj.getMinutes() < 10) ? '0' : '') + dateObj.getMinutes() + " " + dateObj.getDate() + "." + (dateObj.getMonth() + 1) + "." + dateObj.getFullYear();
				}
				send({transaction:rows});
			}
		});
	}
};