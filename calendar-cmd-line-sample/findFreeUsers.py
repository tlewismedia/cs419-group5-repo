from span import *
import datetime
import httplib2
import os
import sys

import json
import pprint

from apiclient import discovery
from oauth2client import file
from oauth2client import client
from oauth2client import tools



# CLIENT_SECRETS is name of a file containing the OAuth 2.0 information for this
# application
CLIENT_SECRETS = os.path.join(os.path.dirname(__file__), 'client_secrets.json')

FLOW = client.flow_from_clientsecrets(CLIENT_SECRETS,
  scope=[
      'https://www.googleapis.com/auth/calendar',
      'https://www.googleapis.com/auth/calendar.readonly',
    ],
    message=tools.message_if_missing(CLIENT_SECRETS))


def getCalEvents(user, startTime, endTime):

  storage = file.Storage('sample.dat')
  credentials = storage.get()
  if credentials is None or credentials.invalid:
    credentials = tools.run_flow(FLOW, storage, flags)

  http = httplib2.Http()
  http = credentials.authorize(http)

  service = discovery.build('calendar', 'v3', http=http)

  try:
    page_token = None
    while True:
      calendar_list = service.calendarList().list(pageToken=page_token).execute()

      freebusy_query = {
        "timeMin" : startTime,
        "timeMax" : endTime,
        "items" :[
          {
            "id" : user
          }
        ]
      }
      request = service.freebusy().query(body=freebusy_query)
      response = request.execute()

      busyList = []
      events = response['calendars'][user]['busy']
      for e in events:
        #print e['start'] + " " + e['end']
        s = timeConv.deGoogle (e['start'])
        en = timeConv.deGoogle (e['end'])
        x = Span(s, en)
        busyList.append(x)
      

      page_token = calendar_list.get('nextPageToken')
      if not page_token:
        return busyList


  except client.AccessTokenRefreshError:
    print ("The credentials have been revoked or expired, please re-run"
      "the application to re-authorize")


def findFreeUsers(emails, span):
    availableUsers =[]
    reqStart = timeConv.forGoogle(span.start)
    reqEnd = timeConv.forGoogle(span.end)
    for email in emails:
        free = True
        eventList = getCalEvents(email, reqStart, reqEnd)
        Span.printSpans(eventList)
        for event in eventList:
            if Span.isConflict (event, span):
                free = False
        if free == True:
            availableUsers.append(email)
    return availableUsers

if __name__ == '__main__':
    email = []
    email.append(sys.argv[1])
    email.append(sys.argv[2])
    start = timeConv.stringtodt(sys.argv[3])
    end = timeConv.stringtodt(sys.argv[4])
    reqTime = Span(start, end)
    freeUsers = findFreeUsers(email, reqTime)
    for user in freeUsers:
        print user

