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
// the first puzzle
$stepsInOrder = '';
while (count($rules)) {
    foreach ($steps as $step => $require) {
        if ($require === 0) {
            foreach ($rules as $ruleId => $rule) {
                if ($rule[0] === $step) {
                    $steps[$rule[1]]--;
                    unset($rules[$ruleId]);
                }
            }
            $steps[$step] = -1;
            $stepsInOrder .= $step;
            break;
        }
    }
}
foreach ($steps as $step => $require) {
    if ($require === 0) {
        $stepsInOrder .= $step;
    }
}
echo $stepsInOrder . PHP_EOL;
// the second puzzle




