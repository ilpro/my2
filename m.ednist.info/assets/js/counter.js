function getPlugins(){var e,n=[];try{for(var r=0;r<navigator.plugins.length;r++){var o=navigator.plugins[r];o=o.name+" "+(o.version||""),e!=o&&(n.push(o),e=o)}}catch(i){}return n}function screenSize(){var e="";try{window.screen.width&&(width=window.screen.width?window.screen.width:"",height=window.screen.height?window.screen.height:"",e+=""+width+" x "+height)}catch(n){}if(""==e)try{window.outerWidth&&(width=window.outerWidth?window.outerWidth:"",height=window.outerHeight?window.outerHeight:"",e+=""+width+" x "+height)}catch(n){}if(""==e)try{document.documentElement.offsetWidth&&(width=document.documentElement.offsetWidth?document.documentElement.offsetWidth:"",height=document.documentElement.offsetHeight?document.documentElement.offsetHeight:"",e+=""+width+" x "+height)}catch(n){}return e}function browserInfo(){var e,n,r,o={nVer:navigator.appVersion,nAgt:navigator.userAgent,browserName:navigator.appName,version:""+parseFloat(navigator.appVersion),majorVersion:parseInt(navigator.appVersion,10),mobil:""};try{-1!=(n=o.nAgt.indexOf("Opera"))&&(o.browserName="Opera",o.version=o.nAgt.substring(n+6),-1!=(n=o.nAgt.indexOf("Version"))&&(o.version=o.nAgt.substring(n+8))),-1!=(n=o.nAgt.indexOf("OPR"))?(o.browserName="Opera",o.version=o.nAgt.substring(n+4)):-1!=(n=o.nAgt.indexOf("MSIE"))?(o.browserName="Microsoft Internet Explorer",o.version=o.nAgt.substring(n+5)):-1!=(n=o.nAgt.indexOf("Chrome"))?(o.browserName="Chrome",o.version=o.nAgt.substring(n+7)):-1!=(n=o.nAgt.indexOf("Safari"))?(o.browserName="Safari",o.version=o.nAgt.substring(n+7),-1!=(n=o.nAgt.indexOf("Version"))&&(o.version=o.nAgt.substring(n+8))):-1!=(n=o.nAgt.indexOf("Firefox"))?(o.browserName="Firefox",o.version=o.nAgt.substring(n+8)):-1!=o.nAgt.indexOf("Trident/")?(o.browserName="Microsoft Internet Explorer",o.version=o.nAgt.substring(o.nAgt.indexOf("rv:")+3)):(e=o.nAgt.lastIndexOf(" ")+1)<(n=o.nAgt.lastIndexOf("/"))&&(o.browserName=o.nAgt.substring(e,n),o.version=o.nAgt.substring(n+1),o.browser.toLowerCase()==o.browser.toUpperCase()&&(o.browserName=navigator.appName)),-1!=(r=o.version.indexOf(";"))&&(o.version=o.version.substring(0,r)),-1!=(r=o.version.indexOf(" "))&&(o.version=o.version.substring(0,r)),-1!=(r=o.version.indexOf(")"))&&(o.version=o.version.substring(0,r)),o.majorVersion=parseInt(""+o.version,10),isNaN(o.majorVersion)&&(o.version=""+parseFloat(navigator.appVersion),o.majorVersion=parseInt(navigator.appVersion,10)),o.mobile=/Mobile|mini|Fennec|Android|iP(ad|od|hone)/.test(o.nVer)}catch(i){}return o}function cookieInfo(){var e=navigator.cookieEnabled?!0:!1;return"undefined"!=typeof navigator.cookieEnabled||e||(document.cookie="testcookie",e=-1!=document.cookie.indexOf("testcookie")?!0:!1),e}function osInfo(){var e="-",n=[{s:"Windows 10",r:/(Windows 10.0|Windows NT 10.0)/},{s:"Windows 8.1",r:/(Windows 8.1|Windows NT 6.3)/},{s:"Windows 8",r:/(Windows 8|Windows NT 6.2)/},{s:"Windows 7",r:/(Windows 7|Windows NT 6.1)/},{s:"Windows Vista",r:/Windows NT 6.0/},{s:"Windows Server 2003",r:/Windows NT 5.2/},{s:"Windows XP",r:/(Windows NT 5.1|Windows XP)/},{s:"Windows 2000",r:/(Windows NT 5.0|Windows 2000)/},{s:"Windows ME",r:/(Win 9x 4.90|Windows ME)/},{s:"Windows 98",r:/(Windows 98|Win98)/},{s:"Windows 95",r:/(Windows 95|Win95|Windows_95)/},{s:"Windows NT 4.0",r:/(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/},{s:"Windows CE",r:/Windows CE/},{s:"Windows 3.11",r:/Win16/},{s:"Android",r:/Android/},{s:"Open BSD",r:/OpenBSD/},{s:"Sun OS",r:/SunOS/},{s:"Linux",r:/(Linux|X11)/},{s:"iOS",r:/(iPhone|iPad|iPod)/},{s:"Mac OS X",r:/Mac OS X/},{s:"Mac OS",r:/(MacPPC|MacIntel|Mac_PowerPC|Macintosh)/},{s:"QNX",r:/QNX/},{s:"UNIX",r:/UNIX/},{s:"BeOS",r:/BeOS/},{s:"OS/2",r:/OS\/2/},{s:"Search Bot",r:/(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/}];for(var r in n){var o=n[r];if(o.r.test(Browser.nAgt)){e=o.s;break}}var i="-";switch(/Windows/.test(e)&&(i=/Windows (.*)/.exec(e)[1],e="Windows"),e){case"Mac OS X":i=/Mac OS X (10[\.\_\d]+)/.exec(Browser.nAgt)[1];break;case"Android":i=/Android ([\.\_\d]+)/.exec(Browser.nAgt)[1];break;case"iOS":i=/OS (\d+)_(\d+)_?(\d+)?/.exec(Browser.nVer),i=i[1]+"."+i[2]+"."+(0|i[3])}return e+" "+i}function base64_encode(e){var n,r,o,i,t,s,a,d,w="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",c=0,g=0,u="",h=[];if(!e)return e;do n=e.charCodeAt(c++),r=e.charCodeAt(c++),o=e.charCodeAt(c++),d=n<<16|r<<8|o,i=d>>18&63,t=d>>12&63,s=d>>6&63,a=63&d,h[g++]=w.charAt(i)+w.charAt(t)+w.charAt(s)+w.charAt(a);while(c<e.length);u=h.join("");var l=e.length%3;return(l?u.slice(0,l-3):u)+"===".slice(l||3)}function getCookie(e){var n=document.cookie.match(new RegExp("(?:^|; )"+e.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g,"\\$1")+"=([^;]*)"));return n?decodeURIComponent(n[1]):void 0}function counters(){void 0!=personId?(xhr.open("POST",URL,!0),xhr.onload=function(){console.log(this.responseText);var e=new Date((new Date).getTime()+314496e5);document.cookie="personId="+xhr.responseText+"; path=/; expires="+e.toUTCString()},xhr.onerror=function(){console.log("Error "+this.status)},xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),xhr.send("act=a2&FH8d4bJD="+sum+"&GJrbb54j="+personId+"&url="+url)):(xhr.open("POST",URL,!0),xhr.onload=function(){console.log(this.responseText);var e=new Date((new Date).getTime()+314496e5);document.cookie="personId="+xhr.responseText+"; path=/; expires="+e.toUTCString()},xhr.onerror=function(){console.log("Error "+this.status)},xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),xhr.send("act=a1&FH8d4bJD="+sum+"&url="+url))}for(var Browser=browserInfo(),browserName=Browser.browserName+" "+Browser.majorVersion+" ("+Browser.version+")",userAgent=navigator.userAgent,mobile=Browser.mobile,language=navigator.language||navigator.userLanguage,os=osInfo(),cookie=cookieInfo(),screen=screenSize(),referrerUrl=document.referrer||"",referrer=""!=referrerUrl?referrerUrl.match(/:\/\/(.[^\/]+)/)[1]:"",host=""!=window.location.host?window.location.host:document.location.host,plugins=getPlugins(),url=""!=window.location.pathname?window.location.pathname:document.location.pathname,all=browserName+"|"+userAgent+"|"+mobile+"|"+language+"|"+os+"|"+cookie+"|"+screen+"|"+referrer+"|"+host+"|",i=0;i<plugins.length;i++)all+=plugins[i];var sum=base64_encode(all),URL="http://www.follownews.info/counter.php",personId=getCookie("personId"),xhr="onload"in new XMLHttpRequest?new XMLHttpRequest:new XDomainRequest;counters();