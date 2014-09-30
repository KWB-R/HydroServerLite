// JavaScript created by Rex Burch 
//Further edits to make it work : Rohit Khattar : 9/18/2014

//Take the first letter from the first name provided

function GetFirstLetter(){ 

	var newLtr="";

	newLtr = $("#firstname").val().substr(0,1); // "R" from Rex
	
	var newFirst = newLtr.toLowerCase(); // becomes "r"

	$("#username").val(newFirst) ; //output current result, which is "r"
}


//Take the first letter from the last name provided and add it to the first initial

function GetLastName(){

	var FirstPiece="";
	
	var lastN="";

	FirstPiece = $("#username").val();

	lastN = $("#lastname").val(); // "Burch"
	
	var newLast = lastN.toLowerCase(); // becomes "burch"
	
	$("#username").val(FirstPiece + newLast); //output combined results, which would be "rburch"
}
