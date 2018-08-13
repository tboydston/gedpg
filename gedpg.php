<?php

readline_callback_handler_install('', function(){});

// Request master password.
$masterPass = validatePassword();

// Validate CLI
if ( isset($argv[1]) ){
	$cliResult = explode("/", $argv[1]);
	if( count($cliResult) == 2) {
		if ( is_numeric($cliResult[0]) && is_numeric($cliResult[1]) ) {
			$index = $cliResult[0];
			$length = $cliResult[1];
		} else {
			echo "\n\033[31mIndex syntax is invalid. It should be formated. [index]/[length]\033[0m";
		}
	} else {
		echo "\n\033[31mIndex syntax is invalid. It should be formated. [index]/[length]\033[0m";
	}
} else {
	$index = validateIndex();
	$length = validateLength();
}


// Generate Password
generatePassword($masterPass, $index, $length);

// Create another.
while (createAnother()==true) {

	$index = validateIndex();
	$length = validateLength();
	generatePassword($masterPass, $index, $length);

}


/*
*
* Helper Functions 
*
*/


/*
*	Process validate password arguments.
*/ 
function validatePassword() {

	while (true) {

		echo("Enter password followed by return.\n");
		echo("Password: ");

		$masterPass = getInput(true);

		echo("\nConfirm password:");

		$confirmPass = getInput(true);
		if ( $confirmPass !== $masterPass ) {
			echo("\n\033[31mPasswords do not match!\033[0m\n");
			continue;
		}

		return $masterPass;

	}

}

/*
*	Get index from user and validate.
*/ 
function validateIndex() {

	while (true) {
		
		echo("\nEnter password index or type 'r' to return a random index: ");
		$indexInput = getInput();

		if ( $indexInput === 'r') {
			$index = random_int(1, 999999);
			return $index;
		}
		if ( is_numeric($indexInput) ) {
			$index = $indexInput;
			return $index;
		} else {
			echo ("\n\033[31mInvalid input, index must be a number or 'r'.\033[0m\n");
			continue;
		}

	}

}

/*
*	Get index from users and validate. 
*/ 
function validateLength() {

	while (true) {

		echo("\nEnter length of password to generate( Up to 64 ): ");
		$passwordLengthInput = getInput();

		if ( is_numeric($passwordLengthInput) ) {
			if ( $passwordLengthInput <= 64 ) {
				$passwordLength = $passwordLengthInput;
				return $passwordLength;
			} else {
				echo ("\n\033[31mInvalid input, length must be less then or equal to 64\033[0m\n");
				continue;
			}		
			
		} else {
			echo ("\n\033[31mInvalid input, length must be a number\033[0m\n");
			continue;
		}

	}

}

/*
*	Confirm if the customer wants to create another 
*/ 
function createAnother() {

	while (true) {

		echo("\Generate another? [y:n]: ");
		
		$responses = getInput();

		if ( 'y' == strtolower($responses) ) {
			return true;
		} else {
			echo "\n";
			exit();
		}

	}

}


function generatePassword($masterPass, $index, $length) {

	$symbols = ")!@#$%^&*(";
	$letters = "abcdefghijklmnopqrstuvwxyz";


	// Get SHA256 Hash.
	for ($i=0; $i < $index ; $i++) { 

		$hash = hash("sha256", $masterPass.$i.$index.$length );

	}


	$hashArray = str_split($hash);
	$lettersListArray = str_split($letters);
	$lettersResult = "";

	// Convert Numbers to Letters.
	for ($i=0; $i < count($hashArray); $i++) { 

		if ( array_key_exists($i+1, $hashArray) && is_numeric($hashArray[$i])  ) { 
			if ( is_numeric($hashArray[$i+1]) ) {
				$number = $hashArray[$i].$hashArray[$i+1];
				$lettersResult .= $lettersListArray[fmod($number,24)];
			} else {
				$lettersResult .= $lettersListArray[$hashArray[$i]];
			}
		} else {
			$lettersResult .= $hashArray[$i];
		}

	}


	$numericResult = "";

	// Convert Letters to ASCII Numbers.
	for ($i=0; $i < count($hashArray); $i++) { 
		if ( is_numeric($hashArray[$i])) {
			$numericResult .= $hashArray[$i];
		} else {
			$numericResult .= ord(strtolower($hashArray[$i])) - 96;		
		}

	}


	$lettersArray = str_split($lettersResult);
	$numericArray = str_split($numericResult);
	$symbolListArray = str_split($symbols);

	$symbolResult = "";
	$startIndex = 1;

	// Convert Numbers to Valid Symbols. Set start index.
	for ($i=0; $i < count($numericArray); $i++) { 
		if ( in_array($numericArray[$i], [1,2,3]) ) {
			$startIndex = $numericArray[$i];
		}
		$symbolResult .= $symbolListArray[$numericArray[$i]];
	}

	$symbolArray = str_split($symbolResult);

	$finalPassword = "";

	// build password to include letters numbers and symbols.
	for ($i=0; $i < $length; $i++) { 
		
		if ( $startIndex == 1 ) {
			$finalPassword .= $lettersArray[$i];
		}

		if ( $startIndex == 2 ) {
			$finalPassword .= $numericArray[$i];
		}

		if ( $startIndex == 3 ) {
			$finalPassword .= $symbolResult[$i];
			$startIndex = 1;
		} else {
			$startIndex++;
		}

	}

	// echo "Hash: $hash\n";
	// echo "letterResult: $lettersResult\n";
	// echo "NumericResult: $numericResult\n";
	// echo "SymbolsResult: $symbolResult\n";
	// echo "Final Pass: $finalPassword\n";
	exec( "echo '$finalPassword' | pbcopy");
	echo "\n\033[32mPassword copied to clipboard. Index: $index/$length End of Hash: ".substr($hash, -6)."\033[0m\n";

}


function getInput( $hidden=false ) {
	$result = "";
	while(true)
	{	
		$strChar = stream_get_contents(STDIN, 1);
		
		if($strChar===chr(13)){
		    break;
		}
		
		$result.=$strChar;
		if ( $hidden == false ) {echo$strChar;}
	}
	return $result;
}