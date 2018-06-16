# plex-webhooks
Scripts to process Plex Webhooks

## /plexhue
A php implementation to run on your local network and send commands directly to your Hue Bridge.
You will need to sign up for a hue developer account on their [Developers Site](https://www.developers.meethue.com) and follow tutorials for creating a user token for the api to access the bridge.

This implementation takes in configuration options via URL parameters so I can avoid checking in tokens and other implementation-specific configurations. This is not secure as the hue token is sent over plain text unless you configure https. I'm not worried about it for something that runs on my internal home network, but if you modify this to run on an external server, you'll want to use https.

Thus, the URL is of the format:
```
http://{host}/{basepath}/plexhue/index.php?playername={PlexPlayer}&huetoken={Hue Bridge Token}&huegroup=[Hue Light Group]&hueaddress={ip address of hue bridge}"
```
|Parameter |Description                                                                          |
|----------|-------------------------------------------------------------------------------------|
|playername|The name of the Plex Player that you want to trigger your lights                     |
|huetoken  |The API Token you get when you connect your developer account to your hue bridge     |
|huegroup  |The group number for the room you want to control. You will get this from the hue API|
|hueaddress|The IP address of your hue bridge. You might be able to use a hostname instead       |
