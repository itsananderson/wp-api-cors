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

### Header switches

`cors_should_send_allow_origin`: Should an 'Access-Control-Allow-Origin' header be sent? Defaults to `true` unless the `Origin` header is empty (a.k.a. request doesn't use CORS).

`cors_should_send_allow_credentials`: Should an 'Access-Control-Allow-Credentials' header be sent? Defaults to `false`.

`cors_should_send_expose_headers`: Should an 'Access-Control-Expose-Headers' header be sent? Defaults to `true`.

`cors_should_send_max_age`: Should an 'Access-Control-Max-Age' header be sent? Defaults to `false`.

`cors_should_send_allow_methods`: Should an `Access-Control-Allow-Methods` header be sent? Default to `true`.

`cors_should_send_allow_headers`: Should an `Access-Control-Allow-Headers` header be sent? Default to `true`.

### Header Values

`cors_allowed_origins`: Array of allowed origins. Defaults to `array( $origin )` (effectively a wildcard).

`cors_allow_origin_value`: String value of the 'Access-Control-Allow-Origin` header. Defaults is `$origin`. Result will be escaped by `esc_url_raw` before being sent.

`cors_allow_credentials_value`: String value of the 'Access-Control-Allow-Credentials' header. Defaults to `'true'`, but by default this header is not sent.

`cors_exposed_headers`: Array of exposed headers. Defaults to `array( 'X-WP-Total', 'X-WP-TotalPages' )`.

`cors_expose_headers_value': String created by imploding the values returned from the `cors_exposed_headers` filter, separated by `', '`.

`cors_max_age_value`: Integer value of the 'Access-Control-Max-Age' header. Defaults value is 600 (ten minutes), but by default this header is not sent.

`cors_allowed_methods`: Array of allowed methods. default is `array( 'POST', 'GET', 'OPTIONS', 'PUT', 'DELETE' )`

`cors_allow_methods_value`: String created by imploding the values returned from the `cors_allowed_methods` filter, separated by `', '`.

`cors_allowed_headers`: Array of allowed headers. default is `array( 'Authorization' )`

`cors_allow_headers_value`: String created by imploding the values returned from the `cors_allowed_headers` filter, separated by `', '`.
