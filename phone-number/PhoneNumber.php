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

class PhoneNumber
{
    private $number;

    function __construct($number) 
    {
        
        if (preg_match('/^\+?1? ?\(?[2-9]\d{2}\)?[- \.]*[2-9]\d{2}[- \.]*\d{4}\s*?$/', $number))
            $this -> number = $number;
        else
            self::identifyNumberError($number); 
    }

    public function number(): string
    {
       return self::stripNumberEvenPrefix($this -> number); 
    }

    public static function identifyNumberError(string $number)
    {
        $strippedNum = self::stripNumber($number);
        self::checkNumberOfDigits($strippedNum);
        self::checkCountryCode($strippedNum);
        self::checkInvalidChars($strippedNum);
        self::checkIfWellFormattedCode('area code',
            strlen($strippedNum) == 11 ? substr($strippedNum, 1, 4):substr($strippedNum, 0, 3));
        self::checkIfWellFormattedCode('exchange code',
            strlen($strippedNum) == 11 ? substr($strippedNum, 4, 7):substr($strippedNum, 3, 6));
    }

    private static function checkIfWellFormattedCode(string $codename, string $number)
    {
        if ((int) $number[0] == 0)
            throw new InvalidArgumentException($codename . ' cannot start with zero');
        elseif  ((int) $number[0] == 1)
             throw new InvalidArgumentException($codename . ' cannot start with one');   
    }

    private static function checkInvalidChars(string $number)
    {
        if (preg_match('/[A-Za-z]+/', $number))
            throw new InvalidArgumentException('letters not permitted');
        elseif (preg_match('/[[:punct:]]/', $number))
           throw new InvalidArgumentException('punctuations not permitted'); 
    }

    private static function checkCountryCode(string $number)
    {
        if (strlen($number) == 11)
            if ($number[0] != 1)
                throw new InvalidArgumentException('11 digits must start with 1');     
    }

    private static function checkNumberOfDigits(string $number) 
    {
        if (strlen($number) < 10)
            throw new InvalidArgumentException('incorrect number of digits');
        elseif (strlen($number) > 11) 
            throw new InvalidArgumentException('more than 11 digits');
    }

    public static function stripNumber(string $number): string 
    {
        return preg_replace('/[\+\s\-\(\)\.]/', '', $number);
    }

    public static function stripNumberEvenPrefix(string $number): string 
    {
        $number = self::stripNumber($number);
        return strlen($number) == 11 ? substr($number, 1):$number; 
    }
}
