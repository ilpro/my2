const socketClient = require('socket.io-client')('https://www.youproud.com', {reconnect: true});
const request = require('request');

const db = require('./db');
const Html_letters = require('./html_letters');
const Helper = require('./helper');
const Mailer = require('./mailer');
const oneSignal = require('./oneSignal');
const User = require('./user');


module.exports = {
  getNotificationsCount: function (sendOnce, data, sendTo) {
    let userId = data.userId;

    db.query(
      'SELECT COUNT(`notificationId`) AS count FROM `tbl_notifications` WHERE `receiverId` = ? AND `notificationRead` = 0;\
			 SELECT SUM(unreadCount) AS count FROM `tbl_conversation_participants` WHERE `userId` = ?;',
      [userId, userId],
      function (err, rows) {
        if (err)
          console.log("Error in Notifications.getNotificationsCount\n" + err);
        else {
          const sendData = {
            count: +rows[0][0].count,
            unreadChatCount: +rows[1][0].count
          };

          if (sendTo) {
            sendTo(null, "updateNotificationCount", JSON.stringify(sendData), userId);
          } else {
            sendOnce(null, "updateNotificationCount", JSON.stringify(sendData));
          }
        }
      });
  },

  addNotifications: function (send, data) {
    var self = this;

    db.query("INSERT INTO `tbl_notifications` (`senderId`, `receiverId`, `notificationType`, `notificationTypeId`) VALUES (?, ?, ?, ?);\
		SELECT COUNT(`notificationId`) AS 'count' FROM `tbl_notifications` WHERE `receiverId` = ? AND `notificationRead` = 0;", [data.senderId, data.receiverId, data.type, data.typeId, data.receiverId], function (err, res) {
      if (err)
        console.log("Error in Notifications.addNotifications\n" + err);
      else {
        send(null, JSON.stringify({count: res[1][0].count}), "updateNotificationCount", data.receiverId);
        socketClient.emit('updateNotificationCount', JSON.stringify({
          receiverId: data.receiverId,
          count: res[1][0].count
        }));
        self.getOneNotification(send, {receiverId: data.receiverId, notificationId: res[0].insertId});
        setTimeout(() => self.sendUnreadNotifications(res[0].insertId), 120000);
        self.sendPushNotification({
          senderId: data.senderId,
          receiverId: data.receiverId,
          notificationType: data.type,
          notificationTypeId: data.typeId
        });
      }
    });
  },
  
  notifyEventSubscribers: function (send, data) {
	var self = this;
	
	db.query("SELECT `userId` FROM `ctbl_userfavorite` WHERE `profileId` = ? \
	 UNION \
	 SELECT t2.`userId` FROM `tbl_events` AS t LEFT JOIN tbl_user AS t2 ON t2.`userCity` = t.`eventLocationCity` WHERE t.`eventId` = ? AND t.`eventLocationCity` != '';", [data.senderId, data.typeId], function (err, rows) {
		if (err)
			console.log("Error in Notifications.notifyEventSubscribers\n" + err);
		else {
			for(var i=0; i<rows.length; i++) {
				self.addNotifications(send, {
					senderId: data.senderId, 
					receiverId: rows[i].userId, 
					type: "Event", 
					typeId: data.typeId
				});
			}
		}
	});
  }, 

  sendUnreadNotifications: function (notificationId) {
    db.query("SELECT * FROM `tbl_notifications` WHERE `notificationId` = ? AND `notificationRead` = 0;", [notificationId], function (err, rows) {
      if (err)
        console.log("Error in Notifications.sendUnreadNotifications #1\n" + err);
      else if (rows.length > 0) {
        db.query("SELECT `userId`, `userNickname` AS userName, `userLastName`, `userPhoto` FROM `tbl_user` WHERE `userId` = ?;\
			SELECT `userId`, `userEmail`, `userNickname` AS userName, `userPhoto`, `userRole`, `subscribeUserFavorite`, `subscribePostLike` FROM `tbl_user` WHERE `userId` = ?;", [rows[0].senderId, rows[0].receiverId],
          function (err, rows2) {
            if (err)
              console.log("Error in Notifications.sendUnreadNotifications #2\n" + err);
            else {
              var profile = rows2[1][0];
              if (profile.userEmail) {
                var user = rows2[0][0];
                if (rows[0].notificationType == "PostLike" && profile.subscribePostLike) {
                  var params = {
                    receiverId: profile.userId,
                    receiverName: '@' + profile.userName,
                    receiverEmail: profile.userEmail,
                    senderId: user.userId,
                    senderName: '@' + user.userName,
                    senderPhoto: "https://www.youproud.com" + user.userPhoto,
                    imageId: rows[0].notificationTypeId
                  };

                  Html_letters.getLikeMessage(params, function (html) {
                    var subject = "Your photo is liked";

                      Mailer.sendMessage({
                        "to": profile.userEmail,
                        "subject": subject,
                        "html": html
                      });
                  });
                }
                else if (rows[0].notificationType == "UserFavorite" && profile.subscribeUserFavorite) {
                  var params = {
                    receiverId: profile.userId,
                    receiverName: '@' + profile.userName,
                    receiverEmail: profile.userEmail,
                    senderId: user.userId,
                    senderName: '@' + user.userName,
                    senderPhoto: "https://www.youproud.com" + user.userPhoto
                  };

                  Html_letters.getFavoriteMessage(params, function (html) {
                    var subject = "You have been added to a favorite";

                      Mailer.sendMessage({
                        "to": profile.userEmail,
                        "subject": subject,
						"text": "Hi, " + params.receiverName + "!\nYou have been added to favorites by user: " + params.senderName + "\nShow here https://www.youproud.com/subscribe",
                        "html": html
                      });
                  });
                }
				else if (rows[0].notificationType == "Event" && profile.subscribeEvent) {
                  var params = {
                    receiverId: profile.userId,
                    receiverName: '@' + profile.userName,
                    receiverEmail: profile.userEmail,
                    senderId: user.userId,
                    senderName: '@' + user.userName,
                    senderPhoto: "https://www.youproud.com" + user.userPhoto
                  };

                  Html_letters.getEventMessage(params, function (html) {
                    var subject = "You have been invited to join event";

                      Mailer.sendMessage({
                        "to": profile.userEmail,
                        "subject": subject,
						"text": "Hi, " + params.receiverName + "!\nYou have been invited to join event, created by: " + params.senderName + "\nShow here https://www.youproud.com/notifications",
                        "html": html
                      });
                  });
                }
              }
            }
          })
      }
    });
  },

  getOneNotification: function (send, data) {
    db.query("SELECT t.*, t2.`userPhoto`, t2.`userNickname` AS userName FROM `tbl_notifications` AS t LEFT JOIN `tbl_user` AS t2 ON t2.`userId` = t.`senderId` WHERE `notificationId` = ?;", [data.notificationId], function (err, rows) {
      if (err)
        console.log("Error in Notifications.getOneNotification\n" + err);
      else {
        rows[0].senderName = '@' + rows[0].userName;
        send(null, JSON.stringify(rows[0]), "newNotification", data.receiverId);
        socketClient.emit('newNotification', JSON.stringify({
          receiverId: data.receiverId,
          data: JSON.stringify(rows[0])
        }));
      }
    });
  },

  getNotifications: function (send, data) {
    var self = this;

    db.query(
      "SELECT \
        t.*, \
        t2.`userPhoto`, \
        t2.`userNickname` AS userName \
      FROM `tbl_notifications` AS t \
      LEFT JOIN `tbl_user` AS t2 \
        ON t2.`userId` = t.`senderId` \
      WHERE `receiverId` = ? \
      ORDER BY `notificationDate` DESC;\
      UPDATE `tbl_notifications` \
      SET `notificationRead` = '1' \
      WHERE `receiverId` = ?;",
      [data.userId, data.userId],
      function (err, rows) {
        if (err)
          console.log("Error in Notifications.getNotifications\n" + err);
        else {
          for (var i = 0; i < rows[0].length; i++) {
            rows[0][i] = self.getNotificationText(rows[0][i]);
          }
          send({rows: rows[0]});
        }
      });
  },

  getNotificationText: function (item) {
    var self = this;

    switch (item.notificationType) {
      case "Event" : {
        item.notificationText = self.replaceVars('<a href="/profile/{senderId}" style="font-weight: bold">{senderName}</a> invited you to <a href="/event/{eventId}">event</a>.', {
          "senderId": item.senderId,
          "senderName": '@' + item.userName, 
		  "eventId": item.notificationTypeId
        });
        break;
      }
      case "PostLike" : {
        item.notificationText = self.replaceVars('<a href="/profile/{senderId}"><strong>{senderName}</strong></a> liked your <a href="/post/{postId}">post</a>.', {
          "senderId": item.senderId,
          "senderName": '@' + item.userName, 
		  "postId": item.notificationTypeId
        });
        break;
      }
      case "UserFavorite" : {
        item.notificationText = self.replaceVars('<a href="/profile/{senderId}" style="font-weight: bold">{senderName}</a> <a href="/fans">followed</a> you.', {
          "senderId": item.senderId,
          "senderName": '@' + item.userName
        });
        break;
      }
    }

    return item;
  },

  replaceVars: function (str, replaces) {
    for (var index in replaces)
      str = str.replace("{" + index + "}", replaces[index]);
    return str;
  },

  readAllNotifications: function (data) {
    db.query("UPDATE `tbl_notifications` SET `notificationRead` = '1' WHERE `receiverId` = ?;", [data.userId], function (err, res) {
      if (err)
        console.log("Error in Notifications.readAllNotifications\n" + err);
    })
  },

  sendPushNotification: function (notObj) {
    const self = this;

    db.query("SELECT `userId` AS senderId, `userNickname` AS userName, `userLastName`, `userPhoto` AS senderPhoto FROM `tbl_user` WHERE `userId` = ?;\
	   SELECT `subscribePush" + notObj.notificationType + "` AS subscribe FROM `tbl_user` WHERE `userId` = ?;",
      [notObj.senderId, notObj.receiverId],
      (err, rows) => {
        if (err)
          console.log('Error occurs in notification.sendPushNotification', err)
		else if(rows[0].length && rows[1].length && rows[1][0].subscribe) {
		  notObj.senderName = '@' + rows[0][0].userName;

          var re = /^https?:/g;
          notObj.senderPhoto = (re.test(rows[0][0].senderPhoto) ? rows[0][0].senderPhoto : 'https://www.youproud.com' + rows[0][0].senderPhoto);

          switch (notObj.notificationType) {
              case "Event" : {
                notObj.msgText = notObj.senderName + ' invited you to event';
                break;
              }
              case "PostLike" : {
                notObj.msgText = notObj.senderName + ' liked your post';
                break;
              }
              case "UserFavorite" : {
                notObj.msgText = notObj.senderName + ' added you to favorites';
                break;
              }
          }

          this._getUnreadMessagesAndNotificationsSum(notObj.receiverId)
            .then(sumNotificationsRes => {
              const ios_badgeCount = +sumNotificationsRes.total;

              oneSignal({
                userId: notObj.receiverId,
                header: notObj.senderName,
                message: notObj.msgText,
                ios_attachments: {senderPhoto: notObj.senderPhoto},
                ios_badgeType: 'Increase',
                ios_badgeCount: ios_badgeCount,
                data: {
                  userName: notObj.senderName,
                  notificationType: notObj.notificationType,
                  notificationTypeId: notObj.notificationTypeId
                }
              });
            })
            .catch((e) => console.log("notification.sendPushNotification error: ", e)); // or throw error
        }
      });
  },

  /**
   * Gets all required data for sending push message and returns oneSignal call
   *
   * @param data
   *   {conversationId, conversationType, senderId, receiverId, message, notificationType}
   */
  async sendPushMessage(data) {
    try{
      const pushData = data;

      pushData.msgText = await this.parseMessage(pushData.msgText);

      const isPushAllowedRes = await this._checkUserPushMessagePermissions(pushData.receiverId, data.notificationType);
      if(!isPushAllowedRes.isPushAllowed) return;

      const userInfoRes = await User.getUserInfoByIdPromise(pushData.userId);
      pushData.userName = userInfoRes.userName;
      pushData.userPhoto = userInfoRes.userPhoto;

      const sumNotificationsRes = await this._getUnreadMessagesAndNotificationsSum(pushData.receiverId);
      const ios_badgeCount = +sumNotificationsRes.total;

      if (pushData.conversationType === 'event') {
        const eventInfoRes = await this._getEventInfoByConversationIdPromise(pushData.conversationId);
        pushData.eventId = eventInfoRes.eventId;
        pushData.eventName = eventInfoRes.eventName;
        pushData.eventImage = eventInfoRes.eventImage;
      }

      return oneSignal({
        userId: pushData.receiverId,
        header: pushData.userName.substring(1),
        message: pushData.msgText,
        ios_attachments: {senderPhoto: pushData.userPhoto},
        ios_badgeType: 'SetTo',
        ios_badgeCount: ios_badgeCount,
        android_group: 'group',
        data: pushData
      });
    } catch(err){
      console.log("Notifications.sendPushMessage error", err);
    }
  },

  _checkUserPushMessagePermissions(receiverId, notificationType){
    return new Promise((res, rej) => {

      // notification types: chatMessage, eventMessage, event, like, following
      // subscribePushEventChatMessages AS `eventMessage`,
      db.query(
        'SELECT \
           subscribePushPrivateChatMessages AS `chatMessage`, \
           subscribePushEventChatMessages AS `eventMessage`,\
           subscribePushEvent AS `event`, \
           subscribePushPostLike AS `like`, \
           subscribePushUserFavorite AS `following`\
         FROM tbl_user WHERE userId = ?',
        [receiverId],
        (err, rows) => {
          if (err) {
            return rej("Error in Notifications._checkUserPushMessagePermissions #1\n" + err);
          }

          if (rows.length === 0) {
            return rej(`Error in Notifications._checkUserPushMessagePermissions #2: user ${receiverId} not found`);
          }

          if(!+rows[0][notificationType]){
            return res({isPushAllowed: false});
          }
          return res({isPushAllowed: true});
        })
    });
  },

  _getEventInfoByConversationIdPromise(conversationId) {
    return new Promise((res, rej) => {
      if (!conversationId) return res(false);

      db.query(
        'SELECT \
           t_events.eventId, \
           t_events.eventName, \
           t_events.eventImage \
				 FROM tbl_events AS t_events \
			   JOIN tbl_events_conversations AS t_event_conv ON t_events.eventId = t_event_conv.eventId \
				 JOIN tbl_conversation AS t_conv ON t_event_conv.conversationId = t_conv.id \
				 WHERE t_conv.id = ?',
        [conversationId],
        (err, rows) => {
          if (err) return rej(err);
          if (!rows.length) return rej(new Error(`Event by conversationId ${conversationId} was not found`));
          return res(rows[0])
        })
    });
  },

  _getUnreadMessagesAndNotificationsSum(userId){
    return new Promise((res, rej) => {
      if (!userId) return rej({message: 'Notifications._getUnreadMessagesAndNotificationsSum: userId not specified'});

      db.query(
        'SELECT SUM(t.count) AS total FROM ( \
           SELECT COUNT(`notificationId`) AS count FROM `tbl_notifications` WHERE `receiverId` = ? AND `notificationRead` = 0\
           UNION \
           SELECT SUM(unreadCount) AS count FROM `tbl_conversation_participants` WHERE `userId` = ?\
         ) AS t;',
        [userId, userId],
        (err, rows) => {
          if (err) return rej(err);
          return res({total: rows[0].total})
        })
    });
  },

  parseMessage: function (msgText) {
    var self = this;

    var parsedMsgTxt = msgText;

    // check if message has stickers inside it
    var stickerMatches = parsedMsgTxt.match(/&amp;s-([0-9]+);/im);
    if (stickerMatches != null) {
      parsedMsgTxt = parsedMsgTxt.replace('&amp;s-' + stickerMatches[1] + ';', ':sticker:');
    }

    // check if message has smiles inside it
    var smilesMathces = parsedMsgTxt.match(/&amp;sm-([0-9]+);/im);
    while (smilesMathces != null) {
      var smileBaseCode = '&amp;sm-' + smilesMathces[1] + ';',
        smileHtmlCode = ':smile:';
      parsedMsgTxt = Helper.stringReplaceAll(parsedMsgTxt, smileBaseCode, smileHtmlCode);
      smilesMathces = parsedMsgTxt.match(/&amp;sm-([0-9]+);/im);
    }

    /*replace all special symbols referring to html*/
    parsedMsgTxt = Helper.htmlEscapeBack(parsedMsgTxt);

    // replace <br/> with new line symbol
    parsedMsgTxt = parsedMsgTxt.replace(/<br\s*[\/]?>/gi, "\n");

    return parsedMsgTxt;
  }
};