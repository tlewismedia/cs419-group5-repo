class User:

	def __init__(self, firstName, lastName,  email, events):
		# Parameters: string firstName, string lastName, string email, span [] events
		# Returns: user
		# determine freeTimes from events?
		self.firstName = firstName
		self.lastName = lastName
		self.email = email
		self.events = events

	def printUser (self)
		# Parameters: user
		# Returns: 
		print self.firstName 
		print self.lastName 
		print self.email 
