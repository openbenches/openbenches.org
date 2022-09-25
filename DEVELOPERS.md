# OpenBenches - Developer Documentation

https://OpenBenches.org/ - is an open data repository for memorial benches. Here's how it works.

## Disclaimer

Most of this PHP code was written quickly to scratch an itch. It is a pile of inconsistent conventions and half-remembered best practices.

## The basic structure

From the user's perspective, there is:

* The root - `openbenches.org`
* Benches are - `/bench/1234`
* Images are - `/image/abc123`
* Individual pages like `/add` or `/edit/1234`

### JS & CSS

* All the PHP is in `/www/`
   * PHP libraries are in `/www/vendor/`
* The CSS is in `/www/css/`
* JavaScript is in `/www/libs/library_name/`
   * JS Libraries are versioned to prevent clients caching the wrong version - e.g. `/www/libs/jquery.3.6.0/`
* Fonts are, you guessed it, in `/www/fonts/`
* UI Images are in `/www/images/` - they're SVG wherever possible.

## Page Construction

From the developer's perspective, all pages are generated through `/index.php` - this uses `.htaccess` to create a pretty URl structure.

For example, `/add` is *really* `/index?q=add` which then loads `/add.php`

Clever, eh?

All pages use a common `header.php` and `footer.php`

Most pages use `functions.php` which is where all the functions are.

## Configuration

The `config.php` file contains all the API keys for the various services we use - and there are a *lot* of them!

Each is defined like:

`define('CLOUD_VISION_KEY', 'abc123');`

Which means they can be called in code as `CLOUD_VISION_KEY`

## Database

The DB is ordinary MySQL. The functions for connecting to it are in `mysql.php`

Everything uses [Prepared Statements](https://dev.mysql.com/doc/refman/8.0/en/sql-prepared-statements.html) like this:

```php
$insert_media = $mysqli->prepare(
	'INSERT INTO `media`
	(`mediaID`, `benchID`, `userID`, `sha1`, `licence`, `importURL`, `media_type`, `width`, `height`, `datetime`, `make`, `model`)
	VALUES
	(NULL,       ?,         ?,        ?,      ?,         ?,           ?,            ?,       ?,       ?,          ?,      ?);'
);

$insert_media->bind_param('iissssiisss', $benchID, $userID, $sha1, $licence, $import, $media_type, $width, $height, $datetime, $make, $model);
```

That prevents any naughtiness or SQL injection attacks.

## Functions

The `functions.php` file contains all the helper functions. For example, how to get a location from an image, or how to Tweet a new bench.

There's a *lot* of stuff in there and it is pretty inconsistent. Sorry!

Most of the functions use the `verb_noun()` convention. For example `get_bench()` or `save_image()`

## Pages

```
/
├── config.php - all the API keys
├── mysql.php - connection to the database
├── functions.php - helper functions
│
├── header.php - page header
├── footer.php - page footer
│   ├── sponsor.php - links to sponsor the site
│   └── menu.php - bottom menu
│
├── front.php - default page
│   └── searchform.php - form to search the site
├── add.php - a user adds a bench. POSTs data to upload.php
├── footer.php - page footer
├── bench.php - displays a bench. Called as /bench/1234
│   ├── sharing.php - links to share a specific page
│   └── searchform.php - form to search the site. POSTs to /search
├── image.php - displays an image. Called as /image/abc123
              - where the 2nd parameter is the SHA1 hash of the media
├── benchimage.php - displays the HTML for a single image. Called as /benchimage/1234
│                  - where 1234 is the ID of the bench.
│                  - Used by JS functions to show a page preview
├── flickr.php - admin page for importing images from flickr.com
├── edit.php - edit a bench
├── search.php - search the site - either by text or SOUNDEX. Paginated
│   └── searchform.php - form to search the site. POSTs to /search
├── search.php - search the site - either by text or SOUNDEX. Paginated
├── sitemap.xml.php - provides a sitemap to search engines
├── data.json.php - redirects users to the API
├── login.php - redirects users to Auth0 for log in
├── logout.php - redirects users to Auth0 for log out
├── leaderboard.php - shows who has uploaded the most benches and photos
├── user.php - shows an individual user's page.	Either:
│            - /user/twitter/edent (shows external username)
│            - or
│            - /user/1234 (shows internal userid)
├── rss.php - RSS feed of latest benches
├── oembed.php - generates a json+oembed
├── api.php - redirector for the API
├── tag.php - shows all benches with a a specific tag /tag/cat
├── location.php - displays the map zoomed to a specific location
|                - /#location/zoom
├── colophon.php - displays a list of products and services used by OpenBenches
├── offline.php - displays an offline page for the service worker





    ├── bsg
    └── bus
        └── usb
            ├── 001
            └── 002
```