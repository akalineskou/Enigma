<?php
require_once(getcwd().DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'init.php');

$html_output = new htmlOutput();
$html_output->setTitle('Enigma');

/*
$alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

$rotor = $alphabet ;//array('V','I','Z','J','G','R','D','B','H','Y','O','P','W','Q','A','N','F','S','X','C','E','K','U','M','T','L');

// rotors 1-5
do {
	shuffle($rotor);
} while (no_dupes($alphabet, $rotor));

printArray($rotor);

do {
	shuffle($rotor);
} while (no_dupes($alphabet, $rotor));

printArray($rotor);

do {
	shuffle($rotor);
} while (no_dupes($alphabet, $rotor));

printArray($rotor);

do {
	shuffle($rotor);
} while (no_dupes($alphabet, $rotor));

printArray($rotor);

do {
	shuffle($rotor);
} while (no_dupes($alphabet, $rotor));

printArray($rotor);

// reflector
$reflector = $rotor;//array('X','S','O','J','F','V','C','R','L','H','Z','Y','W','T','E','G','P','B','D','A','N','K','I','U','Q','M');
$reflector = pair_reflector($alphabet, $reflector);
echo "<br>'reflector': ";
printReflector($reflector);

function pair_reflector($a, $r) {
	$arr = array();
	for ($i = 0; $i < 13; $i++) {
		$arr[$a[$i]] = $r[$i];//array("A" => "X")
		$arr[$r[$i]] = $a[$i];//array("X" => "A")
	}

	return $arr;
}

function printReflector($r) {
	global $alphabet;
	echo '{';
	foreach ($r as $k => $v)
		echo "'{$k}':'{$v}',";
	echo '}';
}

function printArray($arr) {
	global $alphabet;

	echo "<br>//Array(";
	foreach ($arr as $a)
		echo "'{$a}',";
	echo ");<br>";

	echo "'I': {";
	for ($i = 0; $i < count($arr); $i++)
		echo "'{$arr[$i]}',";
	echo "},<br>";
}

function no_dupes($a, $r) {
	for ($i = 0; $i < count($a); $i++)
		if ($a[$i] == $r[$i])
			return true;

	return false;
}
//*/

ob_start("ob_gzhandler");

?>
<script>
var alphabet = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

var rotors = {
		'I':   ['F','I','N','K','H','R','E','J','B','X','T','Q','Z','G','A','C','V','P','D','L','W','Y','O','U','M','S'],
		'II':  ['U','G','I','Y','A','N','K','X','E','C','F','S','B','R','D','L','T','P','Q','Z','W','H','O','J','M','V'],
		'III': ['R','W','Y','O','F','A','Q','V','J','U','T','K','B','P','N','C','H','E','X','Z','G','S','D','L','M','I'],
		'IV':  ['N','O','W','Z','Q','T','I','E','J','X','P','B','F','S','Y','D','L','M','R','H','V','A','G','C','U','K'],
		'V':   ['X','N','W','Y','I','V','K','J','Z','B','F','D','U','Q','S','O','T','A','P','G','L','C','E','H','M','R']};

var reflector = {'A':'Q','B':'Z','C':'P','D':'R','E':'O','F':'U','G':'Y','H':'N','I':'T','J':'X','K':'S','L':'V','M':'W','N':'H','O':'E','P':'C','Q':'A','R':'D','S':'K','T':'I','U':'F','V':'L','W':'M','X':'J','Y':'G','Z':'B'};

var R, M, L;
test = 'abc'
alert(test[0])
var inner_counts = {
	'R' : -1, // right
	'M' : 0, // middle
	'L' : 0}; // left

var letters = 26;
var ascii_number = 65;

function encrypt(string, e) {
	if (string.length == 0) // empty
		return;

	var keycode = e.keyCode? e.keyCode : e.charCode
	if (keycode < ascii_number || keycode > ascii_number + letters) //not letter
		return;

	get_settings();
	//verify_settings();

	my_char = string[string.length - 1].toUpperCase(); // get last char

	update_counts();

	//pass through right rotor + offset
	my_char = R[check_count(getCode(my_char) + inner_counts["R"])];

	//pass through middle rotor + offset
	my_char = M[check_count(getCode(my_char) + inner_counts["M"])];

	//pass through left rotor + offset
	my_char = L[check_count(getCode(my_char) + inner_counts["L"])];

	//pass through reflector
	my_char = reflector[my_char];

	//pass through left rotor - offset
	my_char = alphabet[check_count(get_reverse(L, getCode(my_char)) - inner_counts["L"])];

	//pass through middle rotor - offset
	my_char = alphabet[check_count(get_reverse(M, getCode(my_char)) - inner_counts["M"])];

	//pass through right rotor - offset
	my_char = alphabet[check_count(get_reverse(R, getCode(my_char)) - inner_counts["R"])];

	document.getElementById('show').innerHTML += my_char;
}

function get_settings() {
	Rselect = document.getElementById("R");
	R = rotors[Rselect.options[Rselect.selectedIndex].text];
	Mselect = document.getElementById("M");
	M = rotors[Rselect.options[Mselect.selectedIndex].text];
	Lselect = document.getElementById("L");
	L = rotors[Rselect.options[Lselect.selectedIndex].text];

	inner_counts["R"] = document.getElementById("cR").value - 1;
	inner_counts["M"] = document.getElementById("cM").value - 1;
	inner_counts["L"] = document.getElementById("cL").value - 1;
}

function get_reverse(arr, c) {
	for (i = 0; i < letters; i++)
		if (arr[i] == alphabet[c])
			return i;

	return -1;
}

function check_count(c) {
	if (c >= letters)
		return c - letters;
	else if (c < 0)
		return (letters + c)

	return c;
}

function update_counts() {
	inner_counts["R"]++;

	if (inner_counts["R"] > (letters - 1)) {
		inner_counts["R"] = 0;

		inner_counts["M"]++;
		if (inner_counts["M"] > (letters - 1)) {
			inner_counts["M"] = 0;

			inner_counts["L"]++;
			if (inner_counts["L"] > (letters - 1)) {
				inner_counts["L"] = 0;
			}
		}
	}
	document.getElementById("cR").value = inner_counts["R"] + 1;
	document.getElementById("cM").value = inner_counts["M"] + 1;
	document.getElementById("cL").value = inner_counts["L"] + 1;
}

function getCode(my_char) {
	char_upper = my_char.toUpperCase();

	return char_upper.charCodeAt(0) - ascii_number;
}

function getChar(my_code) {
	return String.fromCharCode(my_code + ascii_number);
}

</script>
Rotors
<select id="L">
	<option>I</option>
	<option>II</option>
	<option selected="selected">III</option>
	<option>IV</option>
	<option>V</option>
</select>
<select id="M">
	<option selected="selected">I</option>
	<option>II</option>
	<option>III</option>
	<option>IV</option>
	<option>V</option>
</select>
<select id="R">
	<option>I</option>
	<option selected="selected">II</option>
	<option>III</option>
	<option>IV</option>
	<option>V</option>
</select><br>
Offset <input type="text" id="cL" value="1" size="3">
		<input type="text" id="cM" value="1" size="3">
		<input type="text" id="cR" value="1" size="3"> 1-26<br>
<br>Text to encrypt<input type="text" onkeyup="encrypt(this.value, event);">
<div id="show"></div>
<?php

$html_output->setBody(ob_get_clean());
?>