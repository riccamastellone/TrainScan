TrainScan
=========
Italy's hi-speed train fares comparator

The idea of this project was to make it easier to choose between the two italian hi-speed train companies, Trenitalia (www.trenitalia.com) and Italo (www.italotreno.it)

It was developed in mid 2012 by me, having a one-month deadline (due to various reasons, we couldn't make this this real).
It worked flawlessly at the time, but it has not been updated since then.

To get Trenitalia timetable and fares, the iPhone app was reverse engineered (MITM).
It uses a (sort-of-broken) SAOP protocol (might have been fixed by now).

To get Italo timetable and fares it simulates user requests and parses HTML response.

Because of the rush, not al possible solutions to implement this logic have been considered: had I to do it today from scratch, I'd go for a CasperJS/PhantomJS way for Italo. 

It also handles 'unclear' stations (some cities, like Milan, have multiple stations), repeating the requests to show all the possibilities.

An attempt to start a multi-thread version using pcntl of this appication to increase parsing speed was made (an it worked!) but abandoned with the whole project.

To avoid useless requests, it has a caching system (similar to SkyScanner), even if the user can still force the application to refresh its results

It does also have a neat front-end based on Bootstrap