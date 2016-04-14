<?php



global $stk, $prv, $car, $dat, $chr, $rtv, $ret, $i, $def, $mds;



$stk = [];

$prv = null;

$car = null;

$dat = [];

$chr = '';

$rtv = '';

$ret = '';

$i   = 0;



$def = [

	// variable

	'var' => [

		'stw' => '$',

		'seq' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_',

		'end' => '',

		'nxt' => ['att', 'bko', 'bkc', 'bro', 'brc', 'xin', 'obj', 'obc', 'ope', 'sep', '?if', 'cln'],

		'nxr' => false,

		'rtv' => true,

	],

	// integer

	'int' => [

		'stw' => '',

		'seq' => '0123456789',

		'end' => '',

		'nxt' => ['bkc', 'brc', 'obj', 'obc', 'ope', 'sep', '?if', 'cln'],

		'nxr' => false,

	],

	// float

	'flt' => [

		'stw' => '',

		'seq' => '0123456789',

		'end' => '',

		'nxt' => ['bkc', 'brc', 'obj', 'obc', 'ope', 'sep', '?if', 'cln'],

		'nxr' => false,

	],

	// operator

	'ope' => [

		'stw' => '',

		'seq' => '+-*/%',

		'end' => '',

		'nxt' => ['att', 'bko', 'flt', 'ope', 'int', 'var'],

		'nxr' => true,

	],

	// assign

	'att' => [

		'stw' => '=',

		'seq' => '',

		'end' => '',

		'nxt' => ['att', 'bro', 'obo', 'flt', 'int', 'ope', 'str', 'txt', 'var'],

		'nxr' => true,

		'rtv' => true,

	],

	// equal (mutation)

	'eql' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => ['flt', 'int', 'ope', 'str', 'txt', 'var'],

		'nxr' => true,

	],

	// object: attribute and method

	'obj' => [

		'stw' => '.',

		'seq' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_',

		'end' => '',

		'nxt' => ['att', 'bko', 'bro', 'obj', 'obc', 'ope', '?if', 'cln'],

		'nxr' => false,

		'rtv' => false,

	],

	// json open

	'obo' => [

		'stw' => '{',

		'seq' => '',

		'end' => '',

		'nxt' => ['str', 'txt'],

		'nxr' => true,

		'rtv' => false,

	],

	// json close

	'obc' => [

		'stw' => '}',

		'seq' => '',

		'end' => '',

		'nxt' => ['bkc', 'brc', 'obj', 'obc', 'sep'],

		'nxr' => false,

		'rtv' => false,

	],

	// bracket open

	'bko' => [

		'stw' => '(',

		'seq' => '',

		'end' => '',

		'nxt' => ['bko', 'bkc', 'bro', 'flt', 'int', 'obo', 'ope', 'str', 'txt', 'var'],

		'nxr' => true,

		'rtv' => true,

	],

	// bracket close

	'bkc' => [

		'stw' => ')',

		'seq' => '',

		'end' => '',

		'nxt' => ['bkc', 'bro', 'brc', 'obj', 'obc', 'ope', 'sep', 'cln', '?if'],

		'nxr' => false,

		'rtv' => true,

	],

	// [

	'bro' => [

		'stw' => '[',

		'seq' => '',

		'end' => '',

		'nxt' => ['bro', 'brc', 'flt', 'int', 'obo', 'ope', 'sep', 'str', 'txt', 'var'],

		'nxr' => true,

		'rtv' => false,

	],

	// ]

	'brc' => [

		'stw' => ']',

		'seq' => '',

		'end' => '',

		'nxt' => ['bkc', 'bro', 'brc', 'obj', 'obc', 'ope', 'sep', 'str', 'txt', '?if', 'cln'],

		'nxr' => false,

		'rtv' => false,

	],

	// separator

	'sep' => [

		'stw' => ',',

		'seq' => '',

		'end' => '',

		'nxt' => ['bko', 'bkc', 'bro', 'brc', 'flt', 'int', 'obc', 'ope', 'sep', 'str', 'txt', 'var'],

		'nxr' => false,

		'rtv' => true,

	],

	// string

	'str' => [

		'stw' => '"',

		'seq' => '',

		'end' => '"',

		'nxt' => ['bkc', 'bro', 'obj', 'obc', 'ope', 'sep', '?if', 'cln'],

		'nxr' => false,

		'rtv' => true,

	],

	// simple text

	'txt' => [

		'stw' => '\'',

		'seq' => '',

		'end' => '\'',

		'nxt' => ['bkc', 'bro', 'brc', 'obj', 'obc', 'ope', 'sep', '?if', 'cln'],

		'nxr' => false,

		'rtv' => true,

	],

	// increment

	'inc' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => ['var'],

	],

	// decrement

	'dec' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => ['var'],

	],

	// functions and constructors

	'fnc' => [

		'stw' => '',

		'seq' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_&|<>',

		'end' => '',

		'nxt' => [],

		'nxr' => false,

		'opt' => [

			'if'		=> 'ctr',

			'elif'		=> 'ctr',

			'elseif'	=> 'ctr',

			'else'		=> 'ctr',

			'&&'		=> 'lgc',

			'||'		=> 'lgc',

			'>'			=> 'lgc',

			'<'			=> 'lgc',

			'true'		=> 'bol',

			'false'		=> 'bol',

			'for'		=> 'for',

			'break'		=> 'cts',

			'continue'	=> 'cts',

			'stop'		=> 'cts',

			'in'		=> 'xin',

			'macro'		=> 'mcr',

			'set'		=> 'set',

			'end'		=> 'end',

			'include'	=> 'fun',

			'eval'		=> 'fun',

		],

	],

	// functions

	'fun' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => ['bko', 'bro', 'str', 'var'],

		'nxr' => true,

	],

	// macro

	'mcr' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => ['nam'],

		'nxr' => true,

	],

	// name / constants

	'nam' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => ['bko'],

		'nxr' => true,

	],

	// loop for

	'for' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => ['bko', 'var'],

		'nxr' => true,

	],

	// in

	'xin'  => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => ['var'],

		'nxr' => true,

	],

	// bool

	'bol' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => ['bkc', 'brc', 'obc', 'ope', 'sep'],

		'nxr' => false,

	],

	// not

	'not' => [

		'stw' => '!',

		'seq' => '',

		'end' => '',

		'nxt' => ['bko', 'var'],

		'nxr' => true,

		'rtv' => true,

	],

	// var definition

	'set' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => ['bko', 'var'],

		'nxr' => false,

	],

	// control structure

	'ctr' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => ['bko', 'not', 'var'],

		'nxr' => true,

	],

	'cts' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => [],

		'nxr' => false,

	],

	// logic operator

	'lgc' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => ['bko', 'flt', 'int', 'not', 'var'],

		'nxr' => true,

	],

	// end

	'end' => [

		'stw' => '',

		'seq' => '',

		'end' => '',

		'nxt' => [],

		'nxr' => false,

	],

	// if ternary

	'?if' => [

		'stw' => '?',

		'seq' => '',

		'end' => '',

		'nxt' => ['flt', 'int', 'str', 'txt', 'var', 'cln'],

		'nxr' => true,

		'rtv' => true,

	],

	// colon

	'cln' => [

		'stw' => ':',

		'seq' => '',

		'end' => '',

		'nxt' => ['bro', 'flt', 'int', 'obo', 'str', 'txt', 'var'],

		'nxr' => true,

		'rtv' => true,

	],

	// comment

	'cmm' => [

		'stw' => '#',

		'seq' => '',

		'end' => "\n",

		'nxt' => [],

		'nxr' => false,

		'rtv' => false,

	],

];



