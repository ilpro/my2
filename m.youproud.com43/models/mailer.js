'use strict';

var nodemailer = require('nodemailer');

module.exports = {
	transporter: nodemailer.createTransport({
		host: '37.1.221.240',
		port: 25,
		auth: {
			user: 'noreply@youproud.com',
			pass: '123456qwe!!'
		},
		secure: false,
		ignoreTLS: true
	}), 
	from: 'Youproud <noreply@youproud.com>',
	sendMessage: function(data){
		var mailOptions = {
			from: this.from, 
			to: data.to, 
			subject: data.subject
		};
		if(data.text)
			mailOptions.text = data.text;
		if(data.html)
			mailOptions.html = data.html;
		
		this.transporter.sendMail(mailOptions, function(err, info){
			if(err)
				console.log("Error in Mailer.sendMessage(" + new Date() + ")\n" + err);
		});
	}
};