<?php

/*
 * By adding type hints and enabling strict type checking, code can become
 * easier to read, self-documenting and reduce the number of potential bugs.
 * By default, type declarations are non-strict, which means they will attempt
 * to change the original type to match the type specified by the
 * type-declaration.
 *
 * In other words, if you pass a string to a function requiring a float,
 * it will attempt to convert the string value to a float.
 *
 * To enable strict mode, a single declare directive must be placed at the top
 * of the file.
 * This means that the strictness of typing is configured on a per-file basis.
 * This directive not only affects the type declarations of parameters, but also
 * a function's return type.
 *
 * For more info review the Concept on strict type checking in the PHP track
 * <link>.
 *
 * To disable strict typing, comment out the directive below.
 */

declare(strict_types=1);

function findFewestCoins(array $coins, int $amount): array
{
    if ($amount < 0)
       throw new InvalidArgumentException('Cannot make change for negative value');  
    if ($amount == 0 or empty($coins))
        return [];
    if ($coins[0] > $amount)
        throw new InvalidArgumentException('No coins small enough to make change');   
    
    $change = [];
    foreach ($coins as $value) 
    {
        if($value <= $amount)
        { 
            $quotient = intdiv($amount, $value);
            for ($i=$quotient;$i>0;$i--)
            {
                try { 
                    $temp = findFewestCoins(array_slice($coins, 0, count($coins)-1),
                        $amount - $i*$value);
                    $temp = array_merge(addNtimes([], $i, $value), $temp);
                    $change = ((count($change) > count($temp)) or empty($change)) ? $temp : $change;
                    break;
                }
                catch (InvalidArgumentException $e) {}
  
            }  
        }

    }
    
    if (array_sum($change) != $amount)
        throw new InvalidArgumentException('No combination can add up to target');
    sort($change); 
    return $change;
}

function addNtimes(array $arr, int $n, int $value) 
{
    for (;$n>0;$n--) 
    {
        array_push($arr, $value);
    }
    return $arr;
}

