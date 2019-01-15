const request = require('request');
const express = require('express');
const cookieParser = require('cookie-parser');
const bodyParser = require('body-parser');
const session = require('express-session');
const fileUpload = require('express-fileupload');
const ioLib = require('socket.io');
const fs = require('fs');
const https = require('https');
const redis = require("ioredis");
const Redis = redis.createClient(6379, '127.0.0.1');

const passport = require('passport');
const FacebookStrategy = require('passport-facebook').Strategy;
const GoogleStrategy = require('passport-google-oauth2').Strategy;
const TwitterStrategy = require('passport-twitter').Strategy;
// const InstagramStrategy = require('passport-instagram').Strategy;

// Socket client
// const socketClient = require('socket.io-client')('https://www.youproud.com', {reconnect: true});

// Utils
const authenticate = require('./utils/middleware').authenticate;
const socketHandler = require('./models/socketHandlers');

// Models
const Helper = require('./models/helper');
const Auth = require('./models/auth');
const User = require('./models/user');
const Notifications = require('./models/notifications');
const langEn = require('./models/langEn');

const Chat = require('./models/chat');

const Image = require('./models/image');
const Video = require('./models/video');
const STATUS = require('./models/status');
const Post = require('./models/post');
const Favorites = require('./models/favorites');
const Events = require('./models/events');


const Place = require('./models/place');
// Passport settings
const Domain = "https://m.youproud.com";

passport.use('facebook', new FacebookStrategy({
    clientID: 1493490550752786,
    clientSecret: '9a3bf2ae8b21ae3e4a61f912561f23a1',
    callbackURL: Domain + "/auth/facebook/callback",
    profileFields: ['id', 'first_name', 'last_name', 'link', 'gender', 'picture', 'verified', 'email', 'birthday']
  },
  function (accessToken, refreshToken, profile, cb) {
    profile._json.accessToken = accessToken;
    profile._json.refreshToken = refreshToken;
    return cb(null, profile);
  }
));
passport.use('google', new GoogleStrategy({
    clientID: "175929904154-m777bf8ijd6s0i74sd0kgs1r4i68t7pa.apps.googleusercontent.com",
    clientSecret: "sMWbIi3e7TDbnyrVQmzMIHyH",
    callbackURL: Domain + "/auth/google/callback", passReqToCallback: true
  },
  function (request, accessToken, refreshToken, profile, cb) {
    profile._json.accessToken = accessToken;
    profile._json.refreshToken = refreshToken;
    return cb(null, profile);
  }
));
passport.use("twitter", new TwitterStrategy({
    consumerKey: "rDwa90TAdA2bq9WzL4OAuAXq4",
    consumerSecret: "JMmAy2N1d2GMr46EkaINzkUHlOMnaYUnPTn5eApPORKJWbpgVM",
    callbackURL: Domain + "/auth/twitter/callback",
  },
  function (accessToken, refreshToken, profile, cb) {
    profile._json.accessToken = accessToken;
    profile._json.refreshToken = refreshToken;
    return cb(null, profile);
  }
));
/* passport.use("instagram", new InstagramStrategy({
    clientID: "0e96ada5f38446c6b6a791f65a0db9fd",
    clientSecret: "858bc5c073364eefa70df71d14a09459",
    callbackURL: Domain + "/auth/instagram/callback",
  },
  function (accessToken, refreshToken, profile, cb) {
    profile._json.accessToken = accessToken;
    profile._json.refreshToken = refreshToken;
    return cb(null, profile);
  }
)); */

passport.serializeUser(function (user, cb) {
  cb(null, user);
});

passport.deserializeUser(function (obj, cb) {
  cb(null, obj);
});

// App settings
const app = express();
const port = 8000;
const permissionError = '<h1>You don`t have permissions to access this page</h1><a href="/">To main</a>';

app.set('view engine', 'ejs');
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended: false}));
app.use(express.static(__dirname + '/public'));
app.use(express.static(__dirname + '/public/js/onesignal'));
app.use("/views", express.static(__dirname + '/views'));
app.use(cookieParser());
app.use(session({secret: 'SM9kdkwfy9wsGHme8e85j6s26LYpEGe7'}));
app.use(fileUpload());

