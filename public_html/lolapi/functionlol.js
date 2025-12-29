
/*

var api = 'RGAPI-97cb27d8-1026-4020-8f17-62adb63048dd';


var request1 = require("request")

var apikey = '?api_key=' + api;
var usuario = 'morlopio';
request({
	url:'https://la2.api.riotgames.com/lol/summoner/v4/summoners/by-name/morlopio?api_key=RGAPI-97cb27d8-1026-4020-8f17-62adb63048dd',
	json:true,
}, function(error,response,body){
	if (!error && response.statusCode == 200){
		var toParse = body;
		var name = toParse.name;
		console.log('Nombre '+ name);
	}
})

*/
 



$(document).ready(function(){ 

var headers1 = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36",
    "Accept-Language": "es-419,es;q=0.9",
    "Accept-Charset": "application/x-www-form-urlencoded; charset=UTF-8",
    "Origin": "https://developer.riotgames.com",
    "X-Riot-Token": "RGAPI-d81ceb0c-f53d-432e-855f-0621a15af2aa"
  }
$.ajax({
    url:'https://la2.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/morlopio?api_key=RGAPI-d81ceb0c-f53d-432e-855f-0621a15af2aa',
    method:"GET",
    headers:headers1,
    dataType:"json",
    success:function(data){
      $('#summoner').val(data.name);
      console.log(data);
    }
  })

 }); 


/*
function inv(){

	fetch('https://la2.api.riotgames.com/lol/summoner/v4/summoners/by-name/morlopio?api_key=RGAPI-d81ceb0c-f53d-432e-855f-0621a15af2aa')
	.then(res => res.json())
	.then(data => {
		console.log(data)
	})
}

*/
