<?php

namespace AKikhaev\Terminal;

class Terminal
{
    public const IS_CLI = PHP_SAPI === 'cli';

    public const DEFAULT = 0;
    public const BOLD = 1;
    public const SEMI_BOLD = 2;
    public const LIGHT_OR_UNDERLINE = 4;
    public const FLASH = 5;
    public const INVERSE = 7;
    public const INTENSIVE_NORMAL = 22;
    public const UNDERLINE_CANCEL = 24;
    public const FLASH_CANCEL = 25;
    public const INVERSE_CANCEL = 27;
    public const BLACK = 30;
    public const RED = 31;
    public const GREEN = 32;
    public const BROWN = 33;
    public const BLUE = 34;
    public const VIOLET = 35;
    public const CYAN = 36;
    public const GRAY = 37;

    /** Return escape sequence
     * @param int $code
     * @return string
     *
     * https://manpages.ubuntu.com/manpages/trusty/man4/console_codes.4.html
     *
     * https://wiki.bash-hackers.org/scripting/terminalcodes
     *
     * https://ascii-table.com/ansi-escape-sequences.php
     *
     * https://ascii-table.com/ansi-escape-sequences-vt-100.php
     *
     */
    public static function es($code = self::DEFAULT): string
    {
        return "\e[$code".'m'; // \e = \x1b = \033
    }

    /** Return escape sequence to set foreground color
     * @param int $color
     * @return string
     */
    public static function color($color = 0): string
    {
        return self::es($color);
    }

    /** Return escape sequence to set background color
     * @param int $color
     * @return string
     */
    public static function background($color = 0): string
    {
        if (($color >= self::BLACK) && ($color <= self::GRAY)) {
            return self::es($color+10);
        }
        return '';
    }

    /**
     * Output to active terminal
     * @param $data
     * @param null $terminal
     * @return bool
     */
    public static function write($data): bool
    {
        if (self::IS_CLI) {
            echo $data;
            return true;
        }
        return false;
    }

    /**
     * Output to active terminal colored
     * @param $data
     * @return bool
     */
    public static function writeColored($data, $color): bool
    {
        return self::write($data . self::color($color));
    }

    /**
     * Output to active terminal with new line at end
     * @param $data
     * @return bool
     */
    public static function writeLn($data = ''): bool
    {
        return self::write($data . PHP_EOL);
    }

    /**
     * Clear terminal scroll memory
     * @return bool
     */
    public static function clearScreenScroll(): bool
    {
        return self::write("\e[2J\e[H\e[3J");
    }

    /**
     * Clear screen. It can be restored by scrolling
     * @return bool
     */
    public static function clearScreen(): bool
    {
        return self::write("\e[2J\e[H");
    }

    /**
     * Clear only scroll memory. All current data stay on screen 
     * @return bool
     */
    public static function clearScroll(): bool
    {
        return self::write("\e[3J");
    }

    /**
     * Default terminal short beep sound
     * @return bool
     */
    public static function beep(): bool
    {
        return self::write("\x07");
    }

    /**
     * Set terminal screen title. Bash usually restore it after process to previous state   
     * @param $title
     * @return bool
     */
    public static function title($title): bool
    {
        return self::write("\e]0;$title\007");
    }

    /**
     * Clear current line. Now you can print from its beginning 
     * @return bool
     */
    public static function clearLine(): bool
    {
        self::write("\e[2K");
    }

    /**
     * Violet time, usual message
     * @param $msg
     * @return bool
     */
    public static function log($msg): bool
    {
        return self::write("\r\e[K" . self::es(self::VIOLET) . date('H:i:s ') . self::es() . $msg . self::es() . PHP_EOL);
    }

    /**
     * Violet time, gray message without new line
     * @param $msg
     * @return bool
     */
    public static function logProcess($msg): bool
    {
        return self::write("\r\e[K" . self::es(self::VIOLET) . date('H:i:s ') . self::es(self::GRAY) . self::es(self::BOLD) . $msg . self::es());
    }

    /**
     * Violet time, red bold message
     * @param $msg
     * @return bool
     */
    public static function logError($msg): bool
    {
        return self::write("\r\e[K" . self::es(self::VIOLET) . date('H:i:s ') . self::es(self::RED) . self::es(self::BOLD) . $msg . self::es() . PHP_EOL);
    }

    /**
     * Violet time, red bold message красное. Halt
     * @param $msg
     * @return bool
     */
    public static function logDie__($msg): bool
    {
        self::write("\r\e[K" . self::es(self::VIOLET) . date('H:i:s ') . self::es(self::RED) . self::es(self::BOLD) . $msg . self::es(self::CYAN) . ' DIE' . self::es() . PHP_EOL);
        die;
    }

    /**
     * Violet time, green message
     * @param $msg
     * @return bool
     */
    public static function logInfo($msg): bool
    {
        return self::write("\r\e[K" . self::es(self::VIOLET) . date('H:i:s ') . self::es(self::GREEN) . $msg . self::es() . PHP_EOL);
    }

}