# Leaflet.Sleep

Leaflet's stock maps are event-greedy and interfere with scrolling.

`Leaflet.Sleep` is an interaction manager, helping your
map do what you want when you want.

### [Demo](http://cliffcloud.github.io/Leaflet.Sleep)

## Use

Available on [npm](#npm), [bower](#bower), and from the single source
[source](https://github.com/CliffCloud/Leaflet.Sleep/blob/master/Leaflet.Sleep.js)
file.

`Leaflet.Sleep` is enabled on all maps by default,
but can be disabled with each map's `sleep` option.

### npm

[`npm install leaflet-sleep`](https://www.npmjs.com/package/leaflet-sleep)

### bower

`bower install leaflet-sleep`

## Config

These are the new options available for `L.map` (and the defaults).

```
{
    // false if you want an unruly map
    sleep: true,

    // time(ms) until map sleeps on mouseout
    sleepTime: 750,

    // time(ms) until map wakes on mouseover
    wakeTime: 750,

    // should the user receive wake instructions?
    sleepNote: true,

    // should hovering wake the map? (non-touch devices only)
    hoverToWake: true,

    // a message to inform users about waking the map
    wakeMessage: 'Click or Hover to Wake',

    // a constructor for a control button
    sleepButton: L.Control.sleepMapControl,

    // opacity for the sleeping map
    sleepOpacity: .7
}
```

## MIT Licensed