app.use(function (req, res, next) {
  res.locals.userId = 0;
  res.locals.userNickname = "";
  res.locals.userPhoto = "/img/guestAva.png";
  res.locals.userAge = 0;
  next();
});

// app.use(function (req, res, next) {
//   /* if (req.cookies.hash && (userId = Helper.getIdByHash(req.cookies.hash))) {
//     db.query("SELECT `userLanguage` FROM `tbl_user` WHERE `userId` = ?;", [userId], function (err, rows) {
//       if (err)
//         console.log("Error in Gift.getAllGift #1\n" + err);
//       else {
//         if(rows.length > 0 && rows[0].userLanguage == "ru") {
//           global.langParam = "paramRu";
//           global.lang = langRu;
//           res.locals.lang = langRu;
//           res.locals.langFile = "langRu.js";
// 		}
// 		else {
//           global.langParam = "paramUa";
//           global.lang = langUa;
//           res.locals.lang = langUa;
//           res.locals.langFile = "langUa.js";
//         }
//         next();
//       }
//     });
//   }
//   else { */
global.langParam = "paramEn";
global.lang = langEn;
global.langFile = "langEn.js";
//     res.locals.lang = langEn;
//     res.locals.langFile = "langEn.js";
//     next();
//   // }
// });
app.use(passport.initialize());
app.use(passport.session());


// Main page
app.get('/', function (req, res) {
	Redis.get("getPopularAllYP", function (err, result) {
		var data = {};
		if (err) {
			console.log(err);
			data.popularUsres = [];
		}
		else {
			result = Helper.shuffleArray(JSON.parse(result));
			data.popularUsres = result;
		}
		res.render(__dirname + '/views/landing', data);
	});
});

// Login page
app.get('/login', (req, res) => {
  if (req.cookies.hash) {
    res.redirect('/');
  } else {
    res.render(__dirname + '/views/login', {page: 'login'});
  }
});

// Email confirm page
app.get('/confirm/:userId/:key', function (req, res) {
  Auth.userConfirm(function (hash) {
    if (hash) {
      /* var maxAge = 365 * 24 * 60 * 60 * 1000;
       res.cookie('hash', hash, {maxAge: maxAge, httpOnly: true});
       res.redirect('/profile/' + req.params.userId); */
      res.send('<script src="/js/cookie.js"></script>\
	  <script>\
setCookie("hash", "' + hash + '", {"path": "/", "expires": 31536000});\
window.location.href = "/profile/' + req.params.userId + '"\
	  </script>');
    }
    else
      res.send("Error!");
  }, {userId: req.params.userId, key: req.params.key});
});

// ========== Auth ==========
// Facebook
app.get('/auth/facebook', passport.authenticate('facebook', {scope: ['public_profile', 'email', 'user_birthday']}));
app.get('/auth/facebook/callback', passport.authenticate('facebook', {failureRedirect: '/login'}), function (req, res) {
  Auth.fbLogin(function (hash, act) {
    res.cookie('hash', hash, {maxAge: (1000 * 60 * 60 * 24 * 365)});
    if (act == "register")
      res.cookie('requiredEmail', 1, {maxAge: (1000 * 60 * 60 * 24 * 365)});
    res.redirect('/');
  }, req.user._json);
});
// Google
app.get('/auth/google', passport.authenticate('google', {scope: ['https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/plus.profile.emails.read', 'https://www.googleapis.com/auth/user.birthday.read', 'https://www.googleapis.com/auth/plus.me']}));
app.get('/auth/google/callback', passport.authenticate('google', {failureRedirect: '/'}), function (req, res) {
  Auth.googleLogin(function (hash, act) {
    res.cookie('hash', hash, {maxAge: (1000 * 60 * 60 * 24 * 365)});
    if (act == "register")
      res.cookie('requiredEmail', 1, {maxAge: (1000 * 60 * 60 * 24 * 365)});
    res.redirect('/');
  }, req.user._json);
});
// Twitter
app.get('/auth/twitter', passport.authenticate('twitter'));
app.get('/auth/twitter/callback', passport.authenticate('twitter', {failureRedirect: '/'}), function (req, res) {
  Auth.twitterLogin(function (hash, act) {
    res.cookie('hash', hash, {maxAge: (1000 * 60 * 60 * 24 * 365)});
    if (act == "register")
      res.cookie('requiredEmail', 1, {maxAge: (1000 * 60 * 60 * 24 * 365)});
    res.redirect('/');
  }, req.user._json);
});
// Instagram
/* app.get('/auth/instagram', passport.authenticate('instagram'));
app.get('/auth/instagram/callback', passport.authenticate('instagram', {failureRedirect: '/'}), function (req, res) {
  Auth.instagramLogin(function (hash) {
    res.cookie('hash', hash, {maxAge: (1000 * 60 * 60 * 24 * 365)});
    if (act == "register")
      res.cookie('requiredEmail', 1, {maxAge: (1000 * 60 * 60 * 24 * 365)});
    res.redirect('/');
  }, req.user._json);
}); */

