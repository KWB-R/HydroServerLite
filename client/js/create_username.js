// JavaScript created by Rex Burch 


//Take the first letter from the first name provided

function GetFirstLetter(){ 

	var newLtr="";

	newLtr = document.newuser.firstname.value.substr(0,1); // "R" from Rex
	
	var newFirst = newLtr.toLowerCase(); // becomes "r"
	
	return newFirst;
}


//Take the first letter from the last name provided and add it to the first initial

function GetLastName(){

	var FirstPiece=GetFirstLetter();
	
	var lastN="";
	
	lastN = document.newuser.lastname.value; // "Burch"
	
	var newLast = lastN.toLowerCase(); // becomes "burch"
	var newUN = FirstPiece + newLast;
	
	if (document.newuser.username.value !=newUN)
	{
	document.newuser.username.value = newUN; //output combined results, which would be "rburch"
	}
}
