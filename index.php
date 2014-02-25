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

$rotors = array('I' => 'EKMFLGDQVZNTOWYHXUSPAIBRCJ',
				'II' => 'AJDKSIRUXBLHWTMCQGZNPYFVOE',
				'III' => 'BDFHJLCPRTXVZNYEIWGAKMUSQO',
				'IV' => 'ESOVPZJAYQUIRHXLNFTGKDCMWB',
				'V' => 'ESOVPZJAYQUIRHXLNFTGKDCMWB',
				'VI' => 'JPGVOUMFYQBENHZRDKASXLICTW',
				'VII' => 'NZJHGRCXMYSWBOUFAIVLPEKQDT',
				'VIII' => 'FKQHTLXOCBJSPDZRAMEWNIUYGV');
?>
<script>
var alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

var rotors = {
<?php
$i = 0;
foreach ($rotors as $text => $value) {
	echo "\t'".$text."': '".$value."'".($i < count($rotors)-1 ? ',' : '')."\n";

	$i++;
}
?>
};

var reflectors = {
	'A': 'EJMZALYXVBWFCRQUONTSPIKHGD',
	'B': 'YRUHQSLDPXNGOKMIEBFZCWVJAT',
	'C': 'FVPJIAOYEDRZXWGCTKUQSBNMHL'};

var R, M, L, reflector, div_show;

var inner_counts = {
	'R' : -1, // right
	'M' : 0, // middle
	'L' : 0}; // left

var letters = 26;
var ascii_number = 65;

function enigma(string, e) {
	if (string.length == 0) // empty
		return;

	var keycode = e.keyCode? e.keyCode : e.charCode
	if (keycode < ascii_number || keycode > ascii_number + letters) //not letter
		return;

	div_show = document.getElementById('show');

	get_settings();
	if (!verify_settings()) {
		div_show.innerHTML = "Wrong settings!";

		return;
	}

	my_char = string[string.length - 1].toUpperCase(); // get last char

	update_counts();

	//pass through right rotor + offset
	my_char = R[check_count(getCode(my_char) + inner_counts["R"])];

	//pass through middle rotor + offset
	my_char = M[check_count(getCode(my_char) + inner_counts["M"])];

	//pass through left rotor + offset
	my_char = L[check_count(getCode(my_char) + inner_counts["L"])];

	//pass through reflector
	my_char = reflector[getCode(my_char)];

	//pass through left rotor - offset
	my_char = alphabet[check_count(get_reverse(L, getCode(my_char)) - inner_counts["L"])];

	//pass through middle rotor - offset
	my_char = alphabet[check_count(get_reverse(M, getCode(my_char)) - inner_counts["M"])];

	//pass through right rotor - offset
	my_char = alphabet[check_count(get_reverse(R, getCode(my_char)) - inner_counts["R"])];

	div_show.innerHTML += my_char;
}

function get_settings() {
	Rselect = document.getElementById("R");
	R = rotors[Rselect.options[Rselect.selectedIndex].text];
	Mselect = document.getElementById("M");
	M = rotors[Rselect.options[Mselect.selectedIndex].text];
	Lselect = document.getElementById("L");
	L = rotors[Rselect.options[Lselect.selectedIndex].text];

	reflector_select = document.getElementById("reflector");
	reflector = reflectors[reflector_select.options[reflector_select.selectedIndex].text];

	inner_counts["R"] = document.getElementById("cR").value - 1;
	inner_counts["M"] = document.getElementById("cM").value - 1;
	inner_counts["L"] = document.getElementById("cL").value - 1;
}

function verify_settings() {
	if ((inner_counts["R"] < 0 || inner_counts["R"] > letters) ||
		(inner_counts["M"] < 0 || inner_counts["M"] > letters) ||
		(inner_counts["L"] < 0 || inner_counts["L"] > letters))
		return false;

	return true;
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

function create_rotor_select(id, selected) {
	selected--;
	
	cur_select = document.getElementById(id);

	<?php
	$i = 0;
	foreach ($rotors as $text => $value) {
	echo "\n\t";
	?>option = new Option('<?php echo $text; ?>', '', selected == <?php echo $i; ?> ? true : false);
	cur_select.options[cur_select.options.length] = option;
<?php

		$i++;
	}
	?>
}

function clear_text() {
	div_show.innerHTML = '';
	document.getElementById("input").value = '';
}

</script>
Rotors
<select id="L"></select>
<select id="M"></select>
<select id="R"></select><br>
Offset <input type="text" id="cL" value="1" size="3">
		<input type="text" id="cM" value="1" size="3">
		<input type="text" id="cR" value="1" size="3"> 1-26<br>
Reflector <select id="reflector">
	<option>A</option>
	<option selected="selected">B</option>
	<option>C</option>
</select><br>
<br>Text <input type="text" id="input" onkeyup="enigma(this.value, event);"><input type="button" value="Clear Text" onclick="clear_text();">
<div id="show"></div>
<script>
create_rotor_select("L", 3);
create_rotor_select("M", 1);
create_rotor_select("R", 2);
</script>
<?php

$html_output->setBody(ob_get_clean());
?>