app.post('/auth/upload-image', function (req, res) {
  const userId = req.cookies.hash ? Helper.getIdByHash(req.cookies.hash) : false;
  const identifyResult = Image.identifyTypeAndGetExtension(req.files.image);

  if (!identifyResult.success) return res.send(identifyResult);

  if (identifyResult.type === 'image') {
    return Image.upload({userId, image: identifyResult.file})
      .then(result => res.send(JSON.stringify({image: result.path})))
      .catch(err => {
        if (err.status === STATUS.INTERNAL_ERROR || !err.status) {
          console.log(err);
          return res.send(JSON.stringify({success: false, status: STATUS.INTERNAL_ERROR, message: 'Server error'}));
        }
        return res.send(JSON.stringify(err));
      })
  }
});

app.post('/events/upload-image', function(req, res) {
	if (req.cookies.hash && (userId = Helper.getIdByHash(req.cookies.hash))) {
		Events.uploadImage(function(err, data) {
			if(err)
				res.send({error:err});
			else
				res.send(data);
		}, {
			userId: userId, 
			image: req.files.image
		});
	}
});


// Travelers page
app.get('/travelers', authenticate, function (req, res) {
  res.render(__dirname + '/views/travelers', {page: "travelers"});
});

// Profile page
app.get('/profile', authenticate, function (req, res) {
  res.redirect('/profile/' + req.params.userId);
});

// Edit profile
app.get('/profile/edit', authenticate, (req, res) => {
  User.getUserEditInfo(function (data) {
    data.page = "profile-edit";
    res.render(__dirname + '/views/profile-edit', data);
  }, {userId: req.params.userId});
});

// Upload image
app.post('/profile/upload-image', authenticate, (req, res) => {
  const userId = req.params.editProfileHash ? req.params.editProfileHash : req.params.userId;

  // Image.upload({userId, image: req.files.image})
  //   .then(img => res.send(JSON.stringify({ error: false, image: img.path })))
  //   .catch(err => {
  //     if(err.status === 'INVALID_IMAGE_FORMAT'){
  //       res.send(JSON.stringify({error:err.message}));
  //     } else {
  //       console.log(err.message);
  //       res.send(JSON.stringify({error: "Server error!"}));
  //     }
  //   })



  User.uploadImage((err, data) => {
    if (err)
      res.send("error");
    else
      res.send(JSON.stringify(data));
  }, {userId, image: req.files.image});
});

app.post('/profile/cropimage', authenticate, (req, res) => {
  const data = req.body;
  data.userId = req.params.editProfileHash ? req.params.editProfileHash : req.params.userId;
  User.cropUserImage(res, data);
});

app.get('/feed', authenticate, (req, res) => {
  Post.getUserFeed(req.params.userId)
    .then(data => {
      data.page = "feed";
      res.render(__dirname + '/views/feed', data);
    })
    .catch(err => console.log(err))
});

