# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html


# DIRECTIONS: 
# 1) install Scrapy
# 2) edit domain and start url to correct address
# 3) run this script with: scrapy crawl courses -o items.json

# this script works with the following URL: http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=BI&termcode=201500&campus=corvallis&columns=bcfjk
# all urls must have similar url arguments

import scrapy
import re

from courses.items import CourseItem

class CoursesSpider(scrapy.Spider):
    name = "courses"
    allowed_domains = ["catalog.oregonstate.edu"]
    start_urls = [
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=BI&termcode=201500&campus=corvallis&columns=bcfjk",
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=ALS&termcode=201500&campus=corvallis&columns=bcfjk"

    ]

    # allowed_domains = ["localhost-scrapy"]
    # start_urls = [
    #     "http://localhost-scrapy"
    # ]

    def parse(self, response):


        print "########### PARSE ###########"

        # goal is to first grab the tables where the first row has a tr 
        # and the first th is 'Term'

        i = 0
        tables = response.xpath('//table')

        for table in tables:

    

            
            i += 1

            if i == 7 or i == 6: continue


            # print table.xpath('.//th/font/b/text()').extract()
            header = table.xpath('.//th/font/b/text()')
            # print "header: "
            
            
            if not header: continue

            # print header

            print '\n\n\n*********************\n'
            print "TABLE: " + str(i) 
            


            print "header[0]: " + header[0].extract()

            if header[0].extract() != "StartDate" : continue

            

            # at this point we know we want this table

            # get rows from this table
            rows = table.xpath('./tr')

            # print "ROWS: "
            # print rows
            
            for row in rows:
                
                

                # if it's a row with no td's then skip
                if not row.xpath('./td/font/text()').extract(): continue               
                

                 # discard courses with TBA dates
                days = re.findall( '[A-Z]+' , row.xpath('./td/font/text()')[4].extract())
                if days[0] == 'TBA' : continue 

                # discard courses with no profs
                prof = row.xpath('./td/font/text()')[3].extract()
                print "prof"
                print prof

                if not re.match('[a-zA-Z0-9_]',prof): continue
                
                
                item = CourseItem()

                item['days'] = days[0]
                item['start'] = row.xpath('./td/font/text()')[0].extract()
                item['end'] = row.xpath('./td/font/text()')[1].extract()
                item['crn'] = row.xpath('./td/font/text()')[2].extract()            
                item['prof'] = prof
                item['times'] = re.findall( '[\d-]+' , row.xpath('./td/font/text()')[4].extract())[0]
                yield item


