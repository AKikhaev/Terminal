# Terminal
Terminal output class. Clear screen or scroll, use colors, title, etc.

#### usage
* `Terminal::writeColored('text',Terminal::GREEN);`
* `Terminal::title('Updating user database');`
* `Terminal::log('17 records changed');`
* Complicated:
```
Terminal::writeLn(
    self::color(self::VIOLET) . 
    date('H:i:s ') . 
    self::color(self::GRAY) . 
    self::es(self::BOLD) . 
    self::backgroud(self::BLUE) . 
    $msg . 
    self::es()
);
```
* etc.