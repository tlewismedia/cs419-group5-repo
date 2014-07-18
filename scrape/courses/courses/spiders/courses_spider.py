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
    # allowed_domains = ["localhost-scrapy"]
    # start_urls = [
    #     "http://localhost-scrapy"
    # ]
    allowed_domains = ["catalog.oregonstate.edu"]
    start_urls = [
        "http://catalog.oregonstate.edu/SOCList.aspx?subjectcode=BI&termcode=201500&campus=corvallis&columns=bcfjk"
    ]

    def parse(self, response):


        # goal is to first grab the tables where the first row has a tr 
        # and the first th is 'Term'

        i = 0
        tables = response.xpath('//table')

        for table in tables:

            
            i += 1

            if i == 7 : continue

            header = table.xpath('.//th/text()')
            if not header: continue

            print '\n\n\n*********************\n'
            print "TABLE: " + str(i) 
            if header[0].extract() != "StartDate" : continue

            

            # at this point we know we want this table

            # get rows from this table
            rows = table.xpath('.//tr')
            
            for sel in rows:
                

                # if it's a row with no td's then skip
                if not sel.xpath('./td/text()').extract(): continue



                
                # cull TBA courses
                days = re.findall( '[A-Z]+' , sel.xpath('./td/text()')[4].extract())
                if days[0] == 'TBA' : continue  
                
                item = CourseItem()

                item['days'] = days[0]
                item['start'] = sel.xpath('./td/text()')[0].extract()
                item['end'] = sel.xpath('./td/text()')[1].extract()
                item['crn'] = sel.xpath('./td/text()')[2].extract()
                item['prof'] = sel.xpath('./td/text()')[3].extract()
                item['times'] = re.findall( '[\d-]+' , sel.xpath('./td/text()')[4].extract())[0]
                yield item