app.post('/feed/upload-image', (req, res) => {
  const userId = req.cookies.hash ? Helper.getIdByHash(req.cookies.hash) : false;
  const identifyResult = Image.identifyTypeAndGetExtension(req.files.image);

  if (!identifyResult.success) return res.send(identifyResult);

  if (identifyResult.type === 'video') {
    return Video.upload({userId, image: identifyResult.file})
      .then(result => res.send(JSON.stringify({img: result})))
      .catch(err => {
        if (err.status === STATUS.INTERNAL_ERROR || !err.status) {
          console.log(err);
          return res.send(JSON.stringify({success: false, status: STATUS.INTERNAL_ERROR, message: 'Server error'}));
        }
        return res.send(JSON.stringify(err));
      })
  }

  if (identifyResult.type === 'image') {
    return Image.upload({userId, image: identifyResult.file})
      .then(result => res.send(JSON.stringify({img: result})))
      .catch(err => {
        if (err.status === STATUS.INTERNAL_ERROR || !err.status) {
          console.log(err);
          return res.send(JSON.stringify({success: false, status: STATUS.INTERNAL_ERROR, message: 'Server error'}));
        }
        return res.send(JSON.stringify(err));
      })
  }
});

// Profile-id page.
app.get('/profile/:profileId', authenticate, (req, res) => {
  const userId = +req.params.userId;
  const profileId = +req.params.profileId;

  // if this is my profile
  if (profileId === userId) {
    User.getUserFullInfo(function (data) {
      if (data) {
        data.page = "profile";
        res.render(__dirname + '/views/profile', data);
      }
      else
        res.send("Not Found");
    }, {userId, profileId: userId});
  }

  // if profile any user
  else {
    User.getUserFullInfo(function (data) {
      if (data) {
        data.page = "profile-id";
        res.render(__dirname + '/views/profile-id', data);
      } else
        res.send("Not Found");
    }, {userId: profileId, profileId: userId});
  }
});

// Chat page
app.get('/chat', authenticate, (req, res) => {
  res.render(__dirname + '/views/chat', {page: "chat", userId: req.params.userId});
});

app.post('/chat/attachment', authenticate, (req, res) => {
  Chat.uploadChatAttachment((err, data) => {
    if (err) res.send("error");
    else res.send(data)
  }, {
    userId: req.params.userId,
    file: req.files.file
  });
});

app.get('/image/:imageId', function (req, res) {
  User.getUserImage(function (data) {
    if (data) {
      data.page = "image";
      res.render(__dirname + '/views/image', data);
    }
    else
      res.redirect('/');
  }, {imageId: req.params.imageId});
});
app.get('/userpost/:postId', function (req, res) {
  feed.getOnePost(function (data) {
    if (data) {
      data.page = "one-post";
      res.render(__dirname + '/views/one-post', data);
    }
    else
      res.redirect('/');
  }, {userId: userId, postId: req.params.postId});
});
app.get('/hashtag-search/:tagName', function (req, res) {
  feed.getPostsByHashTag(function (data) {
    if (data) {
      data.page = "hashtag-search";
      res.render(__dirname + '/views/one-post', data);
    }
    else
      res.redirect('/');
  }, {userId: req.params.userId, tagName: req.params.tagName});
});

// City places
app.get('/places', authenticate, (req, res) => {
  if (!req.params.userId) {
    return res.redirect('/#auth');
  }

  if (req.params.userRole < 100) {
    return res.send(permissionError);
  }

  Place.getAll()
    .then(data => {
      data.page = 'admin.places-list';
      data.userRole = req.params.userRole;
      res.render(__dirname + '/views/admin/places-list', data);
    })
    .catch(err => {
      if (err.status === STATUS.INTERNAL_ERROR || !err.status) {
        console.log(err);
        return res.send(JSON.stringify({success: false, status: STATUS.INTERNAL_ERROR, message: 'Server error'}));
      }
      return res.send(JSON.stringify(err));
    })
});

app.get('/places/add', authenticate, (req, res) => {
  if (!req.params.userId) return res.redirect('/#auth');
  if (req.params.userRole < 100) return res.send(permissionError);

  res.render(__dirname + '/views/admin/place', {page: 'admin.place', place: 0});
});

