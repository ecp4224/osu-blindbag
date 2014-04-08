osu-blindbag
============

A website that gives you a random beatmap each week

#Branch layout
##master
The master branch contains the PHP code that will handle getting a random beatmap and keeping track of when the user can request another beatmap. It will handle all the database handling and the osu! api fetching.

##gh-pages
This is the [website][1]..

The website should treat the PHP code as an API, and should not add any aditional functionality. This will make the project more portable


#Contributing
You can contribute to either the website (gh-pages) or the functionality (master) by simply forking and submitting a pull request. Please be sure to pull request to the **correct branch** otherwise, your pull request may be ignored.

#License
This project is licensed under the GPL v2 license.

See the [license][2] file for more info.

[1]: http://eddiep.me/osu-blindbag
[2]: https://github.com/hypereddie/osu-blindbag/blob/master/LICENSE
