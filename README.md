# wp-api-cors
WordPress plugin that enables sensible (and pluggable) CORS settings for WP-API

Basic Auth
---

For Basic Authentication, you should be able to just enable the plugin.

OAuth1
---

For OAuth1, there are a few constraints due to the implementation of the OAuth plugin.

* Your callback URL must use http or https (no file paths)
* Your callback URL must be on port 80, 443, or 8080

Ionic
---

If you're testing an Ionic app in the browser and authenticating using oauth1, you can make it listen on port 8080 with:

`ionic serve --lab --port 8080`

Hooks
---

The following hooks and can be used to extend the default functionality

`cors_origin_disallowed`: Called if an origin is provided that is not in the list of allowed origins


Filters
---

The following filters can be used to extend the default functionality
