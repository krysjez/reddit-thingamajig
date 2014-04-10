import pg8000

#jessica's username is jessicayang, and her password is testing09876
conn = pg8000.connect(user="michaelnestler", password="testing54321", database="thingamajig", host="cs437dbinstance.cpa1yidpzcc3.us-east-1.rds.amazonaws.com")

conn.autocommit = True #by default this is false. if it is false, then when you change the database (such as insert, update) then you have to do conn.commit() afterwards for the changes to be kept. 

cursor = conn.cursor()

#an example of how to insert values. note that if the primary key already exists in the table, instead of your insert being ignored, you will get an exception. thus, it is necessary to test whether something already exists in the table... and this might be costly
#cursor.execute("insert into test values (10100, 'Debayan','Gupta');")

#so instead, we can check whether a record already exists before inserting it. 
cursor.execute("select * from test where \"StudentID\"=10101;")
if (cursor.rowcount == 0):
	cursor.execute("insert into test values (10101, 'Eli', 'Yale')")

#example of select
cursor.execute("select * from test where \"StudentID\"=10101;")
#cursor.execute("select * from test where StudentID=10100;") does not work. you must have the quotes around StudentID
results = cursor.fetchall()
for row in results:
	SID, firstname, lastname = row
	print("SID = %d, firstname = %s, lastname = %s" % (SID, firstname, lastname))

#print(results)



cursor.close()
conn.close()