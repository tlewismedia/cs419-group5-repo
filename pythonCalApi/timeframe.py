import gflags
import httplib2
try:
  from xml.etree import ElementTree
except ImportError:
  from elementtree import ElementTree
import gdata.calendar.data
import gdata.calendar.client
import gdata.acl.data
import atom
import getopt
import sys
import string
import time

from apiclient.discovery import build
from oauth2client.file import Storage
from oauth2client.client import OAuth2WebServerFlow
from oauth2client.tools import run

FLAGS = gflags.FLAGS

class CalendarExample:

  def __init__(self, email, password, start_date, end_date):
    """Creates a CalendarService and provides ClientLogin auth details to it.
    The email and password are required arguments for ClientLogin.  The
    CalendarService automatically sets the service to be 'cl', as is
    appropriate for calendar.  The 'source' defined below is an arbitrary
    string, but should be used to reference your name or the name of your
    organization, the app name and version, with '-' between each of the three
    values.  The account_type is specified to authenticate either
    Google Accounts or Google Apps accounts.  See gdata.service or
    http://code.google.com/apis/accounts/AuthForInstalledApps.html for more
    info on ClientLogin.  NOTE: ClientLogin should only be used for installed
    applications and not for multi-user web applications."""

    self.cal_client = gdata.calendar.client.CalendarClient(source='Google-Calendar_Python_Sample-1.0')
    self.cal_client.ClientLogin(email, password, self.cal_client.source);

  def _DateRangeQuery(self, start, end):    
    """Retrieves events from the server which occur during the specified date
    range.  This uses the CalendarEventQuery class to generate the URL which is
    used to retrieve the feed.  For more information on valid query parameters,
    see: http://code.google.com/apis/calendar/reference.html#Parameters"""

    print 'Date range query for events on Primary Calendar: %s to %s' % (start, end)
    query = gdata.calendar.client.CalendarEventQuery(start_min=start, start_max=end)
    feed = self.cal_client.GetCalendarEventFeed(q=query)
    for i, an_event in zip(xrange(len(feed.entry)), feed.entry):
      print '\t%s. %s' % (i, an_event.title.text,)
      for a_when in an_event.when:
        print '\t\tStart time: %s' % (a_when.start,)
        print '\t\tEnd time:   %s' % (a_when.end,)

  def Run(self, start, end):
    """Runs each of the example methods defined above.  Note how the result
    of the _InsertSingleEvent call is used for updating the title and the
    result of updating the title is used for inserting the reminder and
    again with the insertion of the extended property.  This is due to the
    Calendar's use of GData's optimistic concurrency versioning control system:
    http://code.google.com/apis/gdata/reference.html#Optimistic-concurrency
    """

    # Getting feeds and query results
    self._DateRangeQuery(start, end)

def main():
  """Runs the CalendarExample application with the provided username and
  and password values.  Authentication credentials are required.
  NOTE: It is recommended that you run this sample using a test account."""

  # parse command line options
  try:
    opts, args = getopt.getopt(sys.argv[1:], "", ["user=", "pw=", "start=", "end="])
  except getopt.error, msg:
    print ('python calendarExample.py --user [username] --pw [password] ' +
        '--start YYYY-MM-DD --end YYYY-MM-DD')
    sys.exit(2)

  user = ''
  pw = ''
  start = ''
  end = ''

  # Process options
  for o, a in opts:
    if o == "--user":
      user = a
    elif o == "--pw":
      pw = a
    elif o == "--start":
      start = a
    elif o == "--end":
      end = a

  if user == '' or pw == '':
    print ('python timeframe.py --user [username] --pw [password] ' +
        '--start YYYY-MM-DD --end YYYY-MM-DD')
    sys.exit(2)

  sample = CalendarExample(user, pw, start, end)
  sample.Run(start, end)

if __name__ == '__main__':
  main()


