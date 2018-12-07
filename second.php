<?php
$steps = [];
$rules = array_map(function ($rule) use (&$steps) {
    $matches = [];
    preg_match('/Step ([A-Z]+) must be finished before step ([A-Z]+) can begin\./', $rule, $matches);
    $steps[$matches[1]] = isset($steps[$matches[1]]) ? $steps[$matches[1]] : 0;
    $steps[$matches[2]] = isset($steps[$matches[2]]) ? $steps[$matches[2]] + 1 : 1;
    return [$matches[1], $matches[2]];
}, file(__DIR__ . '/input.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES));
ksort($steps);
// the second puzzle
$workers = [null, null, null, null, null]; // what step is realizing
$workToDo = [0, 0, 0, 0, 0]; // how many seconds remain to complete the step
$done = '';
for ($t = 0; true; ++$t) { // iterate every single second
	foreach (array_keys($workToDo) as $workerId) {
		if ($workToDo[$workerId] > 0) {
			$workToDo[$workerId]--; // reduce remaining seconds
			if ($workToDo[$workerId] === 0) {
				$steps[$workers[$workerId]] = -2; // set step's status as done
				$done .= $workers[$workerId];
				$workers[$workerId] = null; // mark a worker as available
			}
		}
	}
	// debug display
	echo $t . "\t";
	foreach ($workers as $step) {
		echo (is_null($step) ? '.' : $step) . "\t";
	}
	echo $done . "\t\t";
	foreach ($steps as $step => $required) {
		echo $step . '=' . $required . ' / ';
	}
	echo PHP_EOL;
	// check if all steps are completed
	if (strlen($done) === count($steps)) {
		echo $t . PHP_EOL;
		break;
	}
	// find all available workers
	$availableWorkers = [];
	foreach (array_keys($workers) as $workerId) {
		if (is_null($workers[$workerId])) {
			array_push($availableWorkers, $workerId);
		}
	}
	// if available then assign steps
	if (count($availableWorkers)) {
		foreach (array_keys($steps) as $step) {
			if ($steps[$step] === -2) { // checks if the step is done
				foreach ($rules as $ruleId => $rule) {
					if ($rule[0] === $step) {
						$steps[$rule[1]]--; // reduce a step's requirements
						unset($rules[$ruleId]);
					}
				}
			}
		}
		foreach (array_keys($steps) as $step) {
			if ($steps[$step] === 0 && count($availableWorkers)) { // if all step's requirements satisfied
				$workerId = array_shift($availableWorkers);
				$workers[$workerId] = $step;
				$workToDo[$workerId] = 60 + ord($step) - 64;
				$steps[$step] = -1; // mark the step's status as in progress
			}
		}
	}
}
