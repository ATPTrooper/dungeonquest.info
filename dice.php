<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<link type="text/css" rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

			
		<div id="dice" data-side="1">
		<div class="sides side-1">
				<span class="dot dot-1"></span>
		</div>
		<div class="sides side-2">
				<span class="dot dot-1"></span>
				<span class="dot dot-2"></span>
		</div>
		<div class="sides side-3">
				<span class="dot dot-1"></span>
				<span class="dot dot-2"></span>  
				<span class="dot dot-3"></span>
		</div>
		<div class="sides side-4">
				<span class="dot dot-1"></span>
				<span class="dot dot-2"></span>  
				<span class="dot dot-3"></span>
				<span class="dot dot-4"></span>
		</div>
		<div class="sides side-5">
				<span class="dot dot-1"></span>
				<span class="dot dot-2"></span>  
				<span class="dot dot-3"></span>
				<span class="dot dot-4"></span>
				<span class="dot dot-5"></span>
		</div>
		<div class="sides side-6">
				<span class="dot dot-1"></span>
				<span class="dot dot-2"></span>  
				<span class="dot dot-3"></span>
				<span class="dot dot-4"></span>
				<span class="dot dot-5"></span>
				<span class="dot dot-6"></span>
		</div>
		</div>

		<div id="diceResult" onclick="rollDice();">Click to roll the dice!</div>
		
	<style type="text/css">
		#dice {
			width: 90px;
			height: 90px;
			transform-style: preserve-3d;
			transition: transform 1.5s ease-out;
		}

		#dice:hover {
		  cursor: pointer;
		}

		.sides{
			background-color: #EFE5DC;
			display: block;
			position: absolute;
			width: 100%;
			height: 100%;
			box-shadow: inset 0 0 5px rgba(0,0,0,0.25);
		}

		.dot {
			display: block;
			position: absolute;
			width: 16px;
			height: 16px;
			border-radius: 50%;
			background-color: black;
			transform: translate(-50%, -50%);
		}

		.side-1 .dot-1 { top: 50%; left: 50%; }

		.side-2 .dot-1 { top: 25%; left: 25%; }
		.side-2 .dot-2 { top: 75%; left: 75%; }

		.side-3 .dot-1 { top: 25%; left: 25%; }
		.side-3 .dot-2 { top: 75%; left: 75%; }
		.side-3 .dot-3 { top: 50%; left: 50%; }

		.side-4 .dot-1 { top: 25%; left: 25%; }
		.side-4 .dot-2 { top: 25%; left: 75%; }
		.side-4 .dot-3 { top: 75%; left: 25%; }
		.side-4 .dot-4 { top: 75%; left: 75%; }

		.side-5 .dot-1 { top: 25%; left: 25%; }
		.side-5 .dot-2 { top: 25%; left: 75%; }
		.side-5 .dot-3 { top: 75%; left: 25%; }
		.side-5 .dot-4 { top: 75%; left: 75%; }
		.side-5 .dot-5 { top: 50%; left: 50%; }

		.side-6 .dot-1 { top: 25%; left: 25%; }
		.side-6 .dot-2 { top: 25%; left: 75%; }
		.side-6 .dot-3 { top: 75%; left: 25%; }
		.side-6 .dot-4 { top: 75%; left: 75%; }
		.side-6 .dot-5 { top: 50%; left: 25%; }
		.side-6 .dot-6 { top: 50%; left: 75%; }

		#dice .side-1 {
		  transform: translateZ(45px);
		}
		#dice .side-2 {
		  transform: rotateX(-180deg) translateZ(45px);
		}
		#dice .side-3 {
		  transform: rotateY(90deg) translateZ(45px);
		}
		#dice .side-4 {
		  transform: rotateY(-90deg) translateZ(45px);
		}
		#dice .side-5 {
		  transform: rotateX(90deg) translateZ(45px);
		}
		#dice .side-6 {
		  transform: rotateX(-90deg) translateZ(45px);
		}

		#dice[data-side="1"] {
			transform: rotateX(360deg) rotateY(360deg) rotateZ(720deg);
		}
		#dice[data-side="2"] {
			transform: rotateX(360deg) rotateY(540deg) rotateZ(720deg);
		}
		#dice[data-side="3"] {
			transform: rotateX(360deg) rotateY(630deg) rotateZ(720deg);
		}
		#dice[data-side="4"] {
			transform: rotateX(360deg) rotateY(450deg) rotateZ(720deg);
		}
		#dice[data-side="5"] {
			transform: rotateX(630deg) rotateY(360deg) rotateZ(720deg);
		}
		#dice[data-side="6"] {
			transform: rotateX(450deg) rotateY(360deg) rotateZ(720deg);
		}

		#dice[data-side="1"].reRoll {
			transform: rotateX(0deg) rotateY(0deg) rotateZ(0deg);
		}
		#dice[data-side="2"].reRoll {
			transform: rotateX(0deg) rotateY(180deg) rotateZ(0deg);
		}
		#dice[data-side="3"].reRoll {
			transform: rotateX(0deg) rotateY(270deg) rotateZ(0deg);
		}
		#dice[data-side="4"].reRoll {
			transform: rotateX(0deg) rotateY(90deg) rotateZ(0deg);
		}
		#dice[data-side="5"].reRoll {
			transform: rotateX(270deg) rotateY(0deg) rotateZ(0deg);
		}
		#dice[data-side="6"].reRoll {
			transform: rotateX(90deg) rotateY(0deg) rotateZ(0deg);
		}

		#diceResult{
			position: absolute;
			bottom: 20px;
			left: 50%;
			transform: translateX(-50%);
			background-color: #EE4E34;
			color: white;
			font-weight: bold;
			padding: 0.5rem 1rem;
			border-radius: 0.25rem;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.25);
		}

		#diceResult.hide{
			opacity: 0;
		}

		#diceResult.reveal{
			animation: fadeUp 0.3s forwards;
		}

		@keyframes fadeUp {
		0% {
			opacity: 0;
			bottom: 0;
		}

		100% {
			opacity: 1;
			bottom: 20px;
		}
	</style>
	<script type="text/javascript">
	
let dice = document.getElementById('dice');
var outputDiv = document.getElementById('diceResult');

function rollDice() {
    let result = Math.floor(Math.random() * (6 - 1 + 1)) + 1;
    dice.dataset.side = result;
    dice.classList.toggle("reRoll");

    console.log(result);
  
    outputDiv.classList.remove("reveal");
    outputDiv.classList.add("hide");
    outputDiv.innerHTML = "You've got " + result;
    setTimeout(function(){ outputDiv.classList.add("reveal"); }, 1500);
}

dice.addEventListener("click", rollDice);

	
	</script>

</body>
</html>