$mds = [

	// string

	'join'			=> 'implode',

	'capitalize'	=> 'ucwords',

	'length'		=> 'strlen',

	'lower'			=> 'strtolower',

	'replace'		=> 'replace',

	'split'			=> 'explode',

	'trim'			=> 'trim',

	'upper'			=> 'strtoupper',

	'slug'			=> 'slug',

	

	'length'		=> 'length',

	

	// number

	'ceil'			=> 'ceil',

	'floor'			=> 'floor',

	

	// array

	'push'			=> 'arr_push',

	'add'			=> 'arr_push',

	'pop'			=> 'arr_pop',

	'shift'			=> 'arr_shift',

	'unshift'		=> 'arr_unshift',

	'rand'			=> 'random',

	

	// obj

	'clone'			=> 'clonefn',

	

	// number and date

	// objects

	'json'			=> 'json_encode',

	'obj'			=> 'json_decode',

	

	// generic

	'format'		=> 'format',

	'dump'			=> 'var_dump',

	

	// tests

	'is'			=> 'isx',

	'empty'			=> 'empty',

];



array_walk($def, function(&$v, $k) {if (!isset($v['typ'])) $v['typ'] = $k; $v = (object) $v;});



function mount($typ='#') {

	global $def, $dat, $ret, $rtv, $prv;

	

	//exit('** factory demand **');

	

	// brackets

	$bef = '';

	$aft = '';

	$bks = false;

	

	if ($dat[0][0] == 'ctr') {

		$ctr = array_shift($dat);

		// remove brackets

		while ($dat && $dat[0][0] == 'bko')

			$dat = array_splice($dat, 1, -1);

		

		if ($ctr[1] == 'else') {

			$bef = '}else{';

			$bks = false;

		}

		elseif ($ctr[1] == 'elseif' || $ctr[1] == 'elif') {

			$bef = '}elseif';

			$bks = true;

			$aft = '{';

		}

		else {

			$bef = $ctr[1];

			$bks = true;

			$aft = '{';

		}

	}

	// aliases

	elseif ($dat[0][0] == 'cts') {

		$dat[0][1] = 'exit';

	}

	elseif ($dat[0][0] == 'fun') {

		switch ($dat[0][1]) {

			case 'include':

				$dat[0][1] = 'eval(parse(parse_file(';

				$dat[] = ['', ')))', ''];

				break;

			

			case 'eval':

				$dat[0][1] = 'eval(parse(';

				$dat[] = ['', '))', ''];

				break;

		}

	}

	elseif ($dat[0][0] == 'mcr') {

		$dat[0][1] = 'function ';

		$aft = '{';

		$def['fnc']->opt[$dat[1][1]] = 'fun';

	}

	elseif ($dat[0][0] == 'set') {

		array_shift($dat);

		

		// remove brackets

		while ($dat[0][0] == 'bko')

			$dat = array_splice($dat, 1, -1);

	}

	elseif ($dat[0][0] == 'for') {

		// remove for

		array_shift($dat);

		// remove brackets

		while ($dat[0][0] == 'bko')

			$dat = array_splice($dat, 1, -1);

		

		$bef = 'foreach';

		$bks = true;

		$aft = '{';

		$pos = countkey(['xin'], $dat)['xin'][0];

		$arr = array_slice($dat, $pos + 1);

		$val = array_slice($dat, 0, $pos);

		$sep = countkey(['sep'], $arr)['sep'];

		

		// multi arrays

		if ($sep || count($val) > 3) {

			array_unshift($arr, ['', 'zip(', '']);

			$arr[] = ['', ')', ''];

			array_unshift($val, ['', 'list(', '']);

			$val[] = ['', ')', ''];

		}

		elseif ($pos = countkey(['sep'], $val)['sep']) {

			$val[$pos[0]] = ['', '=>'];

		}

		$dat = array_merge($arr, [['xin', ' as ']], $val);

	}

	elseif ($dat[0][0] == 'end') {

		$dat = [];

		$aft = '}';

	}

	

	$code = make($dat);

	

	if ($bks)

		$code[0][1] = '('. $code[0][1] .')';

	

	$code = $bef . $code[0][1] . $aft;

	

	if ($typ == '$' && !countkey(['att'], $dat)['att'])

		$code = 'print_r('. $code .')';

	

	$prv = null;

	$car = null;

	$dat = [];

	//$rtv = '';

	

	return '<?php '. $code .' ?>';

}



