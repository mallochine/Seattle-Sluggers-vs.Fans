Move a piece from square: <span id='firstselect'></span> to <span id='secondselect'></span>

<br />
<input type='button' id='vote' value='Submit your Vote!' />

<script type='text/javascript' src='./lib/jquery.js'></script>
<script>

$(document).ready(function(){
	$("#firstselect").html(writeSquareSelect('firstsquare'));
	//$("#firstselect").html("something");
	$("#secondselect").html(writeSquareSelect('lastsquare'));

	$("#vote").click(function(event){
		event.preventDefault();
		if (!isFormValid()){
			$("#message").html("You have submitted an invalid form");
			return;
		}

		$("#message").html("Processing request...");
		
		$.post("./player_actions/process_vote.php", {
			playerid: $("#playerid").val(),
			matchid: $("#matchid").val(),
			boardid: $("#boardid").val(),
			firstsquare: $("#firstsquare").val(),
			lastsquare: $("#lastsquare").val()
		}, function(response){
			$("#message").html(response);
		});
	});
});

function isFormValid(){
	// take care of form validation later
	return true;
}

/**
 * write a select with all the possible chess squares
 * 
 * @param: 'id' is the requested id for the select
 */
function writeSquareSelect(id){
	if (id == null){
		id = 'something';
	}
	
    var filecode = "abcdefgh";
    var squareselect = '';
    squareselect += '<select id='+id+'>';
    for (var i=0; i<8; i++){
        for (var j=0; j<8; j++){
            var file = filecode.charAt(i);
            var rank = j+1;
            rank = rank + ''; //converts rank to string
            var square = file+rank;
            var squareid = i + j*8;
            squareselect += '<option value='+squareid+'>';
            squareselect += square;
            squareselect += '</option>';
        }
    }
    squareselect += '</select>';
    //alert(squareselect);
    return squareselect;
}

/**
 * converts the coordinate of a square to a number

 param: 'square' (algebraic form of a square. e.g. a1)
 returns: the square in form of a square. a1 is 1.
 */
function CoordinateToNum(square){
	var square_code = "abcdefgh";
	var somenum = square_code.charAt(square.charAt(0));
	return (somenum * 8) + square.charAt(1); 
}

</script>