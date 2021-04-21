var OAuth = require('oauth');
var axios = require('axios');

/**
 * Create var for the authentication middleware
*/
const portailURL = '__PORTAIL_URL__';
const redirectURL = '__REDIRECT_WANTED_URL__'; // Need to be registered in the DB

var oauth2 = new OAuth.OAuth2(
    "__CLIENT_ID__",
    "__CLIENT_ID__",
    `${portailURL}/`,
    'oauth/authorize',
    'oauth/token',
    null
);

var authURL = oauth2.getAuthorizeUrl({
    response_type: 'code',
    redirect_uri: redirectURL,
    scope: ['user-get-info user-get-assos user-get-roles'],
    state: ''
});

const authenticationFilter = function (req, res, next) {
    /**
     * Check if the request contains a valid token
     */
    const token = req.header('authorization');
    if (token !== null && token !== undefined && token !== '') {
        // Check if the token is valid (use routes)
        axios({
            method: 'GET',
            url: `${portailURL}/api/v1/user`,
            headers: {
                'Accept': 'application/json',
                'Accept-Charset': 'utf-8',
                'Authorization': token
            }
        }).then(function (response) {
            // The token is not valid
            if (response.status !== 200) {
                // Redirect to cas
                return res.redirect(authURL);
            }
        }).catch(function (err) {
            console.error(err);
        });

        // Send the request to next server's middlware
        next();
        return;
    }

    /**
     * Check if the request has query parameters named code
     */
    const authorizationCode = req.query.code;

    if (authorizationCode === null || authorizationCode === undefined || authorizationCode === '') {
        // Handle redirection (the user is not connected with oauth2 yet)
        return res.redirect(authURL);
    } else {
        /** Obtaining access_token */
        oauth2.getOAuthAccessToken(
            authorizationCode,
            {
                'redirect_uri': redirectURL,
                'grant_type':'authorization_code'
            },
            function (err, access_token, refresh_token, results){
                if (err) {
                    console.log(err);
                    res.end(err);
                    return;
                } else if (results.error) {
                    console.log(results);
                    res.end(JSON.stringify(results));
                }
                else {
                    console.log('Obtained access_token: ', access_token);
                    res.end(access_token);

                    // Get information from the connected user
                    // Need to be admin to perform the request
                    axios({
                        method: 'GET',
                        url: `${portailURL}/api/v1/user`,
                        headers: {
                            'Accept': 'application/json',
                            'Accept-Charset': 'utf-8',
                            'Authorization': 'Bearer ' + access_token
                        }
                    }).then(function (response) {
                        // Print user information
                        console.log(response.data);
                    }).catch(function (err) {
                        console.error(err);
                    });
                }
            }
        );
    }

    // Send the request to next server's middlware
    next();
};

module.exports = authenticationFilter;