function make($lst, $end='bkc') {

	static $z = 0;

	//echo '#'. (++$z) .' '. imploder($lst) . "\n";

	

	global $mds;

	

	$stk = [];

	$len = count($lst);

	$skp = false;

	

	for ($i=0; $i < $len; $i++) {

		list($key, $val) = $lst[$i];

		

		if ($skp) {

			$stk[] = $val;

		}

		// ( or [

		elseif ($key == 'bko' || $key == 'bro' || $key == 'obo') {

			$sep = countkey(['sep'], array_slice($lst, $i + 1), substr($key, 0, -1) .'c')['sep'];

			$rst = make(array_slice($lst, $i + 1), substr($key, 0, -1) .'c');

			array_splice($lst, $i, $len - $i, $rst);

			

			if ($key == 'bro') {

				//print_r($lst);

				// slice for variables

				if ($i == 0) {

					$stk[] = $lst[$i][1] = '['. $lst[$i][1] .']';

				}

				else {

					/*

					if (count($stk) > 2) {

						echo '--';

						print_r($rst);

						print_r($lst);

						echo '--';

						

					}*/

					

					if ($sep && in_array($lst[$i-1][0], ['var', 'str'])) {

						$lst[$i-1] = ['var', $stk[count($stk)-1] = 'slice('. $stk[count($stk) - 1] .','. $lst[$i][1] .')'];

						array_splice($lst, $i--, 1);

					}

					else {

						// previous is a variable

						// glue

						if ('var' == $lst[$i-1][0]) {

							$stk[count($stk)-1] .= '['. $lst[$i][1] .']';

							$lst[$i-1][1] .= '['. $lst[$i][1] .']';

							array_splice($lst, $i--, 1);

						}

						else {

							$stk[] = '['. $lst[$i][1] .']';

							$lst[$i] = ['var', '['. $lst[$i][1] .']'];

						}

						

						

						//$lst[$i-1][1] = $stk[count($stk) - 1] .= '['. $lst[$i][1] .']';

					}

					//array_splice($lst, $i, 1);

					//$i--;

				}

			}

			elseif ($i && $lst[$i-1][0] == 'obj' && isset($mds[$lst[$i-1][1]])) {

				array_pop($stk);

				$stk[count($stk) - 1] = $mds[$lst[$i-1][1]] .'('. $lst[$i-2][1] . ($lst[$i][1] ? ',' . $lst[$i][1] : '') .')';

				$lst[$i][1] = $stk[count($stk) - 1];

				array_splice($lst, $i - 1, 1);

				$i--;

			}

			elseif ($key == 'obo') {

				$lst[$i][1] = '(object)['. $lst[$i][1] .']';

				$stk[] = $lst[$i][1];

			}

			else {

				$stk[count($stk) - 1] .= '('. $lst[$i][1] .')';

				$lst[$i - 1][1] .= '('. $lst[$i][1] .')';

				array_splice($lst, $i, 1);

				$i--;

			}

			

			$len = count($lst);

		}

		elseif ($key == $end) {

			break;

		}

		elseif ($key == 'att') {

			if ($val != '=') {

				$pos = countkey(['sep'], array_slice($lst, $i + 1))['sep'];

				$pos = array_map(function($val) use($i) { return $val + $i + 1; }, $pos);

				$tmp = [];

				array_unshift($pos, $i);

				$cnt = count($pos);

				for ($j=$i+1, $k=0; $j < $len; $j++) {

					if ($k < $cnt && $j - 1 == $pos[$k]) {

						$tmp[] = ['var', $stk[$k + $k * 1]];

						$tmp[] = ['ope', $val{0}];

						$k++;

					}

					$tmp[] = $lst[$j];

				}

				array_splice($lst, $i + 1, $len, $tmp);

				$len += $cnt * 2;

				$lst[$i][1] = '=';

				$i--;

			}

			else {

				$cnt = count($stk);

				$bfr = array_search('=', array_reverse($stk)) ?: $cnt;

				$pot = array_map(function($v) { return $v == 'null' ? '' : $v; },

						// slice from previous attr

						array_slice($stk, $cnt - $bfr, $bfr));

				array_splice($stk, $cnt - $bfr, $bfr, isset($pot[1]) ? ['list('. implode($pot) .')'] : $pot);

				$pos = countkey(['sep', 'att'], array_slice($lst, $i + 1));

				

				if (!$pos['att']) {

					if ($pos['sep']) {

						array_splice($lst, $i   + 1, 0, [['', '[']]);

						array_splice($lst, $len + 1, 0, [['', ']']]);

						$len += 2;

					}

				}

				$stk[] = $val;

			}

		}

		elseif ($key == 'obj' && !isset($mds[$val])) {

			$lst[$i - 1][1] .= '->' . $val;

			$stk[count($stk) - 1] .= '->' . $val;

			array_splice($lst, $i--, 1);

			$len--;

		}

		else {

			if ($key == 'mcr') {

				$skp = true;

			}

			

			$stk[] = $val;

		}

	}

	

	return array_merge([['', implode($stk)]], array_slice($lst, $i + 1));

}



