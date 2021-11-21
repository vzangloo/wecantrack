Hello,

I encountered an issue with the following code:
```phpt
$clickout = (new Clickout())->get();
echo $clickout->getReference();
```

WeCanTrack version: PUT HERE YOUR WECANTRACK VERSION (exact version)

PHP version: PUT HERE YOUR PHP VERSION

I expected to get:
```phpt
wct200514135314e7x4d
```
But I actually get:
```phpt
null
```
Thanks!
