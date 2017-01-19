# simple-image-share

Simple pin/password-protected, self hosted image uploader with file-lifetime management.

## Requirements
* PHP 5.6+
* Apache (for .htaccess)

## Installation and Configuration

1. Unzip the release archive files somewhere within on your web server.
2. Copy `inc/conf.inc.sample.php` to `inc/conf.inc.php`.
3. Open `inc/conf.inc.php`
   1. Add a sha256 hashed password to the $pins array (use `scripts/generate_password.php`). If you don't want to use password protection, set `REQUIRE_PIN` to `false` and leave the $pins-array empty.
   2. If wanted, configure IMG_DIR to a another directory. I recommend to move
      this directory outside of the web accessible root, for security purposes.
4. Give the web server write permissions to IMG_DIR.
5. Enjoy image uploading

### Configure automatic image cleanup

simple-image-share comes with functionality to regularly clean up your data
directory. Since images can be uploaded with a lifetime of "a day", "a year"
"a month" or "unlimited" some housekeeper has to clean the old cruft, and that's
what `inc/cron.php` is for.

To use it, you have to configure a secret in 'inc/conf.inc.php' and create a cron-
job for `https://<path to simple-image-share>/inc/cron.php?secret=<yoursecret>`.