function parse($str) {

	global $stk, $prv, $car, $dat, $chr, $rtv, $ret, $def, $i;

	

	$str.= '\\';

	$len = strlen($str);

	$cap = '';

	$tst = true;

	$spc = '';

	

	for (; $i < $len; $i++) {

		$chr = $str{$i};

		//debug();

		

		// capturing

		if ($cap) {	

			if ($car) {

				// cslash

				if ($car->typ == 'str' && $chr == '\\')

					$chr.= $str{++$i};

				

				// keep fetching if

				if (strpos($car->seq, $chr) !== false || ($car->end && $car->end !== $chr)) {

					$rtv.= $chr;

				}

				else {

					if ($car->end) {

						// comment

						if ($car->typ == 'cmm') {

							$car = null;

							$cap = '';

							continue;

						}

						elseif ($car->rtv)

							$rtv.= $chr;

					}

					else {

						// replace generic fn by function

						if ($car->typ == 'fnc') {

							if ($prv && $prv->typ == 'mcr') {

								$car = $def['nam'];

							}

							elseif (isset($car->opt[$rtv])) {

								$car = $def[$car->opt[$rtv]];

							}

							else {

								$car = null;

								$tst = false;

								$rtv.= $chr;

								continue;

							}

						}

						// fix missing null

						elseif (($car->typ == 'sep' && in_array($prv->typ, ['bko', 'bro', 'sep'])) ||

								($car->typ == 'bkc' || $car->typ == 'brc') && $prv->typ == 'sep' ) {

							$dat[] = ['nil', 'null'];

						}

						elseif ($car->typ == 'obo') {

							$stk[] = 'obj';

						}

						elseif ($car->typ == 'obc') {

							array_pop($stk); // remove from stack

						}

						elseif ($car->typ == 'cln' && end($stk) == 'obj') {

							$rtv = '=>';

						}

						elseif ($car->typ == 'var') {

							// not is variable

							if ($rtv == '$') {

								$cap = '';

								$car = null;

								$rtv = '';

								$ret.= '$' . $chr;

								continue;

							}

						}

						

						// instruction end

						if ($chr == ';')

							$tst = false;

						else

							$i--;

					}

					

					$dat[] = [$car->typ, $rtv, $i];

					

					if ($car->typ == 'obj' && $rtv == 'length') {

						$dat[] = ['bko', '(', $i];

						$dat[] = ['bkc', ')', $i];

					}

					

					$prv = $car;

					$car = null;

					$rtv = '';

					$spc = '';

				}

			}

			elseif ($tst && $prv && strpos("\r\n\t ", $chr) !== false) {

				if ($cap == '$')

					$spc.= $chr;

				continue;

			}

			else {

				if ($tst) {

					foreach ($def as $key => $opt) {

						if ($key != 'fnc' && $prv && !in_array($key, $prv->nxt))

							continue;

						elseif ($opt->stw) {

							if ($opt->stw == $chr) {

								$car = $opt;

								if ($car->rtv)

									$rtv = $chr;

							}

						}

						elseif (strpos($opt->seq, $chr) !== false) {

							####

							if ($opt->typ == 'fnc') {

								$opt = clone $opt;

								switch ($chr) {

									case '&':

									case '|':

										$opt->seq = $chr;

										break;

									

									case '<':

									case '>':

										$opt->seq = '';

										break;

									

									default:

										$opt->seq = substr($opt->seq, 0, -4);

										break;

								}

							}

							####

							$car = $opt;

							$rtv = $chr;

							break;

						}

					}

				}

				

				// matched

				if ($car) {

					// mutation

					if ($prv) {

						if ($car->typ == 'att' && $prv->typ == 'ope') {

							$end = count($dat) - 1;

							$dat[$end] = ['att', $dat[$end][1] .'='];

							$prv = $car;

							$car = null;

							$rtv = '';

						}

						// equal

						elseif ($car->typ == 'att' && $prv->typ == 'att') {

							$end = count($dat) - 1;

							$dat[$end] = ['eql', '=='];

							$prv = $def['eql'];

							$car = null;

							$rtv = '';

						}

						elseif ($car->typ == 'obj' && $prv->typ == 'int' && is_numeric($str{$i+1})) {

							$end = array_pop($dat);

							$rtv = $end[1] . $chr;

							$car = $def['flt'];

							$prv = null; // stack

						}

					}

				}

				// false

				else {

					// mout expression

					if ($prv) {

						// to do!

						if ($def[end($dat)[0]]->nxr && end($dat)[1] != 'else') {

							array_pop($dat);

							$i = end($dat)[2] + 1;

							$rtv = '';

						}

						$ret .= mount($cap);

						if ($cap == '$')

							$ret.= $spc;

						if ($tst == false) {

							$ret.= $rtv;

						}

					}

					

					//$ret.= $chr;

					$rtv = '';

					$cap = '';

					$tst = true;

					$i--;

				}

			}

		}

		// constructor

		elseif ($chr == '#') {

			if ($i == 0 || $str[$i-1] != '\\')

				$cap = $chr;

			// replaces escape by #

			else

				$ret = substr_replace($ret, '#', -1);

		}

		// variable

		elseif ($chr == '$') {

			if ($i == 0 || $str[$i-1] != '\\') {

				$cap = $rtv = '$';

				$car = $def['var'];

			}

			// replaces escape by $

			else {

				$ret = substr_replace($ret, '$', -1);

			}

		}

		// text

		else {

			$ret .= $chr;

		}

	}

	

	$ret = substr($ret, 0, -1);

	$str = substr($str, 0, -1);

	

	//echo "\n\ncode: {$str}";

	//echo "\n\nparsed:\n{$ret}";

	

	$stk = [];

	$dat = [];

	$prv = null;

	$car = null;

	$chr = '';

	$i = 0;

	$rtv = '';

	$evl = $ret;

	$ret = '';

	

	return '?>'. $evl .'<?php ';

}



