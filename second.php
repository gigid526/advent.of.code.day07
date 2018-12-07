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
$workers = [0, 0];

for ($t = 0; $t < 3; ++$t) {
    $availableWorkers = [];
    foreach ($workers as $workerId => $work) {
        if ($work > 0) {
            $workers[$workerId]--;
        } else {
            $availableWorkers[] = $workerId;
        }
    }
    var_dump($workers);
    var_dump($availableWorkers);
    if (count($availableWorkers) === 0) {
    }
    $next = $steps;
    foreach ($steps as $step => $require) {
        if ($require === 0) {
            foreach ($rules as $ruleId => $rule) {
                if ($rule[0] === $step) {
                    $next[$rule[1]]--;
                    unset($rules[$ruleId]);
                }
            }
            $next[$step] = -1;
            $workerId = array_shift($availableWorkers);
            $workers[$workerId] = ord($step) - 64;
        }
    }
    var_dump($steps);
    var_dump($next);
    $steps = $next;
}