app.get('/places/:placeId', authenticate, (req, res) => {
  if (!req.params.userId) {
    return res.redirect('/#auth');
  }

  if (req.params.userRole < 100) {
    return res.send(permissionError);
  }

  const placeId = req.params.placeId;

  Place.getById(placeId)
    .then(result => res.render(__dirname + '/views/admin/place', {page: 'admin.place', place: result.place}))
    .catch(err => {
      if (err.status === STATUS.INTERNAL_ERROR || !err.status) {
        console.log(err);
        return res.send(JSON.stringify({success: false, status: STATUS.INTERNAL_ERROR, message: 'Server error'}));
      }

      if(err.status === STATUS.NOT_FOUND){
        return res.send("Not Found");
      }

      return res.send(JSON.stringify(err));
    })
});

app.post('/places/upload-image', (req, res) => {
  const userId = req.cookies.hash ? Helper.getIdByHash(req.cookies.hash) : false;
  const identifyResult = Image.identifyTypeAndGetExtension(req.files.image);

  if (!identifyResult.success) return res.send(identifyResult);

  if (identifyResult.type === 'video') {
    return {success: false, message: 'No handler for video yet'}
  }

  if (identifyResult.type === 'image') {
    return Image.upload({userId, image: identifyResult.file, type: 'place'})
      .then(result => res.send(JSON.stringify({img: result})))
      .catch(err => {
        if (err.status === STATUS.INTERNAL_ERROR || !err.status) {
          console.log(err);
          return res.send(JSON.stringify({success: false, status: STATUS.INTERNAL_ERROR, message: 'Server error'}));
        }
        return res.send(JSON.stringify(err));
      })
  }
});

// Subscribe
app.get('/fans', authenticate, function (req, res) {
  User.updateActiveTime(req.params.userId);
  Favorites.getFollowingInfo(req.params.userId, req.params.userId)
    .then(data => {
      data.page = "subscribe";
      res.render(__dirname + '/views/subscribe.ejs', data);
    })
    .catch(err => console.log(err));
});

// Notifications
app.get('/notifications', authenticate, function (req, res) {
  User.updateActiveTime(req.params.userId);
  Notifications.getNotifications(function (data) {
    if (data) {
      data.page = "notifications";
      res.render(__dirname + '/views/notifications', data);
    }
    else
      res.redirect('/');
  }, {userId: req.params.userId});
});

// Settings page
app.get('/settings', authenticate, function (req, res) {
  if (req.cookies.hash && (userId = Helper.getIdByHash(req.cookies.hash))) {
    User.getUserSettings(function (data) {
      if (data) {
        data.page = "settings";
        res.render(__dirname + '/views/settings', data);
      }
      else {
        User.getResidence(function (city) {
          data = {city: city, page: "settings"};
          res.render(__dirname + '/views/settings-guest', data);
        });
      }
    }, {userId: userId});
  }
  else {
    User.getResidence(function (city) {
      data = {city: city, page: "settings"};
      res.render(__dirname + '/views/settings-guest', data);
    });
  }
});

app.get("/faq", authenticate, function (req, res) {
  var data = {};
  data.page = "faq";
  res.render(__dirname + '/views/faq', data);
});

app.get("/privacy", authenticate, function (req, res) {
  var data = {};
  data.page = "privacy";
  res.render(__dirname + '/views/privacy', data);
});

app.get("/terms", authenticate, function (req, res) {
  var data = {};
  data.page = "terms";
  res.render(__dirname + '/views/terms', data);
});


// hack for FB
app.post('/*', function (req, res) {
  res.redirect('/');
});

// -------------- Express server
const privateKey = fs.readFileSync('ssl/m.youproud.com.key');
const certificate = fs.readFileSync('ssl/m.youproud.com.crt');
const server = https.createServer({key: privateKey, cert: certificate}, app);
server.listen(port, function (err) {
  if (err)
    throw err;
  console.log("server is listening on port " + port);
});

// const server = app.listen(port, function () {
//   console.log('Server listening on port ' + port + '!');
// });

// Socket
const io = ioLib(server);
io.on('connection', client => {
  socketHandler(io, client);
});