/**

 * Fast string evaluation

 * @param string $evaluate

 * @param array $variables

 * @return string

 */

function fast($evaluate, $variables=[]) {

	require_once INCLUDES.'Date/Date.php';



	extract($variables);

	

	// default objects

	$date = new Date;

	

	// order

	if (isset($order) && $order instanceof Order) {

		$vendor = $order->vendor();

		$customer = $order->customer();

		// aliases

		if (!isset($store))

			$store = $vendor;

	}

	

	// charge

	if (isset($charge) && $charge instanceof Order) {

		$vendor = $charge->vendor();

		$customer = $charge->customer();

	}

	

	ob_start();

	eval(parse($evaluate));

	$buffer = ob_get_contents();

	ob_end_clean();

	return $buffer;

}



// test

// $str = '#if $x you #elif $y me #else them #end';

// echo parse($str); exit;



function arr_shift(&$array) {

	array_shift($array);

}



function arr_unshift(&$array) {

	$args = array_slice(func_get_args(), 1);

	$array = array_merge($args, $array);

}



function arr_push(&$array) {

	$args = array_slice(func_get_args(), 1);

	$array = array_merge($array, $args);

}



function arr_pop(&$array) {

	array_pop($array);

}



function format($var) {

	// date

	if ($var instanceof Date) {

		return strftime(func_get_args()[1], $var->getTimestamp());

	}

	

	return call_user_func_array(is_numeric($var) ? 'number_format' : 'sprintf', func_get_args());

}



