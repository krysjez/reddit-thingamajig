#Kevin Liu
import re
import datetime
import pg8000

conn = pg8000.connect(user="jessicayang", password="testing09876", database="thingamajig", host="cs437dbinstance.cpa1yidpzcc3.us-east-1.rds.amazonaws.com")
#conn.autocommit = True
cursor = conn.cursor()

badWords = ['ass', 'asshole', 'assholes', 'arsehole', 'bastard', 'bastards', 'bitch', 'bitches', 'clusterfuck', 'cock', 'cocks', 'cocksucker', 'cocksuckers', 'crap', 'cunt', 'cunts', 'damn', 'damns', 'dick', 'dicks', 'dickhead', 'dickheads', 'dickwad', 'dickwads', 'dumbass', 'dumbshit', 'dumbshits', 'fag', 'fags', 'fagot', 'fagots', 'faggot', 'faggots', 'fuck', 'fucker', 'fucking', 'fucks', 'goatcx', 'goatse', 'goddamn', 'idiot', 'idiots', 'moron', 'morons', 'motherfucker', 'motherfuckers', 'nigga', 'niggas', 'nigger', 'niggers', 'piss', 'pussy', 'shit', 'shits', 'slut', 'sluts', 'stupid', 'wanker', 'wankers']
	
cursor.execute("select \"CommentID\", \"CommentText\" from \"Comments\" where \"TimeRecorded\" > '2014-05-07 01:10:00' and \"TimeRecorded\" < '2014-05-07 04:46:00';")

#12:46am

results = cursor.fetchall()


#recalculates the enthusiasm and profanity scores for each comment
for row in results:
	id, CommentText = row
	badWordCount = 0
	wordCount = 0
	enthu = CommentText.count('?') + CommentText.count('!') + sum(x.isupper() for x in CommentText)
	lower = sum(x.islower() for x in CommentText)
	EnthusiasmScore = (enthu)/float(lower+1)
	for w in re.findall(r"\w+", CommentText):
		if w in badWords:
			badWordCount+=1
		wordCount+=1
	ProfanityScore = (badWordCount)/float(wordCount+1)
	cursor.execute("update public.\"Comments\" set \"EnthusiasmScore\" = %s, \"ProfanityScore\" = %s where \"CommentID\" = %s;", (EnthusiasmScore, ProfanityScore, id))
	conn.commit()
	
cursor.close()
conn.close()