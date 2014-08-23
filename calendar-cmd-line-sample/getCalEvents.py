
"""Command-line skeleton application for Calendar API.
Usage:
  $ python sample.py

You can also get help on all the command-line flags the program understands
by running:

  $ python sample.py --help

"""
from span import Span
from span import Spanlist
from span import timeConv
import time
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
# application, including client_id and client_secret. You can see the Client ID
# and Client secret on the APIs page in the Cloud Console:
# <https://cloud.google.com/console#/project/1022620417954/apiui>
CLIENT_SECRETS = os.path.join(os.path.dirname(__file__), 'client_secrets.json')

# Set up a Flow object to be used for authentication.
# Add one or more of the following scopes. PLEASE ONLY ADD THE SCOPES YOU
# NEED. For more information on using scopes please see
# <https://developers.google.com/+/best-practices>.
FLOW = client.flow_from_clientsecrets(CLIENT_SECRETS,
  scope=[
      'https://www.googleapis.com/auth/calendar',
      'https://www.googleapis.com/auth/calendar.readonly',
    ],
    message=tools.message_if_missing(CLIENT_SECRETS))


def getCalEvents(user, startTime, endTime):
  # Parse the command-line flags.

  # If the credentials don't exist or are invalid run through the native client
  # flow. The Storage object will ensure that if successful the good
  # credentials will get written back to the file.
  storage = file.Storage('sample.dat')
  credentials = storage.get()
  if credentials is None or credentials.invalid:
    credentials = tools.run_flow(FLOW, storage, flags)

  # Create an httplib2.Http object to handle our HTTP requests and authorize it
  # with our good Credentials.
  http = httplib2.Http()
  http = credentials.authorize(http)

  # Construct the service object for the interacting with the Calendar API.
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
        print e['start'] + " " + e['end']
        s = deGoogle (e['start'])
        en = deGoogle (e['end'])
        x = Span(s, en)
        busyList.append(x)
      

      page_token = calendar_list.get('nextPageToken')
      if not page_token:
        return busyList


  except client.AccessTokenRefreshError:
    print ("The credentials have been revoked or expired, please re-run"
      "the application to re-authorize")

if __name__ == '__main__':
    email = []
    email.append(sys.argv[1])
    email.append(sys.argv[2])
    a = sys.argv[3]
    b = sys.argv[4]
    start = time.strptime(a, "%d %b %Y %H:%M")
    end = time.strptime(b, "%d %b %Y %H:%M")

    beg = time.strftime("%Y-%m-%dT%H:%M:%S.000-07:00", start)
    done = time.strftime("%Y-%m-%dT%H:%M:%S.000-07:00", end)
    print getCalEvents(email[0], beg, done)