function slice($var) {

	$args = func_get_args();

	while (end($args) === null) array_pop($args);

	return call_user_func_array(is_array($var) ? 'array_slice' : 'substr', $args);

}



function replace($var) {

	if (is_string($var)) {

		$args = func_get_args();

		array_push($args, array_shift($args));

		return call_user_func_array('str_replace', $args);

	}

}



function countkey($key, $lst, $lim=null) {

	$key = array_fill_keys($key, []);

	$dpt = 0;

	foreach ($lst as $i => $atm)

		if ($atm[0] == $lim && !$dpt)

			break;

		elseif ($atm[0] == 'bko' || $atm[0] == 'bro' || $atm[0] == 'obo')

			$dpt++;

		elseif ($atm[0] == 'bkc' || $atm[0] == 'brc' || $atm[0] == 'obc')

			$dpt--;

		elseif (isset($key[$atm[0]]) && !$dpt)

			$key[$atm[0]][] = $i;

	return $key;

}



function random($values) {

	if (is_string($values)) {

		$values = str_split($values);

		shuffle($values);

		return implode($values);

	}

	else {

		if (count($values) == 2 && is_numeric($values[0]) && is_numeric($values[1]) && $values[1] > $values[0])

			return rand($values[0], $values[1]);

		else {

			return $values[array_rand($values, 1)[0]];

		}

	}

}



