# wp-api-cors
WordPress plugin that enables sensible (and pluggable) CORS settings for WP-API

For Basic Authentication, you should be able to just enable the plugin.

For OAuth1, there are a few constraints due to the implementation of the OAuth plugin.

* Your callback URL must use http or https (no file paths)
* Your callback URL must be on port 80, 443, or 8080

If you're testing an ionic app in the browser, you can make it listen on port 8080 with `ionic serve --lab --port 8080`
