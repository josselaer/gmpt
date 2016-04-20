import random

file = open('test.sql', 'w+') #where it goes


tempFile = open('setup.sql', 'r') #Schema

tmp = tempFile.read()
file.write(tmp + "\n\n\n")




firstNames = ["John", "Jack", "Tom", "Bob", "Sarah", "Grace", "Timmothy", "Paige", "Matt", "Mark", "Jennifer", "Allison", "Mary", "Katie", "Jonathan", "Ben"]
lastNames = ["Johnson", "Smith", "Carter", "Gabrielson", "Fontenot", "Khan", "Mitchell", "Banbury", "Cohen", "Hansen", "Stenbit", "Renninger", "Reiss", "Goldman"]
nums = [2, 4, 5, 6, 8, 10, 15]
sports = ["\"soccer\"", "\"basketball\"", "\"tennis\"", "\"football\"", "\"ultimate frisbee\"", "\"squash\"", "\"indoor soccer\"", "\"pool\"", "\"ping pong\""]
times = ["\"12:30:00\"", "\"14:20:00\"", "\"08:00:00\"", "\"18:15:00\"", "\"22:00:00\"", "\"16:40:00\"", "\"09:30:00\"", "\"13:25:00\"", "\"21:05:00\""]
#end time = start time + 1 or 2 hours
dates = ["\"2016-3-14\"", "\"2016-3-28\"", "\"2016-4-14\"", "\"2016-4-11\"", "\"2016-5-18\"", "\"2016-4-28\"", "\"2016-7-11\"", "\"2016-5-12\""]
locations = ["\"Dedman\"", "\"Intramural Fields\"", "\"Wescott Field\"", "\"Some Park\"", "\"Moody Colliseum\""]
chats = ["Hey, I'll bring a ball!", "I'll bring some snacks!", "Just realized it's raining, so let's cancel?", "See you all in 10.", "Yo we should totally listen to some dank beats while we sports", "Just got here and its busy so we can't play. Sorry!", "Ay where the dank memes at?", "We still on?"]


prefs = [1, 2, 3, 4]

_salt = "1jo5OFAVDpC6xaEgt8sSuHcOSzo1SnkEVF5jHwD39SJegUlvz8nBLNeJBK6StVPCKzNNxUpOToQojUW304fW5gjniSqWejeBxo6Xtlgb0qIWW4vYoRYIIPRph8YwiW1mSxZ6sahYlfruDA52wtwPw82I9EVnEul7jRMbbFGFD2NDNW3AinEFt5sqMa84tKK0V9JJyRe4FY7yFTOVjSMV41WF2srbI3k0QVGoEaQ7r0tijCBnXil4QVwQ0ya1FW3g"
_hash = "d528cbceddde5d3cbc0f6f3b841a48e10d35fd84eea400959a2bc450c19d1a2f"
names = []


for i in range(100):
    name =  random.choice(firstNames) + random.choice(lastNames)+ str(i)
    file.write("INSERT INTO user(name, salt, hash) values(\"" + name + "\",\"" + _salt + "\",\""  + _hash + "\");")
    file.write('\n')
    names.append(name)

gameCounts = []
gamesFull = []

for i in range(5):
    file.write('\n')

for i in range(50):
    num = random.choice(nums)
    full = (random.choice(nums) == 2) #random bool creator
    gameCounts.append(num)
    gamesFull.append(full)
    file.write("INSERT INTO game(sport, time, date, playerCount, location, full) values(" + random.choice(sports) + ',' + random.choice(times) + ', ' + random.choice(dates) + ' , ' + str(num) + " , " + random.choice(locations) + " , " + ("TRUE" if full else "FALSE") + ");")
    file.write("\n")

for i in range(5):
    file.write('\n')

for i in range(50):
    players = int(gameCounts[i])
    if(not gamesFull[i]):
        players = players - 3 if players > 2 else 0
    tempNames = []
   
    for j in range(players):
        name = random.choice(names)
        while name in tempNames:
            name = random.choice(names)
        tempNames.append(name)
        file.write("INSERT INTO enlist values(\"" + name + "\", " + str(i+1) + " );")
        file.write('\n')

for i in range(100):
    temp = []
    for j in range(random.choice(prefs)):
        sport = random.choice(sports)
        while sport in temp:
            sport = random.choice(sports)
        temp.append(sport)
        file.write("INSERT INTO sportPreference(username, sport) values(\"" + names[i] +"\", " + sport + ");")
        file.write('\n')

for i in range(5):
    file.write('\n')

for i in range(50):
    for k in range(random.choice(nums)):
        file.write("INSERT INTO chat(username, gameID, message) values(\"" + random.choice(names) + "\", " + str(i+1) + ", \""  + random.choice(chats) + str(k)  + "\");")
        file.write('\n')




file.close()

    
