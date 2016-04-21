import random
from datetime import timedelta, datetime
import time

t1 = "12:30:00"
tt1 = datetime.strptime(t1, "%H:%M:%S")
print tt1
t3 = tt1 + timedelta(hours=2)
print t3

tf = ""
tf = `t3.hour` + ":" + `t3.minute`
print tf


file = open('test.sql', 'w+') #where it goes


tempFile = open('gmpt.sql', 'r') #Schema

tmp = tempFile.read()
#file.write(tmp + "\n\n\n")

file.write("USE GMPT; \n\n\n")

#firstNames = ["John", "Jack", "Tom", "Bob", "Sarah", "Grace", "Timmothy", "Paige", "Matt", "Mark", "Jennifer", "Allison", "Mary", "Katie", "Jonathan", "Ben"]
#lastNames = ["Johnson", "Smith", "Carter", "Gabrielson", "Fontenot", "Khan", "Mitchell", "Banbury", "Cohen", "Hansen", "Stenbit", "Renninger", "Reiss", "Goldman"]
#nums = [2, 4, 5, 6, 8, 10, 15]
#sports = ["\"soccer\"", "\"basketball\"", "\"tennis\"", "\"football\"", "\"ultimate frisbee\"", "\"squash\"", "\"indoor soccer\"", "\"pool\"", "\"ping pong\""]
startTimes = ["12:30:00", "14:20:00", "08:00:00", "18:15:00", "22:00:00", "16:40:00", "09:30:00", "13:25:00", "21:05:00"]
#end time = start time + 1 or 2 hours
dates = ["\"2016-3-14\"", "\"2016-3-28\"", "\"2016-4-14\"", "\"2016-4-11\"", "\"2016-5-18\"", "\"2016-4-28\"", "\"2016-7-11\"", "\"2016-5-12\""]
locations = ["\"Fondren Library\"", "\"Junkins\"", "\"Innovation Gym\"", "\"Caruth\"", "\"Starbucks\""]
chats = ["Hey, what's up?", "I'll bring some snacks!", "I can't make it", "See you all in 10.", "I hope our teacher likes this", "I have other work to do sorry", "What did you guys talk about?", "We still on?", "Nothing really"]
projectDescription = ["Make a website", "Build an app", "Networks project", "Research paper", "Case Study", "Group Presentation", "Data Structures Search Engine", "Technical Entrepenueralship", "Management Semester Project", "Financial Accounting Project"]
meetingDescription1 = ["Plan or Design", "Start", "Work on", "Finish"]
meetingDescription2 = ["website", "app", "paper", "interview", "case study", "presentation", "group homework", "project"]

#update projectDescriptions
for i in range(50):
    desc = random.choice(projectDescription)
    file.write("UPDATE Project SET Description = " + "\"" +desc + "\"" + " WHERE ProjectID = " + i+1)
    file.write('\n')

file.write('\n')
file.write('\n')
file.write('\n')

#update chats
for i in range(50):
    chat = random.choice(chats)
    file.write("UPDATE Message SET MessageText = " + "\"" + chat + "\"" + " WHERE MessageID = " + i+1)
    file.write('\n')

file.write('\n')
file.write('\n')
file.write('\n')

oddEven = [1,2]

#update meetings
for i in range(50):
    desc1 = random.choice(meetingDescription1)
    desc2 = random.choice(meetingDescription2)
    fullDesc = "\"" + desc1 + " " + desc2 + "\""
    location = random.choice(locations)
    mDate = random.choice(dates)
    stime = random.choice(startTimes)
    startTime = datetime.strptime(t1, "%H:%M:%S")
    et = random.choice(oddEven)
    if (et == 1):
        endT = startTime + timedelta(hours=1)
    else:
        endT = startTime + timedelta(hours=2)
    endTime = ""
    endTime = `endT.hour` + ":" + `endT.minute`
    file.write("UPDATE Meeting SET MeetingDescription = " + fullDesc + ", LocationName = " + "\"" + location + "\"" + ", MeetingDate = " + mDate + ", StartTime = " + "\"" + stime + "\"" + ", EndTime = " + "\"" + endTime + "\"" + " WHERE MeetingID = " + i+1)

file.write('\n')
file.write('\n')
file.write('\n')


'''
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
    
    '''