function zip() {

	$args = func_get_args();

	$zipped = array();

	$n = count($args);

	for ($i=0; $i<$n; ++$i) {

		reset($args[$i]);

	}

	while ($n) {

		$tmp = array();

		for ($i=0; $i<$n; ++$i) {

			if (key($args[$i]) === null) {

				break 2;

			}

			$tmp[] = current($args[$i]);

			next($args[$i]);

		}

		$zipped[] = $tmp;

	}

	return $zipped;

}



function length($var) {

	return is_array($var) ? count($var) : str_length($var + '');

}



function slug($string, $lowercase=true, $separator='-') {

	$lowercase = $lowercase ? 'strtolower' : 'pass';

	return trim(preg_replace('#([^.A-Za-z0-9]+)#', $separator, $lowercase(unaccent(strtr(html_entity_decode($string, ENT_NOQUOTES, 'UTF-8'), '&', ' ')))), '-');

}



function clonefn($obj) {

	return clone $obj;

}



function isx($var, $typ) {

	return is_a($var, $typ);

}



//

// debug

//



function debug() {

	global $dat, $car, $chr, $rtv, $ret, $i;

	static $exe = 0;

	

	echo implode(' ', [

		str_pad($i, 2, '0', STR_PAD_LEFT),

		$chr ?: ' ',

		' ',

		str_pad($car ? $car->typ : '', 10),

		str_pad($rtv, 10),

		str_pad(imploder($dat), 50),

	]) ."\n";

}



function parse_file() {

	global $store;

	$str = '';

	foreach (func_get_args() as $file) {

		if (is_array($file)) {

			$str .= call_user_func_array('parse_file', $file);

		}

		else

			$str .= file_get_contents($store->dir('theme') . $file);

	}

	return $str;

}



function imploder($arr=null) {

	foreach ($arr as &$item)

		if (is_array($item))

			$item = '('. imploder($item) .')';

	return implode(' ', $arr);

}



?>