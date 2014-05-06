

# This is supposed to be the one crawler to rule them all; please don't
# modify unless you are sure of it
# To be integrated with Kevin's functions

import praw
import re
import datetime
import pg8000
import pdb

topreddits = ['pics', 'funny', 'gaming', 'AskReddit', 'worldnews', 'news', 'videos', 'IAmA', 'todayilearned', 'aww', 'AdviceAnimals', 'science', 'Music', 'movies', 'bestof', 'books', 'EarthPorn', 'explainlikeimfive', 'gifs', 'television', 'askscience', 'sports', 'mildlyinteresting', 'LifeProTips', 'woahdude', 'Unexpected', 'reactiongifs', 'Showerthoughts', 'food', 'Jokes', 'photoshopbattles', 'firstworldanarchists', 'FoodPorn', 'HistoryPorn', 'WTF', 'leagueoflegends', 'dogecoin', 'gameofthrones', 'cringepics', '4chan', 'circlejerk', 'trees', 'nba', 'politics', 'atheism', 'pokemon', 'soccer', 'TrollXChromosomes', 'technology', 'pcmasterrace', 'MakeupAddiction', 'DotA2', 'StarWars', 'gentlemanboners', 'Minecraft', 'cats', 'Celebs', 'oddlysatisfying', 'SquaredCircle', 'GlobalOffensive', 'hockey', 'TumblrInAction', 'Games', 'tumblr', 'facepalm', 'teenagers', 'anime', 'twitchplayspokemon', 'conspiracy', 'hiphopheads', 'starcraft', 'Bitcoin', 'comics', 'hearthstone', 'skyrim', 'cringe', 'nfl', 'mylittlepony', 'tattoos', 'Android', 'mildlyinfuriating', 'asoiaf', 'LadyBoners', 'standupshots', 'fffffffuuuuuuuuuuuu', 'polandball', 'thatHappened', 'awwnime', 'nottheonion', 'progresspics', 'DarkSouls2', 'FiftyFifty', 'talesfromtechsupport', 'dayz', 'youtubehaiku', 'motorcycles', 'RedditLaqueristas', 'Fallout', 'sex', 'TalesFromRetail', 'MURICA', 'roosterteeth', 'wow', 'fatlogic', 'OldSchoolCool', 'comicbooks', 'GrandTheftAutoV', 'magicTCG', 'MapPorn', 'wallpapers', 'tf2', 'Frozen', 'offmychest', 'creepyPMs', 'smashbros', 'Whatcouldgowrong', 'AskHistorians', 'TopGear', 'battlefield_4', 'carporn', 'interestingasfuck', 'fatpeoplestories', 'electronic_cigarette', 'australia', 'Diablo']
for topred in topreddits:
    SubredditName=topred
    print (SubredditName)

