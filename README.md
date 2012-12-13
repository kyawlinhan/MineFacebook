MineFacebook
============

It is a website based facebook application. It mine your facebook data and present an easy to understand user interface.
Get public data of a user.
https://graph.facebook.com/[id or me or username]

Easier way to test.
https://developers.facebook.com/tools/explorer/

Testing Map GeoCode API
http://maps.googleapis.com/maps/api/geocode/json?address=Champaign,+Illinois&sensor=false

src folder: all libraries and backend codes. There are sub directories in the src folder to organize different functions.
tests folder: testings
javascripts folder: java scripts files
style folder: css styling file

To run tests written in tests folder:
do login on home page then
go to http://web.engr.illinois.edu/~kyaw2/tests/

Homepage:
First of all, there is welcome! in the content of home page. All menu button functions are explained on the homepage.
Please log in using the login button on menu bar.
After logged in, the webpage will now show your name retrieved from facebook as welcome![your name].
The homepage content now shows the access token. It will be useful if you want to counter check the facebook data shown on this webpage and data available through facebook graph api.

Mapfriends:
So far: This page shows map of 20 friends and var_dump of array1 containing multiple array2. array2 contains 4 elements. [userid, hometown, latitude, longitude].
To check your friends' location, take the first element of 2D array which is user id. Then using token given in homepage and following url, you can countercheck the data.
https://graph.facebook.com/[userid]?access_token=[token]. As the data is directly gotten from facebook and fed to google map api, there is only visual test available.

Statistics:
This page shows words taken from your facebook statuses. Break the sentences into words that are longer than strlen 2 and less than strlen 16. The word cloud shows most frequent word as larger font. Less frequent word, smaller font. It shows most liked status, best time to broadcast (advertise) and lookup an individual user online time distribution.

Social Network:
This page shows your social network visualization. As the library need a json file as the input, a json file is created with a random number name (in temp folder) to make it work. To prevent different users using the same file, a different name is used based on random number. It is just displaying you and your friends as nodes and connected in a graph. The color represents the communities among your friends.

<a rel="license" href="http://creativecommons.org/licenses/by-nc/3.0/deed.en_US"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc/3.0/88x31.png" /></a><br />This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc/3.0/deed.en_US">Creative Commons Attribution-NonCommercial 3.0 Unported License</a>.
